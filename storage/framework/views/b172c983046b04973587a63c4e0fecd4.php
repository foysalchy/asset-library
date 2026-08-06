<?php $__env->startSection('content'); ?>
<div class="p-4 mx-auto w-full  md:p-6">

    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="<?php echo e(route('roles.index')); ?>" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Roles</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium">Edit: <?php echo e($role->label); ?></span>
    </nav>

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Role</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Update permissions and assigned users</p>
    </div>

    <form action="<?php echo e(route('roles.update', $role)); ?>" method="POST">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="lg:col-span-2 space-y-5">

                
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Role Info</h3>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Name</label>
                        <input type="text" value="<?php echo e($role->name); ?>" disabled
                               class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500 cursor-not-allowed" />
                        <p class="mt-1 text-xs text-gray-400">Role name cannot be changed</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Label <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="label" value="<?php echo e(old('label', $role->label)); ?>"
                               class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 <?php $__errorArgs = ['label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                        <?php $__errorArgs = ['label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Permissions</h3>
                        <button type="button" onclick="toggleAll()"
                                class="text-xs text-blue-500 hover:underline" id="toggle-all-btn">
                            Select All
                        </button>
                    </div>
                    <div class="space-y-6">
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupPermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"><?php echo e($group); ?></h4>
                                    <button type="button" onclick="toggleGroup('<?php echo e(Str::slug($group)); ?>')"
                                            class="text-xs text-gray-400 hover:text-blue-500 transition-colors">Select group</button>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" data-group="<?php echo e(Str::slug($group)); ?>">
                                    <?php $__currentLoopData = $groupPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center gap-2.5 rounded-lg border border-gray-200 bg-gray-50/50 p-3 cursor-pointer hover:border-blue-300 dark:border-gray-700 dark:bg-gray-800/40 transition-colors has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 dark:has-[:checked]:border-blue-700">
                                            <input type="checkbox"
                                                   name="permissions[]"
                                                   value="<?php echo e($permission->id); ?>"
                                                   <?php echo e(in_array($permission->id, old('permissions', $selectedPerms)) ? 'checked' : ''); ?>

                                                   class="permission-checkbox w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500 dark:border-gray-600">
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 capitalize">
                                                <?php echo e(explode('.', $permission->name)[1]); ?>

                                            </span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-5">Assigned Users</h3>
                    <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 cursor-pointer hover:border-blue-300 dark:border-gray-700 dark:bg-gray-800/40 transition-colors has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20">
                                <input type="checkbox"
                                       name="users[]"
                                       value="<?php echo e($user->id); ?>"
                                       <?php echo e(in_array($user->id, old('users', $assignedUsers)) ? 'checked' : ''); ?>

                                       class="w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500 dark:border-gray-600">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">
                                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate"><?php echo e($user->name); ?></p>
                                        <p class="text-xs text-gray-400 truncate"><?php echo e($user->email); ?></p>
                                    </div>
                                </div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

            </div>

            
            <div>
                <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sticky top-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Summary</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Permissions</span>
                            <span id="selected-count" class="font-semibold text-gray-700 dark:text-gray-300">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Users assigned</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300"><?php echo e(count($assignedUsers)); ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex items-center justify-end gap-3 mt-5">
            <a href="<?php echo e(route('roles.index')); ?>"
               class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                Update Role
            </button>
        </div>
    </form>
</div>

<script>
const checkboxes = () => document.querySelectorAll('.permission-checkbox');

function updateCount() {
    document.getElementById('selected-count').textContent =
        document.querySelectorAll('.permission-checkbox:checked').length;
}

let allSelected = false;
function toggleAll() {
    allSelected = !allSelected;
    checkboxes().forEach(cb => cb.checked = allSelected);
    document.getElementById('toggle-all-btn').textContent = allSelected ? 'Deselect All' : 'Select All';
    updateCount();
}

function toggleGroup(group) {
    const group_checkboxes = document.querySelectorAll(`[data-group="${group}"] .permission-checkbox`);
    const allChecked = Array.from(group_checkboxes).every(cb => cb.checked);
    group_checkboxes.forEach(cb => cb.checked = !allChecked);
    updateCount();
}

checkboxes().forEach(cb => cb.addEventListener('change', updateCount));
document.addEventListener('DOMContentLoaded', updateCount);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views/roles/edit.blade.php ENDPATH**/ ?>