<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\Asset;
use App\Models\Campaign;
use App\Models\DownloadLog;
use Illuminate\Support\Facades\Log;

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

        $this->logDownload($type, $id);

        return FileUploadHelper::streamFile($model->$field);
    }
public function streamMedia(\App\Models\AssetMedia $media)
{
    \Log::info('streamMedia called', ['media_id' => $media->id, 'file_path' => $media->file_path]);

    if (empty($media->file_path)) {
        \Log::error('streamMedia: file_path empty');
        abort(404);
    }

    if (!str_starts_with($media->file_path, 'drive:')) {
        \Log::error('streamMedia: not a drive file', ['file_path' => $media->file_path]);
        abort(404);
    }

    $fileId = str_replace('drive:', '', $media->file_path);
    \Log::info('streamMedia: fileId', ['fileId' => $fileId]);

    try {
        $client = new \Google\Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

        $token = $client->getAccessToken();
        \Log::info('streamMedia: token', ['token' => $token ? 'OK' : 'FAILED']);

        if (!$token || empty($token['access_token'])) {
            \Log::error('streamMedia: access token missing');
            abort(500, 'Access token error');
        }

        $accessToken = $token['access_token'];

        $service = new \Google\Service\Drive($client);
        $meta    = $service->files->get($fileId, ['fields' => 'name, mimeType, size']);

        \Log::info('streamMedia: file meta', [
            'name'     => $meta->getName(),
            'mimeType' => $meta->getMimeType(),
            'size'     => $meta->getSize(),
        ]);

        $mimeType = $meta->getMimeType();
        $fileSize = (int) $meta->getSize();

        $driveUrl    = "https://www.googleapis.com/drive/v3/files/{$fileId}?alt=media";
        $curlHeaders = ["Authorization: Bearer {$accessToken}"];

        $statusCode      = 200;
        $responseHeaders = [
            'Content-Type'  => $mimeType,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'no-cache',
        ];

        if (request()->hasHeader('Range')) {
            $rangeHeader = request()->header('Range');
            \Log::info('streamMedia: range request', ['range' => $rangeHeader]);

            preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches);
            $start = (int) $matches[1];
            $end   = isset($matches[2]) && $matches[2] !== '' ? (int) $matches[2] : $fileSize - 1;

            $curlHeaders[]  = "Range: {$rangeHeader}";
            $statusCode     = 206;
            $responseHeaders['Content-Range']  = "bytes {$start}-{$end}/{$fileSize}";
            $responseHeaders['Content-Length'] = $end - $start + 1;

            \Log::info('streamMedia: 206 response', [
                'start'  => $start,
                'end'    => $end,
                'length' => $end - $start + 1,
            ]);
        } else {
            $responseHeaders['Content-Length'] = $fileSize;
            \Log::info('streamMedia: 200 full response', ['size' => $fileSize]);
        }

        return response()->stream(function () use ($driveUrl, $curlHeaders, $fileId) {
            \Log::info('streamMedia: stream started', ['fileId' => $fileId]);

            $ch = curl_init($driveUrl);
            curl_setopt_array($ch, [
                CURLOPT_HTTPHEADER     => $curlHeaders,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => false,
                CURLOPT_WRITEFUNCTION  => function ($ch, $data) {
                    echo $data;
                    flush();
                    return strlen($data);
                },
            ]);

            $result   = curl_exec($ch);
            $curlInfo = curl_getinfo($ch);
            $curlErr  = curl_error($ch);
            curl_close($ch);

            \Log::info('streamMedia: curl done', [
                'http_code'    => $curlInfo['http_code'],
                'total_bytes'  => $curlInfo['size_download'],
                'curl_error'   => $curlErr ?: 'none',
            ]);
        }, $statusCode, $responseHeaders);

    } catch (\Exception $e) {
        \Log::error('streamMedia: exception', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        ]);
        abort(500, $e->getMessage());
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
