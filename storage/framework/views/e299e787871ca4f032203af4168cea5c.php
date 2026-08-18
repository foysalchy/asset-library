<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<?php $settings = site_settings(); ?>

<body class="bg-gray-50 dark:bg-gray-950 min-h-screen flex items-center justify-center p-4 font-['Outfit']">

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
        <?php if($errors->any()): ?>
            <div
                class="mb-5 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-900/20">
                <svg class="shrink-0 text-red-500 mt-0.5" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                </svg>
                <p class="text-sm text-red-700 dark:text-red-400"><?php echo e($errors->first()); ?></p>
            </div>
        <?php endif; ?>
        <div class="rounded-xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Create Account</h1>
                <p class="text-lg text-gray-500 dark:text-gray-400 mt-1">Join us today! Please enter your details.</p>
            </div>

            <form action="<?php echo e(route('signup')); ?>" method="POST" id="signupForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="recaptcha_token" id="recaptcha_token_signup">

                <div class="space-y-4">
                    
                    <div>
                        <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Full
                            Name</label>
                        <input type="text" name="name" value="<?php echo e(old('name')); ?>" placeholder="Enter Your Name" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Email
                            Address</label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="test@gmail.com"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
<div class="mb-4">
    <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Select Concern</label>
    <select name="concern" id="concern_select" required
        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        <option value="">-- Select Concern --</option>
        <?php $__currentLoopData = \App\Models\Project::CONCERNS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php echo e(old('concern') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>


<div class="mb-4">
    <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Employee Id</label>
    <div class="flex">
        <!-- প্রেফিক্স দেখানোর বক্স -->
        <span id="prefix_display"
            class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-600 font-bold dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
            ---
        </span>
        <!-- ইউজার শুধু আইডি নম্বর লিখবে -->
        <input type="text" name="employee_id_suffix" id="employee_id_suffix"
            value="<?php echo e(old('employee_id_suffix')); ?>"
            placeholder="Enter ID Number" required
            class="flex-1 rounded-r-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
    </div>
    <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>


                    
                    <div>
                        <label
                            class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Password</label>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>

                    
                    <button type="button" id="signupSubmitBtn"
                        class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium text-white shadow-md hover:bg-blue-700 transition-all">
                        Create Account
                    </button>
                </div>
            </form>


            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Already have an account?
                    <a href="<?php echo e(route('signin')); ?>" class="text-blue-600 hover:underline font-medium">Sign in</a>
                </p>
            </div>
            <div class="mt-4 text-center">
                <p class="text-lg text-gray-400 dark:text-gray-500">
                    Having trouble signing in or signing up?
                    <a href="<?php echo e(route('guest.tickets.create')); ?>" rel="noopener noreferrer"
                        class="inline-flex items-center gap-1 text-green-600 hover:underline font-medium">

                        Create a ticket
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo e(config('services.recaptcha.site_key')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('signupSubmitBtn');
        const form = document.getElementById('signupForm');

        if (!btn || !form) {
            console.error('Signup: button or form not found in DOM');
            return;
        }

        btn.addEventListener('click', function (e) {
            e.preventDefault();

            btn.disabled = true;
            btn.textContent = 'Please wait...';

            grecaptcha.ready(function () {
                grecaptcha.execute('<?php echo e(config('services.recaptcha.site_key')); ?>', { action: 'signup' })
                    .then(function (token) {
                        document.getElementById('recaptcha_token_signup').value = token;
                        form.submit();
                    })
                    .catch(function (error) {
                        console.error('reCAPTCHA error:', error);
                        btn.disabled = false;
                        btn.textContent = 'Create Account';
                        alert('Verification failed. Please try again.');
                    });
            });
        });
    });
</script>
<script>
    const concernPrefixes = <?php echo json_encode(\App\Models\Project::CONCERN_PREFIXES, 15, 512) ?>;

    document.getElementById('concern_select').addEventListener('change', function() {
        const prefix = concernPrefixes[this.value] || '---';
        document.getElementById('prefix_display').textContent = prefix;
    });

    // পেজ লোড হওয়ার সময় যদি ওল্ড ভ্যালু থাকে তবে প্রেফিক্স আপডেট করা
    window.addEventListener('load', function() {
        const select = document.getElementById('concern_select');
        if(select.value) {
            document.getElementById('prefix_display').textContent = concernPrefixes[select.value];
        }
    });
</script>
</html>
<?php /**PATH C:\laragon\www\asset-management\resources\views/frontend/auth/signup.blade.php ENDPATH**/ ?>