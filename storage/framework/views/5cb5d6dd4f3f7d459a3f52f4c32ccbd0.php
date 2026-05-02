<?php $__env->startSection('content'); ?>
    <section class="container mx-auto py-10 font-['Outfit']">
        <div class="flex items-start justify-between mb-10 px-4 lg:px-0">
            <div class="flex items-start gap-5">
                <!-- Back Button -->
                <a href="<?php echo e(url('/')); ?>" class="mt-2 text-[#0071c5] hover:opacity-70 transition-all shrink-0">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <!-- Dynamic Title -->
                <h1 class="text-[#0071c5] text-4xl font-light leading-[1.15] max-w-2xl">
                    <?php echo e($campaign->title); ?>

                </h1>
            </div>
            <!-- Bookmark -->
            <button class="text-[#00aeef] hover:scale-110 transition-transform shrink-0 mt-1">
                <i class="fa-regular fa-bookmark text-4xl"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start px-4 lg:px-8">
            <!-- ── LEFT COLUMN ── -->
            <div class="space-y-7">
                <!-- Content Area -->
                <section>
                    <h2 class="text-[#757575] text-2xl font-normal mb-4">Description</h2>
                    <div class="text-[#333] text-sm leading-relaxed space-y-4">
                        <?php echo $campaign->description; ?>

                    </div>
                </section>

                <!-- Legal Link -->
                <a href="#" class="text-[#757575] text-sm underline hover:text-[#0071c5] block">See legal disclaimers</a>

                <!-- Download Campaign Guide (Dynamic File Link) -->
                <a href="<?php echo e(route('drive.file.stream', ['type' => 'campaign', 'id' => $campaign->id])); ?>" target="_blank" class="flex items-center gap-4 py-2 group cursor-pointer border-none bg-transparent">
                    <div class="w-12 h-12 border-[1.5px] border-[#0071c5] flex items-center justify-center rounded-sm group-hover:bg-blue-50 transition-colors shrink-0">
                        <i class="fa-solid fa-book-open-reader text-[#0071c5] text-2xl"></i>
                    </div>
                    <div class="leading-tight">
                        <h4 class="text-[#0071c5] font-bold text-lg group-hover:underline">
                            Download the Campaign Guide
                        </h4>
                        <p class="text-gray-500 text-xs italic">for execution details</p>
                    </div>
                </a>

                <!-- Language Selector -->
                <div class="pt-4">
                    <div class="flex items-center gap-4">
                        <div class="relative w-[280px] border border-[#0071c5] flex items-center cursor-pointer">
                            <div class="flex-1 px-4 py-2.5 text-[#0071c5] font-bold text-sm border-r border-[#0071c5] uppercase">
                                <?php echo e(collect($campaign->languages)->first() ?? 'English'); ?>

                            </div>
                            <div class="px-4 py-2.5 flex items-center justify-center">
                                <i class="fas fa-chevron-down text-[#0071c5] text-xs"></i>
                            </div>
                        </div>
                        <button onclick="openGlobalShareModal(window.location.href, 'Check out this Campaign')" class="bg-[#0071c5] text-white px-8 py-2.5 flex items-center gap-3 font-bold text-[15px] hover:bg-[#005ea3] transition-all">
                            <i class="fa-solid fa-share-nodes"></i> Share
                        </button>
                    </div>
                    <p class="text-xs text-[#757575] mt-2 ml-1">Choose Another Language</p>
                </div>
            </div>

            <!-- ── RIGHT COLUMN: Poster Image ── -->
            <div class="flex justify-end">
                <div class="w-full relative shadow-2xl">
                    <img src="<?php echo e($campaign->thumbnail_url); ?>" alt="<?php echo e($campaign->title); ?>"
                        class="w-full h-auto block object-contain" />
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/campaignDetails.blade.php ENDPATH**/ ?>