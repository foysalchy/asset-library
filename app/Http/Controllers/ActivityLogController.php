<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query()->with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs       = $query->paginate(20)->withQueryString();
        $users      = User::orderBy('name')->get();
        $modelTypes = ActivityLog::distinct()->orderBy('model_type')->pluck('model_type');

        return view('activity-logs.index', compact('logs', 'users', 'modelTypes'));
    }
}