<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMedia;
use App\Models\Campaign;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
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

    // ✅ File path নেই — সব Drive image ZIP করে download করো
    if (empty($model->$field)) {
        return $this->downloadImagesAsZip($model);
    }

    $this->logDownload($type, $id);

    $filePath = $model->$field;

    if (str_starts_with($filePath, 'drive:')) {
        $fileId = str_replace('drive:', '', $filePath);
        return $this->streamGoogleDriveFile($fileId);
    }

    if (!Storage::disk('google_drive')->exists($filePath)) {
        abort(404, 'File not found.');
    }

    return Storage::disk('google_drive')->response($filePath);
}

private function downloadImagesAsZip($model): \Symfony\Component\HttpFoundation\StreamedResponse
{
    // Media থেকে Drive image গুলো নাও
    $images = $model->media()
        ->where('media_type', 'image')
        ->where('file_path', 'like', 'drive:%')
        ->get();

    if ($images->isEmpty()) {
        abort(404, 'No files available for download.');
    }

    $client = new \Google\Client();
    $client->setClientId(config('filesystems.disks.google_drive.clientId'));
    $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
    $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));
    $service = new \Google\Service\Drive($client);

    // ZIP বানাও
    $zipName = \Str::slug($model->title ?? 'download') . '-images.zip';
    $tmpZip  = sys_get_temp_dir() . '/' . \Str::uuid() . '.zip';

    $zip = new \ZipArchive();
    $zip->open($tmpZip, \ZipArchive::CREATE);

    foreach ($images as $index => $media) {
        $fileId = str_replace('drive:', '', $media->file_path);

        try {
            $response = $service->files->get($fileId, ['alt' => 'media']);
            $meta     = $service->files->get($fileId, ['fields' => 'name, mimeType']);
            $content  = $response->getBody()->getContents();

            $ext      = explode('/', $meta->getMimeType())[1] ?? 'jpg';
            $filename = ($media->file_original_name ?? 'image-' . ($index + 1)) ?: 'image-' . ($index + 1);

            $zip->addFromString($filename, $content);
        } catch (\Exception $e) {
            \Log::error('ZIP image error: ' . $e->getMessage());
        }
    }

    $zip->close();

    // Log download
    $type = class_basename($model) === 'Campaign' ? 'campaign' : 'asset';
    $this->logDownload($type, $model->id);

    return response()->streamDownload(function () use ($tmpZip) {
        readfile($tmpZip);
        @unlink($tmpZip);
    }, $zipName, [
        'Content-Type' => 'application/zip',
    ]);
}

    /**
     * Used for AssetMedia streams
     */
    public function streamMedia(AssetMedia $media, Request $request)
    {
        $version = $request->get('version', 'compressed');

        $filePath = $version === 'original'
            ? $media->file_path
            : ($media->file_path_compressed ?? $media->file_path);
        if (empty($filePath)) {
            abort(404, 'No file attached.');
        }

        if (!str_starts_with($filePath, 'drive:')) {
            abort(404, 'Not a valid drive file.');
        }

        $fileId = str_replace('drive:', '', $filePath);
        return $this->streamGoogleDriveFile($fileId);
    }
    // FileController
    public function base64Image(AssetMedia $media)
    {
        if (empty($media->file_path) || $media->media_type !== 'image') {
            abort(404);
        }

        $fileId = str_replace('drive:', '', $media->file_path);

        $client = new \Google\Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

        $service  = new \Google\Service\Drive($client);
        $meta     = $service->files->get($fileId, ['fields' => 'mimeType']);
        $response = $service->files->get($fileId, ['alt' => 'media']);
        $body     = $response->getBody();

        $content  = '';
        while (!$body->eof()) {
            $content .= $body->read(1024 * 256);
        }

        return response()->json([
            'base64'    => 'data:' . $meta->getMimeType() . ';base64,' . base64_encode($content),
            'mime_type' => $meta->getMimeType(),
        ]);
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
