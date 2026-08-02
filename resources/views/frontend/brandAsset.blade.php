@extends('frontend.layouts.font')

@section('content')
    <section class="container mx-auto px-6 py-12 font-['Outfit']">

        <div class="mb-12 pb-6">
            <h1 class="text-[#0071c5] text-4xl font-light">Brand Assets</h1>
        </div>

        @if ($projects->isEmpty())
            <div class="py-20 text-center text-gray-400">
                <p>No brand assets found.</p>
            </div>
        @else
            @foreach ($projects as $concernKey => $projects)
                <div class="mb-16">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Project::CONCERNS[$concernKey] ?? ucfirst(str_replace('_', ' ', $concernKey)) }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($projects as $project)
                             <a href="{{ route('home.filter', ['section' => 'assets', 'project' => $project->id]) }}"  class="bg-white border border-gray-200 p-8 flex flex-col items-center justify-center text-center hover:shadow-md transition-all duration-300 group cursor-pointer h-full">

                                <div class="h-24 w-full flex items-center justify-center mb-6">
                                    @if ($project->logo)
                                        <img src="{{ $project->logo_url }}" alt="{{ $project->name }}"
                                            class="max-h-full max-w-full object-contain transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <img src="{{ asset('./images/brand/brand-01.svg') }}" alt="default"
                                            class="max-h-full max-w-full object-contain opacity-20">
                                    @endif
                                </div>

                                <h3 class="text-[#0071c5] text-sm font-bold uppercase tracking-[1.5px] leading-tight group-hover:text-[#005ea3]">
                                    {{ $project->name }}
                                </h3>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

    </section>
@endsection
