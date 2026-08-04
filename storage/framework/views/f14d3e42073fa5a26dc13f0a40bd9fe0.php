<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="Explore our Asset">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo e($setup->title ?? 'Bhaiya Asset Library'); ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="">
    <!-- Outfit Font -->
    <link rel="stylesheet" media="print" onload="this.media='all'"
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap">

    <!-- Bangla Fonts -->
    <link rel="stylesheet" media="print" onload="this.media='all'"
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&family=Hind+Siliguri:wght@400;500;600;700&family=Baloo+Da+2:wght@400;500;600;700&family=Atma:wght@400;500;600;700&family=Galada&family=Tiro+Bangla:ital@0;1&display=swap">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        media="print" onload="this.media='all'">


    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

</head>

<body>
    <?php echo $__env->make('frontend.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Content Area -->
    <main class="bg-[#f9f9fb]  ">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- FOOTER -->
    <?php echo $__env->make('frontend.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if (isset($component)) { $__componentOriginalb0886da97c39b6523320c208185b9dbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb0886da97c39b6523320c208185b9dbc = $attributes; } ?>
<?php $component = App\View\Components\Frontend\ShareModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('frontend.share-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Frontend\ShareModal::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb0886da97c39b6523320c208185b9dbc)): ?>
<?php $attributes = $__attributesOriginalb0886da97c39b6523320c208185b9dbc; ?>
<?php unset($__attributesOriginalb0886da97c39b6523320c208185b9dbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb0886da97c39b6523320c208185b9dbc)): ?>
<?php $component = $__componentOriginalb0886da97c39b6523320c208185b9dbc; ?>
<?php unset($__componentOriginalb0886da97c39b6523320c208185b9dbc); ?>
<?php endif; ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>

    
<?php echo $__env->make('frontend.layouts.partials.fcm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</script>
    <script>
        function toggleBookmark(btn, type, id) {
            fetch('/bookmark', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        type,
                        id
                    })
                })
                .then(r => r.json())
                .then(data => {
                    const icon = btn.querySelector('i');
                    if (data.bookmarked) {
                        icon.className = 'fa-solid fa-bookmark text-2xl';
                        btn.classList.remove('text-[#00aeef]');
                        btn.classList.add('text-[#0071c5]');
                    } else {
                        icon.className = 'fa-regular fa-bookmark text-2xl';
                        btn.classList.remove('text-[#0071c5]');
                        btn.classList.add('text-[#00aeef]');
                    }
                });
        }

        // ── Notification ──
        function toggleNotifDropdown() {
            document.getElementById('notifDropdown').classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notifWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('notifDropdown').classList.add('hidden');
            }
        });

        function markRead(e, id, url) {
            e.preventDefault();
            const el = document.getElementById(`notif-${id}`);

            fetch(`/notification/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                if (el) {
                    // ১. আনরিড ক্লাসেস রিমুভ করা
                    el.classList.remove('bg-blue-50/50', 'border-l-4', 'border-[#0071c5]');
                    // ২. রিড ক্লাসেস অ্যাড করা
                    el.classList.add('opacity-60', 'bg-white');

                    const title = el.querySelector('p');
                    if (title) title.classList.replace('font-bold', 'font-normal');

                    const dot = el.querySelector('.unread-dot');
                    if (dot) dot.remove();
                }
                window.location.href = url;
            });
        }

        function markAllRead() {
            fetch('/notification/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                // সব নোটিফিকেশনকে রিড স্টাইলে নিয়ে আসা
                document.querySelectorAll('[id^="notif-"]').forEach(el => {
                    el.classList.remove('bg-blue-50/50', 'border-l-4', 'border-[#0071c5]');
                    el.classList.add('opacity-60', 'bg-white');

                    const title = el.querySelector('p');
                    if (title) title.classList.replace('font-bold', 'font-normal');

                    const dot = el.querySelector('.unread-dot');
                    if (dot) dot.remove();
                });

                const badge = document.getElementById('notifBadge');
                if (badge) badge.remove();
            });
        }
    </script>
</body>



</html><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/layouts/font.blade.php ENDPATH**/ ?>