<?php $__env->startSection('content'); ?>
<section class="max-w-screen-2xl mx-auto px-8 py-10">

    <!-- Header -->
    <div class="flex items-start justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('tickets.index')); ?>" class="text-[#0071c5] hover:opacity-70">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-light text-[#0071c5]"><?php echo e($ticket->subject); ?></h1>
                <p class="text-sm text-gray-400 mt-1">#<?php echo e($ticket->id); ?> · <?php echo e($ticket->created_at->format('d M Y')); ?></p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <?php $colors = [0 => 'green', 1 => 'yellow', 2 => 'red']; $color = $colors[$ticket->status]; ?>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-700">
                <?php echo e($ticket->status_label); ?>

            </span>

            <?php if(auth()->user()->hasRole('admin') && $ticket->status !== 2): ?>
                <form action="<?php echo e(route('tickets.close', $ticket)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 text-xs font-bold transition-colors">
                        Close Ticket
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- LEFT: Conversation -->
        <div class="lg:col-span-2 space-y-4">

            <!-- Original Message -->
            <div class="bg-white border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-full bg-[#0071c5] flex items-center justify-center text-white text-sm font-bold shrink-0">
                        <?php echo e(strtoupper(substr($ticket->user->name, 0, 1))); ?>

                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800"><?php echo e($ticket->user->name); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($ticket->created_at->diffForHumans()); ?></p>
                    </div>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($ticket->description); ?></p>
                <?php if($ticket->image): ?>
                    <img src="<?php echo e($ticket->image_url); ?>" alt="attachment"
                        class="mt-4 max-w-sm rounded border border-gray-200">
                <?php endif; ?>
            </div>

            <!-- Replies -->
            <?php $__currentLoopData = $ticket->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white border shadow-sm p-6
                    <?php echo e($reply->is_admin ? 'border-[#0071c5]/30 bg-blue-50/30' : 'border-gray-100'); ?>">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0
                            <?php echo e($reply->is_admin ? 'bg-[#001e3e]' : 'bg-[#0071c5]'); ?>">
                            <?php echo e(strtoupper(substr($reply->user->name, 0, 1))); ?>

                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">
                                <?php echo e($reply->user->name); ?>

                                <?php if($reply->is_admin): ?>
                                    <span class="ml-2 text-[10px] bg-[#001e3e] text-white px-2 py-0.5 rounded-full uppercase tracking-wide">Admin</span>
                                <?php endif; ?>
                            </p>
                            <p class="text-xs text-gray-400"><?php echo e($reply->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($reply->message); ?></p>
                    <?php if($reply->image): ?>
                        <img src="<?php echo e($reply->image_url); ?>" alt="attachment"
                            class="mt-4 max-w-sm rounded border border-gray-200">
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!-- Reply Form -->
            <?php if($ticket->status !== 2): ?>
                <div class="bg-white border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Reply</h3>
                    <form action="<?php echo e(route('tickets.reply', $ticket)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <textarea name="message" rows="4"
                            placeholder="Write your reply..."
                            class="w-full border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#0071c5] transition-colors font-['Outfit'] resize-none mb-4"><?php echo e(old('message')); ?></textarea>
                        <div class="flex items-center justify-between">
                            <input type="file" name="image" accept="image/*"
                                class="text-sm text-gray-500 font-['Outfit']">
                            <button type="submit"
                                class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-6 py-2 text-sm font-bold transition-colors">
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 text-sm text-center">
                    This ticket is closed.
                </div>
            <?php endif; ?>

        </div>

        <!-- RIGHT: Ticket Info -->
        <div class="space-y-4">
            <div class="bg-white border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 pb-3 border-b border-gray-100">Ticket Info</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-semibold text-<?php echo e($color); ?>-600"><?php echo e($ticket->status_label); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created</span>
                        <span class="font-medium text-gray-700"><?php echo e($ticket->created_at->format('d M Y')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Replies</span>
                        <span class="font-medium text-gray-700"><?php echo e($ticket->replies->count()); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Last Update</span>
                        <span class="font-medium text-gray-700"><?php echo e($ticket->updated_at->diffForHumans()); ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/tickets/show.blade.php ENDPATH**/ ?>