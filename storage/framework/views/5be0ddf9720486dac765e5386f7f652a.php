
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #f4f5f7;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            padding: 40px 0;
        }

        .container {
            max-width: 480px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .header {
            background: linear-gradient(135deg, #0071c5 0%, #005ea3 100%);
            padding: 36px 32px;
            text-align: center;
        }

        .header .logo-box {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .header .logo-box img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .header .site-name {
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.3px;
            margin: 0;
        }

        .header .slogan {
            color: rgba(255, 255, 255, 0.75);
            font-size: 12px;
            margin: 4px 0 0;
        }

        .body {
            padding: 40px 36px;
        }

        .body h1 {
            font-size: 21px;
            color: #1f2937;
            margin: 0 0 12px;
            text-align: center;
            font-weight: 600;
        }

        .body p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.7;
            margin: 0 0 20px;
            text-align: center;
        }

        .btn-wrap {
            text-align: center;
            margin: 28px 0;
        }

        .btn {
            display: inline-block;
            background: #0071c5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .divider {
            border-top: 1px solid #eef0f2;
            margin: 28px 0 20px;
        }

        .link-fallback {
            font-size: 12px;
            color: #9ca3af;
            word-break: break-all;
            text-align: center;
            line-height: 1.6;
        }

        .link-fallback a {
            color: #0071c5;
        }

        .expiry-note {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff7ed;
            color: #c2410c;
            font-size: 12px;
            padding: 8px 14px;
            border-radius: 8px;
        }

        .expiry-wrap {
            text-align: center;
            margin-bottom: 24px;
        }

        .footer {
            padding: 24px 36px;
            background: #fafbfc;
            border-top: 1px solid #eef0f2;
            text-align: center;
        }

        .footer p {
            font-size: 12px;
            color: #9ca3af;
            margin: 0 0 4px;
        }
    </style>
</head>

<body>
    <?php $settings = site_settings(); ?>
    <div class="wrapper">
        <div class="container">

            <div class="header">
                <div class="logo-box">
                    <?php if($settings && $settings->logo): ?>
                    <img src="<?php echo e(url($settings->logo_url)); ?>" alt="<?php echo e($settings->site_name); ?>">
                    <?php else: ?>
                    <span style="color:#fff; font-size:24px; font-weight:bold;">
                        <?php echo e(strtoupper(substr($settings->site_name ?? 'Bhaiya Asset', 0, 1))); ?>

                    </span>
                    <?php endif; ?>
                </div>
                <p class="site-name"><?php echo e($settings->site_name ?? 'Bhaiya Asset'); ?></p>
                <?php if($settings && $settings->slogan): ?>
                <p class="slogan"><?php echo e($settings->slogan); ?></p>
                <?php endif; ?>
            </div>

            <div class="body">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

            <div class="footer">
                <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($settings->site_name ?? config('app.name')); ?>. All rights reserved.</p>
                <p>This is an automated email, please do not reply directly.</p>
            </div>

        </div>
    </div>
</body>

</html><?php /**PATH C:\laragon\www\asset-management\resources\views/emails/layout.blade.php ENDPATH**/ ?>