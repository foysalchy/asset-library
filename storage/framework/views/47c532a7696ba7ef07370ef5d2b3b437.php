<?php if($items->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
        <div class="flex items-center justify-between">
            
            <?php if($items->onFirstPage()): ?>
                <button disabled class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-400 opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800">
                    Previous
                </button>
            <?php else: ?>
                <a href="<?php echo e($items->previousPageUrl()); ?>" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Previous
                </a>
            <?php endif; ?>

            <ul class="hidden items-center gap-1 sm:flex">
                <?php $__currentLoopData = $items->getUrlRange(1, $items->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e($url); ?>" class="flex h-9 w-9 items-center justify-center rounded-lg text-sm font-medium <?php echo e($page == $items->currentPage() ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800'); ?>">
                            <?php echo e($page); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>

            
            <?php if($items->hasMorePages()): ?>
                <a href="<?php echo e($items->nextPageUrl()); ?>" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Next
                </a>
            <?php else: ?>
                <button disabled class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-400 opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800">
                    Next
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?><?php /**PATH C:\laragon\www\asset-management\resources\views/partials/pagination.blade.php ENDPATH**/ ?>