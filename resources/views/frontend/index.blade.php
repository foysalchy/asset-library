@extends('frontend.layouts.font')
@section('content')
<section
    class="relative w-full bg-gradient-to-br from-[#003b7a] via-[#0090a8] to-[#00c7b1] pt-8 pb-52 md:pb-40 lg:pt-10 lg:pb-32 px-4 lg:px-6">
    <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center lg:items-start gap-4">

        <!-- Welcome Info Section -->
        <div class="flex flex-col md:flex-row items-center lg:items-start gap-4 lg:gap-8 text-center lg:text-left w-full lg:w-auto">
            <div class="flex flex-col gap-5 shrink-0">
                <div class="relative w-[85px] h-[85px] lg:w-[115px] lg:h-[115px] flex items-center justify-center shrink-0">
                    <div class="absolute top-0 right-0 w-[90%] h-[7.5px] bg-[#3293e3]"></div>
                    <div class="absolute top-0 right-0 w-[7.5px] h-[90%] bg-[#3293e3]"></div>
                    <div class="absolute bottom-0 left-0 w-[90%] h-[7.5px] bg-[#48b5e6]"></div>
                    <div class="absolute bottom-0 left-0 w-[7.5px] h-[90%] bg-[#48b5e6]"></div>
                    <div class="absolute bottom-[-11px] left-[-11px] w-[12px] h-[12px] bg-[#3293e3] z-20"></div>
                    <div class="relative z-10 flex flex-col items-center justify-center">
                        <span class="text-3xl text-white font-bold italic leading-none tracking-tighter">Bhaiya</span>
                        <p class="text-sm text-blue-300 leading-none mt-1 opacity-95">Asset Library</p>
                    </div>
                </div>
            </div>

            @php $siteSetting = \App\Models\SiteSetting::first(); @endphp
            <div class="pt-0 lg:pt-4">
                <h1 class="text-2xl lg:text-4xl text-white font-bold leading-tight">
                    Welcome {{ $user->name ?? '' }} !
                </h1>
                <p class="text-xs lg:text-sm text-white/75 font-bold uppercase tracking-[2px] mt-1">
                    {{ $siteSetting->slogan }}
                </p>
            </div>
        </div>

        <!-- Testing Notice — right side on desktop, below on mobile -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-2 px-3 py-2.5 rounded-lg w-full lg:w-auto shrink-0">
            <span class="flex items-start md:items-center gap-2 text-xs sm:text-sm text-white text-center md:text-left">
                <i class="fa-solid fa-triangle-exclamation text-yellow-500"></i>
                <span>This platform is currently under <strong>Testing</strong>. Please report any issues:</span>
            </span>
            <a href="{{ route('tickets.create') }}"
                class="bg-white text-[#003b7a] px-3 py-1 rounded shadow-md text-xs sm:text-sm font-bold hover:bg-gray-100 transition-all inline-flex items-center gap-1 whitespace-nowrap">
                <i class="fa-solid fa-plus-circle"></i> Create Ticket
            </a>
        </div>

    </div>

    <!-- SEARCH BAR — overlapping bottom -->
<div class="absolute bottom-0 left-0 right-0 px-4 lg:px-6 mb-4 lg:mb-6">
    <form action="{{ route('home.filter') }}" method="GET"
        class="container mx-auto bg-white border border-gray-200 flex flex-col lg:flex-row items-stretch lg:items-center shadow-lg">

        <div class="flex-1 flex items-center px-4 border-b lg:border-b-0 lg:border-r border-gray-200">
            <i class="fas fa-search text-[#3293e3] mr-3"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                class="w-full py-3 lg:py-4 outline-none text-sm text-gray-600 bg-transparent" />
        </div>

        <div class="grid grid-cols-2 lg:flex items-center divide-x divide-gray-100 lg:divide-gray-200 border-b lg:border-b-0">
            <div class="px-2 lg:px-5 min-w-0">
                <label for="concern-select" class="sr-only">Filter by Concern</label>
                <select name="concern" id="concern-select" aria-label="Filter by Concern"
                    class="w-full outline-none text-[12px] lg:text-sm font-medium text-gray-600 bg-transparent py-3 lg:py-4">
                    <option value="">Concern</option>
                    @foreach ($concerns as $key => $name)
                    <option value="{{ $key }}" {{ request('concern') == $key ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="px-2 lg:px-5 min-w-0">
                <label for="project-select" class="sr-only">Filter by Project</label>
                <select name="project" id="project-select" aria-label="Filter by project"
                    class="w-full outline-none text-[12px] lg:text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-3 lg:py-4 lg:min-w-[90px]">
                    <option value="">Project</option>
                    @foreach ($projects as $project)
                    <option value="{{ $project->id }}" data-key="{{$project->concern}}"
                        {{ request('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="px-2 lg:px-5 min-w-0">
                <label for="type-select" class="sr-only">Filter by Asset Type</label>
                <select name="type" id="type-select" aria-label="Filter by type"
                    class="w-full outline-none text-[12px] lg:text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-3 lg:py-4 lg:min-w-[100px]">
                    <option value="">Asset Type</option>
                    @foreach ($assetTypes as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center gap-2 lg:gap-4 px-4 py-2 lg:py-3 bg-gray-50 lg:bg-transparent">
            <button type="submit"
                class="flex-1 bg-[#0071c5] text-white px-4 lg:px-8 py-2 lg:py-2.5 text-xs lg:text-sm font-bold">
                Search
            </button>
            <a href="{{ route('home.index') }}"
                class="text-[#0071c5] text-[10px] lg:text-sm font-semibold whitespace-nowrap">
                Reset
            </a>
        </div>
    </form>
</div>
</section>       

<!-- ── Latest Marketing Assets Section ── -->
<section class="container mx-auto px-6 py-12">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('home.filter', ['section' => 'assets', 'sort' => 'latest']) }}">
            <h2 class="text-xl lg:text-3xl text-[#0071c5] underline">
                Latest Marketing Assets
            </h2>
        </a>

        <a href="{{ route('home.filter', ['section' => 'assets', 'sort' => 'latest']) }}" aria-label="View all assets"
            class="bg-[#00aeef] text-white w-7 h-7 flex items-center justify-center shrink-0">
            <i class="fas fa-arrow-right text-xs"></i>
        </a>

    <a href="{{ route('home.filter', ['section' => 'assets', 'sort' => 'latest']) }}"
    class="ml-auto inline-flex items-center gap-2 bg-[#0071c5] text-white text-xs lg:text-sm font-bold px-4 py-2 hover:bg-[#005ea3] transition-all">
    View All
    <i class="fas fa-arrow-right text-xs"></i>
</a>
    </div>
    <div class="relative group container mx-auto px-6">
        <div class="absolute -left-5 top-1/2 -translate-y-1/2 z-30   lg:flex">
            <div
                class="swiper-button-prev-custom w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                <i class="fa-solid fa-chevron-left text-lg"></i>
            </div>
        </div>
        <div class="swiper mySwiper overflow-hidden">
            <div class="swiper-wrapper">
                @foreach ($latestAssets as $asset)
                <x-frontend.asset-card :asset="$asset" :swiper="true" />
                @endforeach
            </div>
        </div>

        <!-- Swiper Next Button -->
        <div class="absolute -right-5 top-1/2 -translate-y-1/2 z-30   lg:flex">
            <div
                class="swiper-button-next-custom w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                <i class="fa-solid fa-chevron-right text-lg"></i>
            </div>
        </div>
    </div>
</section>
<!-- ── Latest Video Assets Section ── -->
@if(isset($latestVideos) && $latestVideos->count() > 0)
<section class="container mx-auto px-6 py-12 border-t border-gray-100">
    <!-- ── Latest Video Assets Section ── -->
<div class="flex items-center gap-3 mb-8">
    <a href="{{ route('home.filter', ['video_only' => 1]) }}">
        <h2 class="text-xl lg:text-3xl text-[#0071c5] underline">
            Latest Video Assets
        </h2>
    </a>

    <a href="{{ route('home.filter', ['video_only' => 1]) }}" aria-label="View all videos"
        class="bg-[#00aeef] text-white w-7 h-7 flex items-center justify-center shrink-0">
        <i class="fas fa-arrow-right text-xs"></i>
    </a>

    <a href="{{ route('home.filter', ['video_only' => 1]) }}"
        class="ml-auto inline-flex items-center gap-2 bg-[#0071c5] text-white text-xs lg:text-sm font-bold px-4 py-2 hover:bg-[#005ea3] transition-all">
        View All Videos
        <i class="fas fa-arrow-right text-xs"></i>
    </a>
</div>

    <div class="relative group container mx-auto px-6">
        <!-- Swiper Prev Button (Unique Class) -->
        <div class="absolute -left-5 top-1/2 -translate-y-1/2 z-30 lg:flex">
            <div class="video-prev w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                <i class="fa-solid fa-chevron-left text-lg"></i>
            </div>
        </div>

        <div class="swiper videoSwiper overflow-hidden">
            <div class="swiper-wrapper">
                @foreach ($latestVideos as $asset)
                    <x-frontend.asset-card :asset="$asset" :swiper="true" />
                @endforeach
            </div>
        </div>

        <!-- Swiper Next Button (Unique Class) -->
        <div class="absolute -right-5 top-1/2 -translate-y-1/2 z-30 lg:flex">
            <div class="video-next w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                <i class="fa-solid fa-chevron-right text-lg"></i>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const concernSelect = document.getElementById("concern-select");
    const projectSelect = document.getElementById("project-select");

    concernSelect.addEventListener("change", function () {
        const selectedConcern = this.value;

        projectSelect.querySelectorAll("option").forEach(function (option) {
            const projectConcern = option.dataset.key;

            if (selectedConcern === "" || projectConcern === selectedConcern) {
                option.hidden = false;
            } else {
                option.hidden = true;
            }
        });

        // Reset selected project if it becomes hidden
        if (projectSelect.selectedOptions.length > 0 && projectSelect.selectedOptions[0].hidden) {
            projectSelect.value = "";
        }
    });
});
</script>
@endpush
