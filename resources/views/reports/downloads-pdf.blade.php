{{-- resources/views/reports/downloads-pdf.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        /* ===== Header banner ===== */
        .header {
            background: #0d9488;
            border-radius: 10px;
            padding: 22px 20px 18px;
            margin-bottom: 22px;
            text-align: center;
        }

        .logo-box-wrap {
            width: 58px;
            height: 58px;
            margin: 0 auto 10px;
            display: table;
        }

        .logo-box {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: #ffffff;
            text-align: center;
            vertical-align: middle;
            display: table-cell;
        }

        .logo-box img {
            width: 36px;
            height: 36px;
            margin-top: 11px;
        }

        .logo-box .initial {
            color: #0d9488;
            font-size: 26px;
            font-weight: bold;
        }

        .header .site-name {
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
            margin: 0;
            letter-spacing: 0.4px;
        }

        .header .report-title {
            display: inline-block;
            background: #14b8a6;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 4px 12px;
            border-radius: 20px;
            margin: 8px 0 0;
        }

        .header .period {
            font-size: 11px;
            color: #eef2ff;
            margin: 8px 0 0;
        }

        .header .generated {
            font-size: 9px;
            color: #c7d2fe;
            margin: 4px 0 0;
        }

        /* ===== Stats ===== */
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 22px;
        }

        .stat-box {
            display: table-cell;
            width: 33.33%;
            padding: 0 6px;
        }

        .stat-box .inner {
            border-radius: 8px;
            padding: 14px 10px;
            text-align: center;
            color: #ffffff;
        }

        .stat-box.c1 .inner {
            background: #ea580c;
        }

        .stat-box.c2 .inner {
            background: #e11d48;
        }

        .stat-box.c3 .inner {
            background: #0891b2;
        }

        .stat-box .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            opacity: 0.9;
        }

        .stat-box .value {
            font-size: 24px;
            font-weight: bold;
            margin-top: 5px;
        }

        /* ===== Table ===== */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        table.data th {
            background: #0071c5;
            color: #fff;
            text-align: left;
            padding: 9px 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        table.data th:first-child {
            border-top-left-radius: 6px;
        }

        table.data th:last-child {
            border-top-right-radius: 6px;
        }

        table.data td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }

        table.data tr:nth-child(even) {
            background: #f3f6fb;
        }

        table.data td.count-cell {
            text-align: center;
        }

        table.data .count-badge {
            display: inline-block;
            background: #eef2ff;
            color: #4f46e5;
            font-weight: bold;
            padding: 2px 9px;
            border-radius: 10px;
        }

        /* ===== Footer ===== */
        .footer {
            margin-top: 22px;
            padding-top: 10px;
            border-top: 2px solid #eef2ff;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }
    </style>
</head>

<body>

    @php $settings = site_settings(); @endphp

    <div class="header">


        <p class="site-name">{{ $settings->site_name ?? config('app.name') }}</p>
        <p class="report-title">Download Report</p>
        <p class="period">{{ $startDate->format('d M Y') }} — {{ $endDate->format('d M Y') }}</p>
        <p class="generated">Generated on {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box c1">
            <div class="inner">
                <div class="label">Total Downloads</div>
                <div class="value">{{ $totalDownloads }}</div>
            </div>
        </div>
        <div class="stat-box c2">
            <div class="inner">
                <div class="label">Unique Users</div>
                <div class="value">{{ $uniqueUsers }}</div>
            </div>
        </div>
        <div class="stat-box c3">
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
                <td class="count-cell"><span class="count-badge">{{ $log->count }}</span></td>
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
        &copy; {{ date('Y') }} {{ $settings->site_name ?? config('app.name') }} — Confidential Report
    </div>

</body>

</html>