@extends('frontend.layouts.font')
@section('content')
    <section class="container mx-auto px-8 py-10 font-['Outfit']">
        <!-- Page Title -->
        <h1 class="text-[#0071c5] text-4xl font-light mb-8">My Saved Items</h1>

        <!-- Top Action Bar (Same as Filter Page) -->
        <div class="flex items-center justify-between">
            <!-- View Toggle -->
            <div class="flex items-center gap-1">
                <button type="button" id="listViewBtn" onclick="setView('list')" class="p-1.5 text-gray-400 hover:text-[#0071c5]"><i class="fas fa-list text-base"></i></button>
                <button type="button" id="gridViewBtn" onclick="setView('grid')" class="p-1.5 text-[#0071c5]"><i class="fas fa-th-large text-base"></i></button>
            </div>
        </div>

        <!-- ── Assets Section ── -->
        <div id="assets" class="mb-12">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-[22px] font-light text-gray-800">Saved Assets ({{ $assets->count() }})</h2>
                <button type="button" class="w-6 h-6 rounded-full border-2 border-[#0071c5] text-[#0071c5] flex items-center justify-center text-xs" onclick="toggleSection('assetsGrid', this)">
                    <i class="fas fa-minus text-[10px]"></i>
                </button>
            </div>

            <div id="assetsGrid">
                @if($assets->count() > 0)
                    <div id="assetsCardGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($assets as $asset)
                            <x-frontend.asset-card :asset="$asset" :selectable="true" />
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 italic py-10 text-center bg-white border border-dashed rounded-lg">No saved assets found.</p>
                @endif
            </div>
        </div>
        <hr class="border-gray-300 mb-6" />

        <!-- ── Campaigns Section ── -->
        <div id="campaigns">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-[22px] font-light text-gray-800">Saved Campaigns ({{ $campaigns->count() }})</h2>
                <button type="button" class="w-6 h-6 rounded-full border-2 border-[#0071c5] text-[#0071c5] flex items-center justify-center text-xs" onclick="toggleSection('campaignsGrid', this)">
                    <i class="fas fa-minus text-[10px]"></i>
                </button>
            </div>
            <div id="campaignsGrid">
                @if($campaigns->count() > 0)
                    <div id="campaignsCardGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($campaigns as $campaign)
                            <x-frontend.campaign-card :campaign="$campaign" :selectable="true" />
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 italic py-10 text-center bg-white border border-dashed rounded-lg">No saved campaigns found.</p>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
     function toggleSection(sectionId, btn) {
            const section = document.getElementById(sectionId);
            const icon = btn.querySelector('i');

            section.classList.toggle('hidden');
            const isHidden = section.classList.contains('hidden');

            icon.classList.toggle('fa-plus', isHidden);
            icon.classList.toggle('fa-minus', !isHidden);

            localStorage.setItem(sectionId, isHidden ? 'hidden' : 'visible');
        }
</script>
@endpush
