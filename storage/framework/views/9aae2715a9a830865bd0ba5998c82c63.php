<?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="flex items-start gap-3">
        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5
            <?php echo e(match($log->action) {
                'created' => 'bg-green-100 dark:bg-green-900/30',
                'updated' => 'bg-blue-100 dark:bg-blue-900/30',
                'deleted' => 'bg-red-100 dark:bg-red-900/30',
                default   => 'bg-gray-100 dark:bg-gray-800',
            }); ?>">
            <svg class="<?php echo e(match($log->action) {
                'created' => 'text-green-500',
                'updated' => 'text-blue-500',
                'deleted' => 'text-red-500',
                default   => 'text-gray-400',
            }); ?>" width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                <?php if($log->action === 'created'): ?>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 3.25C10.4142 3.25 10.75 3.58579 10.75 4V9.25H16C16.4142 9.25 16.75 9.58579 16.75 10C16.75 10.4142 16.4142 10.75 16 10.75H10.75V16C10.75 16.4142 10.4142 16.75 10 16.75C9.58579 16.75 9.25 16.4142 9.25 16V10.75H4C3.58579 10.75 3.25 10.4142 3.25 10C3.25 9.58579 3.58579 9.25 4 9.25H9.25V4C9.25 3.58579 9.58579 3.25 10 3.25Z"/>
                <?php elseif($log->action === 'updated'): ?>
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                <?php else: ?>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                <?php endif; ?>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm text-gray-700 dark:text-gray-300"><?php echo e($log->description); ?></p>
            <p class="text-xs text-gray-400 mt-0.5"><?php echo e($log->created_at->diffForHumans()); ?></p>
        </div>
        <span class="text-xs text-gray-400 shrink-0"><?php echo e($log->model_type); ?></span>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views/users/partials/activity-log-rows.blade.php ENDPATH**/ ?>