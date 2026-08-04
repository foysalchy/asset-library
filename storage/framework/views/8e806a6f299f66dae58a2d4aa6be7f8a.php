<?php $__currentLoopData = $downloadLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="flex items-start gap-3">
        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5 bg-purple-100 dark:bg-purple-900/30">
            <svg class="text-purple-500" width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 3a1 1 0 011 1v7.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 11.586V4a1 1 0 011-1zM4 15a1 1 0 011 1v1a1 1 0 001 1h8a1 1 0 001-1v-1a1 1 0 112 0v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1a1 1 0 011-1z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                 <span class="font-medium"><?php echo e($log->file_name. "-" ?? ""); ?><?php echo e($log->title); ?></span>
                <?php if($log->count > 1): ?>
                    <span class="text-xs text-gray-400">(<?php echo e($log->count); ?> times)</span>
                <?php endif; ?>
            </p>
            <p class="text-xs text-gray-400 mt-0.5">
                <?php echo e($log->created_at->diffForHumans()); ?>

                <?php if($log->ip_address): ?>
                    · <?php echo e($log->ip_address); ?>

                <?php endif; ?>
            </p>
        </div>
        <span class="text-xs text-gray-400 shrink-0"><?php echo e($log->model); ?></span>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views/users/partials/download-log-rows.blade.php ENDPATH**/ ?>