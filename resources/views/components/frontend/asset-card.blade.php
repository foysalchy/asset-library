@props(['asset', 'swiper' => false, 'selectable' => false])

@if($swiper)
<div class="swiper-slide h-auto pb-5">
@endif

<div class="bg-white border border-gray-100 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col h-full group cursor-pointer">
    <!-- Banner Area -->
    <div class="relative h-[200px] bg-[#001e3e] flex items-center justify-center overflow-visible p-4">
        @if($selectable)
            <input type="checkbox"
                class="item-checkbox absolute top-3 left-4 z-30 w-5 h-5 cursor-pointer accent-[#0071c5]"
                data-type="asset"
                data-id="{{ $asset->id }}">
        @endif
        <div class="h-full">
            @if ($asset->media->first()?->media_type === 'image')
                <img src="{{ $asset->media->first()->url }}" alt="{{ $asset->title }}"
                    class="h-full w-auto shadow-2xl object-contain">
            @else
                <div class="h-full">
                    <img src="{{ asset('./images/cards/card-01.png') }}" alt="default"
                    class="h-full w-auto shadow-xl object-contain">
                </div>
            @endif
        </div>

        @php $bookmarked = $asset->isBookmarkedBy(auth()->id()); @endphp
        <button onclick="toggleBookmark(this, 'asset', {{ $asset->id }})"
            class="absolute top-2 right-4 hover:scale-110 transition-transform {{ $bookmarked ? 'text-[#0071c5]' : 'text-[#00aeef]' }}" aria-label="Save to bookmarks">
            <i class="{{ $bookmarked ? 'fa-solid' : 'fa-regular' }} fa-bookmark text-2xl"></i>
        </button>

        @if ($asset->sort_order > 0)
            <div class="absolute -bottom-3.5 left-4 bg-[#fdbb30] text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-md z-20">
                Featured
            </div>
        @endif
    </div>

    <div class="p-6 pt-9 flex flex-col flex-grow">
        <h3 class="text-[#005da4] text-lg font-semibold leading-snug min-h-[48px]">
            <a href="{{ route('asset.details', $asset->slug) }}">{{ $asset->title }}</a>
        </h3>

        <p class="text-[#757575] text-sm">
            Topics:
            <span class="font-normal text-gray-500">{{ $asset->project->name ?? 'General' }}</span>
        </p>

        <div class="mb-8 flex flex-wrap gap-2 mt-2">
            @if ($asset->available_formats)
                @foreach (json_decode($asset->available_formats) as $format)
                    <span class="border border-[#005da4] text-[#005da4] px-3 py-0.5 rounded-full text-xs font-medium uppercase">
                        {{ $format }}
                    </span>
                @endforeach
            @endif
        </div>

        <div class="mt-auto flex items-center justify-between">
            <a href="{{ route('drive.file.stream', ['type' => 'asset', 'id' => $asset->id]) }}" download
                class="flex items-center gap-2 text-[#005da4] text-xs font-bold uppercase hover:opacity-75">
                <i class="fa-solid fa-download text-sm"></i>
                <span>Download</span>
            </a>
            <a href="{{ route('asset.details', $asset->slug) }}"
                class="flex items-center gap-2 text-[#005da4] text-xs font-bold uppercase hover:opacity-75">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <span>More Details</span>
            </a>
        </div>
    </div>
</div>

@if($swiper)
</div>
@endif
