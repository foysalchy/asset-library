@extends('frontend.layouts.font')

@section('content')
<!-- JSZip Library for ZIP download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<div class="bg-[#f3f3f3] min-h-screen pb-20 font-['Outfit']" x-data="contentEditor()">

    {{-- Download Selection Modal Overlay --}}
    <div x-show="downloadModalOpen" style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">

        <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden"
            @click.outside="if(!isDownloading) downloadModalOpen = false">

            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Select Images to Download</h3>
                    <p class="text-xs text-gray-500">Only edited images are available for download.</p>
                </div>
                <button @click="if(!isDownloading) downloadModalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            {{-- Modal Body (Thumbnails Grid) --}}
            <div class="p-6 overflow-y-auto flex-1">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-sm font-bold text-[#0071c5]" x-text="selectedForDownload.length + ' selected'"></div>
                    <div class="flex gap-2">
                        <button @click="selectAllForDownload()" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded transition-colors">Select All</button>
                        <button @click="deselectAllForDownload()" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded transition-colors">Deselect All</button>
                    </div>
                </div>

                {{-- Grid of EDITED images only --}}
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                    <template x-for="(media, index) in MEDIA_DATA" :key="index">
                        <div x-show="isImageEdited(index)"
                            @click="if(!isDownloading) toggleDownloadSelection(index)"
                            class="relative cursor-pointer group aspect-square rounded-xl border-2 transition-all overflow-hidden"
                            :class="selectedForDownload.includes(index) ? 'border-[#0071c5] ring-2 ring-offset-2 ring-[#0071c5]' : 'border-transparent hover:border-gray-300'">

                            <img :src="media.thumbnail" class="w-full h-full object-cover">

                            {{-- Overlay for unselected state --}}
                            <div class="absolute inset-0 bg-black/40 transition-opacity" :class="selectedForDownload.includes(index) ? 'opacity-0' : 'opacity-100 group-hover:opacity-60'"></div>

                            {{-- Checkmark for selected --}}
                            <div x-show="selectedForDownload.includes(index)" class="absolute top-2 right-2 bg-[#0071c5] text-white w-6 h-6 flex items-center justify-center rounded-full shadow-md">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Modal Footer (Download Buttons) --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row gap-3 justify-end items-center">

                <div x-show="isDownloading" class="flex items-center gap-2 text-sm text-[#0071c5] font-bold w-full sm:w-auto justify-center sm:justify-start mr-auto">
                    <i class="fa-solid fa-spinner fa-spin"></i> <span x-text="downloadProgress"></span>
                </div>

                <button @click="downloadBatch('png')" :disabled="selectedForDownload.length === 0 || isDownloading"
                    class="w-full sm:w-auto bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-images mr-1"></i> Download Selected (PNG)
                </button>
                <button @click="downloadBatch('zip')" :disabled="selectedForDownload.length === 0 || isDownloading"
                    class="w-full sm:w-auto bg-orange-500 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-orange-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-file-zipper mr-1"></i> Download Selected (ZIP)
                </button>
            </div>
        </div>
    </div>

    <section class="container mx-auto">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-center justify-between py-6 px-6 gap-4 text-[#0071c5]">
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <a href="{{ url()->previous() }}" class="hover:opacity-70">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Edit Content</h1>
                    <p class="text-xs sm:text-sm text-gray-500 truncate max-w-[150px] sm:max-w-xs">{{ $asset->title }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                {{-- Download This 1 (Header) --}}
                <button @click="downloadCurrent()"
                    :disabled="texts.length === 0 || loading"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-[#0071c5] text-white px-4 py-2 sm:px-5 sm:py-2.5 rounded-lg text-sm font-bold hover:bg-[#005ea3] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-file-image"></i>
                    <span class="hidden sm:inline">Download This</span>
                    <span class="sm:hidden">Current</span>
                </button>

                {{-- Download All --}}
                <button @click="openDownloadModal()"
                    :disabled="!isAnyEdited()"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-green-600 text-white px-4 py-2 sm:px-5 sm:py-2.5 rounded-lg text-sm font-bold hover:bg-green-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-download"></i>
                    <span class="hidden sm:inline">Download All</span>
                    <span class="sm:hidden">All</span>
                </button>
            </div>
        </div>

        {{-- Main Editor --}}
        <div class="px-4 sm:px-6">

            {{-- Image Thumbnails Tabs --}}
            <div class="flex gap-3 mb-4 overflow-x-auto pb-4 scrollbar-hide items-center px-1">
                @foreach($asset->media->where('media_type', 'image') as $index => $media)
                <button
                    @click="switchImage({{ $loop->index }})"
                    :class="activeIndex === {{ $loop->index }}
                            ? 'ring-2 ring-offset-2 ring-[#0071c5] border-[#0071c5] opacity-100'
                            : 'border-transparent opacity-60 hover:opacity-100 hover:border-gray-300'"
                    class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-lg border-2 transition-all overflow-hidden p-0 relative block bg-gray-200">
                    <img src="{{ $media->url }}" class="w-full h-full block object-cover aspect-square" alt="thumbnail">

                    <div x-show="activeIndex === {{ $loop->index }}"
                        class="absolute top-1 right-1 bg-[#0071c5] text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div x-show="isImageEdited({{ $loop->index }})"
                        class="absolute bottom-1 right-1 bg-green-500 w-3 h-3 rounded-full border border-white shadow-sm">
                    </div>
                </button>
                @endforeach
            </div>

            {{-- Editor Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Canvas Area --}}
                <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm p-4 w-full overflow-hidden" id="canvas-container">

                    <div x-show="loading" class="flex items-center justify-center h-[300px] sm:h-[500px]">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="animate-spin text-[#0071c5] w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                            </svg>
                            <p class="text-sm text-gray-500">Loading image...</p>
                        </div>
                    </div>

                    <div x-show="!loading" class="relative flex items-center justify-center w-full">
                        <div class="relative inline-block max-w-full">
                            <canvas id="editor-canvas"
                                class="block max-w-full h-auto rounded-lg touch-none"
                                :style="isDragging ? 'cursor: grabbing;' : 'cursor: grab;'"
                                @mousedown="onMouseDown($event)"
                                @mousemove.window="onMouseMove($event)"
                                @mouseup.window="onMouseUp($event)"
                                @touchstart="onMouseDown($event)"
                                @touchmove.window="onMouseMove($event)"
                                @touchend.window="onMouseUp($event)">
                            </canvas>
                            <div x-show="selectedIndex !== null"
                                class="absolute top-2 left-2 bg-[#0071c5] text-white text-[10px] sm:text-xs px-2 py-1 rounded-full pointer-events-none shadow-md">
                                <i class="fa-solid fa-arrows-up-down-left-right mr-1"></i> Drag to move
                            </div>
                        </div>
                    </div>

                    {{-- Download This 2 (Under Canvas) & Info --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mt-4 pt-4 border-t border-gray-100 gap-3">
                        <p class="text-[11px] sm:text-xs text-gray-400">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            Tap/Click text to select · Drag to move
                        </p>
                        <button @click="downloadCurrent()"
                            :disabled="texts.length === 0 || loading"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#0071c5] text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-[#005ea3] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-download"></i>
                            Download This
                        </button>
                    </div>
                </div>

                {{-- Controls Panel --}}
                <div class="lg:col-span-4 space-y-4">

                    {{-- Live Edit / Add Text --}}
                    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                <i class="fa-solid fa-text-height text-[#0071c5]"></i>
                                <span x-text="selectedIndex !== null ? 'Edit Text' : 'Type to Add Text/Number'"></span>
                            </h3>
                            <button x-show="selectedIndex !== null" @click="deselectText()"
                                class="text-xs text-[#0071c5] hover:text-[#005ea3] font-bold bg-blue-50 px-2 py-1 rounded transition-colors">
                                <i class="fa-solid fa-plus mr-1"></i>New Text
                            </button>
                        </div>

                        <textarea x-model="currentTextInput"
                            @input="handleTextInput()"
                            placeholder="Type here to add text to the image..."
                            rows="4"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-[#0071c5] focus:outline-none focus:ring-2 focus:ring-[#0071c5]/10 resize-none">
                        </textarea>
                    </div>

                    {{-- Style Panel --}}
                    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                <i class="fa-solid fa-palette text-[#0071c5]"></i>
                                Text Style
                                <span x-show="selectedIndex !== null"
                                    class="ml-2 text-[10px] sm:text-xs bg-blue-100 text-[#0071c5] px-2 py-0.5 rounded-full">
                                    Editing
                                </span>
                            </h3>
                            <button @click="resetStyle()"
                                class="text-xs text-red-500 hover:text-red-700 underline transition-colors">
                                Reset
                            </button>
                        </div>

                        {{-- Font Size --}}
                        <div class="mb-5">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">
                                Size: <span class="text-[#0071c5] font-bold" x-text="style.fontSize + 'px'"></span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="range" x-model="style.fontSize" min="10" max="300" step="1"
                                    @input="updateSelected()"
                                    class="flex-1 accent-[#0071c5]">
                                <input type="number" x-model="style.fontSize" min="10" max="300"
                                    @input="updateSelected()"
                                    class="w-16 rounded-lg border border-gray-300 px-2 py-1 text-sm text-center focus:border-[#0071c5] focus:outline-none">
                            </div>
                        </div>

                        {{-- Text Color --}}
                        <div class="mb-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Text Color</label>
                            <div class="flex items-center gap-2 mb-3">
                                <input type="color" x-model="style.color" @input="updateSelected()"
                                    class="w-12 h-12 rounded-lg border border-gray-300 cursor-pointer p-0.5 shrink-0">
                                <input type="text" x-model="style.color" @input="updateSelected()"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm uppercase font-mono focus:border-[#0071c5] focus:outline-none"
                                    maxlength="7" placeholder="#FFFFFF">
                            </div>
                            <div class="flex gap-2 flex-wrap">
                                <template x-for="color in quickColors">
                                    <button @click="style.color = color; updateSelected()"
                                        :style="'background:' + color"
                                        :class="style.color === color ? 'ring-2 ring-offset-2 ring-[#0071c5] scale-110' : ''"
                                        class="w-8 h-8 rounded-full border border-gray-200 shadow-sm transition-all shrink-0">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@php
$mediaData = $asset->media
->where('media_type', 'image')
->values()
->map(fn($m) => [
'id' => $m->id,
'base64_url' => route('drive.media.base64', $m->id),
'thumbnail' => $m->url,
'original_name' => $m->file_original_name ?? 'image',
]);
@endphp

@push('scripts')
<script>
    const MEDIA_DATA = @json($mediaData);
 const ASSET_ID        = {{ $asset->id }};
    const TRACK_URL       = "{{ route('download-logs.track') }}";
    const CSRF_TOKEN      = document.querySelector('meta[name=csrf-token]').content;

    async function trackDownload(fileName = null) {
        try {
            await fetch(TRACK_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify({
                    model:    'asset',
                    model_id: ASSET_ID,
                    file_name: fileName,        
                    file_type: 'edited_content',
                }),
            });
        } catch(e) {
            console.error('Track failed:', e);
        }
    }
    function contentEditor() {
        return {
            activeIndex: 0,
            loading: true,
            canvas: null,
            ctx: null,
            baseImage: null,
            imageCache: {},
            texts: [],
            allTexts: {},
            selectedIndex: null,
            isDragging: false,
            dragOffsetX: 0,
            dragOffsetY: 0,
            currentTextInput: '',

            downloadModalOpen: false,
            selectedForDownload: [],
            isDownloading: false,
            downloadProgress: '',

            style: {
                fontFamily: 'Arial',
                fontSize: 60,
                fontWeight: 'bold',
                fontStyle: 'normal',
                underline: false,
                textAlign: 'left',
                lineHeight: 1.2,
                letterSpacing: 0,
                color: '#ffffff',
                strokeWidth: 0,
                strokeColor: '#000000',
                rotation: 0,
                opacity: 1,
            },

            quickColors: ['#ffffff', '#000000', '#ff0000', '#ffff00', '#00ff00', '#0071c5', '#ff6b35', '#f7931e', '#9b59b6', '#2ecc71'],

            async init() {
                this.canvas = document.getElementById('editor-canvas');
                this.ctx = this.canvas.getContext('2d');

                window.addEventListener('resize', () => {
                    if (!this.loading && this.baseImage) {
                        this.render();
                    }
                });

                await this.loadImage(0);
            },

            isImageEdited(index) {
                if (index === this.activeIndex) return this.texts.length > 0;
                return this.allTexts[index] && this.allTexts[index].length > 0;
            },

            isAnyEdited() {
                if (this.texts.length > 0) return true;
                for (let key in this.allTexts) {
                    if (this.allTexts[key] && this.allTexts[key].length > 0) return true;
                }
                return false;
            },

            openDownloadModal() {
                this.deselectText();
                this.saveTexts();
                this.selectedForDownload = MEDIA_DATA.map((_, index) => index).filter(i => this.isImageEdited(i));
                this.downloadModalOpen = true;
            },

            toggleDownloadSelection(index) {
                if (this.selectedForDownload.includes(index)) {
                    this.selectedForDownload = this.selectedForDownload.filter(i => i !== index);
                } else {
                    this.selectedForDownload.push(index);
                }
            },

            selectAllForDownload() {
                this.selectedForDownload = MEDIA_DATA.map((_, index) => index).filter(i => this.isImageEdited(i));
            },

            deselectAllForDownload() {
                this.selectedForDownload = [];
            },

            resetStyle() {
                this.style.fontSize = 60;
                this.style.color = '#ffffff';
                this.updateSelected();
            },
            async loadImage(index) {
                this.loading = true;

                // ✅ আগে texts set করো
                this.texts = this.allTexts[index] ?
                    JSON.parse(JSON.stringify(this.allTexts[index])) :
                    [];

                // ✅ তারপর deselect করো (render call ছাড়া)
                this.selectedIndex = null;
                this.currentTextInput = '';

                if (this.imageCache[index]) {
                    this.setImage(this.imageCache[index]);
                    return;
                }

                try {
                    const res = await fetch(MEDIA_DATA[index].base64_url);
                    const data = await res.json();
                    this.imageCache[index] = data.base64;
                    this.setImage(data.base64);
                } catch (e) {
                    console.error('Image load error:', e);
                    this.loading = false;
                }
            },
            setImage(base64) {
                const img = new Image();
                img.onload = () => {
                    this.baseImage = img;

                    const container = document.getElementById('canvas-container');
                    const maxW = container ? (container.clientWidth - 32) : Math.min(850, window.innerWidth - 32);
                    const scale = img.width > maxW ? maxW / img.width : 1;

                    this.canvas.width = img.width * scale;
                    this.canvas.height = img.height * scale;
                    this.canvas.dataset.scale = scale;
                    this.canvas.dataset.origW = img.width;
                    this.canvas.dataset.origH = img.height;

                    this.loading = false;

                    // ✅ এখানে log করো
                    this.render();
                };
                img.src = base64;
            },

            render() {
                if (!this.ctx || !this.baseImage) return;
                const scale = parseFloat(this.canvas.dataset.scale || 1);

                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                this.ctx.drawImage(this.baseImage, 0, 0, this.canvas.width, this.canvas.height);

                this.texts.forEach((text, index) => {
                    this.drawText(this.ctx, text, index, scale);
                });
            },

            drawText(ctx, text, index, scale) {
                const s = text.style;
                const x = text.x * scale;
                const y = text.y * scale;
                const size = s.fontSize * scale;
                const lineH = size * s.lineHeight;
                const lines = text.content.split('\n');

                ctx.save();
                ctx.globalAlpha = s.opacity;

                ctx.font = `${s.fontStyle !== 'normal' ? s.fontStyle + ' ' : ''}${s.fontWeight} ${size}px "${s.fontFamily}"`;
                ctx.textAlign = s.textAlign;
                ctx.textBaseline = 'top';

                lines.forEach((line, i) => {
                    const ly = y + (i * lineH);
                    ctx.fillStyle = s.color;
                    ctx.fillText(line, x, ly);
                });

                if (this.selectedIndex === index) {
                    const maxW = Math.max(...lines.map(l => ctx.measureText(l).width));
                    const totalH = lines.length * lineH;

                    let boxX = x;
                    if (s.textAlign === 'center') boxX = x - maxW / 2;
                    else if (s.textAlign === 'right') boxX = x - maxW;

                    ctx.strokeStyle = '#0071c5';
                    ctx.lineWidth = 2;
                    ctx.setLineDash([6, 3]);
                    ctx.strokeRect(boxX - 8, y - 8, maxW + 16, totalH + 16);
                    ctx.setLineDash([]);
                }

                ctx.restore();
            },

            handleTextInput() {
                if (this.selectedIndex !== null) {
                    this.texts[this.selectedIndex].content = this.currentTextInput;
                    this.saveTexts();
                    this.render();
                } else {
                    if (this.currentTextInput.trim() !== '') {
                        const scale = parseFloat(this.canvas.dataset.scale || 1);
                        const origW = parseFloat(this.canvas.dataset.origW || this.canvas.width);
                        const origH = parseFloat(this.canvas.dataset.origH || this.canvas.height);

                        const defaultX = origW * 0.05;
                        const defaultY = origH - this.style.fontSize * 1.2 - 30;

                        this.texts.push({
                            content: this.currentTextInput,
                            x: defaultX,
                            y: defaultY,
                            style: {
                                ...this.style
                            },
                        });

                        this.selectedIndex = this.texts.length - 1;
                        this.saveTexts();
                        this.render();
                    }
                }
            },

            deselectText() {
                this.selectedIndex = null;
                this.currentTextInput = '';
                this.render();
            },

            selectText(index) {
                this.selectedIndex = index;
                if (this.texts[index]) {
                    this.style = {
                        ...this.texts[index].style
                    };
                    this.currentTextInput = this.texts[index].content;
                }
                this.render();
            },

            updateSelected() {
                if (this.selectedIndex === null) return;
                this.texts[this.selectedIndex].style = {
                    ...this.style
                };
                this.saveTexts();
                this.render();
            },

            saveTexts() {
                this.allTexts[this.activeIndex] = JSON.parse(JSON.stringify(this.texts));
            },
            async switchImage(index) {
                this.saveTexts();

                if (!this.allTexts[index] && this.texts.length > 0) {
                    this.allTexts[index] = JSON.parse(JSON.stringify(this.texts));
                }

                this.activeIndex = index;
                await this.loadImage(index);

            },

            onMouseDown(e) {
                if (e.target.id !== 'editor-canvas') return;

                const {
                    x,
                    y
                } = this.getCanvasPos(e);
                const scale = parseFloat(this.canvas.dataset.scale || 1);
                const ix = x / scale;
                const iy = y / scale;

                for (let i = this.texts.length - 1; i >= 0; i--) {
                    const text = this.texts[i];
                    const s = text.style;
                    this.ctx.font = `${s.fontStyle !== 'normal' ? s.fontStyle + ' ' : ''}${s.fontWeight} ${s.fontSize}px "${s.fontFamily}"`;

                    const lines = text.content.split('\n');
                    const maxW = Math.max(...lines.map(l => this.ctx.measureText(l).width));
                    const totalH = lines.length * s.fontSize * s.lineHeight;

                    let boxX = text.x;
                    if (s.textAlign === 'center') boxX = text.x - maxW / 2;
                    else if (s.textAlign === 'right') boxX = text.x - maxW;

                    if (ix >= boxX - 10 && ix <= boxX + maxW + 10 &&
                        iy >= text.y - 10 && iy <= text.y + totalH + 10) {

                        this.selectText(i);
                        this.isDragging = true;

                        this.dragOffsetX = ix - text.x;
                        this.dragOffsetY = iy - text.y;
                        return;
                    }
                }

                this.deselectText();
            },

            onMouseMove(e) {
                if (!this.isDragging || this.selectedIndex === null) return;
                const {
                    x,
                    y
                } = this.getCanvasPos(e);
                const scale = parseFloat(this.canvas.dataset.scale || 1);

                this.texts[this.selectedIndex].x = (x / scale) - this.dragOffsetX;
                this.texts[this.selectedIndex].y = (y / scale) - this.dragOffsetY;

                this.render();
            },

            onMouseUp() {
                if (this.isDragging && this.selectedIndex !== null) {
                    this.saveTexts();
                }
                this.isDragging = false;
            },

            getCanvasPos(e) {
                const rect = this.canvas.getBoundingClientRect();

                let clientX = e.clientX;
                let clientY = e.clientY;

                if (e.touches && e.touches.length > 0) {
                    clientX = e.touches[0].clientX;
                    clientY = e.touches[0].clientY;
                } else if (e.changedTouches && e.changedTouches.length > 0) {
                    clientX = e.changedTouches[0].clientX;
                    clientY = e.changedTouches[0].clientY;
                }

                return {
                    x: (clientX - rect.left) * (this.canvas.width / rect.width),
                    y: (clientY - rect.top) * (this.canvas.height / rect.height),
                };
            },

            downloadCurrent() {
                this.deselectText();
       // ✅ clean call

                if (!this.baseImage || this.texts.length === 0) return;

                const fullCanvas = document.createElement('canvas');
                const fullCtx = fullCanvas.getContext('2d');
                fullCanvas.width = this.baseImage.naturalWidth;
                fullCanvas.height = this.baseImage.naturalHeight;
                fullCtx.drawImage(this.baseImage, 0, 0);

                this.texts.forEach((text, index) => {
                    this.drawText(fullCtx, text, index, 1);
                });

                const fileName = MEDIA_DATA[this.activeIndex]?.original_name ?? 'image';
                trackDownload(fileName);                    
                const link = document.createElement('a');
                link.download = fileName.replace(/\.[^.]+$/, '') + '_edited.png';
                link.href = fullCanvas.toDataURL('image/png');
                link.click();
            },

       async downloadBatch(type) {
    if (this.selectedForDownload.length === 0) return;
    this.isDownloading = true;

    const zip = type === 'zip' ? new JSZip() : null;

    for (let i = 0; i < this.selectedForDownload.length; i++) {
        const index = this.selectedForDownload[i];
        this.downloadProgress = `Processing ${i + 1}/${this.selectedForDownload.length}`;

        const data = MEDIA_DATA[index];
  console.log('Tracking file:', data.original_name);
        // ✅ প্রতিটা image এর জন্য আলাদা track
         await trackDownload(data.original_name ?? 'image');

        const img = new Image();
        img.src = this.imageCache[index] || data.base64_url;
        await new Promise(r => img.onload = r);

        const finalCanvas = document.createElement('canvas');
        finalCanvas.width  = img.naturalWidth;
        finalCanvas.height = img.naturalHeight;
        const fCtx = finalCanvas.getContext('2d');
        fCtx.drawImage(img, 0, 0);

        const textsToDraw = (index === this.activeIndex) ? this.texts : this.allTexts[index];
        if (textsToDraw && textsToDraw.length > 0) {
            textsToDraw.forEach((text, idx) => this.drawText(fCtx, text, idx, 1));
        }

        const dataUrl  = finalCanvas.toDataURL('image/png');
        const fileName = (data.original_name.replace(/\.[^.]+$/, '')) + '_edited.png';

        if (type === 'zip') {
            const base64Data = dataUrl.split(',')[1];
            zip.file(fileName, base64Data, { base64: true });
        } else {
            const link = document.createElement('a');
            link.download = fileName;
            link.href     = dataUrl;
            link.click();
            await new Promise(r => setTimeout(r, 400));
        }
    }

    if (type === 'zip') {
        this.downloadProgress = "Zipping files...";
        const content = await zip.generateAsync({ type: "blob" });
        const link    = document.createElement('a');
        link.download = @js($asset->title) + ".zip";
        link.href     = URL.createObjectURL(content);
        link.click();
    }

    this.isDownloading    = false;
    this.downloadModalOpen = false;
    this.downloadProgress  = '';
},
        };
    }
</script>
@endpush