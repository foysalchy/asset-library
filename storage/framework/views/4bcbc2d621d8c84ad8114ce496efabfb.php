<?php $__env->startSection('content'); ?>
<section class="max-w-screen-2xl mx-auto px-4 lg:px-8 py-16 font-['Outfit']">

    <!-- কন্টেইনারকে সেন্টারে আনার জন্য flex এবং items-center ব্যবহার করা হয়েছে -->
    <div class="flex flex-col items-center justify-center">

        <div class="w-full max-w-2xl">
            <!-- Header -->
            <div class="flex items-center gap-4 mb-8">
                <a href="<?php echo e(route('tickets.index')); ?>" class="text-[#0071c5] hover:opacity-70 transition-all">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-light text-[#0071c5]">Open New Ticket</h1>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-xl border border-gray-100 p-10 rounded-sm">

                <?php if($errors->any()): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 mb-6 text-sm">
                        <p class="font-bold mb-1">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('tickets.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <!-- Subject -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Subject</label>
                        <input type="text" name="subject" value="<?php echo e(old('subject')); ?>" required
                            placeholder="What is the issue about?"
                            class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#0071c5] transition-all bg-gray-50/30">
                    </div>

                    <!-- Message -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Detailed Description</label>
                        <textarea name="description" rows="6" required
                            placeholder="Please provide as much detail as possible..."
                            class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#0071c5] transition-all bg-gray-50/30 resize-none"><?php echo e(old('description')); ?></textarea>
                    </div>

                    <!-- Image with Preview Logic -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            Attachment <span class="text-gray-400 font-normal normal-case">(Optional Screenshot)</span>
                        </label>

                        <div class="flex flex-col gap-4">
                            <!-- Custom Styled Upload Box -->
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500">Click to upload or drag and drop</p>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase">PNG, JPG or WEBP (Max 2MB)</p>
                                </div>
                                <input type="file" name="image" id="ticketImage" accept="image/*" class="hidden" onchange="previewFile()">
                            </label>

                            <!-- Image Preview Area -->
                            <div id="previewBox" class="hidden relative w-fit group">
                                <p class="text-[10px] font-bold text-[#0071c5] mb-2 uppercase">Selected Preview:</p>
                                <img id="imagePreview" src="#" alt="preview" class="max-h-48 rounded shadow-md border border-gray-200">
                                <!-- Remove button -->
                                <button type="button" onclick="removeImage()"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-all">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="w-full sm:w-auto bg-[#0071c5] hover:bg-[#001e3e] text-white px-12 py-3 font-bold uppercase text-xs tracking-[2px] transition-all shadow-lg active:scale-95">
                            Submit Support Ticket
                        </button>
                    </div>
                </form>
            </div>

            <p class="mt-8 text-center text-xs text-gray-400">
                Our support team typically responds within 24 hours.
            </p>
        </div>
    </div>

</section>

<?php $__env->startPush('scripts'); ?>
<script>
    function previewFile() {
        const file = document.getElementById('ticketImage').files[0];
        const preview = document.getElementById('imagePreview');
        const previewBox = document.getElementById('previewBox');
        const reader = new FileReader();

        if (file) {
            reader.onload = function() {
                preview.src = reader.result;
                previewBox.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        const input = document.getElementById('ticketImage');
        const previewBox = document.getElementById('previewBox');
        input.value = ""; // ক্লিয়ার ইনপুট
        previewBox.classList.add('hidden');
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/tickets/create.blade.php ENDPATH**/ ?>