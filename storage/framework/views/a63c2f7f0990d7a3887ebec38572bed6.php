

<?php $__env->startSection('content'); ?>

<div class="p-4 mx-auto max-w-screen-2xl md:p-6">

    
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="<?php echo e(route('campaigns.index')); ?>" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Campaigns</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
        </svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium truncate max-w-[300px]"><?php echo e($campaign->title); ?></span>
    </nav>

    
    <?php if(session('success')): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 dark:border-green-800 dark:bg-green-900/20">
        <svg class="shrink-0 text-green-500" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
        </svg>
        <p class="text-sm font-medium text-green-700 dark:text-green-400"><?php echo e(session('success')); ?></p>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">

                
                <?php if($campaign->thumbnail_url): ?>
                <div class="w-full h-48 overflow-hidden">
                    <img src="<?php echo e($campaign->thumbnail_url); ?>" alt="<?php echo e($campaign->title); ?>"
                        class="w-full h-full object-cover">
                </div>
                <?php endif; ?>

                
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <h1 class="text-xl font-semibold text-gray-800 dark:text-white/90"><?php echo e($campaign->title); ?></h1>
                                <?php if($campaign->is_featured): ?>
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                    ★ Featured
                                </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-400 dark:text-gray-500 font-mono"><?php echo e($campaign->slug); ?></p>
                        </div>
                        <span class="shrink-0 px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize <?php echo e($campaign->statusBadgeClass); ?>">
                            <?php echo e($campaign->status); ?>

                        </span>
                    </div>
                </div>

                
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Description</h3>
                    <?php if($campaign->description): ?>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap"><?php echo e($campaign->description); ?></p>
                    <?php else: ?>
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">No description provided.</p>
                    <?php endif; ?>
                </div>

                
                <div class="grid grid-cols-2 divide-x divide-gray-200 dark:divide-gray-700">
                    <div class="p-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Published</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            <?php echo e($campaign->published_at ? $campaign->published_at->format('M d, Y') : '—'); ?>

                        </p>
                    </div>
                    <div class="p-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Expires</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            <?php echo e($campaign->expired_at ? $campaign->expired_at->format('M d, Y') : '—'); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="space-y-5">

            
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Actions</h3>
                <div class="space-y-2">
                    <?php if($campaign->file_url): ?>
                    <a href="<?php echo e(route('drive.file.stream', ['type' => 'campaign', 'id' => $campaign->id])); ?>"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-colors">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" />
                        </svg>
                        Download
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('campaigns.edit', $campaign)); ?>"
                        class="flex items-center gap-3 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.05] transition-colors">
                        <svg class="text-gray-400" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit Campaign
                    </a>

                    <form action="<?php echo e(route('campaigns.destroy', $campaign)); ?>" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this campaign?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit"
                            class="flex items-center gap-3 w-full rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-medium text-red-500 shadow-theme-xs hover:bg-red-50 dark:border-red-900/40 dark:bg-transparent dark:hover:bg-red-900/20 transition-colors">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                            </svg>
                            Delete Campaign
                        </button>
                    </form>
                </div>
            </div>

            
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Details</h3>
                <dl class="space-y-3">
                    
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Languages</dt>
                        <dd class="flex flex-wrap gap-1.5">
                            <?php $__currentLoopData = $campaign->languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 uppercase dark:bg-gray-700 dark:text-gray-300">
                                <?php if($lang === 'en'): ?> 🇬🇧 <?php elseif($lang === 'bn'): ?> 🇧🇩 <?php endif; ?>
                                <?php echo e($lang); ?>

                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </dd>
                    </div>

                    
                    <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Sort Order</dt>
                        <dd class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($campaign->sort_order); ?></dd>
                    </div>

                    
                    <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Featured</dt>
                        <dd>
                            <?php if($campaign->is_featured): ?>
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400">Yes</span>
                            <?php else: ?>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-400">No</span>
                            <?php endif; ?>
                        </dd>
                    </div>

                    
                    <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Created</dt>
                        <dd class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($campaign->created_at->format('M d, Y')); ?></dd>
                    </div>

                    
                    <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Last Updated</dt>
                        <dd class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($campaign->updated_at->diffForHumans()); ?></dd>
                    </div>
                </dl>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views\campaigns\show.blade.php ENDPATH**/ ?>