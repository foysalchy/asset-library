<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Support Ticket</title>
    <meta name="robots" content="noindex, nofollow">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<?php $settings = site_settings(); ?>

<body class="bg-gray-50 dark:bg-gray-950 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-5xl">

        
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

        
        <div class="rounded-xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

            
            <a href="<?php echo e(route('signin')); ?>" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mb-5 transition-colors">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 111.414 1.414L9.414 10l3.293 3.293a1 1 0 010 1.414z" />
                </svg>
                Back to Sign In
            </a>

            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Create Support Ticket</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Facing issues signing in or up? Let us know.</p>
            </div>

            
            <?php if($errors->any()): ?>
            <div class="mb-5 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-900/20">
                <svg class="shrink-0 text-red-500 mt-0.5" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                </svg>
                <p class="text-sm text-red-700 dark:text-red-400"><?php echo e($errors->first()); ?></p>
            </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
            <div class="mb-4 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 dark:border-green-800 dark:bg-green-900/20">
                <svg class="shrink-0 text-green-500 mt-0.5" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                </svg>
                <p class="text-sm text-green-700 dark:text-green-400"><?php echo e(session('success')); ?></p>
            </div>
            <?php endif; ?>

<form action="<?php echo e(route('guest.tickets.store')); ?>" method="POST" enctype="multipart/form-data" id="guestTicketForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                <div class="space-y-5">

                    
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="<?php echo e(old('name')); ?>"
                            placeholder="Your full name" required
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                    </div>


                    
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>"
                            placeholder="+880 1XX-XXXXXXX" required
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                    </div>

                    
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" value="<?php echo e(old('subject')); ?>"
                            placeholder="What is the issue about?" required
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                    </div>

                    
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Detailed Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="5" required
                            placeholder="Please provide as much detail as possible..."
                            class="shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 resize-none <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description')); ?></textarea>
                    </div>

                    
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Attachment <span class="text-gray-400 font-normal">(Optional Screenshot)</span>
                        </label>

                        <div class="flex flex-col gap-3">
                            <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-4 pb-5">
                                    <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload or drag and drop</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 uppercase">PNG, JPG or WEBP (Max 2MB)</p>
                                </div>
                                <input type="file" name="image" id="ticketImage" accept="image/*" class="hidden" onchange="previewFile()">
                            </label>

                            <div id="previewBox" class="hidden relative w-fit">
                                <p class="text-[10px] font-bold text-blue-600 mb-2 uppercase">Selected Preview:</p>
                                <img id="imagePreview" src="#" alt="preview" class="max-h-40 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                                <button type="button" onclick="removeImage()"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-all">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    
                 <button type="button" id="submitBtn"
        class="flex w-full items-center justify-center rounded-lg bg-blue-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
        Submit Support Ticket
    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500">
                Our support team typically responds within 24 hours.
            </p>
        </div>
    </div>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo e(config('services.recaptcha.site_key')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('guestTicketForm');

        console.log('Form element:', form);
        console.log('Submit button element:', submitBtn);

        if (!form) {
            console.error('Form with id "guestTicketForm" NOT FOUND in DOM');
            return;
        }

        if (!submitBtn) {
            console.error('Button with id "submitBtn" NOT FOUND in DOM');
            return;
        }

        submitBtn.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('Submit button clicked');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            grecaptcha.ready(function () {
                grecaptcha.execute('<?php echo e(config('services.recaptcha.site_key')); ?>', { action: 'guest_ticket_submit' })
                    .then(function (token) {
                        console.log('Token generated:', token);
                        document.getElementById('recaptcha_token').value = token;
                        form.submit();
                    })
                    .catch(function (error) {
                        console.error('reCAPTCHA execute error:', error);
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit Support Ticket';
                        alert('Verification failed: ' + error);
                    });
            });
        });
    });
</script>
    <script>
        function previewFile() {
            const file = document.getElementById('ticketImage').files[0];
            const preview = document.getElementById('imagePreview');
            const previewBox = document.getElementById('previewBox');
            const reader = new FileReader();

            if (file) {
                reader.onload = function() {
                    preview.src = reader.result;
                    previewBox.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            const input = document.getElementById('ticketImage');
            const previewBox = document.getElementById('previewBox');
            input.value = "";
            previewBox.classList.add('hidden');
        }
    </script>

</body>

</html><?php /**PATH C:\laragon\www\asset-management\resources\views/frontend/tickets/guest.blade.php ENDPATH**/ ?>