<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'project_id',
        'description',
        'status',
        'is_featured',
        'thumbnail',
        'file',
        'languages',
        'sort_order',
        'created_by',
        'published_at',
        'expired_at',
    ];

    protected $casts = [
        'languages'    => 'array',
        'is_featured'  => 'boolean',
        'published_at' => 'date',
        'expired_at'   => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Campaign $c) {
            if (empty($c->slug)) $c->slug = Str::slug($c->title);
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active'  => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'draft'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'expired' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default   => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
        };
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) return null;

        $path = ltrim(str_replace('public/', '', $this->thumbnail), '/');
        return Storage::disk('public')->url($path);
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file ? Storage::disk('public')->url($this->file) : null;
    }

    public function getFileNameAttribute(): ?string
    {
        return $this->file ? basename($this->file) : null;
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
}
