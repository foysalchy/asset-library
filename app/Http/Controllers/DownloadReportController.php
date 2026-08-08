<?php

namespace App\Http\Controllers;

use App\Models\DownloadLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DownloadReportController extends Controller
{
    // DownloadReportController.php

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'today');
        [$startDate, $endDate] = $this->resolveDateRange($filter, $request);

        $query = DownloadLog::with('user:id,name,email,avatar')
            ->whereBetween('updated_at', [$startDate, $endDate]);

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

      if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

        $logs = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();

        $totalDownloads = (clone $query)->sum('count');
        $uniqueUsers    = (clone $query)->distinct('user_id')->count('user_id');
        $uniqueAssets   = (clone $query)->distinct('model_id')->count('model_id');
        $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
        return view('reports.downloads', compact(
            'logs',
            'filter',
            'startDate',
            'endDate',
            'totalDownloads',
            'uniqueUsers',
            'uniqueAssets',
            'users'
        ));
    }


    public function exportPdf(Request $request)
    {
        $filter = $request->get('filter', 'today');
        [$startDate, $endDate] = $this->resolveDateRange($filter, $request);

        $query = DownloadLog::with('user:id,name,email')
            ->whereBetween('updated_at', [$startDate, $endDate]);

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderByDesc('updated_at')->get(); // ✅ PDF-e pagination lagবে না, shob record

        $totalDownloads = $logs->sum('count');
        $uniqueUsers    = $logs->pluck('user_id')->unique()->count();
        $uniqueAssets   = $logs->pluck('model_id')->unique()->count();

        $pdf = Pdf::loadView('reports.downloads-pdf', compact(
            'logs',
            'filter',
            'startDate',
            'endDate',
            'totalDownloads',
            'uniqueUsers',
            'uniqueAssets'
        ))->setPaper('a4', 'landscape');

        $filename = 'download-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    protected function resolveDateRange(string $filter, Request $request): array
    {
        return match ($filter) {
            'today'      => [Carbon::today(), Carbon::today()->endOfDay()],
            'yesterday'  => [Carbon::yesterday(), Carbon::yesterday()->endOfDay()],
            'last_7_days' => [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'custom'     => [
                $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : Carbon::today(),
                $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : Carbon::today()->endOfDay(),
            ],
            default      => [Carbon::today(), Carbon::today()->endOfDay()],
        };
    }
}
