<?php $__env->startSection('content'); ?>
    <section class="max-w-screen-2xl mx-auto px-4 md:px-8 py-10">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <h1 class="text-2xl md:text-3xl font-light text-[#0071c5]">My Tickets</h1>
            <a href="<?php echo e(route('tickets.create')); ?>"
                class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-6 py-2.5 text-sm font-bold transition-colors w-full sm:w-auto text-center">
                <i class="fas fa-plus mr-2"></i> New Ticket
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-sm mb-6 text-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 text-gray-600 font-semibold">#</th>
                            <th class="text-left px-6 py-3 text-gray-600 font-semibold">Subject</th>
                            <th class="text-left px-6 py-3 text-gray-600 font-semibold">Status</th>
                            <th class="text-left px-6 py-3 text-gray-600 font-semibold">Replies</th>
                            <th class="text-left px-6 py-3 text-gray-600 font-semibold">Date</th>
                            <th class="text-left px-6 py-3 text-gray-600 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">#<?php echo e($ticket->id); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800"><?php echo e($ticket->subject); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                        $colors = [0 => 'green', 1 => 'yellow', 2 => 'red'];
                                        $color = $colors[$ticket->status];
                                    ?>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold
                                bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-700">
                                        <?php echo e($ticket->status_label); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?php echo e($ticket->replies_count); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?php echo e($ticket->created_at->format('d M Y')); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="<?php echo e(route('tickets.show', $ticket)); ?>"
                                        class="text-[#0071c5] text-xs font-bold hover:underline uppercase tracking-wide">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 whitespace-nowrap">
                                    No tickets yet. <a href="<?php echo e(route('tickets.create')); ?>"
                                        class="text-[#0071c5] underline">Create one</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                <?php echo e($tickets->links()); ?>

            </div>
        </div>

    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/tickets/index.blade.php ENDPATH**/ ?>