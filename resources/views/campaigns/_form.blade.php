@php $isEdit = isset($campaign) && $campaign->exists; @endphp
@push('head')
<style>
    /* ১. Editor-এর Height বাড়ানো হলো */
    .ck-editor__editable_inline {
        min-height: 300px !important;
        /* প্রয়োজন হলে 500px করতে পারেন */
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
@endpush

@push('scripts')

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
@endpush

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ── Left Column ─────────────────────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Title --}}
        <div>
            <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Title <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 dark:border-gray-800 text-gray-500">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M3 4a1 1 0 000 2h1v8H3a1 1 0 100 2h14a1 1 0 100-2h-1V6h1a1 1 0 100-2H3zm3 2h2v8H6V6zm4 0h4v8h-4V6z" fill="#667085" />
                    </svg>
                </span>
                <input type="text" name="title" id="title"
                    value="{{ old('title', $isEdit ? $campaign->title : '') }}"
                    placeholder="e.g. Eid Special Offer 2025"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('title') border-red-400 dark:border-red-500 @enderror" />
            </div>
            @error('title')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Slug --}}
        <div>
            <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Slug <span class="text-xs font-normal text-gray-400 ml-1">(auto-generated if empty)</span>
            </label>
            <div class="relative">
                <span class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 dark:border-gray-800 text-gray-500">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" fill="#667085" />
                    </svg>
                </span>
                <input type="text" name="slug" id="slug"
                    value="{{ old('slug', $isEdit ? $campaign->slug : '') }}"
                    placeholder="eid-special-offer-2025"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('slug') border-red-400 @enderror" />
            </div>
            @error('slug')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Description (CKEditor 5) --}}
        {{-- Description (CKEditor 5) --}}
        {{-- Description (CKEditor 5) --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Description
            </label>

            <div class="text-gray-900 dark:text-white">
                {{-- id এবং name ঠিক রাখুন --}}
                <textarea name="description" id="campaign-description">{!! old('description', $isEdit ? $campaign->description : '') !!}</textarea>
            </div>

            @error('description')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        {{-- Dates Row --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Published At</label>
                <input type="date" name="published_at"
                    value="{{ old('published_at', $isEdit && $campaign->published_at ? $campaign->published_at->format('Y-m-d') : '') }}"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('published_at') border-red-400 @enderror" />
                @error('published_at')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Expires At</label>
                <input type="date" name="expired_at"
                    value="{{ old('expired_at', $isEdit && $campaign->expired_at ? $campaign->expired_at->format('Y-m-d') : '') }}"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('expired_at') border-red-400 @enderror" />
                @error('expired_at')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── Upload Cards ──────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

            {{-- Thumbnail Upload --}}
            <div x-data="{
                preview: '{{ $isEdit && $campaign->thumbnail ? $campaign->thumbnailUrl : '' }}',
                fileName: '',
                removed: false,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    this.fileName = file.name;
                    this.removed = false;
                    const reader = new FileReader();
                    reader.onload = (ev) => this.preview = ev.target.result;
                    reader.readAsDataURL(file);
                },
                remove() {
                    this.preview = '';
                    this.fileName = '';
                    this.removed = true;
                    document.getElementById('thumbnail_input').value = '';
                }
            }">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Thumbnail Image
                    <span class="text-xs font-normal text-gray-400 ml-1">JPG, PNG, WEBP · max 5MB</span>
                </label>
                <input type="hidden" name="remove_thumbnail" :value="removed ? '1' : '0'">

                {{-- Drop Zone --}}
                <div @click="$refs.thumbnailInput.click()"
                    @dragover.prevent @drop.prevent="handleFile({ target: { files: $event.dataTransfer.files } })"
                    class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-4 cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 dark:border-gray-700 dark:bg-gray-800/40 dark:hover:border-blue-600 dark:hover:bg-blue-900/10 transition-all"
                    :class="preview ? 'border-blue-400 bg-blue-50/20 dark:border-blue-700' : ''">

                    {{-- Preview --}}
                    <template x-if="preview">
                        <div class="relative w-full">
                            <img :src="preview" alt="Preview" class="w-full h-32 object-cover rounded-lg">
                            <button type="button" @click.stop="remove()"
                                class="absolute -top-2 -right-2 w-6 h-6 flex items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600 transition-colors">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                        </div>
                    </template>

                    {{-- Placeholder --}}
                    <template x-if="!preview">
                        <div class="flex flex-col items-center gap-2 py-4 text-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900/30">
                                <svg class="text-blue-500" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Click or drag image here</p>
                                <p class="text-xs text-gray-400 mt-0.5" x-text="fileName || 'No file selected'"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <input type="file" id="thumbnail_input" x-ref="thumbnailInput" name="thumbnail"
                    accept="image/*" class="hidden" @change="handleFile($event)">
                @error('thumbnail')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- File Upload --}}
            <div x-data="{
                currentFile: '{{ $isEdit && $campaign->file ? $campaign->fileName : '' }}',
                newFileName: '',
                fileSize: '',
                removed: false,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    this.newFileName = file.name;
                    this.fileSize = this.formatSize(file.size);
                    this.removed = false;
                },
                remove() {
                    this.newFileName = '';
                    this.fileSize = '';
                    this.removed = true;
                    document.getElementById('file_input').value = '';
                },
                formatSize(bytes) {
                    const units = ['B','KB','MB','GB'];
                    let i = 0;
                    while (bytes >= 1024 && i < units.length - 1) { bytes /= 1024; i++; }
                    return Math.round(bytes * 10) / 10 + ' ' + units[i];
                },
                get displayName() {
                    return this.newFileName || (!this.removed && this.currentFile ? this.currentFile : '');
                }
            }">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Attachment File
                    <span class="text-xs font-normal text-gray-400 ml-1">PDF, DOC, XLS, ZIP · max 20MB</span>
                </label>
                <input type="hidden" name="remove_file" :value="removed ? '1' : '0'">

                {{-- Drop Zone --}}
                <div @click="$refs.fileInput.click()"
                    @dragover.prevent @drop.prevent="handleFile({ target: { files: $event.dataTransfer.files } })"
                    class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-4 cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 dark:border-gray-700 dark:bg-gray-800/40 dark:hover:border-blue-600 transition-all"
                    :class="displayName ? 'border-green-400 bg-green-50/20 dark:border-green-700 dark:bg-green-900/5' : ''">

                    <template x-if="displayName">
                        <div class="flex items-center gap-3 w-full px-2">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0 dark:bg-green-900/30">
                                <svg class="text-green-600" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate" x-text="displayName"></p>
                                <p class="text-xs text-gray-400" x-text="fileSize || 'Existing file'"></p>
                            </div>
                            <button type="button" @click.stop="remove()"
                                class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-red-100 hover:text-red-500 dark:hover:bg-red-900/30 transition-colors">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                        </div>
                    </template>

                    <template x-if="!displayName">
                        <div class="flex flex-col items-center gap-2 py-4 text-center">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center dark:bg-gray-700">
                                <svg class="text-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Click or drag file here</p>
                                <p class="text-xs text-gray-400 mt-0.5">PDF, DOC, XLS, ZIP and more</p>
                            </div>
                        </div>
                    </template>
                </div>

                <input type="file" id="file_input" x-ref="fileInput" name="file"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.txt,.csv"
                    class="hidden" @change="handleFile($event)">
                @error('file')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

        </div>
        {{-- End Upload Cards --}}

    </div>

    {{-- ── Right Column ─────────────────────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Campaign Settings --}}
        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/40 space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Campaign Settings</h4>

            {{-- Concern --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Project <span class="text-red-500">*</span>
                </label>
                <select name="project_id"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('concern') border-red-400 @enderror">
                    <option value="">— Select Project —</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id', $isEdit ? $campaign->project_id : '') === $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                    @endforeach
                </select>
                @error('project_id')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('status') border-red-400 @enderror">
                    <option value="draft" {{ old('status', $isEdit ? $campaign->status : 'draft') === 'draft'   ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ old('status', $isEdit ? $campaign->status : '')       === 'active'  ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ old('status', $isEdit ? $campaign->status : '')       === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @error('status')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Sort Order --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Sort Order</label>
                <input type="number" name="sort_order" min="0"
                    value="{{ old('sort_order', $isEdit ? $campaign->sort_order : 0) }}"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>

            {{-- Featured Toggle --}}
            <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Highlight this campaign</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1"
                        {{ old('is_featured', $isEdit ? $campaign->is_featured : false) ? 'checked' : '' }}
                        class="sr-only peer">
                    <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700"></div>
                </label>
            </div>
        </div>

        {{-- Languages --}}
        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/40">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-0.5">
                Languages <span class="text-red-500">*</span>
            </h4>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Select at least one language</p>

            @php $selectedLangs = old('languages', $isEdit ? $campaign->languages : []); @endphp

            <div class="space-y-2">
                <label class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 cursor-pointer hover:border-blue-300 dark:border-gray-700 dark:bg-gray-800 transition-colors has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 dark:has-[:checked]:border-blue-700">
                    <input type="checkbox" name="languages[]" value="en"
                        {{ in_array('en', (array)$selectedLangs) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500 dark:border-gray-600">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🇬🇧</span>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">English</p>
                            <p class="text-xs text-gray-400">en</p>
                        </div>
                    </div>
                </label>
                <label class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 cursor-pointer hover:border-blue-300 dark:border-gray-700 dark:bg-gray-800 transition-colors has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 dark:has-[:checked]:border-blue-700">
                    <input type="checkbox" name="languages[]" value="bn"
                        {{ in_array('bn', (array)$selectedLangs) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500 dark:border-gray-600">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🇧🇩</span>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Bengali</p>
                            <p class="text-xs text-gray-400">bn</p>
                        </div>
                    </div>
                </label>
            </div>
            @error('languages')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

    </div>
</div>