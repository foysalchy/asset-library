<?php $isEdit = isset($project); ?>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-5">
        
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Project Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="<?php echo e(old('name', $isEdit ? $project->name : '')); ?>" 
                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div x-data="{ preview: '<?php echo e($isEdit && $project->logo ? $project->logoUrl : ''); ?>', removed: false }">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Logo</label>
            <input type="hidden" name="remove_logo" :value="removed ? '1' : '0'">
            
            <div @click="$refs.logoInput.click()" class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-4 cursor-pointer hover:border-blue-400 dark:border-gray-700 dark:bg-gray-800/40">
                <template x-if="preview">
                    <div class="relative">
                        <img :src="preview" class="h-32 w-32 object-contain rounded-lg">
                        <button type="button" @click.stop="preview=''; removed=true" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1">
                            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                        </button>
                    </div>
                </template>
                <template x-if="!preview">
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Click to upload logo</p>
                    </div>
                </template>
            </div>
            <input type="file" x-ref="logoInput" name="logo" class="hidden" @change="const file = $event.target.files[0]; if(file){ removed=false; const reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(file); }">
        </div>
    </div>

    <div class="space-y-5">
        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/40 space-y-4">
            
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Concern</label>
                <select name="concern" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <?php $__currentLoopData = \App\Models\Project::CONCERNS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(old('concern', $isEdit ? $project->concern : '') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="active" <?php echo e(old('status', $isEdit ? $project->status : '') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(old('status', $isEdit ? $project->status : '') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\asset-management\resources\views\projects\_form.blade.php ENDPATH**/ ?>