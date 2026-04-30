<?php $__env->startSection('content'); ?>
    <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
        
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
            <a href="<?php echo e(route('projects.index')); ?>" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Projects</a>
            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
            <span class="text-gray-700 dark:text-gray-300 font-medium">Create Project</span>
        </nav>

        
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">New Project</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Add a new project to your portfolio</p>
        </div>

        <form action="<?php echo e(route('projects.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <?php echo $__env->make('projects._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <div class="flex items-center justify-end gap-3 mt-5">
                <a href="<?php echo e(route('projects.index')); ?>" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-600 transition-colors">
                    Create Project
                </button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/projects/create.blade.php ENDPATH**/ ?>