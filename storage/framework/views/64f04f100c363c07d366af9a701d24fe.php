<?php $__env->startSection('content'); ?>
    <section class="container mx-auto px-6 py-12 font-['Outfit']">

        <div class="mb-12 pb-6">
            <h1 class="text-[#0071c5] text-4xl font-light">Brand Assets</h1>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">

            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div
                    class="bg-white border border-gray-200 p-8 flex flex-col items-center justify-center text-center hover:shadow-md transition-all duration-300 group cursor-pointer h-full">

                    <div class="h-24 w-full flex items-center justify-center mb-6">
                        <?php if($project->logo): ?>
                            <img src="<?php echo e($project->logo_url); ?>" alt="<?php echo e($project->name); ?>"
                                class="max-h-full max-w-full object-contain transition-transform duration-500 group-hover:scale-110">
                        <?php else: ?>
                            <img src="<?php echo e(asset('./images/brand/brand-01.svg')); ?>" alt="<?php echo e($project->name); ?>"
                                class="max-h-full max-w-full object-contain transition-transform duration-500 group-hover:scale-110">
                        <?php endif; ?>
                    </div>

                    <h3
                        class="text-[#0071c5] text-lg tracking-[2px] leading-tight group-hover:text-[#005ea3]">
                        <?php echo e($project->name); ?>

                    </h3>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

        <div class="mt-12">
            <?php echo e($projects->links()); ?>

        </div>

        <?php if($projects->isEmpty()): ?>
            <div class="py-20 text-center text-gray-400">
                <p>No brand assets found.</p>
            </div>
        <?php endif; ?>

    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/brandAsset.blade.php ENDPATH**/ ?>