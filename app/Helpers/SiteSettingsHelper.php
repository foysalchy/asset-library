<?php

namespace App\Helpers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingsHelper
{
    public static function get()
    {
        return Cache::remember('site_settings', now()->addHours(12), function () {
            return SiteSetting::first();
        });
    }
}
