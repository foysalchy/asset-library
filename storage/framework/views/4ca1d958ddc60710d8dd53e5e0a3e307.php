<header class="w-full bg-[#003b7a] text-white sticky top-0 z-30 shadow-md">
    <div class="container mx-auto mx-auto flex justify-between items-center py-2">
        <!-- ── LOGO ── -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="<?php echo e(route('home.index')); ?>" class="bg-white px-2 py-2 flex items-center">
                <img src="" alt="Intel" class="h-[22px] w-auto block" />
            </a>
            <div class="flex flex-col justify-center leading-[0.9] text-white">
                <p class="text-xs uppercase tracking-[1.2px] mb-0.5 opacity-90">
                    partner <br />
                    marketing <br />
                    studio
                </p>
            </div>
        </div>

        <!-- ── NAV ── -->
        <nav class="hidden lg:flex items-stretch gap-0">
            <!-- Home (active) -->
            <a href="<?php echo e(route('home.index')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 border-b-2 border-white text-white">
                <i class="fas fa-home text-lg"></i>
                <span class="text-sm tracking-wide">Home</span>
            </a>

            <!-- Campaigns -->
            <a href="<?php echo e(route('home.index')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-bullhorn text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Campaigns <i
                        class="fas fa-chevron-down text-[7px] mt-px"></i></span>
            </a>

            <!-- Assets -->
            <a href="<?php echo e(route('home.index')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-box text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Assets <i
                        class="fas fa-chevron-down text-[7px] mt-px"></i></span>
            </a>

            <!-- Brand Assets -->
            <a href="<?php echo e(route('home.index')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-palette text-lg"></i>
                <span class="text-sm tracking-wide">Brand Assets</span>
            </a>

            <!-- Tools -->
            <a href="<?php echo e(route('home.index')); ?>"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fa-solid fa-screwdriver-wrench text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Tools <i
                        class="fas fa-chevron-down text-[7px] mt-px"></i></span>
            </a>
        </nav>
        <nav class="hidden lg:flex items-stretch gap-0">

            <?php if(auth()->guard()->guest()): ?>
                <!-- ১. যদি লগইন না করা থাকে (Guest) -->
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
                <a href="#"
                    class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">

                    <?php if(Auth::user()->getAttributes()['avator']): ?>
                        <img src="<?php echo e(Auth::user()->avator); ?>" alt="Profile"
                            class="w-6 h-6 rounded-full object-cover border border-white/50">
                    <?php else: ?>
                        <img src="<?php echo e(asset('images/user/user-36.jpg')); ?>" alt="Profile"
                            class="w-6 h-6 rounded-full object-cover border border-white/50">
                    <?php endif; ?>

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

            <a href="#"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fa-solid fa-globe text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">English <i
                        class="fas fa-chevron-down text-[7px] mt-px"></i></span>
            </a>

            <a href="#"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fa-regular fa-circle-question text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Help <i
                        class="fas fa-chevron-down text-[7px] mt-px"></i></span>
            </a>
        </nav>
    </div>
</header>
<?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/partials/header.blade.php ENDPATH**/ ?>