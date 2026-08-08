@extends('frontend.layouts.font')
@section('content')
    <section class="container mx-auto px-8 py-10 font-['Outfit']">
        <!-- Page Title -->
        <h1 class="text-[#0071c5] text-4xl font-light mb-8">My Bookmarked Items</h1>

        <!-- ── Assets Section ── -->
        <div id="assets" class="mb-12">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-[22px] font-light text-gray-800">Bookmarked Assets ({{ $assets->count() }})</h2>
              
            </div>

            <div id="assetsGrid">
                @if($assets->count() > 0)
                    <div id="assetsCardGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($assets as $asset)
                            <x-frontend.asset-card :asset="$asset" :selectable="true" />
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 italic py-10 text-center bg-white border border-dashed rounded-lg">No bookmarked assets found.</p>
                @endif
            </div>
        </div>
        <hr class="border-gray-300 mb-6" />


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
