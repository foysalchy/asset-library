<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        Storage::disk('google_drive')->setVisibility($path, 'private');

        // ✅ path return করো, URL না
        return $path;
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
    public static function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }


    public static function deleteFile(?string $pathOrUrl): void
    {
        if (empty($pathOrUrl)) return;

        // drive: prefix হলে file ID দিয়ে delete করো
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

        // Full URL হলে skip
        if (str_starts_with($pathOrUrl, 'http')) {
            return;
        }

        // Normal path হলে disk দিয়ে delete
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
