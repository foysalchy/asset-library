<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function getMenuGroups(): array
    {
        return [
            [
                'title' => 'Menu',
                'items' => self::getMainNavItems(),
            ],
        ];
    }

    public static function getMainNavItems(): array
    {
        $user = Auth::user();
        if (!$user) return [];

        $items = [
            [
                'icon'       => 'dashboard',
                'name'       => 'Dashboard',
                'path'       => route('dashboard'),
                'permission' => 'dashboard.view',
            ],
            [
                'icon'       => 'project',
                'name'       => 'Projects',
                'path'       => route('projects.index'),
                'permission' => 'projects.view',
            ],
            [
                'icon'       => 'asset-type',
                'name'       => 'Asset Types',
                'path'       => route('asset-types.index'),
                'permission' => 'asset_types.view',
            ],
            [
                'icon'       => 'campaign',
                'name'       => 'Campaigns',
                'path'       => route('campaigns.index'),
                'permission' => 'campaigns.view',
            ],
            [
                'icon'       => 'asset',
                'name'       => 'Asset Upload',
                'path'       => route('assets.index'),
                'permission' => 'assets.view',
            ],
            [
                'icon'       => 'user-profile',
                'name'       => 'Users',
                'path'       => route('users.index'),
                'permission' => 'users.view',
            ],
            [
                'icon'       => 'roles',
                'name'       => 'Roles & Permissions',
                'path'       => route('roles.index'),
                'permission' => 'roles.view',
            ],
            [
                'icon'       => 'activity-log',
                'name'       => 'Activity Logs',
                'path'       => route('activity-logs.index'),
                'permission' => 'activity_logs.view',
            ],
            [
                'icon'       => 'download-logs',
                'name'       => 'Download Logs',
                'path'       => route('download-logs.index'),
                'permission' => 'activity_logs.view',
            ],

            [
                'icon'       => 'settings',
                'name'       => 'Site Settings',
                'path'       => route('settings.index'),
                'permission' => 'settings.manage',
            ],
            [
                'icon'       => 'support',
                'name'       => 'Support Ticket',
                'path'       => route('ticket.admin'),
                'permission' => 'settings.manage',
            ],
        ];

        // Permission filter
        return array_filter($items, function ($item) use ($user) {
            if ($item['permission'] === null) return true;
            return $user->hasPermission($item['permission']);
        });
    }

    public static function isActive(string $path): bool
    {
        return request()->url() === $path
            || str_starts_with(request()->url(), rtrim($path, '/') . '/');
    }
    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',

            'project' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18M3 12h18M3 17h18"/><path d="M8 3l-5 4 5 4"/></svg>',

            'asset-type' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 10h16M4 14h10M4 18h6"/><circle cx="19" cy="16" r="3"/><path d="M21.5 18.5L23 20"/></svg>',

            'campaign' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',

            'asset' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>',

            'users' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',

            'roles' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>',

            'activity-log' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8M16 17H8M10 9H8"/></svg>',
'download-logs' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
            'user-profile' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
            'settings' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/></svg>',
          'support' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    <path d="M4 12a8 8 0 0116 0"/>
    <path d="M4 12v4a2 2 0 002 2h1a1 1 0 001-1v-4a1 1 0 00-1-1H4z"/>
    <path d="M20 12v4a2 2 0 01-2 2h-1a1 1 0 01-1-1v-4a1 1 0 011-1h3z"/>
    <path d="M12 18h4"/>
</svg>',

        ];

        return $icons[$iconName] ?? '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/></svg>';
    }
}
