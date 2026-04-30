<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\Campaign;
use App\Models\Project;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'campaigns' => Campaign::count(),
            'assets'    => Asset::count(),
            'users'     => User::count(),
            'projects'  => Project::count(),
        ];

        $recentCampaigns = Campaign::with('project')
            ->latest()->limit(5)->get();

        $recentAssets = Asset::with('assetType')
            ->latest()->limit(5)->get();

        $recentLogs = ActivityLog::with('user')
            ->latest()->limit(8)->get();

        return view('dashboard.dashboard', compact(
            'stats',
            'recentCampaigns',
            'recentAssets',
            'recentLogs'
        ));
    }
}
