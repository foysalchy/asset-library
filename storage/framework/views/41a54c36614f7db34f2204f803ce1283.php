<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['asset', 'swiper' => false, 'selectable' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['asset', 'swiper' => false, 'selectable' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($swiper): ?>
<div class="swiper-slide h-auto pb-5">
<?php endif; ?>

<div class="bg-white border border-gray-100 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col h-full group cursor-pointer">
    <!-- Banner Area -->
    <div class="relative h-[200px] bg-[#001e3e] flex items-center justify-center overflow-visible p-4">
        <?php if($selectable): ?>
            <input type="checkbox"
                class="item-checkbox absolute top-3 left-4 z-30 w-5 h-5 cursor-pointer accent-[#0071c5]"
                data-type="asset"
                data-id="<?php echo e($asset->id); ?>">
        <?php endif; ?>
        <div class="h-full">
            <?php if($asset->media->first()?->media_type === 'image'): ?>
                <img src="<?php echo e($asset->media->first()->url); ?>" alt="<?php echo e($asset->title); ?>"
                    class="h-full w-auto shadow-2xl object-contain">
            <?php else: ?>
                <div class="h-full">
                    <img src="<?php echo e(asset('./images/cards/card-01.png')); ?>" alt="default"
                    class="h-full w-auto shadow-xl object-contain">
                </div>
            <?php endif; ?>
        </div>

        <?php $bookmarked = $asset->isBookmarkedBy(auth()->id()); ?>
        <button onclick="toggleBookmark(this, 'asset', <?php echo e($asset->id); ?>)"
            class="absolute top-2 right-4 hover:scale-110 transition-transform <?php echo e($bookmarked ? 'text-[#0071c5]' : 'text-[#00aeef]'); ?>" aria-label="Save to bookmarks">
            <i class="<?php echo e($bookmarked ? 'fa-solid' : 'fa-regular'); ?> fa-bookmark text-2xl"></i>
        </button>

        <?php if($asset->sort_order > 0): ?>
            <div class="absolute -bottom-3.5 left-4 bg-[#fdbb30] text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-md z-20">
                Featured
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 pt-9 flex flex-col flex-grow">
        <h3 class="text-[#005da4] text-lg font-semibold leading-snug min-h-[48px]">
            <a href="<?php echo e(route('asset.details', $asset->slug)); ?>"><?php echo e($asset->title); ?></a>
        </h3>

        <p class="text-[#757575] text-sm">
            Topics:
            <span class="font-normal text-gray-500"><?php echo e($asset->project->name ?? 'General'); ?></span>
        </p>

        <div class="mb-8 flex flex-wrap gap-2">
            <?php if($asset->available_formats): ?>
                <?php $__currentLoopData = json_decode($asset->available_formats); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $format): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="border border-[#005da4] text-[#005da4] px-3 py-0.5 rounded-full text-xs font-medium uppercase">
                        <?php echo e($format); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

        <div class="mt-auto flex items-center justify-between">
            <a href="<?php echo e(route('drive.file.stream', ['type' => 'asset', 'id' => $asset->id])); ?>" download
                class="flex items-center gap-2 text-[#005da4] text-xs font-bold uppercase hover:opacity-75">
                <i class="fa-solid fa-download text-sm"></i>
                <span>Download</span>
            </a>
            <a href="<?php echo e(route('asset.details', $asset->slug)); ?>"
                class="flex items-center gap-2 text-[#005da4] text-xs font-bold uppercase hover:opacity-75">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <span>More Details</span>
            </a>
        </div>
    </div>
</div>

<?php if($swiper): ?>
</div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\asset-library\resources\views/components/frontend/asset-card.blade.php ENDPATH**/ ?>