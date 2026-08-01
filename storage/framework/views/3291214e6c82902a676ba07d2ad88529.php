<?php $__env->startSection('content'); ?>
    <section class="container mx-auto px-4 lg:px-8 py-12 font-['Outfit']">

        <div class="mb-10">
            <h1 class="text-[#0071c5] text-4xl font-light">My Account</h1>
            <p class="text-gray-500 mt-2 text-sm">Manage your profile information and view your download activity.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- LEFT: Profile Settings Form -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white border border-gray-200 shadow-sm p-8 rounded-sm">
                    <h2 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2 uppercase tracking-wider">Personal Info
                    </h2>

                    <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Avatar Upload -->
                        <div class="mb-8 flex flex-col items-center">
                            <div class="relative group">
                                <img id="avatarPreview"
                                    src="<?php echo e(Auth::user()->avatar_url ?? asset('./images/user/images.png')); ?>"
                                    alt="avatar"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-md">
                                <label for="avatarInput"
                                    class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                                    <i class="fa-solid fa-camera text-white text-xl"></i>
                                </label>
                                <input type="file" name="avatar" id="avatarInput" class="hidden"
                                    onchange="previewAvatar(this)">
                            </div>
                            <p class="text-[10px] text-gray-600 mt-3 uppercase font-bold">Change Profile Photo</p>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-500 uppercase mb-2">Full
                                Name</label>
                            <input id="name" type="text" name="name" value="<?php echo e(Auth::user()->name); ?>"
                                class="w-full border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#0071c5] transition-all">
                </div>
                <div class="mb-5">
                    <label for="phone" class="block text-xs font-bold text-gray-500 uppercase mb-2">Phone</label>
                    <input id="phone" type="text" name="phone" value="<?php echo e(Auth::user()->phone); ?>"
                        class="w-full border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#0071c5] transition-all">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-500 uppercase mb-2">Email Address</label>
                    <input id="email" type="email" value="<?php echo e(Auth::user()->email); ?>" disabled
                        class="w-full border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 cursor-not-allowed">
                </div>

                <button type="submit"
                    class="w-full mt-5 bg-[#0071c5] text-white py-3 font-bold uppercase text-xs tracking-widest hover:bg-[#003b7a] transition-all shadow-md">
                    Update Profile
                </button>
                </form>
            </div>
        </div>

        <!-- RIGHT: Download History -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 shadow-sm rounded-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800 uppercase tracking-wider">Download History</h2>
                    <span
                        class="bg-blue-100 text-blue-800 text-[10px] font-black px-2 py-1 rounded"><?php echo e($downloadLogs->count()); ?>

                        Total</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[11px] uppercase text-gray-600 font-bold border-b border-gray-100">
                                <th class="text-gray-700 px-6 py-4">Resource</th>
                                <th class="text-gray-700 px-6 py-4">Type</th>
                                <th class="text-gray-700 px-6 py-4 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php $__empty_1 = true; $__currentLoopData = $downloadLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $item =
                                        $log->model === 'asset'
                                            ? \App\Models\Asset::find($log->model_id)
                                            : \App\Models\Campaign::find($log->model_id);
                                ?>
                                <?php if($item): ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <a href="<?php echo e(route($log->model . '.details', $item->slug)); ?>"
                                                class="text-sm font-semibold text-[#0071c5] hover:underline">
                                                <?php echo e($item->title); ?>

                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-[10px] font-bold uppercase px-2 py-0.5 rounded <?php echo e($log->model === 'asset' ? 'bg-blue-100 text-blue-800' : 'bg-teal-100 text-teal-800'); ?>">
                                                <?php echo e($log->model); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-xs text-gray-600">
                                            <?php echo e($log->updated_at->format('d M Y, h:i A')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-600 italic">No download
                                        history found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        </div>
    </section>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/auth/profile.blade.php ENDPATH**/ ?>