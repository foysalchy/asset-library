
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

        .header {
            text-align: center;
            border-bottom: 2px solid #0071c5;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .header .logo {
            width: 48px;
            height: 48px;
            margin: 0 auto 8px;
            display: block;
        }

        .header .site-name {
            font-size: 18px;
            font-weight: bold;
            color: #0071c5;
            margin: 0;
        }

        .header .report-title {
            font-size: 13px;
            color: #374151;
            margin: 4px 0 0;
        }

        .header .period {
            font-size: 11px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        .header .generated {
            font-size: 9px;
            color: #9ca3af;
            margin: 6px 0 0;
        }

        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stat-box {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
        }

        .stat-box .inner {
            background: #f3f4f6;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }

        .stat-box .label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .stat-box .value {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 4px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data th {
            background: #0071c5;
            color: #fff;
            text-align: left;
            padding: 8px;
            font-size: 10px;
            text-transform: uppercase;
        }

        table.data td {
            padding: 7px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }

        table.data tr:nth-child(even) {
            background: #f9fafb;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }
    </style>
</head>

<body>

    <?php $settings = site_settings(); ?>

    <div class="header">
        <?php if($settings && $settings->logo): ?>
        <img src="<?php echo e(url($settings->logo_url)); ?>" alt="<?php echo e($settings->site_name); ?>">
        <?php else: ?>
        <span style="color:#fff; font-size:24px; font-weight:bold;">
            <?php echo e(strtoupper(substr($settings->site_name ?? 'Bhaiya Asset', 0, 1))); ?>

        </span>
        <?php endif; ?>
        <p class="site-name"><?php echo e($settings->site_name ?? config('app.name')); ?></p>
        <p class="report-title">Download Report</p>
        <p class="period"><?php echo e($startDate->format('d M Y')); ?> — <?php echo e($endDate->format('d M Y')); ?></p>
        <p class="generated">Generated on <?php echo e(now()->format('d M Y, h:i A')); ?></p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="inner">
                <div class="label">Total Downloads</div>
                <div class="value"><?php echo e($totalDownloads); ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="inner">
                <div class="label">Unique Users</div>
                <div class="value"><?php echo e($uniqueUsers); ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="inner">
                <div class="label">Unique Assets</div>
                <div class="value"><?php echo e($uniqueAssets); ?></div>
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
            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($log->user->name ?? 'Unknown'); ?></td>
                <td><?php echo e($log->user->email ?? '—'); ?></td>
                <td><?php echo e($log->display_name); ?></td>
                <td><?php echo e($log->count); ?></td>
                <td><?php echo e($log->ip_address ?? '—'); ?></td>
                <td><?php echo e($log->updated_at->format('d M Y, h:i A')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #9ca3af;">No downloads found for this period.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        &copy; <?php echo e(date('Y')); ?> <?php echo e($siteSetting->site_name ?? config('app.name')); ?> — Confidential Report
    </div>

</body>

</html><?php /**PATH C:\laragon\www\asset-management\resources\views/reports/downloads-pdf.blade.php ENDPATH**/ ?>