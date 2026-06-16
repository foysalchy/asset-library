<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class AssetMedia extends Model
{


    protected $fillable = [
        'asset_id',
        'file_path',
        'file_original_name',
        'file_path_compressed',
        'media_type',
        'mime_type',
        'file_size',
        'sort_order',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }



    public function getStreamUrlAttribute(): ?string
    {
        if (!$this->file_path) return null;

        if (str_starts_with($this->file_path, 'drive:')) {
            return route('drive.media.stream', $this->id);
        }

        return Storage::disk('public')->url($this->file_path);
    }
    // Thumbnail — compressed
    public function getUrlAttribute(): ?string
    {
        $path = $this->file_path_compressed ?? $this->file_path;
        if (!$path) return null;

        if (str_starts_with($path, 'drive:')) {
            return route('drive.media.stream', ['media' => $this->id, 'version' => 'compressed']);
        }
        return Storage::disk('public')->url($path);
    }

    // Original — download/edit
    public function getOriginalUrlAttribute(): ?string
    {
        if (!$this->file_path) return null;

        if (str_starts_with($this->file_path, 'drive:')) {
            return route('drive.media.stream', ['media' => $this->id, 'version' => 'original']);
        }
        return Storage::disk('public')->url($this->file_path);
    }
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->file_path) return null;

        // Local image/video
        if (!str_starts_with($this->file_path, 'drive:')) {
            return Storage::disk('public')->url($this->file_path);
        }

        // Drive video — Google Drive thumbnail URL
        $fileId = str_replace('drive:', '', $this->file_path);
        return "https://drive.google.com/thumbnail?id={$fileId}&sz=w200";
    }
}
