@extends('frontend.layouts.font')
@section('content')
<section class="max-w-screen-2xl mx-auto px-8 py-10">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('tickets.index') }}" class="text-[#0071c5] hover:opacity-70">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-light text-[#0071c5]">New Ticket</h1>
    </div>

    <div class="bg-white shadow-sm border border-gray-100 p-8 max-w-2xl">

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-sm mb-6 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Subject -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}"
                    placeholder="Enter ticket subject"
                    class="w-full border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#0071c5] transition-colors font-['Outfit']">
            </div>

            <!-- Message -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                <textarea name="description" rows="6"
                    placeholder="Describe your issue..."
                    class="w-full border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#0071c5] transition-colors font-['Outfit'] resize-none">{{ old('description') }}</textarea>
            </div>

            <!-- Image -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Screanshot <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-gray-300 px-4 py-2.5 text-sm text-gray-600 font-['Outfit']">
            </div>

            <button type="submit"
                class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-8 py-2.5 text-sm font-bold transition-colors">
                Submit Ticket
            </button>
        </form>
    </div>

</section>
@endsection
