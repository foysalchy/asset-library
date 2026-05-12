@extends('frontend.layouts.font')
@section('content')
<div class="bg-[#f3f3f3] pb-20 font-['Outfit']">
    <section class="container mx-auto">
        <!-- 1. Sub-header Navigation -->
        <div class="flex items-center justify-between py-6 px-6 text-[#0071c5]">
            <div class="flex items-center gap-6">
                <a href="{{ url()->previous() }}" class="hover:opacity-70"><i class="fas fa-arrow-left text-xl"></i></a>
                <p class="text-sm">
                    <span class="text-[#757575] font-semibold">Preview this content in a different language:</span>
                    <span class="font-bold cursor-pointer ml-1">English <i
                            class="fas fa-chevron-down text-[10px] ml-1"></i></span>
                </p>
            </div>

        </div>

        <!-- 2. Main Content Grid -->
        <div class="px-6 grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

            <!-- LEFT: Asset Image Preview Section (Gallery) -->
            <div class="lg:col-span-7 bg-white p-6 shadow-sm flex gap-8 items-start min-h-[600px]">

                <!-- Thumbnail Rail (বাম পাশের ছোট ইমেজগুলো) -->
                <div class="w-20 shrink-0 flex flex-col gap-4">
                    @foreach ($asset->media as $index => $media)
                    <div
                        class="thumb-item border-2 {{ $index == 0 ? 'border-[#0071c5]' : 'border-transparent' }} p-1 cursor-pointer transition-all hover:border-gray-300">
                        <img src="{{ $media->url }}" class="w-full h-auto block object-cover aspect-square"
                            alt="thumbnail">
                    </div>
                    @endforeach
                </div>

                <div class="flex-1 flex items-center justify-center bg-gray-50/50 overflow-hidden rounded-sm">
                    <img id="main-preview"
                        src="{{ $asset->media->first()->url ?? asset('assets/images/placeholder.jpg') }}"
                        alt="Main Asset"
                        class="w-full h-auto max-h-[750px] object-contain transition-opacity duration-300 transform scale-100 hover:scale-105 cursor-zoom-in">
                </div>

            </div>
            <!-- RIGHT: Actions & Meta Info Section -->
            <div class="lg:col-span-5 bg-white shadow-sm border border-gray-200 flex flex-col">
                <!-- Buttons Top Area -->
                <div class="p-6 border-b border-gray-100 flex gap-3">
                    <a href="{{ route('drive.file.stream', ['type' => 'asset', 'id' => $asset->id]) }}"
                        class="flex-1 bg-[#0071c5] text-white font-bold py-3 px-6 flex items-center justify-center gap-2 hover:bg-[#005ea3] transition-all"
                        onclick="handleDownload(this, event)">
                        <i class="fa-solid fa-download"></i>
                        <span>Download</span>
                    </a>
                    <button onclick="openGlobalShareModal(window.location.href, 'Check out this Asset')"
                        class="flex-1 border-2 border-[#0071c5] text-[#0071c5] font-bold py-3 px-6 flex items-center justify-center gap-2 hover:bg-blue-50 transition-all">
                        <i class="fa-solid fa-share-nodes"></i> Share
                    </button>
                </div>

                <!-- Meta Info Area -->
                <div class="p-8 flex-grow">
                    <h2 class="text-[#0071c5] text-[22px] font-medium leading-snug mb-4">
                        {{ $asset->title }}
                    </h2>
                    <p class="text-gray-600 text-[14px] mb-8">
                        {!! $asset->description !!}
                    </p>

                    <div class="space-y-3 text-[14px]">
                        <p><span class="font-bold text-gray-700">ID#</span> {{ $asset->asset_id_code ?? 'N/A' }}</p>
                        <p><span class="font-bold text-gray-700">Upload date:</span>
                            {{ $asset->uploaded_at?->format('d/m/Y') ?? $asset->created_at->format('d/m/Y') }}
                        </p>
                        <p><span class="font-bold text-gray-700">Topics:</span>
                            <span
                                class="text-[#0071c5] cursor-pointer hover:underline">{{ $asset->project->title ?? 'General' }}</span>
                        </p>
                        <p><span class="font-bold text-gray-700">Asset Type:</span>
                            {{ $asset->assetType->name ?? 'Online Asset' }}
                        </p>
                        <p><span class="font-bold text-gray-700">Available File Format:</span>
                            <span
                                class="capitalize">{{ is_array($asset->available_formats) ? implode(', ', $asset->available_formats) : $asset->available_formats }}</span>
                        </p>
                        <p><span class="font-bold text-gray-700">File Size:</span> {{ $asset->file_size_formatted }}
                        </p>
                        <p><span class="font-bold text-gray-700">Asset Dimensions:</span>
                            {{ is_array($asset->dimensions) ? implode('x', $asset->dimensions) : $asset->dimensions }}
                        </p>
                        <p class="leading-relaxed"><span class="font-bold text-gray-700">Product:</span>
                            {{ $asset->project->title ?? 'Bhaiya Asset Library' }}
                        </p>
                    </div>

                    <div class="mt-10 flex justify-end">
                        <a href="#"
                            class="text-[#757575] font-bold text-sm flex items-center gap-2 hover:text-[#0071c5]">
                            See less <i class="fas fa-arrow-right text-[#0071c5]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- RELATED CAMPAIGN SECTION -->
    <!-- @if ($asset->project)
    <section class="container mx-auto mt-16 px-6 font-['Outfit']">
        <h2 class="text-[#757575] text-[28px] font-light mb-8">
            Find this asset in the following campaign(s):
        </h2>

        <div
            class="bg-white flex flex-col md:flex-row overflow-hidden border border-gray-100 group cursor-pointer transition-all hover:shadow-lg shadow-sm">

            <div class="flex-1 p-10">
                <h3 class="text-[#0071c5] text-[26px] font-medium leading-tight mb-4">
                    {{ $asset->project->name }}
                </h3>

                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    {{ $asset->project->description ?? 'Explore the latest updates and assets for ' . $asset->project->name . '. Part of our ' . ($asset->project->concern ?? 'General') . ' initiative.' }}
                </p>

                <p class="text-gray-500 text-[13px] mb-4 font-bold">
                    Concern:
                    {{ \App\Models\Project::CONCERNS[$asset->project->concern] ?? $asset->project->concern }}
                </p>

                <div class="flex gap-2">
                    @if (isset($asset->project->languages) && is_array($asset->project->languages))
                    @foreach ($asset->project->languages as $lang)
                    <span
                        class="border border-[#0071c5] text-[#0071c5] text-[11px] px-3 py-1 rounded-full uppercase">{{ $lang }}</span>
                    @endforeach
                    @else
                    <span
                        class="border border-[#0071c5] text-[#0071c5] text-[11px] px-3 py-1 rounded-full uppercase">English</span>
                    @endif
                </div>
            </div>

            <div class="md:w-2/5 bg-[#001e3e] relative flex items-center justify-center overflow-hidden p-8">
                <img src="{{ $asset->project->logo_url ?? asset('./images/brand/brand-02.svg') }}"
                    alt="{{ $asset->project->name }}"
                    class="w-full h-auto max-h-[200px] object-contain shadow-2xl transition-transform duration-500 group-hover:scale-105" />

                <i class="fa-regular fa-bookmark absolute top-4 right-4 text-[#00aeef] text-2xl"></i>
            </div>
        </div>
    </section>
    @endif -->
</div>
@endsection

@push('scripts')
<script>
    const thumbnails = document.querySelectorAll('.thumb-item');
    const mainImg = document.getElementById('main-preview');

    thumbnails.forEach(item => {
        item.addEventListener('click', function() {
            const newSrc = this.querySelector('img').src;
            mainImg.style.opacity = '0';
            setTimeout(() => {
                mainImg.src = newSrc;
                mainImg.style.opacity = '1';
            }, 200);

            thumbnails.forEach(thumb => {
                thumb.classList.remove('border-[#0071c5]');
                thumb.classList.add('border-transparent');
            });
            this.classList.remove('border-transparent');
            this.classList.add('border-[#0071c5]');
        });
    });
</script>
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
@endpush