<?php $__env->startSection('content'); ?>
<div class="p-4 mx-auto max-w-screen-2xl md:p-6">

    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="<?php echo e(route('assets.index')); ?>" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Assets</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
        </svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium"><?php echo e(Str::limit($asset->title, 40)); ?></span>
    </nav>

    
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90"><?php echo e($asset->title); ?></h1>
                <?php if($asset->asset_id_code): ?>
                <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    <?php echo e($asset->asset_id_code); ?>

                </span>
                <?php endif; ?>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?php echo e($asset->project->name); ?></p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <?php if($asset->file_url): ?>
            <a href="<?php echo e(route('drive.file.stream', ['type' => 'asset', 'id' => $asset->id])); ?>"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-colors">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" />
                </svg>
                Download
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('assets.edit', $asset)); ?>"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        
        <div class="lg:col-span-2 space-y-5">

            
            <?php if($asset->media->count()): ?>
            <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]"
                x-data="{ active: 0, total: <?php echo e($asset->media->count()); ?> }">

                
                <div class="relative bg-gray-900 aspect-video">
                    <?php $__currentLoopData = $asset->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div x-show="active === <?php echo e($i); ?>"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="absolute inset-0">
                        <?php if($media->media_type === 'image'): ?>
                        <img src="<?php echo e($media->url); ?>"
                            alt="<?php echo e($media->file_original_name); ?>"
                            class="w-full h-full object-contain">
                        <?php else: ?>
                        <video src="<?php echo e($media->url); ?>"
                            controls
                            class="w-full h-full object-contain"></video>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php if($asset->media->count() > 1): ?>
                    <button @click="active = (active - 1 + total) % total"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 flex items-center justify-center rounded-full bg-black/40 text-white hover:bg-black/60 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
                        </svg>
                    </button>
                    <button @click="active = (active + 1) % total"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 flex items-center justify-center rounded-full bg-black/40 text-white hover:bg-black/60 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                        </svg>
                    </button>

                    
                    <div class="absolute bottom-3 right-3 rounded-full bg-black/50 px-2.5 py-1 text-xs text-white">
                        <span x-text="active + 1"></span> / <?php echo e($asset->media->count()); ?>

                    </div>
                    <?php endif; ?>
                </div>

                
                <?php if($asset->media->count() > 1): ?>
                <div class="flex gap-2 p-3 overflow-x-auto bg-gray-50 dark:bg-gray-800/50">
                    <?php $__currentLoopData = $asset->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="active = <?php echo e($i); ?>"
                        class="shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all"
                        :class="active === <?php echo e($i); ?>

                                            ? 'border-blue-500 ring-2 ring-blue-500/20'
                                            : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'">
                        <?php if($media->media_type === 'image'): ?>
                        <img src="<?php echo e($media->url); ?>"
                            alt="<?php echo e($media->file_original_name); ?>"
                            class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full bg-gray-700 flex items-center justify-center">
                            <svg class="text-white" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                            </svg>
                        </div>
                        <?php endif; ?>
                    </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <?php if($asset->description): ?>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Description</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    <?php echo nl2br(e($asset->description)); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="space-y-5">

            
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Asset Details</h3>

                <div class="space-y-3 text-sm">

                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500 dark:text-gray-400 shrink-0">Type</span>
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            <?php echo e($asset->assetType->name); ?>

                        </span>
                    </div>

                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500 dark:text-gray-400 shrink-0">Project</span>
                        <a href="<?php echo e(route('projects.show', $asset->project)); ?>"
                            class="font-medium text-blue-500 hover:underline text-right truncate max-w-[180px]">
                            <?php echo e($asset->project->title); ?>

                        </a>
                    </div>

                    <?php if($asset->uploaded_at): ?>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500 dark:text-gray-400 shrink-0">Upload date</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">
                            <?php echo e($asset->uploaded_at->format('m/d/Y')); ?>

                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($asset->available_formats): ?>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500 dark:text-gray-400 shrink-0">Formats</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300 text-right">
                            <?php echo e(implode(', ', $asset->available_formats)); ?>

                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($asset->dimensions): ?>
                    <div class="flex flex-col gap-2">
                        <span class="text-gray-500 dark:text-gray-400">Dimensions</span>
                        <div class="flex flex-wrap gap-1">
                            <?php $__currentLoopData = $asset->dimensions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                <?php echo e($dim); ?>

                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($asset->file_path): ?>
                    <div class="pt-3 border-t border-gray-100 dark:border-gray-700 space-y-3">
                        <div class="flex justify-between gap-2">
                            <span class="text-gray-500 dark:text-gray-400 shrink-0">File size</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">
                                <?php echo e($asset->file_size_formatted); ?>

                            </span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-gray-500 dark:text-gray-400 shrink-0">File name</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300 text-right truncate max-w-[160px]"
                                title="<?php echo e($asset->file_original_name); ?>">
                                <?php echo e($asset->file_original_name); ?>

                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($asset->creator): ?>
                    <div class="flex justify-between gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400 shrink-0">Created by</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">
                            <?php echo e($asset->creator->name); ?>

                        </span>
                    </div>
                    <?php endif; ?>

                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500 dark:text-gray-400 shrink-0">Created</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">
                            <?php echo e($asset->created_at->format('M d, Y')); ?>

                        </span>
                    </div>

                </div>
            </div>

            
            <?php if($asset->media->count()): ?>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Media Files</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <svg class="text-purple-500" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            <?php echo e($asset->media->count()); ?> <?php echo e(Str::plural('file', $asset->media->count())); ?>

                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <?php echo e($asset->media->where('media_type', 'image')->count()); ?> image(s),
                            <?php echo e($asset->media->where('media_type', 'video')->count()); ?> video(s)
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/assets/show.blade.php ENDPATH**/ ?>