
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <meta name="robots" content="noindex, nofollow">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
    <?php $settings = site_settings(); ?>

<body class="bg-gray-50 dark:bg-gray-950 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        
       <div class="flex justify-center mb-8">
            <div class="w-32 px-4 py-3   bg-gray-200 flex items-center justify-center">
                <?php if($settings && $settings->logo): ?>
                <img src="<?php echo e(url($settings->logo_url)); ?>" alt="<?php echo e($settings->site_name); ?>">
                <?php else: ?>
                <span style="color:#fff; font-size:24px; font-weight:bold;">
                    <?php echo e(strtoupper(substr($settings->site_name ?? 'Bhaiya Asset', 0, 1))); ?>

                </span>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="rounded-xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] text-center">

            <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                <svg width="28" height="28" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" class="text-blue-500">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" fill="currentColor" stroke="none" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" fill="currentColor" stroke="none" />
                </svg>
            </div>

            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90 mb-2">Verify Your Email</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                We've sent a verification link to <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo e(auth()->user()->email); ?></span>.
                Please check your inbox or spam folder and click the activation link to activate your account. </p>

            <?php if(session('success')): ?>
            <div class="mb-5 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 dark:border-green-800 dark:bg-green-900/20 text-left">
                <svg class="shrink-0 text-green-500 mt-0.5" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                </svg>
                <p class="text-sm text-green-700 dark:text-green-400"><?php echo e(session('success')); ?></p>
            </div>
            <?php endif; ?>

            <form action="<?php echo e(route('verification.send')); ?>" method="POST" class="mb-4">
                <?php echo csrf_field(); ?>
                <button type="submit"
                    class="flex w-full items-center justify-center rounded-lg bg-blue-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
                    Resend Verification Email
                </button>
            </form>

            <form action="<?php echo e(route('frontend.logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:underline">
                    Log out
                </button>
            </form>

        </div>
    </div>

</body>

</html><?php /**PATH C:\laragon\www\asset-management\resources\views/frontend/auth/verify-email.blade.php ENDPATH**/ ?>