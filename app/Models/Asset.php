<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'asset_type_id',
        'title',
        'slug',
        'asset_id_code',
        'description',
        'file_path',
        'file_original_name',
        'file_mime_type',
        'file_size',
        'available_formats',
        'dimensions',
        'sort_order',
        'uploaded_at',
        'created_by',
    ];

    protected $casts = [
        'available_formats' => 'array',
        'dimensions'        => 'array',
        'uploaded_at'       => 'date',
    ];
    protected static function booted(): void
    {
        static::creating(function (Asset $a) {
            if (empty($a->slug)) $a->slug = Str::slug($a->title);
        });
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }

    public function media()
    {
        return $this->hasMany(AssetMedia::class)->orderBy('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '—';
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 1) . ' ' . $units[$i];
    }
    public function downloadLogs()
    {
        return $this->hasMany(DownloadLog::class, 'model_id')
            ->where('model', class_basename($this));
    }

    public function getTotalDownloadsAttribute(): int
    {
        return $this->downloadLogs()->sum('count');
    }
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function isBookmarkedBy($userId): bool
    {
        return $this->bookmarks()->where('user_id', $userId)->exists();
    }
}
