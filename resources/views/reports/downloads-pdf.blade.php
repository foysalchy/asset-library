<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1f2937; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0071c5; padding-bottom: 12px; margin-bottom: 16px; }
        .header h1 { font-size: 18px; margin: 0; color: #0071c5; }
        .header p { font-size: 11px; color: #6b7280; margin: 2px 0 0; }
        .stats { display: table; width: 100%; margin-bottom: 20px; }
        .stat-box { display: table-cell; width: 33.33%; padding: 10px; }
        .stat-box .inner { background: #f3f4f6; border-radius: 6px; padding: 12px; }
        .stat-box .label { font-size: 10px; color: #6b7280; text-transform: uppercase; }
        .stat-box .value { font-size: 20px; font-weight: bold; color: #1f2937; margin-top: 4px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background: #0071c5; color: #fff; text-align: left; padding: 8px; font-size: 10px; text-transform: uppercase; }
        table.data td { padding: 7px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table.data tr:nth-child(even) { background: #f9fafb; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <h1>Download Report</h1>
            <p>{{ $startDate->format('d M Y') }} — {{ $endDate->format('d M Y') }}</p>
        </div>
        <div style="text-align: right;">
            <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="inner">
                <div class="label">Total Downloads</div>
                <div class="value">{{ $totalDownloads }}</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="inner">
                <div class="label">Unique Users</div>
                <div class="value">{{ $uniqueUsers }}</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="inner">
                <div class="label">Unique Assets</div>
                <div class="value">{{ $uniqueAssets }}</div>
            </div>
        </div>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Email</th>
                <th>File Name</th>
                <th>Count</th>
                <th>IP Address</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $log->user->name ?? 'Unknown' }}</td>
                <td>{{ $log->user->email ?? '—' }}</td>
                <td>{{ $log->display_name }}</td>
                <td>{{ $log->count }}</td>
                <td>{{ $log->ip_address ?? '—' }}</td>
                <td>{{ $log->updated_at->format('d M Y, h:i A') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #9ca3af;">No downloads found for this period.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }} — Confidential Report
    </div>

</body>
</html>