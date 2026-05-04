@php $isEdit = isset($asset) && $asset->exists; @endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ── Left Column ──────────────────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Title --}}
        <div>
            <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" id="title" required
                value="{{ old('title', $isEdit ? $asset->title : '') }}"
                placeholder="e.g. Static Banners:"
                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('title') border-red-400 @enderror" />
            @error('title')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Slug --}}
        <div>
            <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Slug <span class="text-xs font-normal text-gray-400 ml-1">(auto-generated if empty)</span>
            </label>
            <input type="text" name="slug" id="slug"
                value="{{ old('slug', $isEdit ? $asset->slug : '') }}"
                placeholder="static-banners-bhaiya-asset-library-arc-b580"
                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('slug') border-red-400 @enderror" />
            @error('slug')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Description</label>
            <textarea name="description" rows="4"
                placeholder="Describe this asset..."
                class="shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('description') border-red-400 @enderror">{{ old('description', $isEdit ? $asset->description : '') }}</textarea>
            @error('description')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Available Formats & Dimensions --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Available Formats
                    <span class="text-xs font-normal text-gray-400 ml-1">comma separated</span>
                </label>
                <input type="text" name="available_formats_input"
                    value="{{ old('available_formats_input', $isEdit ? implode(', ', $asset->available_formats ?? []) : '') }}"
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
                    value="{{ old('dimensions_input', $isEdit ? implode(', ', $asset->dimensions ?? []) : '') }}"
                    placeholder="300x250, 728x90, 1456x180"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <p class="mt-1 text-xs text-gray-400">e.g. 300x250, 600x500</p>
            </div>
        </div>

        {{-- ── Media Upload ─────────────────────────────────────── --}}
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

            {{-- Delete IDs hidden inputs --}}
            <template x-for="id in deletedIds" :key="id">
                <input type="hidden" name="delete_media[]" :value="id">
            </template>

            {{-- Existing Media (edit mode) --}}
            @if($isEdit && $asset->media->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3">
                    @foreach($asset->media as $media)
                        <div id="media-{{ $media->id }}" class="relative group rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 aspect-video">
                            @if($media->media_type === 'image')
                                <img src="{{ $media->url }}" alt="{{ $media->file_original_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <video src="{{ $media->url }}" class="w-full h-full object-cover"></video>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                    <svg class="text-white" width="28" height="28" viewBox="0 0 20 20" fill="currentColor"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                </div>
                            @endif
                            <button type="button"
                                    @click="markDelete('{{ $media->id }}')"
                                    class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center rounded-full bg-red-500 text-white opacity-0 group-hover:opacity-100 transition-opacity shadow">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- New Media Previews --}}
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

            {{-- Drop Zone --}}
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
            @error('media.*')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Download File --}}
        <div x-data="{
            currentFile: '{{ $isEdit && $asset->file_path ? $asset->file_original_name : '' }}',
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
             <x-drive-upload
                    field-name="drive_file_id"
                    :file-id="old('drive_file_id', $isEdit && $asset->file ? str_replace('drive:', '', $asset->file) : '')" />


                <input type="file" id="file_input" x-ref="fileInput" name="file"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.txt,.csv"
                    class="hidden" @change="handleFile($event)">
                @error('file')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            <input type="file" id="asset_file_input" x-ref="fileInput" name="file"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.txt,.csv"
                class="hidden" @change="handleFile($event)">
            @error('file')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

    </div>

    {{-- ── Right Column ──────────────────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Asset Settings --}}
        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/40 space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Asset Settings</h4>

            {{-- Campaign --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Project <span class="text-red-500">*</span>
                </label>
                <select name="project_id" required
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('project_id') border-red-400 @enderror">
                    <option value="">— Select Project —</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}"
                            {{ old('project_id', $isEdit ? $asset->project_id : ($selectedProject ?? '')) == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Asset Type --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Asset Type <span class="text-red-500">*</span>
                </label>
                <select name="asset_type_id" required
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('asset_type_id') border-red-400 @enderror">
                    <option value="">— Select Type —</option>
                    @foreach($assetTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ old('asset_type_id', $isEdit ? $asset->asset_type_id : '') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('asset_type_id')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Asset ID Code --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Asset ID Code
                    <span class="text-xs font-normal text-gray-400 ml-1">e.g. AS6748EN</span>
                </label>
                <input type="text" name="asset_id_code"
                    value="{{ old('asset_id_code', $isEdit ? $asset->asset_id_code : '') }}"
                    placeholder="AS6748EN"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>

            {{-- Sort Order --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Sort Order</label>
                <input type="number" name="sort_order" min="0"
                    value="{{ old('sort_order', $isEdit ? $asset->sort_order : 0) }}"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>

            {{-- Upload Date --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Upload Date</label>
                <input type="date" name="uploaded_at"
                    value="{{ old('uploaded_at', $isEdit && $asset->uploaded_at ? $asset->uploaded_at->format('Y-m-d') : '') }}"
                    class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>
        </div>

    </div>
</div>

{{-- Formats & Dimensions hidden array inputs (JS convert করবে) --}}
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
</script>