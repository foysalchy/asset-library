<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileUploadHelper
{
    /**
     * Image upload — always local (public disk)
     */
    public static function uploadImage(UploadedFile $file, string $folder = 'campaigns/thumbnails'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * File upload — Google Drive
     */
    /**
     * File upload — Google Drive, returns shareable URL
     */
    public static function uploadFile(UploadedFile $file, string $folder = 'campaigns/files'): string
    {
        $filename = Str::uuid() . '_' . $file->getClientOriginalName();
        $path     = $folder . '/' . $filename;

        Storage::disk('google_drive')->put($path, file_get_contents($file));

        // ✅ File ID নাও Drive থেকে
        $client = new \Google\Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

        $service = new \Google\Service\Drive($client);
        $files   = $service->files->listFiles([
            'q'      => "name = '{$filename}' and trashed = false",
            'fields' => 'files(id)',
        ]);

        $fileId = $files->getFiles()[0]->getId() ?? null;

        if (!$fileId) {
            \Log::error('Drive file ID not found after upload', ['path' => $path]);
            return 'drive:' . $path; // fallback
        }

        // ✅ path না, file ID store করো
        return 'drive:' . $fileId;
    }
    // public static function uploadFile(UploadedFile $file, string $folder = 'campaigns/files'): string
    // {
    //     $filename = Str::uuid() . '_' . $file->getClientOriginalName();
    //     $path     = $folder . '/' . $filename;

    //     Storage::disk('google_drive')->put($path, file_get_contents($file));

    //     // Private করে দাও — public access বন্ধ
    //     // Storage::disk('google_drive')->setVisibility($path, 'private');

    //     return $path;
    // }
    /**
     * Delete image from local
     */

    /**
     * Image compress করো — thumbnail এর জন্য
     * Laravel এ GD/Imagick দিয়ে
     */
    public static function compressImage(UploadedFile $file, int $quality = 75, int $maxWidth = 500, int $maxHeight = 500): string
    {
        $manager = new ImageManager(new Driver());
        $image   = $manager->read($file->getPathname());

        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->scaleDown(width: $maxWidth, height: $maxHeight);
        }

        $filename = Str::uuid() . '_thumb.' . $file->getClientOriginalExtension();
        $tempPath = sys_get_temp_dir() . '/' . $filename;

        $image->toJpeg($quality)->save($tempPath);

        return $tempPath;
    }
/**
 * Image সরাসরি upload করে (already server এ আছে) — compress + original দুটোই Drive এ upload করে।
 * কোনো Drive download লাগে না কারণ file already locally available.
 */
public static function uploadImageWithCompressionToDrive(UploadedFile $file): array
{
    $client = new \Google\Client();
    $client->setClientId(config('filesystems.disks.google_drive.clientId'));
    $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
    $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));
    $service = new \Google\Service\Drive($client);

    $originalName = $file->getClientOriginalName();
    $mimeType     = $file->getMimeType();
    $fileSize     = $file->getSize();
    $ext          = $file->getClientOriginalExtension();

    // ── Original upload ──────────────────────────────────────
    $originalFilename = \Str::uuid() . '.' . $ext;
    $originalPath      = 'assets/media/' . $originalFilename;
    Storage::disk('google_drive')->put($originalPath, file_get_contents($file->getRealPath()));

    $originalFiles = $service->files->listFiles([
        'q'      => "name = '{$originalFilename}' and trashed = false",
        'fields' => 'files(id)',
    ]);
    $originalId = $originalFiles->getFiles()[0]->getId() ?? null;

    // ── Compress (already local file — তাৎক্ষণিক, কোনো download লাগে না) ──
    $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
    $image   = $manager->read($file->getRealPath());

    $maxWidth = 800;
    if ($image->width() > $maxWidth) {
        $image->scaleDown(width: $maxWidth);
    }

    $compressedFilename = \Str::uuid() . '_thumb.jpg';
    $compressedTempPath = sys_get_temp_dir() . '/' . $compressedFilename;
    $image->toJpeg(75)->save($compressedTempPath);

    // ── Compressed upload ─────────────────────────────────────
    $compressedPath = 'assets/media/compressed/' . $compressedFilename;
    Storage::disk('google_drive')->put($compressedPath, file_get_contents($compressedTempPath));
    @unlink($compressedTempPath);

    $compressedFiles = $service->files->listFiles([
        'q'      => "name = '{$compressedFilename}' and trashed = false",
        'fields' => 'files(id)',
    ]);
    $compressedId = $compressedFiles->getFiles()[0]->getId() ?? null;

    return [
        'original_id'    => $originalId,
        'compressed_id'  => $compressedId,
        'original_name'  => $originalName,
        'mime_type'      => $mimeType,
        'file_size'      => $fileSize,
    ];
}
    /**
     * Image upload to Drive — original + compressed দুটোই
     * Returns ['original' => 'drive:ID', 'compressed' => 'drive:ID']
     */
    public static function uploadImageToDriveWithCompressed(UploadedFile $file, string $folder = 'assets/media'): array
    {
        // ── Original upload ──────────────────────────────────────
        $originalId = self::uploadImageToDrive($file, $folder);

        // ── Compressed upload ────────────────────────────────────
        $tempPath  = self::compressImage($file);
        $filename  = Str::uuid() . '_thumb.' . $file->getClientOriginalExtension();
        $drivePath = $folder . '/compressed/' . $filename;

        Storage::disk('google_drive')->put($drivePath, file_get_contents($tempPath));
        unlink($tempPath); // temp file মুছো

        $client = new \Google\Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

        $service = new \Google\Service\Drive($client);
        $files   = $service->files->listFiles([
            'q'      => "name = '{$filename}' and trashed = false",
            'fields' => 'files(id)',
        ]);

        $compressedId = $files->getFiles()[0]->getId() ?? null;

        return [
            'original'   => $originalId,
            'compressed' => $compressedId ? 'drive:' . $compressedId : $originalId, // fallback original
        ];
    }
    public static function uploadImageToDrive(UploadedFile $file, string $folder = 'assets/media'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path     = $folder . '/' . $filename;

        Storage::disk('google_drive')->put($path, file_get_contents($file));

        $client = new \Google\Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

        $service = new \Google\Service\Drive($client);
        $files   = $service->files->listFiles([
            'q'      => "name = '{$filename}' and trashed = false",
            'fields' => 'files(id)',
        ]);

        $fileId = $files->getFiles()[0]->getId() ?? null;

        if (!$fileId) {
            \Log::error('Drive image ID not found after upload', ['path' => $path]);
            return 'drive:' . $path;
        }

        return 'drive:' . $fileId;
    }
    public static function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }


    public static function deleteFile(?string $pathOrUrl): void
    {
        if (empty($pathOrUrl)) return;

        if (str_starts_with($pathOrUrl, 'drive:')) {
            $fileId = str_replace('drive:', '', $pathOrUrl);
            try {
                $client = new \Google\Client();
                $client->setClientId(config('filesystems.disks.google_drive.clientId'));
                $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
                $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

                $service = new \Google\Service\Drive($client);
                $service->files->delete($fileId);
            } catch (\Exception $e) {
                \Log::error('Drive file delete error: ' . $e->getMessage());
            }
            return;
        }

        if (str_starts_with($pathOrUrl, 'http')) {
            return;
        }

        if (Storage::disk('google_drive')->exists($pathOrUrl)) {
            Storage::disk('google_drive')->delete($pathOrUrl);
        }
    }
    /**
     * Get public URL for local image
     */
    public static function imageUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }

    /**
     * Get shareable URL for Google Drive file
     */
    public static function fileUrl(?string $path): ?string
    {
        return $path ? Storage::disk('google_drive')->url($path) : null;
    }
    /**
     * Drive file ID থেকে stream করো
     */
    public static function streamFile(string $pathOrId): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Drive file ID হলে (prefix: drive:)
        if (str_starts_with($pathOrId, 'drive:')) {
            $fileId = str_replace('drive:', '', $pathOrId);
            return self::streamFromDriveId($fileId);
        }

        // Path হলে — disk দিয়ে stream করো
        if (!Storage::disk('google_drive')->exists($pathOrId)) {
            abort(404, 'File not found.');
        }

        $stream   = Storage::disk('google_drive')->readStream($pathOrId);
        $mimeType = Storage::disk('google_drive')->mimeType($pathOrId);
        $filename = basename($pathOrId);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    private static function streamFromDriveId(string $fileId): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $client = new \Google\Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

        $service  = new \Google\Service\Drive($client);
        $response = $service->files->get($fileId, ['alt' => 'media']);
        $body     = $response->getBody();
        $meta     = $service->files->get($fileId, ['fields' => 'name, mimeType']);

        return response()->stream(function () use ($body) {
            while (!$body->eof()) {
                echo $body->read(1024 * 1024); // 1MB chunk করে
                flush();
            }
        }, 200, [
            'Content-Type'        => $meta->getMimeType(),
            'Content-Disposition' => 'inline; filename="' . $meta->getName() . '"',
        ]);
    }
}
