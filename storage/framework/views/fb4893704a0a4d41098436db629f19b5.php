<?php $__env->startSection('content'); ?>
<div class="bg-[#f3f3f3] pb-20 font-['Outfit']">
    <section class="container mx-auto">
        <!-- Sub-header Navigation -->
        <div class="flex items-center justify-between py-6 px-6 text-[#0071c5]">
            <div class="flex items-center gap-6">
                <a href="<?php echo e(url()->previous()); ?>" class="hover:opacity-70"><i class="fas fa-arrow-left text-xl"></i></a>
                <p class="text-sm">
                    <span class="text-[#757575] font-semibold">Preview this content in a different language:</span>
                    <span class="font-bold cursor-pointer ml-1">English <i class="fas fa-chevron-down text-[10px] ml-1"></i></span>
                </p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="px-6 grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

            <!-- LEFT: Gallery Section -->
            <div class="lg:col-span-7 bg-white p-6 shadow-sm flex gap-8 items-start min-h-[600px]"
                x-data="{
                    active: 0,
                    zoomed: false,
                    zoomX: 0,
                    zoomY: 0,
                    media: <?php echo e($asset->media->map(fn($m) => [
                          'url'       => $m->url,
    'streamUrl' => $m->stream_url,  
    'type'      => $m->media_type,
                    ])->toJson()); ?>,

                    get current() { return this.media[this.active]; },
                    get isVideo()  { return this.current?.type === 'video'; },

                    switchMedia(index) {
                        if (this.active === index) return;
                        this.zoomed = false;
                        this.active = index;
                    },

                    handleZoom(e) {
                        if (this.isVideo) return;
                        if (!this.zoomed) {
                            const rect = e.currentTarget.getBoundingClientRect();
                            const x = ((e.clientX - rect.left) / rect.width)  * 100;
                            const y = ((e.clientY - rect.top)  / rect.height) * 100;
                            this.zoomX  = x;
                            this.zoomY  = y;
                            this.zoomed = true;
                        } else {
                            this.zoomed = false;
                        }
                    },

                    moveZoom(e) {
                        if (!this.zoomed || this.isVideo) return;
                        const rect = e.currentTarget.getBoundingClientRect();
                        this.zoomX = ((e.clientX - rect.left) / rect.width)  * 100;
                        this.zoomY = ((e.clientY - rect.top)  / rect.height) * 100;
                    }
                 }">
                <!-- Thumbnail Rail -->
                <div class="w-20 shrink-0 flex flex-col gap-3">
                    <?php $__currentLoopData = $asset->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div
                        @click="switchMedia(<?php echo e($index); ?>)"
                        :class="active === <?php echo e($index); ?> ? 'border-[#0071c5]' : 'border-transparent hover:border-gray-300'"
                        class="border-2 p-1 cursor-pointer transition-all relative overflow-hidden">
                        <?php if($media->media_type === 'video'): ?>
                        
                        <div class="w-full aspect-square bg-gray-900 flex items-center justify-center relative">
                            <i class="fa-solid fa-play text-white text-sm absolute"></i>
                            <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 opacity-80"></div>
                        </div>
                        <?php else: ?>
                        <img src="<?php echo e($media->url); ?>"
                            class="w-full h-auto block object-cover aspect-square"
                            alt="thumbnail">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Main Preview -->
                <div class="flex-1 flex items-center justify-center bg-gray-50/50 rounded-sm overflow-hidden relative"
                    style="min-height: 400px;">

                    
                    <template x-if="!isVideo">
                        <div
                            class="w-full h-full flex items-center justify-center overflow-hidden"
                            :class="zoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'"
                            @click="handleZoom($event)"
                            @mousemove="moveZoom($event)"
                            @mouseleave="zoomed = false"
                            style="min-height: 400px;">
                            <img
                                :src="current?.url"
                                alt="Main Preview"
                                class="w-full h-auto max-h-[600px] object-contain transition-all duration-300 select-none"
                                :style="zoomed
                                    ? `transform: scale(2.5); transform-origin: ${zoomX}% ${zoomY}%; transition: transform 0.1s ease;`
                                    : 'transform: scale(1); transition: transform 0.3s ease;'"
                                draggable="false">
                        </div>
                    </template>

                    
                    
                    <template x-if="isVideo">
                        <div class="relative w-full rounded-xl overflow-hidden bg-black" style="aspect-ratio: 16/9;">

                            
                            <video
                                :key="active"
                                :src="current?.streamUrl"
                                controls
                                preload="auto"
                                autoplay
                                muted
                                playsinline
                                class="w-full h-full object-contain"
                                x-ref="videoPlayer"
                                x-effect="$el.pause(); $el.load();"
                                @play="$refs.playOverlay.style.opacity = '0'; $refs.playOverlay.style.pointerEvents = 'none'"
                                @pause="$refs.playOverlay.style.opacity = '1'; $refs.playOverlay.style.pointerEvents = 'auto'">
                                Your browser does not support the video tag.
                            </video>

                            
                            <div x-ref="playOverlay"
                                @click="$refs.videoPlayer.play()"
                                class="absolute inset-0 flex flex-col items-center justify-center cursor-pointer transition-opacity duration-200"
                                style="background: rgba(0,0,0,0.5); border-radius: inherit;">

                                
                                <div class="flex items-center justify-center w-16 h-16 rounded-full mb-3"
                                    style="background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.4);">
                                    <svg class="w-7 h-7 text-white ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                                    </svg>
                                </div>
                                <span class="text-xs tracking-wider" style="color: rgba(255,255,255,0.7);">tap to play</span>
                            </div>

                            
                            <div class="absolute top-3 right-3 flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs"
                                style="background: rgba(0,0,0,0.5); color: rgba(255,255,255,0.75); font-family: monospace;">
                                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm12.553 1.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                </svg>
                                VIDEO
                            </div>

                        </div>
                    </template>
                    </template>

                    
                    <template x-if="!isVideo">
                        <div class="absolute bottom-3 right-3 pointer-events-none">
                            <span
                                x-show="!zoomed"
                                x-transition
                                class="text-xs text-gray-400 bg-white/80 px-2 py-1 rounded-full backdrop-blur-sm flex items-center gap-1">
                                <i class="fa-solid fa-magnifying-glass-plus text-[10px]"></i> Click to zoom
                            </span>
                            <span
                                x-show="zoomed"
                                x-transition
                                class="text-xs text-gray-400 bg-white/80 px-2 py-1 rounded-full backdrop-blur-sm flex items-center gap-1">
                                <i class="fa-solid fa-magnifying-glass-minus text-[10px]"></i> Click to zoom out
                            </span>
                        </div>
                    </template>

                </div>
            </div>

            <!-- RIGHT: Actions & Meta Info -->
            <div class="lg:col-span-5 bg-white shadow-sm border border-gray-200 flex flex-col">

                <!-- Buttons -->
                <div class="p-6 border-b border-gray-100 flex gap-3">
                    <a href="<?php echo e(route('drive.file.stream', ['type' => 'asset', 'id' => $asset->id])); ?>"
                        class="flex-1 bg-[#0071c5] text-white font-bold py-3 px-6 flex items-center justify-center gap-2 hover:bg-[#005ea3] transition-all"
                        onclick="handleDownload(this, event)">
                        <i class="fa-solid fa-download"></i>
                        <span>Download</span>
                    </a>
                    <a href="<?php echo e(route('assets.edit-content', $asset->slug)); ?>"
                        class="flex-1 border-2 border-[#0071c5] text-[#0071c5] font-bold py-3 px-6 flex items-center justify-center gap-2 hover:bg-blue-50 transition-all">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Content
                    </a>
                    <button onclick="openGlobalShareModal(window.location.href, 'Check out this Asset')"
                        class="flex-1 border-2 border-[#0071c5] text-[#0071c5] font-bold py-3 px-6 flex items-center justify-center gap-2 hover:bg-blue-50 transition-all">
                        <i class="fa-solid fa-share-nodes"></i> Share
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 flex flex-col gap-4 flex-grow">

                    <!-- Title -->
                    <h2 class="text-[#0071c5] text-[22px] font-medium leading-snug">
                        <?php echo e($asset->title); ?>

                    </h2>

                    <!-- Description -->
                    <div>
                        <style>
                            .description-content img {
                                display: inline-block;
                                max-width: 100%;
                                height: auto;
                            }
                        </style>
                        <div class="description-content text-sm text-gray-600 leading-relaxed">
                            <?php echo $asset->description; ?>

                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-100"></div>

                    <!-- Meta Info -->
                    <div class="space-y-3 text-[14px]">
                        <p><span class="font-bold text-gray-700">ID#</span> <?php echo e($asset->asset_id_code ?? 'N/A'); ?></p>
                        <p><span class="font-bold text-gray-700">Upload date:</span>
                            <?php echo e($asset->uploaded_at?->format('d/m/Y') ?? $asset->created_at->format('d/m/Y')); ?>

                        </p>
                        <p><span class="font-bold text-gray-700">Topics:</span>
                            <span class="text-[#0071c5] cursor-pointer hover:underline"><?php echo e($asset->project->title ?? 'General'); ?></span>
                        </p>
                        <p><span class="font-bold text-gray-700">Asset Type:</span>
                            <?php echo e($asset->assetType->name ?? 'Online Asset'); ?>

                        </p>
                        <p><span class="font-bold text-gray-700">Available File Format:</span>
                            <span class="capitalize"><?php echo e(is_array($asset->available_formats) ? implode(', ', $asset->available_formats) : $asset->available_formats); ?></span>
                        </p>
                        <p><span class="font-bold text-gray-700">File Size:</span> <?php echo e($asset->file_size_formatted); ?></p>
                        <p><span class="font-bold text-gray-700">Asset Dimensions:</span>
                            <?php echo e(is_array($asset->dimensions) ? implode('x', $asset->dimensions) : $asset->dimensions); ?>

                        </p>
                        <p><span class="font-bold text-gray-700">Product:</span>
                            <?php echo e($asset->project->title ?? 'Bhaiya Asset Library'); ?>

                        </p>
                    </div>

                </div>
            </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function handleDownload(el, event) {
        event.preventDefault();

        const icon = el.querySelector('i');
        const text = el.querySelector('span');
        const href = el.href;

        icon.className = 'fa-solid fa-spinner fa-spin text-sm';
        text.textContent = 'Starting...';
        el.classList.add('pointer-events-none', 'opacity-60');

        setTimeout(() => {
            window.location.href = href;
        }, 300);

        setTimeout(() => {
            icon.className = 'fa-solid fa-circle-check text-sm text-green-500';
            text.textContent = 'Download Started!';
            el.classList.remove('pointer-events-none', 'opacity-60');
        }, 1500);

        setTimeout(() => {
            icon.className = 'fa-solid fa-download text-sm';
            text.textContent = 'Download';
        }, 4000);
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views/frontend/assetDetails.blade.php ENDPATH**/ ?>