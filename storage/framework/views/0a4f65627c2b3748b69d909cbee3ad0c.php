<?php $__env->startSection('content'); ?>
    <section class="max-w-screen-2xl mx-auto px-4 lg:px-8 py-10 font-['Outfit']">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row items-start justify-between mb-8 gap-4">
            <div class="flex items-center gap-3 lg:gap-4">
                <a href="<?php echo e(route('tickets.index')); ?>" class="text-[#0071c5] hover:opacity-70" aria-label="arrow">
                    <i class="fas fa-arrow-left text-lg lg:text-xl"></i>
                </a>
                <div>
                    <!-- text-xl (mobile) lg:text-2xl -->
                    <h1 class="text-xl lg:text-2xl font-light text-[#0071c5] leading-tight"><?php echo e($ticket->subject); ?></h1>
                    <p class="text-[10px] lg:text-xs text-gray-600 mt-1">Ticket #<?php echo e($ticket->id); ?> ·
                        <?php echo e($ticket->created_at->format('d M Y')); ?></p>
                </div>
            </div>
            <!-- Badge Container -->
            <div class="shrink-0">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border <?php echo e($ticket->status_badge); ?>">
                    <?php echo e($ticket->status_label); ?>

                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- ── LEFT: Chat Window (Designing like Admin) ── -->
            <div class="lg:col-span-2">
                <div
                    class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col h-[550px] lg:h-[650px]">

                    <!-- Messages Area -->
                    <div class="flex-1 overflow-y-auto px-3 lg:px-4 py-6 space-y-6 bg-gray-50/50" id="chatBox">

                        
                        <div class="flex items-end gap-2 <?php echo e($ticket->user_id === auth()->id() ? 'flex-row-reverse' : ''); ?>">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-white text-[10px] font-bold shrink-0 <?php echo e($ticket->user_id === auth()->id() ? 'bg-[#001e3e]' : 'bg-[#0071c5]'); ?>">
                                <?php echo e(strtoupper(substr($ticket->user->name, 0, 1))); ?>

                            </div>
                            <div class="max-w-[85%] lg:max-w-[75%]">
                                <p
                                    class="text-[10px] text-gray-600 mb-1 <?php echo e($ticket->user_id === auth()->id() ? 'text-right mr-1' : 'ml-1'); ?>">
                                    <?php echo e($ticket->user->name); ?> · <?php echo e($ticket->created_at->diffForHumans()); ?>

                                </p>
                                <div
                                    class="px-4 py-3 shadow-sm <?php echo e($ticket->user_id === auth()->id() ? 'bg-[#0071c5] text-white rounded-tl-2xl rounded-bl-2xl rounded-br-2xl' : 'bg-white border border-gray-200 text-gray-800 rounded-tr-2xl rounded-br-2xl rounded-bl-2xl'); ?>">
                                    <p class="text-sm leading-relaxed"><?php echo e($ticket->description); ?></p>
                                </div>
                                <?php if($ticket->image): ?>
                                    <img src="<?php echo e($ticket->image_url); ?>" alt="attachment"
                                        class="mt-2 w-full max-w-[200px] sm:max-w-[250px] rounded-xl border border-gray-100">
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <?php $__currentLoopData = $ticket->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="flex items-end gap-2 <?php echo e($reply->user_id === auth()->id() ? 'flex-row-reverse' : ''); ?>">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-white text-[10px] font-bold shrink-0 <?php echo e($reply->user_id === auth()->id() ? 'bg-[#001e3e]' : 'bg-[#0071c5]'); ?>">
                                    <?php echo e(strtoupper(substr($reply->user->name, 0, 1))); ?>

                                </div>
                                <div class="max-w-[75%]">
                                    <p
                                        class="text-[10px] text-gray-600 mb-1 <?php echo e($reply->user_id === auth()->id() ? 'text-right mr-1' : 'ml-1'); ?>">
                                        <?php echo e($reply->user->name); ?> <?php if($reply->is_admin): ?>
                                            <span class="text-[#0071c5] font-black uppercase text-[8px] ml-1">Staff</span>
                                        <?php endif; ?> · <?php echo e($reply->created_at->diffForHumans()); ?>

                                    </p>
                                    <div
                                        class="px-4 py-3 shadow-sm <?php echo e($reply->user_id === auth()->id() ? 'bg-[#0071c5] text-white rounded-tl-2xl rounded-bl-2xl rounded-br-2xl' : 'bg-white border border-gray-200 text-gray-800 rounded-tr-2xl rounded-br-2xl rounded-bl-2xl'); ?>">
                                        <p class="text-sm leading-relaxed"><?php echo e($reply->message); ?></p>
                                    </div>
                                    <?php if($reply->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $reply->image)); ?>" alt="attachment"
                                            class="mt-2 max-w-[250px] rounded-xl <?php echo e($reply->user_id === auth()->id() ? 'ml-auto' : ''); ?> block border border-gray-100">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                    <!-- Input Area (Only if ticket is not closed) -->
                    <?php if($ticket->status !== 2): ?>
                        <form action="<?php echo e(route('tickets.reply', $ticket)); ?>" method="POST" enctype="multipart/form-data"
                            class="shrink-0 border-t border-gray-100 bg-white  px-3 lg:px-4 py-4 flex items-end gap-3">
                            <?php echo csrf_field(); ?>

                            <!-- Attach file -->
                            <label
                                class="cursor-pointer inline-flex items-center justify-centerw-9 h-9 lg:w-10 lg:h-10 rounded-full text-gray-600 hover:bg-gray-100 transition-colors shrink-0">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z">
                                    </path>
                                </svg>
                                <input type="file" name="image" accept="image/*" class="hidden"
                                    onchange="showFile(this)">
                            </label>

                            <!-- Textarea -->
                            <div class="flex-1">
                                <textarea name="message" id="replyMsg" rows="1" required placeholder="Type your response here..."
                                    class="w-full border border-gray-200 rounded-2xl px-4 lg:px-5 py-2 lg:py-2.5 text-sm text-gray-700 bg-gray-50 outline-none resize-none leading-relaxed focus:border-[#0071c5] transition-all"
                                    oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,150)+'px'"><?php echo e(old('message')); ?></textarea>
                                <p id="fileName" class="hidden text-[10px] text-blue-600 mt-1 px-2 font-bold uppercase">
                                </p>
                            </div>

                            <!-- Send Button -->
                            <button type="submit"  aria-label="submit"
                                class="w-9 h-9 lg:w-10 lg:h-10 rounded-full bg-[#0071c5] hover:bg-[#001e3e] flex items-center justify-center text-white transition-all shadow-md shrink-0">
                                <i class="fa-solid fa-paper-plane text-sm"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="p-4 bg-red-50 text-center text-xs font-bold text-red-700 uppercase tracking-widest">
                            This ticket is closed. No further replies can be sent.
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- ── RIGHT: Sidebar Info ── -->
            <div class="space-y-6">
                <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-xl">
                    <h3 class="text-xs font-bold text-gray-600 uppercase tracking-widest mb-4 pb-3 border-b border-gray-50">
                        Ticket Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 uppercase font-medium">Status</span>
                            <span
                                class="text-xs font-bold <?php echo e($ticket->status_badge); ?> px-2 py-0.5 rounded uppercase"><?php echo e($ticket->status_label); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 uppercase font-medium">Messages</span>
                            <span class="text-sm font-bold text-gray-800"><?php echo e($ticket->replies->count() + 1); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 uppercase font-medium">Last Activity</span>
                            <span
                                class="text-xs text-gray-700 font-medium"><?php echo e($ticket->updated_at->diffForHumans()); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100/50">
                    <p class="text-xs text-[#0071c5] leading-relaxed">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Our technical support team typically responds within 24 business hours. Please ensure your
                        descriptions are clear for faster resolution.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <?php $__env->startPush('scripts'); ?>
        <script>
            const chatBox = document.getElementById('chatBox');
            if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

            function showFile(input) {
                const el = document.getElementById('fileName');
                if (input.files.length > 0) {
                    el.textContent = 'Selected: ' + input.files[0].name;
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/tickets/show.blade.php ENDPATH**/ ?>