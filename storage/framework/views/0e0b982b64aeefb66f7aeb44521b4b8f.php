<?php $__env->startSection('content'); ?>
    <section class="container mx-auto px-8 py-10 font-['Outfit']">
        <!-- Page Title -->
        <h1 class="text-[#0071c5] text-4xl font-light mb-8">My Bookmarked Items</h1>

        <!-- ── Assets Section ── -->
        <div id="assets" class="mb-12">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-[22px] font-light text-gray-800">Bookmarked Assets (<?php echo e($assets->count()); ?>)</h2>
                <button type="button" class="w-6 h-6 rounded-full border-2 border-[#0071c5] text-[#0071c5] flex items-center justify-center text-xs" onclick="toggleSection('assetsGrid', this)">
                    <i class="fas fa-minus text-[10px]"></i>
                </button>
            </div>

            <div id="assetsGrid">
                <?php if($assets->count() > 0): ?>
                    <div id="assetsCardGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal895cdfb360c88ca78237e9e20ebefe47 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal895cdfb360c88ca78237e9e20ebefe47 = $attributes; } ?>
<?php $component = App\View\Components\Frontend\AssetCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('frontend.asset-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Frontend\AssetCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['asset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($asset),'selectable' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal895cdfb360c88ca78237e9e20ebefe47)): ?>
<?php $attributes = $__attributesOriginal895cdfb360c88ca78237e9e20ebefe47; ?>
<?php unset($__attributesOriginal895cdfb360c88ca78237e9e20ebefe47); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal895cdfb360c88ca78237e9e20ebefe47)): ?>
<?php $component = $__componentOriginal895cdfb360c88ca78237e9e20ebefe47; ?>
<?php unset($__componentOriginal895cdfb360c88ca78237e9e20ebefe47); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 italic py-10 text-center bg-white border border-dashed rounded-lg">No bookmarked assets found.</p>
                <?php endif; ?>
            </div>
        </div>
        <hr class="border-gray-300 mb-6" />

        <!-- ── Campaigns Section ── -->
        <div id="campaigns">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-[22px] font-light text-gray-800">Bookmarked Campaigns (<?php echo e($campaigns->count()); ?>)</h2>
                <button type="button" class="w-6 h-6 rounded-full border-2 border-[#0071c5] text-[#0071c5] flex items-center justify-center text-xs" onclick="toggleSection('campaignsGrid', this)">
                    <i class="fas fa-minus text-[10px]"></i>
                </button>
            </div>
            <div id="campaignsGrid">
                <?php if($campaigns->count() > 0): ?>
                    <div id="campaignsCardGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal0f89098fc988976a319558d2a570c936 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0f89098fc988976a319558d2a570c936 = $attributes; } ?>
<?php $component = App\View\Components\Frontend\CampaignCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('frontend.campaign-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Frontend\CampaignCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['campaign' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($campaign),'selectable' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0f89098fc988976a319558d2a570c936)): ?>
<?php $attributes = $__attributesOriginal0f89098fc988976a319558d2a570c936; ?>
<?php unset($__attributesOriginal0f89098fc988976a319558d2a570c936); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0f89098fc988976a319558d2a570c936)): ?>
<?php $component = $__componentOriginal0f89098fc988976a319558d2a570c936; ?>
<?php unset($__componentOriginal0f89098fc988976a319558d2a570c936); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 italic py-10 text-center bg-white border border-dashed rounded-lg">No bookmarked campaigns found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
     function toggleSection(sectionId, btn) {
            const section = document.getElementById(sectionId);
            const icon = btn.querySelector('i');

            section.classList.toggle('hidden');
            const isHidden = section.classList.contains('hidden');

            icon.classList.toggle('fa-plus', isHidden);
            icon.classList.toggle('fa-minus', !isHidden);

            localStorage.setItem(sectionId, isHidden ? 'hidden' : 'visible');
        }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/bookmarks.blade.php ENDPATH**/ ?>