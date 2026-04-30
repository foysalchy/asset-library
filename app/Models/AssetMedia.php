<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AssetMedia extends Model
{
    

    protected $fillable = [
        'asset_id', 'file_path', 'file_original_name',
        'media_type', 'mime_type', 'file_size', 'sort_order',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }
}