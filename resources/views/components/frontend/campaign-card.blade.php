@props(['campaign', 'selectable' => false])
<div class="bg-white border border-gray-100 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col h-full group cursor-pointer">
    <!-- Banner Area -->
    <div class="relative h-[200px] bg-[#001e3e] flex items-center justify-center overflow-visible p-4">
        @if($selectable)
            <input type="checkbox" aria-label="checkbox"
                class="item-checkbox absolute top-3 left-4 z-30 w-5 h-5 cursor-pointer accent-[#0071c5]"
                data-type="campaign"
                data-id="{{ $campaign->id }}">
        @endif
        <div class="h-full flex items-center justify-center">
            @if ($campaign->thumbnail)
                <img src="{{ asset('storage/' . $campaign->thumbnail) }}" alt="{{ $campaign->title }}"
                    class="h-full w-auto shadow-2xl object-contain">
            @else
                <img src="{{ asset('./images/cards/card-01.png') }}" alt="default"
                    class="h-full w-auto shadow-xl object-contain">
            @endif
        </div>

        @php $bookmarked = $campaign->isBookmarkedBy(auth()->id()); @endphp
        <button onclick="toggleBookmark(this, 'campaign', {{ $campaign->id }})"
            class="absolute top-2 right-4 hover:scale-110 transition-transform {{ $bookmarked ? 'text-[#0071c5]' : 'text-[#00aeef]' }}" aria-label="Save to bookmarks">
            <i class="{{ $bookmarked ? 'fa-solid' : 'fa-regular' }} fa-bookmark text-2xl"></i>
        </button>

        @if ($campaign->is_featured)
            <div class="absolute -bottom-3.5 left-4 bg-[#fdbb30] text-[#001e3e] px-4 py-1.5 rounded-full text-xs font-bold shadow-md z-20">
                Featured
            </div>
        @endif
    </div>

    <div class="p-6 pt-9 flex flex-col flex-grow">
        <h3 class="text-[#005da4] text-lg font-semibold leading-snug min-h-[48px]">
            <a href="{{ route('campaign.details', $campaign->slug) }}">{{ $campaign->title }}</a>
        </h3>

        <p class="text-[#757575] text-sm">
            Topics:
            <span class="font-normal text-gray-500">{{ $campaign->project->name ?? 'General' }}</span>
        </p>

        <div class="mb-8 flex flex-wrap gap-2 mt-2">
            @if ($campaign->languages)
                @foreach (array_slice($campaign->languages, 0, 3) as $lang)
                    <span class="border border-[#005da4] text-[#005da4] px-3 py-0.5 rounded-full text-xs font-medium uppercase">
                        {{ $lang }}
                    </span>
                @endforeach
                @if (count($campaign->languages) > 3)
                    <span class="border border-[#005da4] text-[#005da4] px-3 py-0.5 rounded-full text-xs font-medium">
                        +{{ count($campaign->languages) - 3 }}
                    </span>
                @endif
            @endif
        </div>

        <div class="mt-auto flex items-center justify-end">
            <a href="{{ route('campaign.details', $campaign->slug) }}"
                class="flex items-center gap-2 text-[#005da4] text-xs font-bold uppercase hover:opacity-75">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                <span>More Details</span>
            </a>
        </div>
    </div>
</div>
