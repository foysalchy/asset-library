@props(['asset', 'swiper' => false, 'selectable' => false])

@if($swiper)
    <div class="swiper-slide h-auto pb-5">
@endif

    <div class="bg-white border border-gray-100 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col h-full group cursor-pointer"
        onclick="window.location='{{ route('asset.details', $asset->slug) }}'">
        <!-- Banner Area -->
        <div class="relative min-h-[200px] bg-gradient-to-br from-[#001e3e] to-[#003366] overflow-hidden">
            @php
                $staticCount = $asset->media->where('media_type', 'image')->count();
                $videoCount = $asset->media->where('media_type', 'video')->count();
            @endphp

            <div class="absolute top-2 {{ $selectable ? 'left-12' : 'left-3' }} z-20">
                @if($videoCount > 1)
                    <div
                        class="bg-red-600/90 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                        <span>{{ $videoCount }} Videos</span>
                    </div>
                @elseif($videoCount == 0 && $staticCount > 1)
                    <div
                        class="bg-red-600/90 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                        <span>{{ $staticCount }} Static More</span>
                    </div>
                @endif
            </div>
            @if($selectable)
                <input type="checkbox"
                    class="item-checkbox absolute top-3 left-4 z-30 w-5 h-5 cursor-pointer accent-[#0071c5]"
                    data-type="asset" data-id="{{ $asset->id }}" onclick="event.stopPropagation()">
            @endif

            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle, #00aeef 1px, transparent 1px); background-size: 20px 20px;">
            </div>

           @if ($asset->media->first()?->media_type === 'image')
    <img src="{{ $asset->media->first()->url }}" alt="{{ $asset->title }}"
        class="inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
@elseif ($asset->media->first()?->media_type === 'video')
    <div class="relative inset-0 w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center overflow-hidden transition-transform duration-500 group-hover:scale-105">
        <svg width="100%" height="100%" viewBox="0 0 400 300" class="absolute inset-0 opacity-20" preserveAspectRatio="xMidYMid slice">
            <defs>
                <pattern id="grid-{{ $asset->id }}" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-{{ $asset->id }})"/>
        </svg>

        <div class="relative z-10 flex flex-col items-center justify-center gap-2">
            <div class="w-14 h-14 rounded-full flex items-center justify-center"
                style="background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.4);">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="white" class="ml-1">
                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                </svg>
            </div>
            <span class="text-white/70 text-[10px] tracking-wider uppercase font-medium">Video</span>
        </div>
    </div>
@else
    <img src="{{ asset('./images/cards/card-01.png') }}" alt="default"
        class="inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
@endif

            @php $bookmarked = $asset->isBookmarkedBy(auth()->id()); @endphp
            <button onclick="event.stopPropagation(); toggleBookmark(this, 'asset', {{ $asset->id }})"
                class="absolute top-2 right-3 z-10 w-8 h-8 flex items-center justify-center bg-white/90 hover:bg-white rounded-full shadow-md hover:scale-110 transition-all duration-200 {{ $bookmarked ? 'text-[#0071c5]' : 'text-gray-400' }}"
                aria-label="Save to bookmarks">
                <i class="{{ $bookmarked ? 'fa-solid' : 'fa-regular' }} fa-bookmark text-sm"></i>
            </button>

            @if ($asset->sort_order > 0)
                <div
                    class="absolute -bottom-3.5 left-4 bg-[#fdbb30] text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-md z-20">
                    Featured
                </div>
            @endif
        </div>

        <div class="p-6 pt-9 flex flex-col flex-grow">
            <h3 class="text-[#005da4] text-lg font-semibold leading-snug min-h-[48px]">
                {{ $asset->title }}
            </h3>

            <p class="text-[#757575] text-sm">
                Concern:
                <span class="font-normal text-gray-500">{{ $asset->project->concern_name ?? 'General' }}</span>
            </p>

            <div class="mb-8 flex flex-wrap gap-2 mt-2">
                @if ($asset->available_formats)
                    @foreach (json_decode($asset->available_formats) as $format)
                        <span
                            class="border border-[#005da4] text-[#005da4] px-3 py-0.5 rounded-full text-xs font-medium uppercase">
                            {{ $format }}
                        </span>
                    @endforeach
                @endif
            </div>
            <div class="mt-auto flex items-center justify-between">
                <a href="{{ route('drive.file.stream', ['type' => 'asset', 'id' => $asset->id]) }}"
                    class="download-btn flex items-center gap-2 text-[#005da4] text-xs font-bold uppercase hover:opacity-75"
                    onclick="event.stopPropagation(); handleDownload(this, event)">
                    <i class="fa-solid fa-download text-sm"></i>
                    <span>Download</span>
                </a>
                <span class="flex items-center gap-2 text-[#005da4] text-xs font-bold uppercase hover:opacity-75">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    <span>More Details</span>
                </span>
            </div>
        </div>
    </div>

    @if($swiper)
        </div>
        <script>
            function handleDownload(el, event) {
                event.preventDefault();

                const icon = el.querySelector('i');
                const text = el.querySelector('span');
                const href = el.href;

                // Loader state
                icon.className = 'fa-solid fa-spinner fa-spin text-sm';
                text.textContent = 'Starting...';
                el.classList.add('pointer-events-none', 'opacity-60');

                // Trigger download
                setTimeout(() => {
                    window.location.href = href;
                }, 300);

                // Download started state
                setTimeout(() => {
                    icon.className = 'fa-solid fa-circle-check text-sm text-green-500';
                    text.textContent = 'Download Started!';
                    el.classList.remove('pointer-events-none', 'opacity-60');
                }, 1500);

                // Reset
                setTimeout(() => {
                    icon.className = 'fa-solid fa-download text-sm';
                    text.textContent = 'Download';
                }, 4000);
            }
        </script>
    @endif
