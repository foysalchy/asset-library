<?php $__env->startSection('content'); ?>
<div class="p-4 mx-auto w-full  md:p-6">

    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Support Tickets</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage all user support tickets</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 dark:border-green-800 dark:bg-green-900/20">
            <svg class="shrink-0 text-green-500" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
            </svg>
            <p class="text-sm font-medium text-green-700 dark:text-green-400"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>

    <?php
        $statusConfig = [
            0 => ['label' => 'Open',        'class' => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
            1 => ['label' => 'In Progress', 'class' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'],
            2 => ['label' => 'Closed',      'class' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
            3 => ['label' => 'Solved',      'class' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'],
        ];
        $s = $statusConfig[$ticket->status] ?? $statusConfig[0];

        // Fallback display name (use this if Ticket model doesn't already have a display_name accessor)
        $ticketDisplayName = $ticket->user_id
            ? ($ticket->user->name ?? 'User')
            : ($ticket->name ?? 'Guest User');
    ?>

    <div class="grid grid-cols-1 gap-6">

        <!-- ── Chat window ── -->
        <div>
            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-white/[0.03] overflow-hidden flex flex-col" style="height:620px;">

                <!-- Top bar -->
                <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-white/[0.03] shrink-0">
                    <a href="<?php echo e(route('ticket.admin')); ?>"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors shrink-0">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/>
                        </svg>
                    </a>
                    <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium shrink-0">
                        <?php echo e(strtoupper(substr($ticketDisplayName, 0, 1))); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate"><?php echo e($ticket->subject); ?></p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 truncate">
                            #<?php echo e($ticket->id); ?> · <?php echo e($ticketDisplayName); ?>

                            <?php if(!$ticket->user_id): ?>
                                <span class="text-amber-500">(Guest)</span>
                            <?php endif; ?>
                            · <?php echo e($ticket->created_at->format('d M Y')); ?>

                        </p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate mt-0.5 flex flex-wrap items-center gap-x-3 gap-y-0.5">
                            <?php if($ticket->phone): ?>
                                <span class="inline-flex items-center gap-1">
                                    <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor" class="shrink-0"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-1C7.82 18 2 12.18 2 5V4a1 1 0 010-1z"/></svg>
                                    <?php echo e($ticket->phone); ?>

                                </span>
                            <?php endif; ?>
                            <?php if($ticket->employee_id): ?>
                                <span class="inline-flex items-center gap-1">
                                    <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor" class="shrink-0"><path fill-rule="evenodd" clip-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15a24.974 24.974 0 01-8-1.308z"/></svg>
                                    ID: <?php echo e($ticket->employee_id); ?>

                                </span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Status dropdown -->
                    <form action="<?php echo e(route('tickets.updateStatus', $ticket)); ?>" method="POST" class="shrink-0">
                        <?php echo csrf_field(); ?>
                        <select name="status" onchange="this.form.submit()"
                            class="text-xs font-medium rounded-full px-2.5 py-1.5 border-0 cursor-pointer focus:ring-2 focus:ring-blue-400 outline-none <?php echo e($s['class']); ?>">
                            <option value="0" <?php if($ticket->status == 0): echo 'selected'; endif; ?>>Open</option>
                            <option value="1" <?php if($ticket->status == 1): echo 'selected'; endif; ?>>In Progress</option>
                            <option value="3" <?php if($ticket->status == 3): echo 'selected'; endif; ?>>Solved</option>
                            <option value="2" <?php if($ticket->status == 2): echo 'selected'; endif; ?>>Closed</option>
                        </select>
                    </form>

                    <?php if($ticket->status !== 2): ?>
                        <form action="<?php echo e(route('tickets.close', $ticket)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-red-500 hover:bg-red-600 px-3 py-1.5 text-xs font-medium text-white transition-colors shrink-0">
                                Close
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4 bg-gray-50 dark:bg-white/[0.01]" id="chatBox">

                    <!-- Original message -->
                    <?php if($ticket->user_id && $ticket->user_id === auth()->id()): ?>
                        <!-- My ticket — right -->
                        <div class="flex items-end gap-2 flex-row-reverse">
                            <div class="w-7 h-7 rounded-full bg-[#001e3e] flex items-center justify-center text-white text-xs font-medium shrink-0">
                                <?php echo e(strtoupper(substr($ticketDisplayName, 0, 1))); ?>

                            </div>
                            <div class="max-w-[68%]">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 mr-1 text-right"><?php echo e($ticketDisplayName); ?> · <?php echo e($ticket->created_at->diffForHumans()); ?></p>
                                <div class="bg-[#0071c5] px-4 py-2.5 rounded-tl-2xl rounded-bl-2xl rounded-br-2xl">
                                    <p class="text-sm text-white leading-relaxed"><?php echo e($ticket->description); ?></p>
                                </div>
                                <?php if($ticket->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $ticket->image)); ?>" alt="attachment"
                                        class="mt-2 max-w-[200px] rounded-xl ml-auto block">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Other user / Guest ticket — left -->
                        <div class="flex items-end gap-2">
                            <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium shrink-0">
                                <?php echo e(strtoupper(substr($ticketDisplayName, 0, 1))); ?>

                            </div>
                            <div class="max-w-[68%]">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 ml-1">
                                    <?php echo e($ticketDisplayName); ?>

                                    <?php if(!$ticket->user_id): ?>
                                        <span class="text-amber-500">(Guest)</span>
                                    <?php endif; ?>
                                    · <?php echo e($ticket->created_at->diffForHumans()); ?>

                                </p>
                                <div class="bg-white dark:bg-white/10 border border-gray-200 dark:border-gray-700 px-4 py-2.5 rounded-tr-2xl rounded-br-2xl rounded-bl-2xl">
                                    <p class="text-sm text-gray-800 dark:text-white/90 leading-relaxed"><?php echo e($ticket->description); ?></p>
                                </div>
                                <?php if($ticket->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $ticket->image)); ?>" alt="attachment"
                                        class="mt-2 max-w-[200px] rounded-xl border border-gray-100 dark:border-gray-700">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Replies -->
                    <?php $__currentLoopData = $ticket->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $replyDisplayName = $reply->user_id
                                ? ($reply->user->name ?? 'User')
                                : ($reply->name ?? 'Guest User');
                        ?>
                        <?php if($reply->user_id === auth()->id()): ?>
                            <!-- My message — right -->
                            <div class="flex items-end gap-2 flex-row-reverse">
                                <div class="w-7 h-7 rounded-full bg-[#001e3e] flex items-center justify-center text-white text-xs font-medium shrink-0">
                                    <?php echo e(strtoupper(substr($replyDisplayName, 0, 1))); ?>

                                </div>
                                <div class="max-w-[68%]">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 mr-1 text-right"><?php echo e($replyDisplayName); ?> · <?php echo e($reply->created_at->diffForHumans()); ?></p>
                                    <div class="bg-[#0071c5] px-4 py-2.5 rounded-tl-2xl rounded-bl-2xl rounded-br-2xl">
                                        <p class="text-sm text-white leading-relaxed"><?php echo e($reply->message); ?></p>
                                    </div>
                                    <?php if($reply->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $reply->image)); ?>" alt="attachment"
                                            class="mt-2 max-w-[200px] rounded-xl ml-auto block">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Other message — left -->
                            <div class="flex items-end gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium shrink-0">
                                    <?php echo e(strtoupper(substr($replyDisplayName, 0, 1))); ?>

                                </div>
                                <div class="max-w-[68%]">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 ml-1"><?php echo e($replyDisplayName); ?> · <?php echo e($reply->created_at->diffForHumans()); ?></p>
                                    <div class="bg-white dark:bg-white/10 border border-gray-200 dark:border-gray-700 px-4 py-2.5 rounded-tr-2xl rounded-br-2xl rounded-bl-2xl">
                                        <p class="text-sm text-gray-800 dark:text-white/90 leading-relaxed"><?php echo e($reply->message); ?></p>
                                    </div>
                                    <?php if($reply->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $reply->image)); ?>" alt="attachment"
                                            class="mt-2 max-w-[200px] rounded-xl border border-gray-100 dark:border-gray-700">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

                <!-- Input -->
                <?php if($ticket->status !== 2): ?>
                    <form action="<?php echo e(route('admin.tickets.reply', $ticket)); ?>" method="POST" enctype="multipart/form-data"
                        class="shrink-0 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-white/[0.03] px-3 py-3 flex items-end gap-2">
                        <?php echo csrf_field(); ?>

                        <!-- Attach image -->
                        <label class="cursor-pointer inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors shrink-0">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="showFile(this)">
                        </label>

                        <!-- Textarea -->
                        <div class="flex-1">
                            <textarea name="message" id="replyMsg" rows="1"
                                placeholder="Write a reply..."
                                class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 bg-gray-50 dark:bg-white/5 outline-none resize-none leading-relaxed focus:border-blue-300 dark:focus:border-blue-600 transition-colors"
                                oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,120)+'px'"><?php echo e(old('message')); ?></textarea>
                            <p id="fileName" class="hidden text-xs text-gray-400 mt-1 px-1"></p>
                        </div>

                        <!-- Send -->
                        <button type="submit"
                            class="w-9 h-9 rounded-full bg-[#0071c5] hover:bg-[#005ea3] flex items-center justify-center text-white transition-colors shrink-0">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                            </svg>
                        </button>
                    </form>
                <?php else: ?>
                    <div class="shrink-0 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-white/[0.03] px-4 py-3 text-center text-xs text-red-500">
                        This ticket is closed.
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    const chatBox = document.getElementById('chatBox');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    function showFile(input) {
        const el = document.getElementById('fileName');
        if (input.files.length > 0) {
            el.textContent = 'Attached: ' + input.files[0].name;
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views/support-ticket/show.blade.php ENDPATH**/ ?>