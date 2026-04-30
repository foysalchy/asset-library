<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use App\Helpers\FileUploadHelper;
use App\Services\ActivityLogService;

class SiteSettingController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLog
    ) {}
    public function index()
    {
        $setting = SiteSetting::get();
        return view('site-settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'slogan'    => ['nullable', 'string', 'max:255'],
            'logo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,ico', 'max:512'],
        ]);

        $setting = SiteSetting::get();
        $data    = $request->only('site_name', 'slogan');

        if ($request->hasFile('logo')) {
            FileUploadHelper::deleteImage($setting->logo);
            $data['logo'] = FileUploadHelper::uploadImage($request->file('logo'), 'settings');
        } elseif ($request->boolean('remove_logo')) {
            FileUploadHelper::deleteImage($setting->logo);
            $data['logo'] = null;
        }

        if ($request->hasFile('favicon')) {
            FileUploadHelper::deleteImage($setting->favicon);
            $data['favicon'] = FileUploadHelper::uploadImage($request->file('favicon'), 'settings');
        } elseif ($request->boolean('remove_favicon')) {
            FileUploadHelper::deleteImage($setting->favicon);
            $data['favicon'] = null;
        }

        $setting->update($data);
        $this->activityLog->log('updated', $setting, "Updated site settings");

        return back()->with('success', 'Settings updated successfully.');
    }
}
