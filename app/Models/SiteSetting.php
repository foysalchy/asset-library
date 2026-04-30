<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = ['site_name', 'slogan', 'logo', 'favicon'];

    public static function get(): self
    {
        return static::firstOrCreate([], [
            'site_name' => 'My App',
            'slogan'    => '',
        ]);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? Storage::url($this->logo) : null;
    }

    public function getFaviconUrlAttribute(): ?string
    {
        return $this->favicon ? Storage::url($this->favicon) : null;
    }
}