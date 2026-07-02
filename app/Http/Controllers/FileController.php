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

    public function processVideo1(Request $request, AssetMedia $media)
    {
        $validated = $request->validate([
            'text'       => ['nullable', 'string', 'max:100'],
            'bg_color'   => ['required', 'string'],
            'bg_opacity' => ['required', 'numeric', 'min:0', 'max:1'],
            'font_size'  => ['required', 'integer', 'min:10', 'max:80'],
            'text_color' => ['required', 'string'],
            'x_percent'  => ['required', 'numeric', 'min:0', 'max:100'],
            'y_percent'  => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        if ($media->media_type !== 'video' || !str_starts_with($media->file_path, 'drive:')) {
            abort(404, 'Invalid video media.');
        }

        $fileId = str_replace('drive:', '', $media->file_path);

        // ── Step 1: Download video from Google Drive ──────────────────────────
        $client = new \Google\Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

        $service  = new \Google\Service\Drive($client);
        $response = $service->files->get($fileId, ['alt' => 'media']);
        $body     = $response->getBody();

        $tempInput = storage_path('app/temp/' . \Str::uuid() . '.mp4');
        if (!is_dir(dirname($tempInput))) {
            mkdir(dirname($tempInput), 0755, true);
        }

        $out = fopen($tempInput, 'w');
        while (!$body->eof()) {
            fwrite($out, $body->read(1024 * 1024));
        }
        fclose($out);

        Log::info('Video downloaded: ' . $tempInput);

        // ── Step 2: Get video dimensions ───────────────────────────────────
        $ffprobe = \FFMpeg\FFProbe::create([
            'ffmpeg.binaries'  => 'C:\ffmpeg\bin\ffmpeg.exe',
            'ffprobe.binaries' => 'C:\ffmpeg\bin\ffprobe.exe',
        ]);
        $videoStream = $ffprobe->streams($tempInput)->videos()->first();
        $videoWidth  = $videoStream->get('width');
        $videoHeight = $videoStream->get('height');

        Log::info("Video dimensions: {$videoWidth}x{$videoHeight}");

        // ── Step 3: Calculate box position ─────────────────────────────────
        $boxWidth  = max(200, strlen($validated['text'] ?? '') * $validated['font_size'] * 0.6 + 60);
        $boxHeight = $validated['font_size'] * 1.8;

        $boxX = ($validated['x_percent'] / 100) * $videoWidth - ($boxWidth / 2);
        $boxY = ($validated['y_percent'] / 100) * $videoHeight - ($boxHeight / 2);

        $boxX = max(0, min($videoWidth - $boxWidth, $boxX));
        $boxY = max(0, min($videoHeight - $boxHeight, $boxY));

        Log::info("Box position: x={$boxX}, y={$boxY}, w={$boxWidth}, h={$boxHeight}");

        $bgRgb   = $this->hexToFFmpegColor($validated['bg_color'], $validated['bg_opacity']);
        $textRgb = $this->hexToFFmpegColor($validated['text_color'], 1);

        // ── Step 4: Prepare text and paths ──────────────────────────────────
        $text = $validated['text'] ?? '';
        // Remove characters that break filter parsing
        $text = str_replace(["'", ":", "\\", "\n", "[", "]"], "", $text);
        Log::info("Cleaned text: '{$text}'");

        $tempOutput = storage_path('app/temp/' . \Str::uuid() . '_output.mp4');
        $fontFile = public_path('font/Outfit-VariableFont_wght.ttf');

        // ✅ Convert all paths to forward slashes for FFmpeg
        $tempInput = str_replace('\\', '/', $tempInput);
        $tempOutput = str_replace('\\', '/', $tempOutput);
        $fontFile = str_replace('\\', '/', $fontFile);

        Log::info("Input: {$tempInput}");
        Log::info("Output: {$tempOutput}");
        Log::info("Font: {$fontFile}");
        Log::info("Font exists: " . (file_exists(str_replace('/', '\\', $fontFile)) ? 'yes' : 'no'));

        // Text position
        $textX = (int)($boxX + ($boxWidth / 2));
        $textY = (int)($boxY + ($boxHeight / 2));

        // ── Build filter string ─────────────────────────────────────────────
        // ✅ Use forward slashes in the path - Windows FFmpeg handles them correctly

        $filter = "drawbox=x=" . (int)$boxX
            . ":y=" . (int)$boxY
            . ":w=" . (int)$boxWidth
            . ":h=" . (int)$boxHeight
            . ":color=" . $bgRgb
            . ":t=fill"
            . ",drawtext=fontfile='" . $fontFile . "'"
            . ":text='" . $text . "'"
            . ":fontsize=" . (int)$validated['font_size']
            . ":fontcolor=" . $textRgb
            . ":x=" . $textX
            . ":y=" . $textY
            . ":line_spacing=" . (int)($validated['font_size'] * 0.2);

        Log::info("Complete filter string: " . $filter);

        // ── Step 5: Run FFmpeg ──────────────────────────────────────────────
        $ffmpegPath = 'C:\ffmpeg\bin\ffmpeg.exe';

        $process = new \Symfony\Component\Process\Process([
            $ffmpegPath,
            '-i',
            $tempInput,
            '-vf',
            $filter,
            '-codec:a',
            'copy',
            '-y',
            $tempOutput,
        ]);

        $process->setTimeout(300);
        $process->setIdleTimeout(300);

        // Capture everything
        $process->run();

        // Get full output
        $stdout = $process->getOutput();
        $stderr = $process->getErrorOutput();

        if (!empty($stdout)) {
            Log::info('FFmpeg STDOUT (last 500 chars): ' . substr($stdout, -500));
        }
        if (!empty($stderr)) {
            Log::error('FFmpeg STDERR (last 1000 chars): ' . substr($stderr, -1000));
        }

        if (!$process->isSuccessful()) {
            @unlink($tempInput);

            $errorMsg = 'FFmpeg failed';
            if (strpos($stderr, 'No such file') !== false) {
                $errorMsg = 'Font file not found: ' . $fontFile;
            } elseif (strpos($stderr, 'fontconfig') !== false) {
                $errorMsg = 'Font loading error - verify font path exists';
            } elseif (strpos($stderr, 'Error parsing') !== false) {
                $errorMsg = 'Filter syntax error';
            }

            Log::error('Video processing failed: ' . $errorMsg);
            Log::error('Exit code: ' . $process->getExitCode());

            return response()->json(['message' => $errorMsg], 500);
        }

        @unlink($tempInput);

        Log::info('Video processing completed successfully');

        // ── Step 6: Return processed file ────────────────────────────────────
        return response()->download($tempOutput, 'edited_video.mp4')->deleteFileAfterSend(true);
    }


    public function processVideo(Request $request, AssetMedia $media)
    {
        $validated = $request->validate([
            'text'       => ['nullable', 'string', 'max:100'],
            'bg_color'   => ['required', 'string'],
            'bg_opacity' => ['required', 'numeric', 'min:0', 'max:1'],
            'font_size'  => ['required', 'integer', 'min:10', 'max:80'],
            'text_color' => ['required', 'string'],
            'x_percent'  => ['required', 'numeric', 'min:0', 'max:100'],
            'y_percent'  => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        if ($media->media_type !== 'video' || !str_starts_with($media->file_path, 'drive:')) {
            abort(404, 'Invalid video media.');
        }

        $fileId = str_replace('drive:', '', $media->file_path);



        // ── Step 1: Download video from Google Drive ──────────────────────────
        $client = new \Google\Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

        $service  = new \Google\Service\Drive($client);
        $response = $service->files->get($fileId, ['alt' => 'media']);
        $body     = $response->getBody();

        $tempInput = storage_path('app/temp/' . \Str::uuid() . '.mp4');
        if (!is_dir(dirname($tempInput))) {
            mkdir(dirname($tempInput), 0755, true);
        }

        $out = fopen($tempInput, 'w');
        while (!$body->eof()) {
            fwrite($out, $body->read(1024 * 1024));
        }
        fclose($out);

        Log::info('Video downloaded: ' . $tempInput);
        $ffmpegPath = 'C:\ffmpeg\bin\ffmpeg.exe';

        // ── Step 2: Get video dimensions & Bitrate ────────────────────────────
        $ffprobe = \FFMpeg\FFProbe::create([
            'ffmpeg.binaries'  => 'C:\ffmpeg\bin\ffmpeg.exe',
            'ffprobe.binaries' => 'C:\ffmpeg\bin\ffprobe.exe',
        ]);
        $videoStream = $ffprobe->streams($tempInput)->videos()->first();
        $videoWidth  = $videoStream->get('width');
        $videoHeight = $videoStream->get('height');

        $bitRate = $videoStream->has('bit_rate') ? $videoStream->get('bit_rate') : null;

        Log::info("Video dimensions: {$videoWidth}x{$videoHeight}, Bitrate: {$bitRate}");

        // ── Step 3: Calculate box position ─────────────────────────────────
        $boxWidth  = max(200, strlen($validated['text'] ?? '') * $validated['font_size'] * 0.6 + 60);
        $boxHeight = $validated['font_size'] * 1.8;

        $boxX = ($validated['x_percent'] / 100) * $videoWidth - ($boxWidth / 2);
        $boxY = ($validated['y_percent'] / 100) * $videoHeight - ($boxHeight / 2);

        $boxX = max(0, min($videoWidth - $boxWidth, $boxX));
        $boxY = max(0, min($videoHeight - $boxHeight, $boxY));

        Log::info("Box position: x={$boxX}, y={$boxY}, w={$boxWidth}, h={$boxHeight}");

        $bgRgb   = $this->hexToFFmpegColor($validated['bg_color'], $validated['bg_opacity']);
        $textRgb = $this->hexToFFmpegColor($validated['text_color'], 1);

        // ── Step 4: Prepare text and BULLETPROOF FONT PATH ───────────────────
        $text = $validated['text'] ?? '';
        $text = str_replace(["'", ":", "\\", "\n", "[", "]"], "", $text);
        Log::info("Cleaned text: '{$text}'");

        $tempOutput = storage_path('app/temp/' . \Str::uuid() . '_output.mp4');
        $fontFileOriginal = public_path('font/Outfit-VariableFont_wght.ttf');

        if (!file_exists($fontFileOriginal)) {
            @unlink($tempInput);
            Log::error("Font not found at: " . $fontFileOriginal);
            return response()->json(['message' => 'System error: Font file is missing in public directory.'], 500);
        }

        $tempFontPath = storage_path('app/temp/font_' . \Str::uuid() . '.ttf');
        copy($fontFileOriginal, $tempFontPath);

        $fontFileName = basename($tempFontPath);

        // Text position
        $textX = (int)($boxX + ($boxWidth / 2));
        $textY = (int)($boxY + ($boxHeight / 2));
        $filter = "drawbox=x=" . (int)$boxX
            . ":y=" . (int)$boxY
            . ":w=" . (int)$boxWidth
            . ":h=" . (int)$boxHeight
            . ":color=" . $bgRgb
            . ":t=fill"
            . ",drawtext=fontfile='" . $fontFileName . "'"
            . ":text='" . $text . "'"
            . ":fontsize=" . (int)$validated['font_size']
            . ":fontcolor=" . $textRgb
            . ":x=" . $textX . "-text_w/2"
            . ":y=" . $textY . "-text_h/2"
            . ":line_spacing=" . (int)($validated['font_size'] * 0.2);

        Log::info("Complete filter string: " . $filter);


        $processArgs = [
            $ffmpegPath,
            '-i',
            basename($tempInput),
            '-vf',
            $filter,
            '-c:v',
            'libx264',
            '-preset',
            'slow',
        ];

        if ($bitRate) {
            $processArgs = array_merge($processArgs, [
                '-b:v',
                $bitRate,
                '-maxrate',
                $bitRate,
                '-bufsize',
                (string)((int)$bitRate * 2),
            ]);
        } else {
            $processArgs = array_merge($processArgs, ['-crf', '14']);
        }

        $processArgs = array_merge($processArgs, [
            '-codec:a',
            'copy',
            '-y',
            basename($tempOutput),
        ]);

        $process = new \Symfony\Component\Process\Process($processArgs);

        $process->setWorkingDirectory(dirname($tempInput));

        $process->setTimeout(300);
        $process->setIdleTimeout(300);

        $process->run();

        $stdout = $process->getOutput();
        $stderr = $process->getErrorOutput();

        if (!empty($stdout)) {
            Log::info('FFmpeg STDOUT: ' . substr($stdout, -500));
        }
        if (!empty($stderr)) {
            Log::error('FFmpeg STDERR: ' . substr($stderr, -1000));
        }

        // ── Step 7: Cleanup & Return ────────────────────────────────────────

        // প্রসেস শেষ হলে অরিজিনাল ভিডিও এবং টেম্প ফন্ট ডিলিট করা হচ্ছে
        @unlink($tempInput);
        @unlink($tempFontPath);

        if (!$process->isSuccessful()) {
            $errorMsg = 'FFmpeg failed';
            if (strpos($stderr, 'No such file') !== false || strpos($stderr, 'fontconfig') !== false) {
                $errorMsg = 'Font loading error - verify font exists';
            } elseif (strpos($stderr, 'Error parsing') !== false) {
                $errorMsg = 'Filter syntax error';
            }

            Log::error('Video processing failed: ' . $errorMsg);
            Log::error('Exit code: ' . $process->getExitCode());

            return response()->json(['message' => $errorMsg], 500);
        }

        Log::info('Video processing completed successfully');

        return response()->download($tempOutput, 'edited_video.mp4')->deleteFileAfterSend(true);
    }
    private function hexToFFmpegColor(string $hex, float $opacity): string
    {
        $hex = ltrim($hex, '#');
        return '0x' . $hex . '@' . round($opacity, 2);
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
    public function downloadVideo(Asset $asset, AssetMedia $media)
    {
        if ($media->asset_id !== $asset->id || $media->media_type !== 'video') {
            abort(404);
        }

        // Download log
        $this->logDownload('asset', $asset->id);

        $fileId = str_replace('drive:', '', $media->file_path);
        return $this->streamGoogleDriveFile($fileId);
    }
}
