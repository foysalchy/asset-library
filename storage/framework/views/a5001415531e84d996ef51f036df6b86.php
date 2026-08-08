<?php $settings = site_settings(); ?>

<footer class="bg-[#001e3e] text-white font-['Outfit',sans-serif] pt-12 px-6 pb-6">
    <div class="max-w-[1200px] mx-auto">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_1fr] gap-10 pb-10 border-b border-white/10">

            <!-- Brand -->
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-2.5 mb-4">

                    <div>
                        <div class="text-xl font-bold text-white leading-none">Bhaiya Asset </div>
                        <div class="text-sm text-[#90caf9] mt-0.5">Asset Management Platform</div>
                    </div>
                </div>
                <p class="text-sm text-white/55 leading-relaxed mb-5">
                    Centralized digital asset library for Bhaiya Housing, Bhaiya Hotel &amp; Resort, and Right Aid Hospital. Access, manage, and distribute marketing assets seamlessly.
                </p>
                <div class="flex gap-2.5">
                    <a href="https://www.facebook.com/bhaiyadigitalofficial/" target="_blank" class="w-[34px] h-[34px] rounded-md bg-white/[0.08] border border-white/10 flex items-center justify-center text-white/60 hover:text-[#48b5e6] hover:border-[#48b5e6]/40 transition-colors text-sm">
                        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                    </a>

                    <a href="https://www.linkedin.com/company/bhaiya-digital/" target="_blank" class="w-[34px] h-[34px] rounded-md bg-white/[0.08] border border-white/10 flex items-center justify-center text-white/60 hover:text-[#48b5e6] hover:border-[#48b5e6]/40 transition-colors text-sm">
                        <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
                    </a>

                </div>
            </div>

            <!-- Concerns -->
            <div>
                <h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Our Concerns</h4>
                <ul class="list-none p-0 m-0 flex flex-col gap-2.5">
                    <li><a href="https://bhaiyahousing.com/" target="_blank" class="text-sm text-white/55 hover:text-[#48b5e6] no-underline flex items-center gap-1.5 transition-colors"><i class="fa-solid fa-building text-sm" aria-hidden="true"></i>Bhaiya Housing</a></li>
                    <li><a href="#" class="text-sm text-white/55 hover:text-[#48b5e6] no-underline flex items-center gap-1.5 transition-colors"><i class="fa-solid fa-hotel text-sm" aria-hidden="true"></i>Bhaiya Hotel &amp; Resort</a></li>
                    <li><a href="https://rightaidhospital.com/" target="_blank" class="text-sm text-white/55 hover:text-[#48b5e6] no-underline flex items-center gap-1.5 transition-colors"><i class="fa-solid fa-heart-pulse text-sm" aria-hidden="true"></i>Right Aid Hospital</a></li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Quick Links</h4>
                <ul class="list-none p-0 m-0 flex flex-col gap-2.5">
                    <li><a href="<?php echo e(route('home.index')); ?>" class="text-sm text-white/55 hover:text-[#48b5e6] no-underline transition-colors">Home</a></li>
                    <li><a href="<?php echo e(route('home.filter')); ?>" class="text-sm text-white/55 hover:text-[#48b5e6] no-underline transition-colors">Latest Assets</a></li>
                    <li><a href="<?php echo e(route('profile.index')); ?>" class="text-sm text-white/55 hover:text-[#48b5e6] no-underline transition-colors">Download History</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Contact</h4>
                <ul class="list-none p-0 m-0 flex flex-col gap-3">
                    <li class="flex gap-2 items-start">
                        <i class="fa-solid fa-location-dot text-sm text-[#48b5e6] mt-0.5 shrink-0" aria-hidden="true"></i>
                        <span class="text-sm text-white/55 leading-relaxed">House 85 Flat 3A, Road No. 3, Dhaka 1213</span>
                    </li>
                    <li class="flex gap-2 items-center">
                        <i class="fa-solid fa-envelope text-sm text-[#48b5e6]" aria-hidden="true"></i>
                        <a href="mailto:assetbhaiyadigital@gmail.com" class="text-sm text-white/55 hover:text-[#48b5e6] no-underline transition-colors">info@bhaiyahousing.com</a>
                    </li>
                    <li class="flex gap-2 items-center">
                        <i class="fa-solid fa-phone text-sm text-[#48b5e6]" aria-hidden="true"></i>
                        <a href="tel:+8801922-030303" class="text-sm text-white/55 hover:text-[#48b5e6] no-underline transition-colors">+880 1922-030303</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Bottom bar -->
        <div class="flex items-center justify-between flex-wrap gap-3 pt-5">
            <p class="text-lg text-white/35 m-0">
                &copy; 2025 <a href="https://www.bhaiya.digital/" target="_blank" class="text-white/35 text-decoration-none" style="cursor: pointer;">Bhaiya Digital</a>. All rights reserved.
            </p>
            <!-- <div class="flex gap-5">
                <a href="#" class="text-xs text-white/35 hover:text-[#48b5e6] no-underline transition-colors">Privacy Policy</a>
                <a href="#" class="text-xs text-white/35 hover:text-[#48b5e6] no-underline transition-colors">Terms of Use</a>
            </div> -->
        </div>

    </div>
</footer><?php /**PATH C:\laragon\www\asset-management\resources\views/frontend/partials/footer.blade.php ENDPATH**/ ?>