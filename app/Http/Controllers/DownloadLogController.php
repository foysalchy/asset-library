<?php

namespace App\Http\Controllers;

use App\Models\DownloadLog;
use App\Models\User;
use Illuminate\Http\Request;

class DownloadLogController extends Controller
{
public function index(Request $request)
{

    $query = DownloadLog::query()->with('user');

    if ($request->filled('model')) {
        $query->where('model', $request->model);
    }

    if ($request->filled('search')) {
        $search     = $request->search;
        $modelTypes = $request->filled('model')
            ? [$request->model]
            : ['campaign', 'asset'];

        $matchingIds = [];

        if (in_array('campaign', $modelTypes)) {
            $ids = \App\Models\Campaign::where('title', 'like', "%{$search}%")->pluck('id');
            foreach ($ids as $id) {
                $matchingIds[] = ['model' => 'campaign', 'id' => (string) $id];
            }
        }

        if (in_array('asset', $modelTypes)) {
            $ids = \App\Models\Asset::where('title', 'like', "%{$search}%")->pluck('id');
            foreach ($ids as $id) {
                $matchingIds[] = ['model' => 'asset', 'id' => (string) $id];
            }
        }


        if (empty($matchingIds)) {
            $query->whereRaw('1 = 0');
        } else {
            $query->where(function ($q) use ($matchingIds) {
                foreach ($matchingIds as $item) {
                    $q->orWhere(function ($q2) use ($item) {
                        $q2->where('model', $item['model'])
                           ->where('model_id', $item['id']);
                    });
                }
            });
        }
    }

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('updated_at', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('updated_at', '<=', $request->date_to);
    }

    // SQL query দেখো

    $logs = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();


    $logs->getCollection()->transform(function ($log) {
        $log->modelRecord = $this->getModelRecord($log->model, $log->model_id);
        return $log;
    });

    $stats = [
        'total_downloads'    => DownloadLog::sum('count'),
        'unique_users'       => DownloadLog::distinct('user_id')->count('user_id'),
        'campaign_downloads' => DownloadLog::where('model', 'campaign')->sum('count'),
        'asset_downloads'    => DownloadLog::where('model', 'asset')->sum('count'),
    ];

    $users = User::orderBy('name')->get(['id', 'name']);

    return view('download-logs.index', compact('logs', 'stats', 'users'));
}
  

    private function getModelRecord(string $model, string $modelId): ?object
    {
        $models = [
            'campaign' => \App\Models\Campaign::class,
            'asset'    => \App\Models\Asset::class,
        ];

        if (!isset($models[$model])) return null;

        return $models[$model]::find($modelId);
    }
}
