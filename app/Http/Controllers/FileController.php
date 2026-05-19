<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Campaign;
use App\Models\DownloadLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    private array $models = [
        'campaign' => [
            'class' => Campaign::class,
            'field' => 'file',
        ],
        'asset' => [
            'class' => Asset::class,
            'field' => 'file_path',
        ],
    ];

    /**
     * Download button calls this method
     */
    public function stream(string $type, string $id)
    {
        if (!array_key_exists($type, $this->models)) {
            abort(404);
        }

        $config = $this->models[$type];
        $model  = $config['class']::findOrFail($id);
        $field  = $config['field'];

        if (empty($model->$field)) {
            abort(404, 'No file attached.');
        }

        // Log the download
        $this->logDownload($type, $id);

        $filePath = $model->$field;

        // If it's a Google Drive file, stream it directly from here with the 403 API fix
        if (str_starts_with($filePath, 'drive:')) {
            $fileId = str_replace('drive:', '', $filePath);
            return $this->streamGoogleDriveFile($fileId);
        }

        // If it's a normal local file, use Laravel's default storage stream
        if (!Storage::disk('google_drive')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('google_drive')->response($filePath);
    }

    /**
     * Used for AssetMedia streams
     */
    public function streamMedia(\App\Models\AssetMedia $media)
    {
        if (empty($media->file_path)) {
            abort(404, 'No file attached.');
        }

        if (str_starts_with($media->file_path, 'drive:')) {
            $fileId = str_replace('drive:', '', $media->file_path);
            return $this->streamGoogleDriveFile($fileId);
        }

        abort(404, 'Not a valid drive file.');
    }

    /**
     * The Main Fix: Handles Drive Streaming with Chunking & Range Support
     */
   /**
     * The Main Fix: Handles Drive Streaming with Chunking, Range Support & Abuse Bypass
     */
    private function streamGoogleDriveFile(string $fileId)
    {
        try {
            $client = new \Google\Client();
            $client->setClientId(config('filesystems.disks.google_drive.clientId'));
            $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
            
            $refreshToken = config('filesystems.disks.google_drive.refreshToken');
            $client->fetchAccessTokenWithRefreshToken($refreshToken);

            $token = $client->getAccessToken();
            if (!$token || empty($token['access_token'])) {
                Log::error('Drive Access Token missing');
                abort(500, 'Access token error');
            }

            $service = new \Google\Service\Drive($client);
            // মেটাডেটা ঠিকমতো পাচ্ছে কিনা চেক করা হচ্ছে
            $meta = $service->files->get($fileId, ['fields' => 'name, mimeType, size']);

            $mimeType = $meta->getMimeType();
            $fileSize = (int) $meta->getSize();
            $filename = $meta->getName();

            // acknowledgeAbuse=true যুক্ত করা হলো (বড় ফাইলের জন্য বাধ্যতামূলক)
            $driveUrl = "https://www.googleapis.com/drive/v3/files/{$fileId}?alt=media&acknowledgeAbuse=true";

            $statusCode      = 200;
            $responseHeaders = [
                'Content-Type'        => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Accept-Ranges'       => 'bytes',
                'Cache-Control'       => 'no-cache',
            ];

            $rangeHeader = request()->header('Range');
            $requestHeaders = [
                'Authorization' => 'Bearer ' . $token['access_token']
            ];

            if ($rangeHeader) {
                preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches);
                $start = (int) $matches[1];
                $end   = isset($matches[2]) && $matches[2] !== '' ? (int) $matches[2] : $fileSize - 1;

                $statusCode = 206;
                $responseHeaders['Content-Range']  = "bytes {$start}-{$end}/{$fileSize}";
                $responseHeaders['Content-Length'] = $end - $start + 1;
                
                $requestHeaders['Range'] = $rangeHeader;
            } else {
                $responseHeaders['Content-Length'] = $fileSize;
            }

            // Guzzle রিকোয়েস্টটি স্ট্রিমের বাইরে করা হলো, যাতে Error সহজে ধরা যায়
            $httpClient = new \GuzzleHttp\Client();
            $response = $httpClient->request('GET', $driveUrl, [
                'headers'         => $requestHeaders,
                'stream'          => true,
                'allow_redirects' => true, // Guzzle নিজে রিডাইরেক্ট সামলাবে এবং পরের ডোমেইনে হেডার ড্রপ করবে
            ]);

            $body = $response->getBody();

            
            return response()->stream(function () use ($body) {
                while (!$body->eof()) {
                    echo $body->read(8192); // 8KB chunks
                    flush();
                }
            }, $statusCode, $responseHeaders);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // যদি গুগল ডাউনলোড রিজেক্ট করে, তার এক্স্যাক্ট মেসেজটি লগ করবে
            $errorBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Drive API Stream 403/400 Detail: ' . $errorBody);
            abort(500, 'Google Drive Error: Check Laravel Log for details.');
        } catch (\Exception $e) {
            Log::error('streamGoogleDriveFile exception', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            abort(500, 'Error streaming file: ' . $e->getMessage());
        }
    }
    private function logDownload(string $type, string $id): void
    {
        try {
            DownloadLog::updateOrCreate(
                [
                    'user_id'  => auth()->id(),
                    'model'    => $type,
                    'model_id' => $id,
                ],
                [
                    'ip_address' => request()->ip(),
                ]
            )->increment('count');
        } catch (\Exception $e) {
            Log::error('Download log error: ' . $e->getMessage());
        }
    }
}