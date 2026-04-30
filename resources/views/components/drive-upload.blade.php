{{-- resources/views/components/drive-upload.blade.php --}}
<div x-data="{
    file: null,
    progress: 0,
    status: 'idle',
    errorMsg: '',
    fileId: '{{ $fileId ?? '' }}',   // edit এ পুরনো value

    async handleUpload(e) {
        this.file = e.target.files[0];
        if (!this.file) return;

        this.status   = 'uploading';
        this.progress = 0;
        this.errorMsg = '';
        this.fileId   = '';

        try {
            const sessionRes = await fetch('{{ route('drive.upload.session') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({
                    filename:  this.file.name,
                    mime_type: this.file.type || 'application/octet-stream',
                    size:      this.file.size,
                }),
            });

            if (!sessionRes.ok) throw new Error('Could not start upload session.');

            const { upload_url, file_name } = await sessionRes.json();
            await this.uploadToDrive(upload_url, file_name);

        } catch (err) {
            this.status   = 'failed';
            this.errorMsg = err.message || 'Upload failed. Please try again.';
        }
    },

    uploadToDrive(uploadUrl, fileName) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    this.progress = Math.round((e.loaded / e.total) * 100);
                }
            });

            xhr.addEventListener('load', async () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        await this.resolveFileId(fileName);
                        this.status   = 'completed';
                        this.progress = 100;
                        resolve();
                    } catch(e) {
                        reject(new Error('Upload done but could not get file info.'));
                    }
                } else {
                    reject(new Error('Drive upload failed: ' + xhr.status));
                }
            });

            xhr.addEventListener('error', async () => {
                if (this.progress === 100) {
                    try {
                        await this.resolveFileId(fileName);
                        this.status = 'completed';
                        resolve();
                    } catch (e) {
                        reject(new Error('Upload done but could not get file info.'));
                    }
                } else {
                    reject(new Error('Network error during upload.'));
                }
            });

            xhr.open('PUT', uploadUrl);
            xhr.setRequestHeader('Content-Type', this.file.type || 'application/octet-stream');
            xhr.send(this.file);
        });
    },

    // Drive file_id resolve করো — model update না করে শুধু id নাও
    async resolveFileId(fileName) {
        const res = await fetch('{{ route('drive.upload.resolve') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({ file_name: fileName }),
        });

        if (!res.ok) throw new Error('Could not resolve file.');

        const data  = await res.json();
        this.fileId = data.file_id; // hidden input এ বসবে
    },

    get fileSizeHuman() {
        if (!this.file) return '';
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = this.file.size, i = 0;
        while (size >= 1024 && i < units.length - 1) { size /= 1024; i++; }
        return Math.round(size * 10) / 10 + ' ' + units[i];
    }
}"
>
    {{-- Hidden input — form এর সাথে file_id পাঠাবে --}}
    <input type="hidden" name="{{ $fieldName ?? 'drive_file_id' }}" :value="fileId">

    {{-- Drop Zone --}}
    <div
        @click="if(status !== 'uploading') $refs.fileInput.click()"
        @dragover.prevent
        @drop.prevent="if(status !== 'uploading') handleUpload({ target: { files: $event.dataTransfer.files } })"
        class="flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-6 transition-all dark:border-gray-700 dark:bg-gray-800/40"
        :class="{
            'cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 dark:hover:border-blue-600': status !== 'uploading',
            'cursor-not-allowed': status === 'uploading',
            'border-green-400 bg-green-50/20 dark:border-green-700': status === 'completed',
            'border-red-400 bg-red-50/20 dark:border-red-700': status === 'failed',
            'border-blue-400 bg-blue-50/20 dark:border-blue-700': status === 'uploading',
        }"
    >
        {{-- Idle --}}
        <template x-if="status === 'idle' && !fileId">
            <div class="flex flex-col items-center gap-2 text-center pointer-events-none">
                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <svg class="text-gray-400" width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Click or drag file here</p>
                    <p class="text-xs text-gray-400 mt-0.5">PDF, DOC, XLS, ZIP — up to 2GB</p>
                </div>
            </div>
        </template>

        {{-- Edit এ existing file --}}
        <template x-if="status === 'idle' && fileId">
            <div class="flex items-center gap-3 pointer-events-none">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                    <svg class="text-green-600" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">File already uploaded</p>
                    <p class="text-xs text-gray-400 mt-0.5">Click to replace</p>
                </div>
            </div>
        </template>

        {{-- Uploading --}}
        <template x-if="status === 'uploading'">
            <div class="w-full space-y-3 pointer-events-none">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-gray-700 dark:text-gray-300 truncate max-w-[200px]" x-text="file.name"></span>
                    <span class="text-gray-400 shrink-0 ml-2" x-text="fileSizeHuman"></span>
                </div>
                <div class="w-full h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 overflow-hidden">
                    <div class="h-2.5 bg-blue-500 rounded-full transition-all duration-300"
                         :style="'width: ' + progress + '%'"></div>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="animate-spin text-blue-500 shrink-0" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                        </svg>
                        Uploading to Google Drive...
                    </span>
                    <span class="font-semibold text-blue-500" x-text="progress + '%'"></span>
                </div>
            </div>
        </template>

        {{-- Completed --}}
        <template x-if="status === 'completed'">
            <div class="flex items-center gap-3 pointer-events-none">
                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                    <svg class="text-green-500" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-green-700 dark:text-green-400 truncate" x-text="file.name"></p>
                    <p class="text-xs text-gray-400 mt-0.5">Uploaded to Google Drive ✅</p>
                </div>
            </div>
        </template>

        {{-- Failed --}}
        <template x-if="status === 'failed'">
            <div class="flex flex-col items-center gap-2 text-center pointer-events-none">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="text-red-500" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-red-600 dark:text-red-400" x-text="errorMsg"></p>
                    <p class="text-xs text-gray-400 mt-0.5">Click to try again</p>
                </div>
            </div>
        </template>
    </div>

    <input type="file" x-ref="fileInput" class="hidden"
           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.txt,.csv"
           @change="handleUpload($event)">
</div>