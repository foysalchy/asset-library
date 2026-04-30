@extends('frontend.layouts.font')
@section('content')
    <section class="relative w-full bg-gradient-to-br from-[#003b7a] via-[#0090a8] to-[#00c7b1] pt-10 pb-28 px-6">
        <div class="container mx-auto mx-auto flex flex-col md:flex-row justify-between items-start md:items-center">
            <!-- Welcome Info Section -->
            <div class="flex items-start gap-8">
                <div class="flex flex-col gap-5 shrink-0">
                    <!-- Intel Badge -->
                    <div class="relative w-[105px] h-[105px] flex items-center justify-center">
                        <div class="absolute top-0 right-0 w-[90%] h-[7.5px] bg-[#3293e3]"></div>
                        <div class="absolute top-0 right-0 w-[7.5px] h-[90%] bg-[#3293e3]"></div>

                        <div class="absolute bottom-0 left-0 w-[90%] h-[7.5px] bg-[#48b5e6]"></div>
                        <div class="absolute bottom-0 left-0 w-[7.5px] h-[90%] bg-[#48b5e6]"></div>
                        <div class="absolute bottom-[-11px] left-[-11px] w-[12px] h-[12px] bg-[#3293e3] z-20"></div>
                        <div class="relative z-10 flex flex-col items-center justify-center">
                            <span class="text-3xl text-white font-bold italic leading-none tracking-tighter">intel</span>
                            <p class="text-sm text-blue-300 leading-none mt-1 opacity-95">
                                partner
                            </p>
                        </div>
                    </div>

                    <div class="bg-white px-3 py-2 shadow-md w-fit leading-tight ml-1">
                        <p class="text-[#00609d] text-xs font-medium">
                            Not your correct tier?
                        </p>
                        <a href="#" class="text-[#3293e3] text-xs font-bold underline hover:no-underline">Contact ISC
                            for support</a>
                    </div>
                </div>

                <div class="pt-4">
                    <h1 class="text-4xl text-white leading-none tracking-tight">
                        {{ $user->name ?? 'Welcome !'}}
                    </h1>
                    <p class="text-sm text-white/75 font-bold uppercase tracking-[3px] mt-2">
                        PARTNER | BUILDER
                    </p>
                </div>
            </div>

            <a href="#"
                class="flex items-center gap-4 bg-white/10 backdrop-blur-md border-2 border-white/30 px-4 py-2.5 hover:bg-white/20 transition-all text-white group">
                <!-- Partner Alliance Button -->
                <div
                    class="absolute -top-2.5 -left-2.5 w-[20px] h-[20px] bg-white rounded-full flex items-center justify-center shadow-md z-20 border border-gray-100">
                    <i class="fa-regular fa-circle-question text-xs text-[#0071c5]"></i>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-3xl font-medium italic leading-none tracking-tighter">intel</span>

                    <div class="flex flex-col text-xs leading-[1.2] tracking-tight opacity-95">
                        <span>partner</span>
                        <span>alliance</span>
                    </div>
                </div>

                <div class="ml-2">
                    <i class="fa-solid fa-arrow-right text-[14px] transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>
        </div>

        <!-- SEARCH BAR — overlapping bottom -->
        <div class="absolute bottom-0 left-0 right-0 px-6 mb-6">
            <div class="container mx-auto mx-auto bg-white border border-gray-200 flex items-center flex-wrap">
                <!-- Search Input -->
                <div class="flex-1 flex items-center px-5 border-r border-gray-200 min-w-[220px]">
                    <i class="fas fa-search text-[#3293e3] mr-3 text-lg"></i>
                    <input type="text" placeholder="Search devices, products and more..."
                        class="w-full py-4 outline-none text-sm text-gray-600 placeholder-gray-400 bg-transparent" />
                </div>

                <!-- Dropdowns -->
                <div class="flex items-center divide-x divide-gray-200">
                    <div class="px-5">
                        <select
                            class="outline-none text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-4 min-w-[90px]">
                            <option>Concern</option>
                            <option>Hardware</option>
                            <option>Software</option>
                        </select>
                    </div>
                    <div class="px-5">
                        <select
                            class="outline-none text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-4 min-w-[90px]">
                            <option>Project</option>
                            <option>Project A</option>
                            <option>Project B</option>
                        </select>
                    </div>
                    <div class="px-5">
                        <select
                            class="outline-none text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-4 min-w-[100px]">
                            <option>Asset Type</option>
                            <option>Video</option>
                            <option>Image</option>
                            <option>Document</option>
                        </select>
                    </div>
                    <div class="px-5">
                        <select
                            class="outline-none text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-4 min-w-[90px]">
                            <option>Language</option>
                            <option>English</option>
                            <option>French</option>
                            <option>Dutch</option>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="ml-auto flex items-center gap-4 px-5 py-3">
                    <button class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-8 py-2.5 text-sm font-bold tracking-wide">
                        Search
                    </button>
                    <button class="text-[#0071c5] text-sm font-semibold whitespace-nowrap">
                        Reset filters
                    </button>
                </div>
            </div>
        </div>
    </section>
    <!-- END HERO -->

    <!-- ── PAGE  Campaigns ── -->
    <section class="container mx-auto mx-auto px-6 py-10">
        <!-- Section Title -->
        <div class="flex items-center gap-3 mb-6">
            <a href="#" class="text-2xl md:text-4xl text-[#0071c5] underline">
                Campaigns to Sell the Latest Products
            </a>
            <a href="{{ route('home.filter') }}"
                class="bg-[#3293e3] text-white w-8 h-8 flex items-center justify-center shrink-0">
                <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
            @foreach ($featuredCampaigns as $campaign)
                <div
                    class="bg-white hover:shadow-lg transition-all duration-300 group cursor-pointer border border-gray-100">
                    <div
                        class="relative h-[220px] bg-gradient-to-br from-[#003b7a] to-[#0090a8] flex items-center justify-center overflow-visible">
                        <div class="px-4 py-0 md:px-8">
                            <img src="{{ $campaign->thumbnail_url ?? asset('./images/cards/card-01.jpg') }}"
                                alt="{{ $campaign->title }}" class="max-h-55 max-w-70 shadow-xl object-contain" />
                        </div>

                        <button class="absolute top-4 right-4 text-[#00aeef] hover:scale-110 transition-transform">
                            <i class="fa-regular fa-bookmark text-2xl"></i>
                        </button>

                        @if ($campaign->is_featured)
                            <div
                                class="absolute -bottom-4 left-4 bg-[#fdbb30] text-white px-5 py-2 rounded-2xl text-xs font-bold shadow-md z-20">
                                Featured
                            </div>
                        @endif
                    </div>

                    <div class="p-6 pt-10">
                        <h3 class="text-[#005da4] text-lg font-medium leading-snug min-h-[56px]">
                            <a href="{{ route('campaign.details', $campaign->slug) }}">{{ $campaign->title }}</a>
                        </h3>

                        <p class="text-[#757575] text-sm mb-3">
                            Topics: <span class="font-normal">{{ $campaign->project->name ?? 'General' }}</span>
                        </p>

                        <div class="mb-4 flex gap-2">
                            @foreach ($campaign->languages ?? [] as $lang)
                                <span
                                    class="border border-[#005da4] text-[#005da4] px-4 py-1 rounded-full text-xs font-medium uppercase">
                                    {{ $lang }}
                                </span>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-2.5 text-[#005da4] text-xs font-bold tracking-[1.2px] uppercase">
                            <i class="fa-solid fa-magnifying-glass text-[14px]"></i>
                            <a href="{{ route('campaign.details', $campaign->slug) }}">More Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ── Latest Marketing Assets Section ── -->
    <section class="container mx-auto px-6 py-12">
        <div class="flex items-center gap-3 mb-8">
            <h2 class="text-3xl text-[#0071c5] font-light underline cursor-pointer">
                Latest Marketing Assets
            </h2>
            <a href="{{ route('home.filter') }}" class="bg-[#00aeef] text-white w-7 h-7 flex items-center justify-center">
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="relative group container mx-auto px-6">
            <div class="swiper mySwiper overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ($latestAssets as $asset)
                        <div class="swiper-slide h-auto pb-5">
                            <div
                                class="bg-white border border-gray-100 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col h-full group cursor-pointer">
                                <!-- Banner Area -->
                                <div
                                    class="relative h-[200px] bg-[#001e3e] flex items-center justify-center overflow-visible p-4">
                                    <div class="h-full">
                                        @if ($asset->media->first()?->media_type === 'image')
                                            <img src="{{ $asset->media->first()->url }}" alt="{{ $asset->title }}"
                                                class="h-full w-auto shadow-2xl object-contain">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center shrink-0">
                                                <svg class="text-purple-500" width="18" height="18"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <button
                                        class="absolute top-2 right-4 text-[#00aeef] hover:scale-110 transition-transform">
                                        <i class="fa-regular fa-bookmark text-2xl"></i>
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
                                        <a href="{{ route('asset.details', $asset->slug) }}">{{ $asset->title }}</a>
                                    </h3>

                                    <p class="text-[#757575] text-sm ">
                                        Topics:
                                        <span
                                            class="font-normal text-gray-500">{{ $asset->project->name ?? 'General' }}</span>
                                    </p>

                                    <div class="mb-8 flex flex-wrap gap-2">
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
                                        <a href="{{ $asset->file_path }}" download
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
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Swiper Next Button -->
            <div class="absolute -right-5 top-1/2 -translate-y-1/2 z-30">
                <div
                    class="swiper-button-next-custom w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                    <i class="fa-solid fa-chevron-right text-lg"></i>
                </div>
            </div>
        </div>
    </section>
    <!-- ── recommended ── -->
    <section class="container mx-auto px-6 py-12">
        <div class="flex items-center gap-3 mb-8">
            <h2 class="text-3xl text-gray-800 font-light cursor-pointer">
                Recommended Assets for You
            </h2>
        </div>
        <div class="relative group container mx-auto px-6">
            <div class="swiper recommendSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ($recommendedAssets as $asset)
                        <div class="swiper-slide h-auto pb-5">
                            <div
                                class="bg-white border border-gray-100 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col h-full group cursor-pointer">

                                <!-- Image Section -->
                                <div
                                    class="relative h-[200px] bg-[#001e3e] flex items-center justify-center overflow-visible p-4">
                                    <div class="h-full">
                                        <img src="{{ $asset->media->first()->url ?? asset('assets/images/placeholder.jpg') }}"
                                            alt="{{ $asset->title }}" class="h-full w-auto shadow-2xl object-contain" />
                                    </div>
                                    <button
                                        class="absolute top-2 right-4 text-[#00aeef] hover:scale-110 transition-transform">
                                        <i class="fa-regular fa-bookmark text-2xl"></i>
                                    </button>
                                </div>

                                <!-- Content Section -->
                                <div class="p-6 pt-9 flex flex-col flex-grow">
                                    <h3
                                        class="text-[#005da4] text-lg font-semibold leading-snug mb-6 min-h-[48px]">
                                        <a href="{{ route('asset.details', $asset->slug) }}">{{ $asset->title }}</a>
                                    </h3>

                                    <div class="mt-auto flex items-center justify-between">
                                        <!-- Download Link -->
                                        <a href="{{ $asset->file_url }}" download
                                            class="flex items-center gap-2 text-[#005da4] text-xs font-bold uppercase hover:opacity-75">
                                            <i class="fa-solid fa-download text-sm"></i>
                                            <span>Download</span>
                                        </a>

                                        <!-- Details Link -->
                                        <a href="{{ route('asset.details', $asset->slug) }}"
                                            class="flex items-center gap-2 text-[#005da4] text-xs font-bold uppercase hover:opacity-75">
                                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                                            <span>More Details</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Next Button -->
            <div class="absolute -right-5 top-1/2 -translate-y-1/2 z-30">
                <div
                    class="swiper-button-next-recommend w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                    <i class="fa-solid fa-chevron-right text-lg"></i>
                </div>
            </div>
        </div>
    </section>
@endsection
