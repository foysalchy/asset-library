<?php
$siteSetting = \App\Models\SiteSetting::first();

?>

<header class="w-full bg-[#003b7a] text-white sticky top-0 z-50 shadow-md" x-data="{ mobileMenu: false }">
    <div class="container mx-auto px-4 lg:px-6 flex justify-between items-center py-2">
        <!-- ── LOGO ── -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="<?php echo e(route('home.index')); ?>" class=" px-2 py-2 flex items-center">
                <img src="<?php echo e($siteSetting->logo_url); ?>" alt="Bhaiya Asset" class="  w-auto h-[40px] block" />
            </a>

        </div>
        <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-2xl p-2 focus:outline-none" aria-label="Toggle navigation menu">
            <i class="fa-solid" :class="mobileMenu ? 'fa-xmark' : 'fa-bars'"></i>
        </button>

        <!-- ── NAV ── -->
        <nav class="hidden lg:flex items-stretch gap-0">
            <!-- Home (active) -->
            <a href="<?php echo e(route('home.index')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 border-b-2 border-white text-white">
                <i class="fas fa-home text-lg"></i>
                <span class="text-sm tracking-wide">Home</span>
            </a>

            <!-- Campaigns -->
            <!-- <a href="<?php echo e(route('home.filter', ['section' => 'campaigns'])); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-bullhorn text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Campaigns </span>
            </a> -->

            <!-- Assets -->
            <a href="<?php echo e(route('home.filter', ['section' => 'assets', 'sort' => 'latest'])); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-box text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Assets </span>
            </a>

            <!-- Brand Assets -->
            <a href="<?php echo e(route('brand.index')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-palette text-lg"></i>
                <span class="text-sm tracking-wide">Brand Assets</span>
            </a>
        </nav>
        <nav class="hidden lg:flex items-stretch gap-0">

            <?php if(auth()->guard()->guest()): ?>
            <a href="<?php echo e(route('frontend.signin')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-sign-in-alt text-lg"></i>
                <span class="text-sm tracking-wide">Sign In</span>
            </a>

            <a href="<?php echo e(route('frontend.signup')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-user-plus text-lg"></i>
                <span class="text-sm tracking-wide">Sign Up</span>
            </a>
            <?php endif; ?>

            <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('profile.index')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">


                <img src="<?php echo e(Auth::user()->avatar_url ?? asset('./images/user/images.png')); ?>" alt="Profile"
                    class="w-6 h-6 rounded-full object-cover border border-white/50">

                <span class="text-sm tracking-wide"><?php echo e(Auth::user()->name); ?></span>
            </a>

            <form id="logout-form" action="<?php echo e(route('frontend.logout')); ?>" method="POST" class="hidden">
                <?php echo csrf_field(); ?>
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-red-400 transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-power-off text-lg"></i>
                <span class="text-sm tracking-wide">Logout</span>
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('bookmark.list')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <div class="relative">
                    <i class="fa-regular fa-bookmark text-lg"></i>
                    <?php if($bookmarkCount > 0): ?>
                    <span
                        class="absolute -top-2 -right-2 bg-white text-[#003b7a] text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
                        <?php echo e($bookmarkCount > 99 ? '99+' : $bookmarkCount); ?>

                    </span>
                    <?php endif; ?>
                </div>
                <span class="text-sm tracking-wide flex items-center gap-1">Bookmark</span>
            </a>
            <div class="relative" id="notifWrapper">
                <button onclick="toggleNotifDropdown()"
                    class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                    <div class="relative">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <?php if($unreadCount > 0): ?>
                        <span id="notifBadge"
                            class="absolute -top-2 -right-2 bg-white text-[#003b7a] text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            <?php echo e($unreadCount > 99 ? '99+' : $unreadCount); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                    <span class="text-sm tracking-wide">Notification</span>
                </button>

                <!-- Dropdown -->
                <div id="notifDropdown"
                    class="hidden absolute right-0 top-full mt-1 w-80 bg-white shadow-xl border border-gray-100 rounded-sm z-50">

                    <!-- Header -->
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <span class="text-sm font-semibold text-gray-700">Notifications</span>
                        <?php if($unreadCount > 0): ?>
                        <button onclick="markAllRead()"
                            class="text-[11px] text-[#0071c5] font-semibold hover:underline">
                            Mark all as read
                        </button>
                        <?php endif; ?>
                    </div>

                    <!-- List -->
                    <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                        // check notification by user
                        $userId = auth()->id();
                        $readBy = $notif->read_by ?? []; //
                        $isRead = in_array($userId, $readBy);
                        ?>

                        <a href="<?php echo e($notif->url); ?>"
                            onclick="markRead(event, <?php echo e($notif->id); ?>, '<?php echo e($notif->url); ?>')"
                            id="notif-<?php echo e($notif->id); ?>" 
                            class="flex items-start gap-3 px-4 py-4 transition-all border-b border-gray-100
                                <?php echo e(!$isRead ? 'bg-[#f0f7ff] border-l-4 border-l-[#0071c5]' : 'bg-white opacity-50'); ?> hover:bg-gray-50">

                            <div
                                class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 mt-0.5
                                    <?php echo e($notif->type === 'asset' ? 'bg-blue-100 text-blue-600' : 'bg-teal-100 text-teal-600'); ?>">
                                <i
                                    class="fa-solid <?php echo e($notif->type === 'asset' ? 'fa-file' : 'fa-bullhorn'); ?> text-xs"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-[13px] leading-snug <?php echo e(!$isRead ? 'font-bold text-gray-900' : 'font-normal text-gray-500'); ?>">
                                    <?php echo e($notif->title); ?>

                                </p>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    <?php echo e($notif->created_at->diffForHumans()); ?>

                                </p>
                            </div>

                            <?php if(!$isRead): ?>
                            <div class="unread-dot w-2.5 h-2.5 bg-[#0071c5] rounded-full shrink-0 mt-2"></div>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="px-4 py-10 text-center text-sm text-gray-400">
                            No notifications yet
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if(auth()->user()?->isSuperAdmin()): ?>
            <a href="<?php echo e(route('dashboard')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fa-solid fa-gauge text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Admin Dashboard</span>
            </a>
            <?php else: ?>
            <a href="<?php echo e(route('tickets.index')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fa-regular fa-circle-question text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Help</span>
            </a>
            <?php endif; ?>
        </nav>
        <!-- ── MOBILE NAV DRAWER (সব ডিভাইসে নিখুঁত কাজ করবে) ── -->
        <div x-show="mobileMenu"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-0 z-50 lg:hidden overflow-hidden" style="display: none;">

            <!-- ব্যাকগ্রাউন্ড ওভারলে -->
            <div class="absolute inset-0 bg-black/50" @click="mobileMenu = false"></div>

            <!-- মেনু কন্টেন্ট -->
            <div class="absolute right-0 top-0 h-full w-[280px] bg-[#001e3e] shadow-2xl flex flex-col p-6 space-y-6">
                <!-- ক্লোজ বাটন -->
                <div class="flex justify-end">
                    <button @click="mobileMenu = false" class="text-white text-3xl">&times;</button>
                </div>

                <div class="flex flex-col space-y-4 overflow-y-auto">
                    <a href="<?php echo e(route('home.index')); ?>" class="text-lg font-medium border-b border-white/10 pb-2">Home</a>
                    <a href="<?php echo e(route('home.filter', ['section' => 'campaigns'])); ?>" class="text-lg font-medium border-b border-white/10 pb-2">Campaigns</a>
                    <a href="<?php echo e(route('home.filter', ['section' => 'assets', 'sort' => 'latest'])); ?>" class="text-lg font-medium border-b border-white/10 pb-2">Assets</a>
                    <a href="<?php echo e(route('brand.index')); ?>" class="text-lg font-medium border-b border-white/10 pb-2">Brand Assets</a>

                    <div class="pt-6 space-y-4">
                        <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('frontend.signin')); ?>" class="flex items-center gap-3"><i class="fas fa-sign-in-alt"></i> Sign In</a>
                        <?php endif; ?>
                        <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('profile.index')); ?>" class="flex items-center gap-3">
                            <img src="<?php echo e(Auth::user()->avatar_url ?? asset('./images/user/owner.jpg')); ?>" class="w-8 h-8 rounded-full">
                            <span><?php echo e(Auth::user()->name); ?></span>
                        </a>
                        <?php endif; ?>

                        <a href="<?php echo e(route('bookmark.list')); ?>" class="flex items-center justify-between">
                            <span><i class="fa-regular fa-bookmark mr-2"></i> Saved Items</span>
                            <?php if($bookmarkCount > 0): ?> <span class="bg-red-500 px-2 rounded-full text-xs"><?php echo e($bookmarkCount); ?></span> <?php endif; ?>
                        </a>

                        <a href="<?php echo e(route('tickets.index')); ?>" class="flex items-center gap-3"><i class="fa-regular fa-circle-question"></i> Help Center</a>

                        <?php if(auth()->guard()->check()): ?>
                        <button onclick="document.getElementById('logout-form').submit();" class="text-red-400 font-bold pt-4 text-left">
                            <i class="fas fa-power-off mr-2"></i> Logout
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header><?php /**PATH C:\laragon\www\asset-management\resources\views/frontend/partials/header.blade.php ENDPATH**/ ?>