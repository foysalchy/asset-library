@extends('frontend.layouts.font')

@section('content')
<div class="bg-[#f3f3f3] min-h-screen pb-20 font-['Outfit']">
    <section class="container mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between py-6 px-6 text-[#0071c5]">
            <div class="flex items-center gap-4">
                <a href="{{ route('assets.show', $asset->slug) }}" class="hover:opacity-70">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Edit Content</h1>
                    <p class="text-xs sm:text-sm text-gray-500 truncate max-w-[150px] sm:max-w-xs">{{ $asset->title }}</p>
                </div>
            </div>
            {{-- Download All --}}
            <button onclick="downloadAll()"
                class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 sm:px-5 sm:py-2.5 rounded-lg text-sm font-bold hover:bg-green-700 transition-all shrink-0">
                <i class="fa-solid fa-download"></i>
                <span class="hidden sm:inline">Download All</span>
                <span class="sm:hidden">All</span>
            </button>
        </div>

        {{-- Main Editor --}}
        <div class="px-4 sm:px-6" x-data="contentEditor()">

            {{-- Image Thumbnails Tabs --}}
            <div class="flex gap-3 mb-4 overflow-x-auto pb-4 scrollbar-hide items-center px-1">
                @foreach($asset->media->where('media_type', 'image') as $index => $media)
                <button
                    @click="switchImage({{ $loop->index }})"
                    :class="activeIndex === {{ $loop->index }}
                            ? 'ring-2 ring-offset-2 ring-[#0071c5] border-[#0071c5] opacity-100'
                            : 'border-transparent opacity-60 hover:opacity-100 hover:border-gray-300'"
                    class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-lg border-2 transition-all overflow-hidden p-0 relative block bg-gray-200">
                    <img src="{{ $media->url }}"
                        class="w-full h-full block object-cover aspect-square"
                        alt="thumbnail">
                    
                    {{-- Active Indicator Checkmark --}}
                    <div x-show="activeIndex === {{ $loop->index }}" 
                         class="absolute top-1 right-1 bg-[#0071c5] text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </button>
                @endforeach
            </div>

            {{-- Editor Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Canvas Area --}}
                <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm p-4 w-full overflow-hidden" id="canvas-container">

                    {{-- Loading --}}
                    <div x-show="loading" class="flex items-center justify-center h-[300px] sm:h-[500px]">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="animate-spin text-[#0071c5] w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                            </svg>
                            <p class="text-sm text-gray-500">Loading image...</p>
                        </div>
                    </div>

                    {{-- Canvas --}}
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
                                @touchend.window="onMouseUp($event)"
                                @dblclick="onDblClick($event)">
                            </canvas>
                            <div x-show="selectedIndex !== null"
                                class="absolute top-2 left-2 bg-[#0071c5] text-white text-[10px] sm:text-xs px-2 py-1 rounded-full pointer-events-none shadow-md">
                                <i class="fa-solid fa-arrows-up-down-left-right mr-1"></i> Drag to move
                            </div>
                        </div>
                    </div>

                    {{-- Canvas Actions --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mt-4 pt-4 border-t border-gray-100 gap-3">
                        <p class="text-[11px] sm:text-xs text-gray-400">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            Tap/Click text to select · Drag to move · Pencil icon to edit
                        </p>
                        <button @click="downloadCurrent()"
                            :disabled="loading"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#0071c5] text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-[#005ea3] transition-all disabled:opacity-50">
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
                                <span x-text="selectedIndex !== null ? 'Edit Text' : 'Type to Add Text'"></span>
                            </h3>
                            <button x-show="selectedIndex !== null" @click="deselectText()" 
                                class="text-xs text-[#0071c5] hover:text-[#005ea3] font-bold bg-blue-50 px-2 py-1 rounded transition-colors">
                                <i class="fa-solid fa-plus mr-1"></i>New Text
                            </button>
                        </div>

                        <textarea x-model="currentTextInput"
                            @input="handleTextInput()"
                            placeholder="Type here to add text to the image..."
                            rows="3"
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
                                Reset Styles
                            </button>
                        </div>

                        {{-- Font Family --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Font Family</label>
                            <select x-model="style.fontFamily" @change="updateSelected()"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#0071c5] focus:outline-none">
                                <option value="Arial">Arial</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Verdana">Verdana</option>
                                <option value="Times New Roman">Times New Roman</option>
                                <option value="Courier New">Courier New</option>
                                <option value="Impact">Impact</option>
                                <option value="Trebuchet MS">Trebuchet MS</option>
                                <option value="Comic Sans MS">Comic Sans MS</option>
                                <option value="Palatino">Palatino</option>
                                <option value="Garamond">Garamond</option>
                            </select>
                        </div>

                        {{-- Font Size --}}
                        <div class="mb-3">
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

                        {{-- Font Weight & Style --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Style</label>
                            <div class="flex gap-2">
                                <button @click="style.fontWeight = style.fontWeight === 'bold' ? 'normal' : 'bold'; updateSelected()"
                                    :class="style.fontWeight === 'bold' ? 'bg-[#0071c5] text-white' : 'bg-gray-100 text-gray-600'"
                                    class="flex-1 py-1.5 rounded-lg text-sm font-bold transition-colors">B</button>
                                <button @click="style.fontStyle = style.fontStyle === 'italic' ? 'normal' : 'italic'; updateSelected()"
                                    :class="style.fontStyle === 'italic' ? 'bg-[#0071c5] text-white' : 'bg-gray-100 text-gray-600'"
                                    class="flex-1 py-1.5 rounded-lg text-sm italic transition-colors">I</button>
                                <button @click="style.underline = !style.underline; updateSelected()"
                                    :class="style.underline ? 'bg-[#0071c5] text-white' : 'bg-gray-100 text-gray-600'"
                                    class="flex-1 py-1.5 rounded-lg text-sm underline transition-colors">U</button>
                            </div>
                        </div>

                        {{-- Text Align --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Align</label>
                            <div class="flex gap-2">
                                <button @click="style.textAlign = 'left'; updateSelected()"
                                    :class="style.textAlign === 'left' ? 'bg-[#0071c5] text-white' : 'bg-gray-100 text-gray-600'"
                                    class="flex-1 py-1.5 rounded-lg text-sm transition-colors">
                                    <i class="fa-solid fa-align-left"></i>
                                </button>
                                <button @click="style.textAlign = 'center'; updateSelected()"
                                    :class="style.textAlign === 'center' ? 'bg-[#0071c5] text-white' : 'bg-gray-100 text-gray-600'"
                                    class="flex-1 py-1.5 rounded-lg text-sm transition-colors">
                                    <i class="fa-solid fa-align-center"></i>
                                </button>
                                <button @click="style.textAlign = 'right'; updateSelected()"
                                    :class="style.textAlign === 'right' ? 'bg-[#0071c5] text-white' : 'bg-gray-100 text-gray-600'"
                                    class="flex-1 py-1.5 rounded-lg text-sm transition-colors">
                                    <i class="fa-solid fa-align-right"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Line Height --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">
                                Line Height: <span class="text-[#0071c5] font-bold" x-text="style.lineHeight + 'x'"></span>
                            </label>
                            <input type="range" x-model="style.lineHeight" min="0.8" max="3" step="0.1"
                                @input="updateSelected()"
                                class="w-full accent-[#0071c5]">
                        </div>

                        {{-- Letter Spacing --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">
                                Letter Spacing: <span class="text-[#0071c5] font-bold" x-text="style.letterSpacing + 'px'"></span>
                            </label>
                            <input type="range" x-model="style.letterSpacing" min="-5" max="30" step="0.5"
                                @input="updateSelected()"
                                class="w-full accent-[#0071c5]">
                        </div>

                        {{-- Text Color --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Text Color</label>
                            <div class="flex items-center gap-2 mb-2">
                                <input type="color" x-model="style.color" @input="updateSelected()"
                                    class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer p-0.5 shrink-0">
                                <input type="text" x-model="style.color" @input="updateSelected()" 
                                    class="w-24 rounded-lg border border-gray-300 px-2 py-2 text-sm uppercase font-mono focus:border-[#0071c5] focus:outline-none" 
                                    maxlength="7" placeholder="#FFFFFF">
                            </div>
                            <div class="flex gap-1.5 flex-wrap">
                                <template x-for="color in quickColors">
                                    <button @click="style.color = color; updateSelected()"
                                        :style="'background:' + color"
                                        :class="style.color === color ? 'ring-2 ring-offset-1 ring-gray-400 scale-110' : ''"
                                        class="w-7 h-7 rounded-full border border-gray-200 transition-all shrink-0">
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Stroke --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">
                                Stroke Width: <span class="text-[#0071c5] font-bold" x-text="style.strokeWidth + 'px'"></span>
                            </label>
                            <input type="range" x-model="style.strokeWidth" min="0" max="15" step="0.5"
                                @input="updateSelected()"
                                class="w-full accent-[#0071c5] mb-2">
                                
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="style.strokeColor" @input="updateSelected()"
                                    class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer p-0.5 shrink-0">
                                <input type="text" x-model="style.strokeColor" @input="updateSelected()" 
                                    class="w-24 rounded-lg border border-gray-300 px-2 py-2 text-sm uppercase font-mono focus:border-[#0071c5] focus:outline-none" 
                                    maxlength="7" placeholder="#000000">
                            </div>
                        </div>

                        {{-- Rotation --}}
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">
                                Rotation: <span class="text-[#0071c5] font-bold" x-text="style.rotation + '°'"></span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="range" x-model="style.rotation" min="-180" max="180" step="1"
                                    @input="updateSelected()"
                                    class="flex-1 accent-[#0071c5]">
                                <button @click="style.rotation = 0; updateSelected()"
                                    class="text-xs text-gray-400 hover:text-[#0071c5] transition-colors shrink-0">Reset</button>
                            </div>
                        </div>

                        {{-- Opacity --}}
                        <div class="mb-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">
                                Opacity: <span class="text-[#0071c5] font-bold" x-text="Math.round(style.opacity * 100) + '%'"></span>
                            </label>
                            <input type="range" x-model="style.opacity" min="0.1" max="1" step="0.05"
                                @input="updateSelected()"
                                class="w-full accent-[#0071c5]">
                        </div>
                    </div>

                    {{-- Text List --}}
                    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5" x-show="texts.length > 0">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-list text-[#0071c5]"></i>
                            Texts (<span x-text="texts.length"></span>)
                        </h3>

                        <div class="space-y-2 max-h-[250px] overflow-y-auto pr-1 scrollbar-hide">
                            <template x-for="(text, index) in texts" :key="index">
                                <div class="rounded-lg border transition-colors"
                                    :class="selectedIndex === index
                                        ? 'border-[#0071c5] bg-blue-50'
                                        : 'border-gray-200 bg-gray-50 hover:border-gray-300'">

                                    {{-- Text row --}}
                                    <div class="flex items-center gap-2 p-2 cursor-pointer"
                                        @click="selectText(index)">
                                        <div class="w-4 h-4 rounded-full shrink-0 border border-gray-200"
                                            :style="'background:' + text.style.color"></div>
                                        <span class="text-sm text-gray-700 truncate flex-1" x-text="text.content"></span>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-1 shrink-0">
                                            {{-- Edit --}}
                                            <button @click.stop="openEditModal(index)"
                                                class="w-6 h-6 flex items-center justify-center rounded text-gray-400 hover:text-[#0071c5] hover:bg-blue-50 transition-colors"
                                                title="Edit text">
                                                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </button>
                                            {{-- Duplicate --}}
                                            <button @click.stop="duplicateText(index)"
                                                class="w-6 h-6 flex items-center justify-center rounded text-gray-400 hover:text-green-500 hover:bg-green-50 transition-colors"
                                                title="Duplicate">
                                                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                                                    <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
                                                </svg>
                                            </button>
                                            {{-- Delete --}}
                                            <button @click.stop="removeText(index)"
                                                class="w-6 h-6 flex items-center justify-center rounded text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                                                title="Remove">
                                                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                              
                                </div>
                            </template>
                        </div>

                        <button @click="clearAll()"
                            class="mt-3 w-full py-2 rounded-lg border border-red-200 text-red-500 text-sm hover:bg-red-50 transition-colors">
                            <i class="fa-solid fa-trash mr-1"></i> Clear All
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

{{-- Edit Text Modal --}}
<div id="edit-modal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative w-full max-w-sm bg-white rounded-2xl p-6 shadow-xl z-10">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-pen text-[#0071c5]"></i> Edit Text
        </h3>
        <textarea id="modal-text-input" rows="5"
            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-[#0071c5] focus:outline-none resize-none mb-4"
            placeholder="Enter your text..."></textarea>
        <div class="flex gap-3">
            <button onclick="closeEditModal()"
                class="flex-1 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-600 hover:bg-gray-50">Cancel</button>
            <button onclick="saveEditModal()"
                class="flex-1 py-2.5 rounded-lg bg-[#0071c5] text-white text-sm font-bold hover:bg-[#005ea3]">Save</button>
        </div>
    </div>
</div>

@endsection

@php
$mediaData = $asset->media
->where('media_type', 'image')
->values()
->map(fn($m) => [
'id' => $m->id,
'base64_url' => route('drive.media.base64', $m->id),
'original_name' => $m->file_original_name ?? 'image',
]);
@endphp

@push('scripts')
<script>
    const MEDIA_DATA = @json($mediaData);
    let editorInstance = null;
    let editingModalIndex = null;

    function openEditModal(index) {
        editingModalIndex = index;
        const text = editorInstance.texts[index].content;
        document.getElementById('modal-text-input').value = text;
        document.getElementById('edit-modal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
        editingModalIndex = null;
    }

    function saveEditModal() {
        if (editingModalIndex === null) return;
        const newText = document.getElementById('modal-text-input').value;
        editorInstance.texts[editingModalIndex].content = newText;
        editorInstance.currentTextInput = newText; 
        editorInstance.saveTexts();
        editorInstance.render();
        closeEditModal();
    }

    async function downloadAll() {
        if (!editorInstance) return;

        editorInstance.saveTexts();

        const editedIndices = [];
        for (let i = 0; i < MEDIA_DATA.length; i++) {
            if (editorInstance.allTexts[i] && editorInstance.allTexts[i].length > 0) {
                editedIndices.push(i);
            }
        }

        if (editedIndices.length === 0) {
            alert("No edited images found. Please add some text first.");
            return;
        }

        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>';
        btn.disabled = true;

        for (let i = 0; i < editedIndices.length; i++) {
            const indexToProcess = editedIndices[i];
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> ${i + 1}/${editedIndices.length}`;

            await editorInstance.switchImage(indexToProcess);
            await new Promise(r => setTimeout(r, 400)); 

            editorInstance.downloadCurrent();
            await new Promise(r => setTimeout(r, 600)); 
        }

        btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Done!';
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }, 2000);
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

            style: {
                fontFamily: 'Arial',
                fontSize: 90,
                fontWeight: 'bold',
                fontStyle: 'normal',
                underline: false,
                textAlign: 'left',
                lineHeight: 1.2,
                letterSpacing: 0,
                color: '#ffffff',
                strokeWidth: 2,
                strokeColor: '#000000',
                shadowBlur: 0,
                shadowX: 0,
                shadowY: 0,
                rotation: 0,
                opacity: 1,
            },

            quickColors: ['#ffffff', '#000000', '#ff0000', '#ffff00', '#00ff00', '#0071c5', '#ff6b35', '#f7931e', '#9b59b6', '#2ecc71'],

            async init() {
                this.canvas = document.getElementById('editor-canvas');
                this.ctx = this.canvas.getContext('2d');
                editorInstance = this;
                
                window.addEventListener('resize', () => {
                    if(!this.loading && this.baseImage) {
                        this.render();
                    }
                });

                await this.loadImage(0);
            },

            resetStyle() {
                this.style = {
                    fontFamily: 'Arial',
                    fontSize: 90,
                    fontWeight: 'bold',
                    fontStyle: 'normal',
                    underline: false,
                    textAlign: 'left',
                    lineHeight: 1.2,
                    letterSpacing: 0,
                    color: '#ffffff',
                    strokeWidth: 2,
                    strokeColor: '#000000',
                    shadowBlur: 0,
                    shadowX: 0,
                    shadowY: 0,
                    rotation: 0,
                    opacity: 1,
                };
                this.updateSelected();
            },

            async loadImage(index) {
                this.loading = true;
                this.deselectText();
                this.texts = this.allTexts[index] ? [...this.allTexts[index]] : [];

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

                if (s.rotation !== 0) {
                    const cx = x;
                    const cy = y;
                    ctx.translate(cx, cy);
                    ctx.rotate((s.rotation * Math.PI) / 180);
                    ctx.translate(-cx, -cy);
                }

                ctx.font = `${s.fontStyle !== 'normal' ? s.fontStyle + ' ' : ''}${s.fontWeight} ${size}px "${s.fontFamily}"`;
                ctx.textAlign = s.textAlign;
                ctx.textBaseline = 'top';

                const drawTextWithSpacing = (ctx, text, x, y) => {
                    if (!s.letterSpacing) {
                        if (s.strokeWidth > 0) {
                            ctx.strokeText(text, x, y);
                        }
                        ctx.fillText(text, x, y);
                        return;
                    }
                    let currentX = x;
                    for (let char of text) {
                        if (s.strokeWidth > 0) {
                            ctx.strokeText(char, currentX, y);
                        }
                        ctx.fillText(char, currentX, y);
                        currentX += ctx.measureText(char).width + (s.letterSpacing * scale);
                    }
                };

                lines.forEach((line, i) => {
                    const ly = y + (i * lineH);

                    if (s.strokeWidth > 0) {
                        ctx.strokeStyle = s.strokeColor;
                        ctx.lineWidth = s.strokeWidth * scale;
                    }

                    ctx.fillStyle = s.color;

                    drawTextWithSpacing(ctx, line, x, ly);

                    if (s.underline) {
                        const width = ctx.measureText(line).width + (s.letterSpacing * scale * Math.max(0, line.length - 1));
                        const ux = s.textAlign === 'center' ? x - width / 2 :
                            s.textAlign === 'right' ? x - width :
                            x;
                        ctx.fillStyle = s.color;
                        ctx.fillRect(ux, ly + size + 2, width, Math.max(1, size * 0.05));
                    }
                });

                ctx.shadowColor = 'transparent';
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 0;

                if (this.selectedIndex === index) {
                    const maxW = Math.max(...lines.map(l => ctx.measureText(l).width + (s.letterSpacing * scale * Math.max(0, l.length - 1))));
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

            // Handles auto-creating and live-updating text
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
                            style: { ...this.style },
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
                    this.style = { ...this.texts[index].style };
                    this.currentTextInput = this.texts[index].content;
                }
                this.render();
            },

            updateSelected() {
                if (this.selectedIndex === null) return;
                this.texts[this.selectedIndex].style = { ...this.style };
                this.saveTexts();
                this.render();
            },

            removeText(index) {
                this.texts.splice(index, 1);
                if (this.selectedIndex === index) {
                    this.deselectText();
                } else if (this.selectedIndex > index) {
                    this.selectedIndex--;
                }
                this.saveTexts();
                this.render();
            },

            duplicateText(index) {
                const original = this.texts[index];
                const copy = JSON.parse(JSON.stringify(original));
                copy.x += 40; 
                copy.y += 40;
                this.texts.splice(index + 1, 0, copy);
                this.selectText(index + 1);
                this.saveTexts();
                this.render();
            },

            clearAll() {
                this.texts = [];
                this.deselectText();
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

                const { x, y } = this.getCanvasPos(e);
                const scale = parseFloat(this.canvas.dataset.scale || 1);
                const ix = x / scale;
                const iy = y / scale;

                for (let i = this.texts.length - 1; i >= 0; i--) {
                    const text = this.texts[i];
                    const s = text.style;
                    this.ctx.font = `${s.fontStyle !== 'normal' ? s.fontStyle + ' ' : ''}${s.fontWeight} ${s.fontSize}px "${s.fontFamily}"`;
                    
                    const lines = text.content.split('\n');
                    const maxW = Math.max(...lines.map(l => this.ctx.measureText(l).width + (s.letterSpacing * Math.max(0, l.length - 1))));
                    const totalH = lines.length * s.fontSize * s.lineHeight;

                    let checkX = ix;
                    let checkY = iy;

                    if (s.rotation !== 0) {
                        const angle = -(s.rotation * Math.PI) / 180;
                        const dx = ix - text.x;
                        const dy = iy - text.y;
                        checkX = text.x + dx * Math.cos(angle) - dy * Math.sin(angle);
                        checkY = text.y + dx * Math.sin(angle) + dy * Math.cos(angle);
                    }

                    let boxX = text.x;
                    if (s.textAlign === 'center') boxX = text.x - maxW / 2;
                    else if (s.textAlign === 'right') boxX = text.x - maxW;

                    if (checkX >= boxX - 10 && checkX <= boxX + maxW + 10 &&
                        checkY >= text.y - 10 && checkY <= text.y + totalH + 10) {
                        
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
                const { x, y } = this.getCanvasPos(e);
                const scale = parseFloat(this.canvas.dataset.scale || 1);

                this.texts[this.selectedIndex].x = (x / scale) - this.dragOffsetX;
                this.texts[this.selectedIndex].y = (y / scale) - this.dragOffsetY;
                
                this.render();
            },

            onMouseUp() {
                if(this.isDragging && this.selectedIndex !== null) {
                    this.saveTexts();
                }
                this.isDragging = false;
            },

            onDblClick(e) {
                if (this.selectedIndex === null) return;
                openEditModal(this.selectedIndex);
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
                if (!this.baseImage) return;

                const fullCanvas = document.createElement('canvas');
                const fullCtx = fullCanvas.getContext('2d');
                fullCanvas.width = this.baseImage.naturalWidth;
                fullCanvas.height = this.baseImage.naturalHeight;
                fullCtx.drawImage(this.baseImage, 0, 0);

                this.texts.forEach((text, index) => {
                    this.drawText(fullCtx, text, index, 1);
                });

                const name = MEDIA_DATA[this.activeIndex]?.original_name ?? 'image';
                const link = document.createElement('a');
                link.download = name.replace(/\.[^.]+$/, '') + '_edited.png';
                link.href = fullCanvas.toDataURL('image/png');
                link.click();
            },
        };
    }
</script>
@endpush