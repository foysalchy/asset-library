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

        // Shareable link return করো
        return Storage::disk('google_drive')->url($path);
    }

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
}
