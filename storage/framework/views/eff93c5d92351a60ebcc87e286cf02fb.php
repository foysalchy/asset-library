<?php $isEdit = isset($asset) && $asset->exists; ?>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    
    <div class="lg:col-span-2 space-y-5">

        
        <div>
            <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" id="title" required
                value="<?php echo e(old('title', $isEdit ? $asset->title : '')); ?>"
                placeholder="e.g. Static Banners: Intel® Arc™ B580 Graphics"
                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div>
            <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Slug <span class="text-xs font-normal text-gray-400 ml-1">(auto-generated if empty)</span>
            </label>
            <input type="text" name="slug" id="slug"
                value="<?php echo e(old('slug', $isEdit ? $asset->slug : '')); ?>"
                placeholder="static-banners-intel-arc-b580"
                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Description</label>
            <textarea name="description" rows="4"
                placeholder="Describe this asset..."
                class="shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description', $isEdit ? $asset->description : '')); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Available Formats
                    <span class="text-xs font-normal text-gray-400 ml-1">comma separated</span>
                </label>
                <input type="text" name="available_formats_input"
                    value="<?php echo e(old('available_formats_input', $isEdit ? implode(', ', $asset->available_formats ?? []) : '')); ?>"
                    placeholder="jpg, psd, png"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <p class="mt-1 text-xs text-gray-400">e.g. jpg, psd, png</p>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Dimensions
                    <span class="text-xs font-normal text-gray-400 ml-1">comma separated</span>
                </label>
                <input type="text" name="dimensions_input"
                    value="<?php echo e(old('dimensions_input', $isEdit ? implode(', ', $asset->dimensions ?? []) : '')); ?>"
                    placeholder="300x250, 728x90, 1456x180"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <p class="mt-1 text-xs text-gray-400">e.g. 300x250, 600x500</p>
            </div>
        </div>

        
        <div x-data="{
            mediaFiles: [],
            deletedIds: [],
            addFiles(e) {
                Array.from(e.target.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        this.mediaFiles.push({
                            name: file.name,
                            preview: ev.target.result,
                            isVideo: file.type.startsWith('video'),
                            size: this.formatSize(file.size)
                        });
                    };
                    reader.readAsDataURL(file);
                });
            },
            removeNew(index) { this.mediaFiles.splice(index, 1); },
            markDelete(id) {
                this.deletedIds.push(id);
                document.getElementById('media-' + id).remove();
            },
            formatSize(bytes) {
                const units = ['B','KB','MB','GB'];
                let i = 0;
                while (bytes >= 1024 && i < units.length - 1) { bytes /= 1024; i++; }
                return Math.round(bytes * 10) / 10 + ' ' + units[i];
            }
        }">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Media Files
                <span class="text-xs font-normal text-gray-400 ml-1">Images & Videos (multiple)</span>
            </label>

            
            <template x-for="id in deletedIds" :key="id">
                <input type="hidden" name="delete_media[]" :value="id">
            </template>

            
            <?php if($isEdit && $asset->media->count()): ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3">
                    <?php $__currentLoopData = $asset->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div id="media-<?php echo e($media->id); ?>" class="relative group rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 aspect-video">
                            <?php if($media->media_type === 'image'): ?>
                                <img src="<?php echo e($media->url); ?>" alt="<?php echo e($media->file_original_name); ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <video src="<?php echo e($media->url); ?>" class="w-full h-full object-cover"></video>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                    <svg class="text-white" width="28" height="28" viewBox="0 0 20 20" fill="currentColor"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                </div>
                            <?php endif; ?>
                            <button type="button"
                                    @click="markDelete('<?php echo e($media->id); ?>')"
                                    class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center rounded-full bg-red-500 text-white opacity-0 group-hover:opacity-100 transition-opacity shadow">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                            </button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3" x-show="mediaFiles.length > 0">
                <template x-for="(file, index) in mediaFiles" :key="index">
                    <div class="relative group rounded-xl overflow-hidden border border-blue-200 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20 aspect-video">
                        <template x-if="!file.isVideo">
                            <img :src="file.preview" :alt="file.name" class="w-full h-full object-cover">
                        </template>
                        <template x-if="file.isVideo">
                            <div class="w-full h-full flex flex-col items-center justify-center gap-1">
                                <svg class="text-blue-400" width="28" height="28" viewBox="0 0 20 20" fill="currentColor"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                <span class="text-xs text-blue-500 truncate px-2 w-full text-center" x-text="file.name"></span>
                            </div>
                        </template>
                        <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1">
                            <p class="text-xs text-white truncate" x-text="file.size"></p>
                        </div>
                        <button type="button" @click="removeNew(index)"
                                class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center rounded-full bg-red-500 text-white opacity-0 group-hover:opacity-100 transition-opacity shadow">
                            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            
            <div @click="$refs.mediaInput.click()"
                 @dragover.prevent @drop.prevent="addFiles({ target: { files: $event.dataTransfer.files } })"
                 class="flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-6 cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 dark:border-gray-700 dark:bg-gray-800/40 dark:hover:border-blue-600 transition-all">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900/30">
                    <svg class="text-blue-500" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"/>
                    </svg>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Click or drag media files here</p>
                    <p class="text-xs text-gray-400 mt-0.5">JPG, PNG, WEBP, GIF, MP4, MOV, WEBM</p>
                </div>
            </div>
            <input type="file" x-ref="mediaInput" name="media[]" multiple
                accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/quicktime,video/webm"
                class="hidden" @change="addFiles($event)">
            <?php $__errorArgs = ['media.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div x-data="{
            currentFile: '<?php echo e($isEdit && $asset->file_path ? $asset->file_original_name : ''); ?>',
            newFileName: '', fileSize: '', removed: false,
            handleFile(e) {
                const file = e.target.files[0]; if (!file) return;
                this.newFileName = file.name;
                this.fileSize = this.formatSize(file.size);
                this.removed = false;
            },
            remove() { this.newFileName = ''; this.fileSize = ''; this.removed = true; document.getElementById('asset_file_input').value = ''; },
            formatSize(bytes) {
                const units = ['B','KB','MB','GB']; let i = 0;
                while (bytes >= 1024 && i < units.length - 1) { bytes /= 1024; i++; }
                return Math.round(bytes * 10) / 10 + ' ' + units[i];
            },
            get displayName() { return this.newFileName || (!this.removed && this.currentFile ? this.currentFile : ''); }
        }">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Download File
                <span class="text-xs font-normal text-gray-400 ml-1">ZIP, PDF, DOC, XLS · max 100MB</span>
            </label>
            <input type="hidden" name="remove_file" :value="removed ? '1' : '0'">
             <?php if (isset($component)) { $__componentOriginal517308841572cfc57e5e3528a0910ad7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal517308841572cfc57e5e3528a0910ad7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.drive-upload','data' => ['fieldName' => 'drive_file_id','fileId' => old('drive_file_id', $isEdit && $asset->file ? str_replace('drive:', '', $asset->file) : '')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('drive-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field-name' => 'drive_file_id','file-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('drive_file_id', $isEdit && $asset->file ? str_replace('drive:', '', $asset->file) : ''))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal517308841572cfc57e5e3528a0910ad7)): ?>
<?php $attributes = $__attributesOriginal517308841572cfc57e5e3528a0910ad7; ?>
<?php unset($__attributesOriginal517308841572cfc57e5e3528a0910ad7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal517308841572cfc57e5e3528a0910ad7)): ?>
<?php $component = $__componentOriginal517308841572cfc57e5e3528a0910ad7; ?>
<?php unset($__componentOriginal517308841572cfc57e5e3528a0910ad7); ?>
<?php endif; ?>


                <input type="file" id="file_input" x-ref="fileInput" name="file"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.txt,.csv"
                    class="hidden" @change="handleFile($event)">
                <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <input type="file" id="asset_file_input" x-ref="fileInput" name="file"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.txt,.csv"
                class="hidden" @change="handleFile($event)">
            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

    </div>

    
    <div class="space-y-5">

        
        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/40 space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Asset Settings</h4>

            
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Project <span class="text-red-500">*</span>
                </label>
                <select name="project_id" required
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">— Select Project —</option>
                    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($project->id); ?>"
                            <?php echo e(old('project_id', $isEdit ? $asset->project_id : ($selectedProject ?? '')) == $project->id ? 'selected' : ''); ?>>
                            <?php echo e($project->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['project_id'];
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
                    Asset Type <span class="text-red-500">*</span>
                </label>
                <select name="asset_type_id" required
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 <?php $__errorArgs = ['asset_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">— Select Type —</option>
                    <?php $__currentLoopData = $assetTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->id); ?>"
                            <?php echo e(old('asset_type_id', $isEdit ? $asset->asset_type_id : '') == $type->id ? 'selected' : ''); ?>>
                            <?php echo e($type->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['asset_type_id'];
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
                    Asset ID Code
                    <span class="text-xs font-normal text-gray-400 ml-1">e.g. AS6748EN</span>
                </label>
                <input type="text" name="asset_id_code"
                    value="<?php echo e(old('asset_id_code', $isEdit ? $asset->asset_id_code : '')); ?>"
                    placeholder="AS6748EN"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>

            
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Sort Order</label>
                <input type="number" name="sort_order" min="0"
                    value="<?php echo e(old('sort_order', $isEdit ? $asset->sort_order : 0)); ?>"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>

            
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Upload Date</label>
                <input type="date" name="uploaded_at"
                    value="<?php echo e(old('uploaded_at', $isEdit && $asset->uploaded_at ? $asset->uploaded_at->format('Y-m-d') : '')); ?>"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>
        </div>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', function () {
        // available_formats
        const fmtInput = form.querySelector('[name="available_formats_input"]');
        if (fmtInput) {
            fmtInput.value.split(',').map(s => s.trim()).filter(Boolean).forEach(val => {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = 'available_formats[]'; input.value = val;
                form.appendChild(input);
            });
        }
        // dimensions
        const dimInput = form.querySelector('[name="dimensions_input"]');
        if (dimInput) {
            dimInput.value.split(',').map(s => s.trim()).filter(Boolean).forEach(val => {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = 'dimensions[]'; input.value = val;
                form.appendChild(input);
            });
        }
    });
});
</script><?php /**PATH C:\laragon\www\asset-management\resources\views\assets\_form.blade.php ENDPATH**/ ?>