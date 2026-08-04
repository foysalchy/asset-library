<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\Campaign;
use App\Models\DownloadLog;
use App\Models\Project;
use App\Models\User;

class DashboardController extends Controller
{
  public function index()
{
    $stats = [
        'total_downloads' => DownloadLog::sum('count'),
        'assets'          => Asset::count(),
        'users'           => User::count(),
        'projects'        => Project::count(),
    ];

    $topDownloaders = DownloadLog::selectRaw('user_id, SUM(count) as total_downloads')
        ->with('user:id,name,avatar')
        ->groupBy('user_id')
        ->orderByDesc('total_downloads')
        ->limit(5)
        ->get();

    $recentAssets = Asset::with('assetType')
        ->latest()->limit(5)->get();

    $recentLogs = ActivityLog::with('user')
        ->latest()->limit(8)->get();

    return view('dashboard.dashboard', compact(
        'stats',
        'topDownloaders',
        'recentAssets',
        'recentLogs'
    ));
}
}
