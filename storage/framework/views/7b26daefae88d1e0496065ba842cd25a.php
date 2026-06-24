<?php $isEdit = isset($asset) && $asset->exists; ?>
<?php $__env->startPush('head'); ?>
<style>
    /* ১. Editor-এর Height বাড়ানো হলো */
    .ck-editor__editable_inline {
        min-height: 300px !important;

    }

    /* ২. Fix Tailwind Preflight stripping list & heading styles */
    .ck-content ul {
        list-style-type: disc !important;
        padding-left: 1.5rem !important;
    }

    .ck-content ol {
        list-style-type: decimal !important;
        padding-left: 1.5rem !important;
    }

    .ck-content h2 {
        font-size: 1.5em !important;
        font-weight: bold !important;
        margin-bottom: 0.5em !important;
    }

    .ck-content h3 {
        font-size: 1.25em !important;
        font-weight: bold !important;
        margin-bottom: 0.5em !important;
    }

    .ck-content p {
        margin-bottom: 0.5em !important;
    }

    /* Validation Error Border */
    .has-error .ck-editor__main>.ck-editor__editable,
    .has-error .ck-toolbar {
        border-color: #f87171 !important;
    }

    /* Tailwind Dark Mode Integration */
    .dark .ck.ck-editor__main>.ck-editor__editable {
        background-color: #111827 !important;
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }

    .dark .ck.ck-toolbar {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }

    .dark .ck.ck-toolbar .ck.ck-button {
        color: #e5e7eb !important;
    }

    .dark .ck.ck-toolbar .ck.ck-button:hover {
        background-color: #374151 !important;
    }

    .dark .ck.ck-toolbar .ck.ck-button.ck-on {
        background-color: #4b5563 !important;
        color: #ffffff !important;
    }

    .dark .ck.ck-dropdown__panel {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }

    .dark .ck.ck-list__item__button {
        color: #e5e7eb !important;
    }

    .dark .ck.ck-list__item__button:hover {
        background-color: #374151 !important;
    }

    /* Modal Focus Fix: Modal-এর ভেতর থাকলে যেন ড্রপডাউনগুলো কাজ করে */
    :root {
        --ck-z-default: 100;
        --ck-z-modal: calc(var(--ck-z-default) + 9999);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/super-build/ckeditor.js"></script>

<script>
    // ফাংশন তৈরি করে রাখলাম যেন যেকোনো সময় কল করা যায়
    function initializeCKEditor() {
        const editorElement = document.querySelector('#campaign-description');

        if (!editorElement) return;

        if (editorElement.classList.contains('ck-initialized')) return;
        editorElement.classList.add('ck-initialized');

        CKEDITOR.ClassicEditor.create(editorElement, {
                toolbar: {
                    items: [
                        'undo', 'redo', '|',
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                        'alignment', '|',
                        'bulletedList', 'numberedList', '|',
                        'link', 'blockQuote', 'insertTable', 'horizontalLine'
                    ],
                    shouldNotGroupWhenFull: true
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                },

                removePlugins: [
                    'AIAssistant', 'OpenAITextAdapter', 'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeRevisionHistory',
                    'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData', 'RevisionHistory',
                    'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template',
                    'DocumentOutline', 'FormatPainter', 'TableOfContents', 'PasteFromOfficeEnhanced',
                    'ExportPdf', 'ExportWord', 'CKBox', 'CKFinder', 'EasyImage', 'Base64UploadAdapter',
                    'CaseChange'
                ],
            })
            .then(editor => {
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
                });

                window.campaignEditor = editor;

                const form = editorElement.closest('form');
                if (form) {
                    form.addEventListener('submit', () => {
                        editorElement.value = editor.getData();
                    });
                }
            })
            .catch(error => {
                console.error('CKEditor error:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', initializeCKEditor);
    document.addEventListener('livewire:load', initializeCKEditor);
    document.addEventListener('livewire:navigated', initializeCKEditor);
</script>
<?php $__env->stopPush(); ?>
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    
    <div class="lg:col-span-2 space-y-5">

        
        <div>
            <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" id="title" required
                value="<?php echo e(old('title', $isEdit ? $asset->title : '')); ?>"
                placeholder="e.g. Static Banners:"
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
                placeholder="static-banners-bhaiya-asset-library-arc-b580"
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
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Description
            </label>

            <div class="text-gray-900 dark:text-white">
                
                <textarea name="description" id="campaign-description"><?php echo old('description', $isEdit ? $asset->description : ''); ?></textarea>
            </div>

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
                Media Files (1080P x 1080P)
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
                        <svg class="text-white" width="28" height="28" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                        </svg>
                    </div>
                    <?php endif; ?>
                    <button type="button"
                        @click="markDelete('<?php echo e($media->id); ?>')"
                        class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center rounded-full bg-red-500 text-white opacity-0 group-hover:opacity-100 transition-opacity shadow">
                        <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
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
                                <svg class="text-blue-400" width="28" height="28" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                                </svg>
                                <span class="text-xs text-blue-500 truncate px-2 w-full text-center" x-text="file.name"></span>
                            </div>
                        </template>
                        <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1">
                            <p class="text-xs text-white truncate" x-text="file.size"></p>
                        </div>
                        <button type="button" @click="removeNew(index)"
                            class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center rounded-full bg-red-500 text-white opacity-0 group-hover:opacity-100 transition-opacity shadow">
                            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

            
            <div x-data="{
    images: [],
    completedData: [],

  async addImages(e) {
    const newFiles = Array.from(e.target.files);
    for (const file of newFiles) {
        const image = {
            file, name: file.name, size: this.formatSize(file.size),
            status: 'idle', progress: 0, errorMsg: '',
            preview: URL.createObjectURL(file),
        };
        this.images.push(image);

        const reactiveImage = this.images[this.images.length - 1];
        await this.uploadImage(reactiveImage);
    }
    e.target.value = '';
},

    uploadImage(image) {
        return new Promise((resolve) => {
            image.status = 'uploading';
            image.progress = 0;
            this.$dispatch('drive-uploading', { uploading: true });

            const xhr = new XMLHttpRequest();
            const formData = new FormData();
            formData.append('image', image.file);
            formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
xhr.upload.addEventListener('progress', (e) => {
    if (e.lengthComputable) {
        const pct = Math.round((e.loaded / e.total) * 90);
        image.progress = pct;
        this.images = [...this.images]; // ✅ force re-render
    }
});

           xhr.addEventListener('load', () => {
    try {
        const data = JSON.parse(xhr.responseText);
        if (data.success) {
            image.progress = 100;
            image.status   = 'completed';
            image.driveId  = data.drive_id; 
            image.compressedId   = data.drive_compressed_id; 


            this.completedData.push({
                drive_id: data.drive_id,
                compressed_id: data.drive_compressed_id,
                original_name: data.original_name,
                mime_type: data.mime_type,
                file_size: data.file_size,
            });
        } else {
            image.status = 'failed';
            image.errorMsg = data.message || 'Upload failed.';
        }
    } catch(e) {
        image.status = 'failed';
        image.errorMsg = 'Invalid server response.';
    }

    const allDone = this.images.every(v => v.status !== 'uploading');
    if (allDone) this.$dispatch('drive-uploading', { uploading: false });
    resolve();
});
            xhr.addEventListener('error', () => {
                image.status = 'failed';
                image.errorMsg = 'Network error.';
                resolve();
            });

            xhr.open('POST', '<?php echo e(route('assets.media.upload-image')); ?>');
            xhr.send(formData);
        });
    },

async remove(index) {
    const img = this.images[index];

    if (img.driveId) {
        img.status = 'removing'; 

        try {
            await fetch('<?php echo e(route('assets.media.delete-temp-image')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({
                    drive_id: img.driveId,
                    compressed_id: img.compressedId,
                }),
            });
        } catch (e) {
            console.error('Failed to delete from Drive:', e);
        }

        this.completedData = this.completedData.filter(d => d.drive_id !== img.driveId);
    }

    this.images.splice(index, 1);
},

    formatSize(bytes) {
        const units = ['B','KB','MB','GB'];
        let i = 0;
        while (bytes >= 1024 && i < units.length - 1) { bytes /= 1024; i++; }
        return Math.round(bytes * 10) / 10 + ' ' + units[i];
    }
}">

                
                <template x-for="(data, index) in completedData" :key="index">
                    <input type="hidden" name="drive_images[]" :value="JSON.stringify(data)">
                </template>

                
                <div @click="$refs.imageInput.click()"
                    @dragover.prevent
                    @drop.prevent="addImages({ target: { files: $event.dataTransfer.files } })"
                    class="flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-6 cursor-pointer hover:border-blue-400 transition-all">
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-700">Click or drag images here</p>
                        <p class="text-xs text-gray-400 mt-0.5">JPG, PNG, WEBP — uploads + compresses instantly</p>
                    </div>
                </div>

                
                <template x-if="images.length > 0">
                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="relative rounded-lg border border-gray-200 bg-white p-2">
                                <div class="relative aspect-square rounded-lg overflow-hidden mb-2 bg-gray-100">
                                    <img :src="image.preview" class="w-full h-full object-cover" :class="image.status === 'uploading' ? 'opacity-50' : ''">
                                    <template x-if="image.status == 'uploading'">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                            <span class="text-white text-xs font-bold" x-text="image.progress + '%'"></span>
                                        </div>
                                    </template>

                                    <template x-if="image.status === 'removing'">
                                        <div class="absolute inset-0 flex items-center justify-center bg-red-500/30">
                                            <svg class="animate-spin text-white w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                                            </svg>
                                        </div>
                                    </template>
                                    <template x-if="image.status === 'completed'">
                                        <div class="absolute top-1 right-1 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                            <svg width="10" height="10" viewBox="0 0 20 20" fill="white">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                            </svg>
                                        </div>
                                    </template>
                                </div>
                                <p class="text-xs text-gray-500 truncate" x-text="image.name"></p>
                                <button type="button" @click="remove(index)"
                                    :disabled="image.status === 'uploading' || image.status === 'removing'"
                                    class="text-xs text-red-500 hover:underline mt-1"
                                    :class="(image.status === 'uploading' || image.status === 'removing') ? 'opacity-30 cursor-not-allowed' : ''">
                                    <span x-show="image.status !== 'removing'">Remove</span>
                                    <span x-show="image.status === 'removing'" class="flex items-center gap-1">
                                        <svg class="animate-spin w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                                        </svg>
                                        Removing...
                                    </span>
                                </button>
                            </div>
                        </template>
                    </div>
                </template>

                <input type="file" x-ref="imageInput" multiple accept="image/jpeg,image/png,image/webp,image/gif" class="hidden" @change="addImages($event)">
            </div>

            
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Videos
                    <span class="text-xs font-normal text-gray-400 ml-1">MP4, MOV, WEBM · Direct to Google Drive</span>
                </label>

                <div x-data="{
        videos: [],
        completedIds: [],

      async addVideos(e) {
    const newFiles = Array.from(e.target.files);
    for (const file of newFiles) {
        const video = {
            file,
            name:     file.name,
            size:     this.formatSize(file.size),
            status:   'idle',
            progress: 0,
            fileId:   '',
            errorMsg: '',
        };
        this.videos.push(video);
        // index দিয়ে track করো
        const index = this.videos.length - 1;
        await this.uploadVideo(index); // object এর বদলে index পাঠাও
    }
    e.target.value = '';
},

async uploadVideo(index) {
    this.videos[index].status   = 'uploading';
    this.videos[index].progress = 0;
    this.$dispatch('drive-uploading', { uploading: true });

    try {
        const video = this.videos[index];
        const sessionRes = await fetch('<?php echo e(route('drive.upload.session')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({
                filename:  video.file.name,
                mime_type: video.file.type || 'video/mp4',
                size:      video.file.size,
            }),
        });

        if (!sessionRes.ok) throw new Error('Could not start upload session.');
        const { upload_url, file_name } = await sessionRes.json();

        await this.uploadToDrive(index, upload_url, file_name);

    } catch (err) {
        this.videos[index].status   = 'failed';
        this.videos[index].errorMsg = err.message || 'Upload failed.';
    }

    const allDone = this.videos.every(v => v.status !== 'uploading');
    if (allDone) this.$dispatch('drive-uploading', { uploading: false });
},

uploadToDrive(index, uploadUrl, fileName) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                // ✅ index দিয়ে directly update — Alpine reactive ধরবে
                this.videos[index].progress = Math.round((e.loaded / e.total) * 100);
            }
        });

        xhr.addEventListener('load', async () => {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    await this.resolveFileId(index, fileName);
                    this.videos[index].status   = 'completed';
                    this.videos[index].progress = 100;
                    resolve();
                } catch(e) {
                    this.videos[index].status   = 'failed';
                    this.videos[index].errorMsg = 'Upload done but could not get file info.';
                    reject(e);
                }
            } else {
                reject(new Error('Drive upload failed: ' + xhr.status));
            }
        });

        xhr.addEventListener('error', async () => {
            if (this.videos[index].progress === 100) {
                try {
                    await this.resolveFileId(index, fileName);
                    this.videos[index].status = 'completed';
                    resolve();
                } catch(e) {
                    this.videos[index].status   = 'failed';
                    this.videos[index].errorMsg = 'Upload done but could not get file info.';
                    reject(e);
                }
            } else {
                this.videos[index].status   = 'failed';
                this.videos[index].errorMsg = 'Network error during upload.';
                reject(new Error('Network error'));
            }
        });

        xhr.open('PUT', uploadUrl);
        xhr.setRequestHeader('Content-Type', this.videos[index].file.type || 'video/mp4');
        xhr.send(this.videos[index].file);
    });
},

async resolveFileId(index, fileName) {
    const res = await fetch('<?php echo e(route('drive.upload.resolve')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
        body: JSON.stringify({ file_name: fileName }),
    });
    if (!res.ok) throw new Error('Could not resolve file.');
    const data = await res.json();
    this.videos[index].fileId = data.file_id;
    this.completedIds.push(data.file_id);
},

remove(index) {
    const video = this.videos[index];
    if (video.fileId) {
        this.completedIds = this.completedIds.filter(id => id !== video.fileId);
    }
    this.videos.splice(index, 1);
},

retry(index) {
    if (this.videos[index].fileId) {
        this.completedIds = this.completedIds.filter(id => id !== this.videos[index].fileId);
        this.videos[index].fileId = '';
    }
    this.uploadVideo(index);
},

        formatSize(bytes) {
            const units = ['B','KB','MB','GB'];
            let i = 0;
            while (bytes >= 1024 && i < units.length - 1) { bytes /= 1024; i++; }
            return Math.round(bytes * 10) / 10 + ' ' + units[i];
        }
    }">

                    <template x-for="fileId in completedIds" :key="fileId">
                        <input type="hidden" name="drive_video_ids[]" :value="fileId">
                    </template>

                    <div @click="$refs.videoInput.click()"
                        @dragover.prevent
                        @drop.prevent="addVideos({ target: { files: $event.dataTransfer.files } })"
                        class="flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-6 cursor-pointer hover:border-purple-400 hover:bg-purple-50/30 dark:border-gray-700 dark:bg-gray-800/40 dark:hover:border-purple-600 transition-all">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center dark:bg-purple-900/30">
                            <svg class="text-purple-500" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm12.553 1.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Click or drag videos here</p>
                            <p class="text-xs text-gray-400 mt-0.5">MP4, MOV, WEBM · Uploads directly to Google Drive</p>
                        </div>
                    </div>

                    <template x-if="videos.length > 0">
                        <div class="mt-3 space-y-2">
                            <template x-for="(video, index) in videos" :key="index">
                                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-800">

                                    
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                        :class="{
                                'bg-purple-100 dark:bg-purple-900/30': video.status === 'idle' || video.status === 'uploading',
                                'bg-green-100 dark:bg-green-900/30':   video.status === 'completed',
                                'bg-red-100 dark:bg-red-900/30':       video.status === 'failed',
                             }">
                                        <svg class="w-4 h-4"
                                            :class="{
                                    'text-purple-500 animate-pulse': video.status === 'uploading',
                                    'text-green-500':                video.status === 'completed',
                                    'text-red-500':                  video.status === 'failed',
                                    'text-gray-400':                 video.status === 'idle',
                                 }"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm12.553 1.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                        </svg>
                                    </div>

                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate" x-text="video.name"></p>

                                        
                                        <template x-if="video.status === 'uploading'">
                                            <div class="mt-1.5">
                                                <div class="w-full h-1.5 bg-gray-200 rounded-full dark:bg-gray-700 overflow-hidden">
                                                    <div class="h-1.5 bg-purple-500 rounded-full transition-all duration-300"
                                                        :style="'width: ' + video.progress + '%'"></div>
                                                </div>
                                                <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                                    <svg class="animate-spin w-3 h-3 text-purple-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                                                    </svg>
                                                    <span x-text="video.progress + '% · ' + video.size"></span>
                                                </p>
                                            </div>
                                        </template>

                                        
                                        <template x-if="video.status === 'completed'">
                                            <p class="text-xs text-green-600 dark:text-green-400 mt-0.5">
                                                ✅ Uploaded to Google Drive
                                            </p>
                                        </template>

                                        
                                        <template x-if="video.status === 'failed'">
                                            <p class="text-xs text-red-500 mt-0.5 truncate" x-text="video.errorMsg"></p>
                                        </template>

                                        
                                        <template x-if="video.status === 'idle'">
                                            <p class="text-xs text-gray-400 mt-0.5" x-text="video.size"></p>
                                        </template>
                                    </div>

                                    
                                    <div class="flex items-center gap-1 shrink-0">
                                        
                                        <template x-if="video.status === 'failed'">
                                            <button type="button" @click="retry(index)"> 
                                                class="w-7 h-7 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" />
                                                </svg>
                                            </button>
                                        </template>

                                        
                                        <button type="button" @click="remove(index)"
                                            :disabled="video.status === 'uploading'"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 transition-colors"
                                            :class="video.status === 'uploading' ? 'opacity-30 cursor-not-allowed' : ''">
                                            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                                            </svg>
                                        </button>
                                    </div>

                                </div>
                            </template>
                        </div>
                    </template>

                    <input type="file" x-ref="videoInput" multiple
                        accept="video/mp4,video/quicktime,video/webm"
                        class="hidden" @change="addVideos($event)">
                </div>
            </div>
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

        
        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/40 space-y-4">
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
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="active" <?php echo e(old('status', $isEdit ? $asset->status : 'active')       === 'active'  ? 'selected' : ''); ?>>Active</option>
                    <option value="draft" <?php echo e(old('status', $isEdit ? $asset->status : '') === 'draft'   ? 'selected' : ''); ?>>Draft</option>
                    <option value="expired" <?php echo e(old('status', $isEdit ? $asset->status : '')       === 'expired' ? 'selected' : ''); ?>>Expired</option>
                </select>
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

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (!form) return;
        form.addEventListener('submit', function() {
            // available_formats
            const fmtInput = form.querySelector('[name="available_formats_input"]');
            if (fmtInput) {
                fmtInput.value.split(',').map(s => s.trim()).filter(Boolean).forEach(val => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'available_formats[]';
                    input.value = val;
                    form.appendChild(input);
                });
            }
            // dimensions
            const dimInput = form.querySelector('[name="dimensions_input"]');
            if (dimInput) {
                dimInput.value.split(',').map(s => s.trim()).filter(Boolean).forEach(val => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'dimensions[]';
                    input.value = val;
                    form.appendChild(input);
                });
            }
        });
    });
</script><?php /**PATH C:\laragon\www\asset-management\resources\views/assets/_form.blade.php ENDPATH**/ ?>