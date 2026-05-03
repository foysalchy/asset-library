@extends('frontend.layouts.font')
@section('content')
<section class="max-w-screen-2xl mx-auto px-8 py-10">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-light text-[#0071c5]">My Tickets</h1>
        <a href="{{ route('tickets.create') }}"
            class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-6 py-2.5 text-sm font-bold transition-colors">
            <i class="fas fa-plus mr-2"></i> New Ticket
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-sm mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-sm border border-gray-100">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">#</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Subject</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Status</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Replies</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Date</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500">#{{ $ticket->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $ticket->subject }}</td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [0 => 'green', 1 => 'yellow', 2 => 'red'];
                                $color = $colors[$ticket->status];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                bg-{{ $color }}-100 text-{{ $color }}-700">
                                {{ $ticket->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $ticket->replies_count }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('tickets.show', $ticket) }}"
                                class="text-[#0071c5] text-xs font-bold hover:underline uppercase tracking-wide">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            No tickets yet. <a href="{{ route('tickets.create') }}" class="text-[#0071c5] underline">Create one</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $tickets->links() }}
        </div>
    </div>

</section>
@endsection
