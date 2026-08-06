<?php $isEdit = isset($user) && $user->exists; ?>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    
    <div class="lg:col-span-2 space-y-5">

        
        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-5 dark:border-gray-700 dark:bg-gray-800/40 space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Basic Info</h4>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="<?php echo e(old('name', $isEdit ? $user->name : '')); ?>"
                        placeholder="Full name"
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Phone
                    </label>
                    <input type="text" name="phone" value="<?php echo e(old('phone', $isEdit ? $user->phone : '')); ?>"
                        placeholder="+880 1xxx-xxxxxx"
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="<?php echo e(old('email', $isEdit ? $user->email : '')); ?>"
                    placeholder="email@example.com"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        
        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-5 dark:border-gray-700 dark:bg-gray-800/40 space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                Password
                <?php if($isEdit): ?>
                <span class="text-xs font-normal text-gray-400 ml-1">Leave blank to keep current</span>
                <?php endif; ?>
            </h4>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Password <?php echo e(!$isEdit ? '*' : ''); ?>

                    </label>
                    <input type="password" name="password"
                        placeholder="<?php echo e($isEdit ? 'Leave blank to keep current' : 'Min 8 characters'); ?>"
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Confirm Password
                    </label>
                    <input type="password" name="password_confirmation"
                        placeholder="Repeat password"
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
            </div>
        </div>

        
        
        <?php if(!$isEdit || !$user->roles->contains('name', 'super_admin')): ?>
        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-5 dark:border-gray-700 dark:bg-gray-800/40">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Assign Roles</h4>

            <div class="space-y-2">
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!$role->is_super_admin): ?>
                <label class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 cursor-pointer hover:border-blue-300 dark:border-gray-700 dark:bg-gray-800 transition-colors has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 dark:has-[:checked]:border-blue-700">

                    <input type="checkbox" name="roles[]" value="<?php echo e($role->id); ?>"
                        <?php echo e(in_array($role->id, old('roles', $assignedRoles ?? [])) ? 'checked' : ''); ?>

                        class="w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500 dark:border-gray-600">

                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($role->label); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($role->name); ?></p>
                    </div>

                </label>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php $__errorArgs = ['roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="space-y-5">

        
        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-5 dark:border-gray-700 dark:bg-gray-800/40"
            x-data="{
                preview: '<?php echo e($isEdit && $user->avatar ? $user->avatar_url : ''); ?>',
                removed: false,
                handleFile(e) {
                    const file = e.target.files[0]; if (!file) return;
                    this.removed = false;
                    const reader = new FileReader();
                    reader.onload = (ev) => this.preview = ev.target.result;
                    reader.readAsDataURL(file);
                },
                remove() { this.preview = ''; this.removed = true; document.getElementById('avatar_input').value = ''; }
             }">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Avatar</h4>
            <input type="hidden" name="remove_avatar" :value="removed ? '1' : '0'">

            <div class="flex flex-col items-center gap-4">
                <div class="relative">
                    <img :src="preview || 'https://ui-avatars.com/api/?name=<?php echo e(urlencode($isEdit ? $user->name : 'User')); ?>&background=3b82f6&color=fff'"
                        class="w-24 h-24 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                    <template x-if="preview">
                        <button type="button" @click="remove()"
                            class="absolute -top-1 -right-1 w-6 h-6 flex items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </template>
                </div>
                <button type="button" @click="$refs.avatarInput.click()"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
                    Choose Photo
                </button>
                <input type="file" id="avatar_input" x-ref="avatarInput" name="avatar"
                    accept="image/*" class="hidden" @change="handleFile($event)">
            </div>
            <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs text-red-500 text-center"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-5 dark:border-gray-700 dark:bg-gray-800/40">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Status</h4>
            <div class="space-y-2">
                <?php $__currentLoopData = ['active' => 'Active', 'inactive' => 'Inactive']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 cursor-pointer hover:border-blue-300 dark:border-gray-700 dark:bg-gray-800 transition-colors has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20">
                    <input type="radio" name="status" value="<?php echo e($value); ?>"
                        <?php echo e(old('status', $isEdit ? $user->status : 'active') === $value ? 'checked' : ''); ?>

                        class="w-4 h-4 border-gray-300 text-blue-500 focus:ring-blue-500 dark:border-gray-600">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($label); ?></span>
                </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

    </div>
</div><?php /**PATH C:\laragon\www\asset-management\resources\views/users/_form.blade.php ENDPATH**/ ?>