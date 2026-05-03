@extends('layouts.app')
@section('content')
<div class="p-6">

    <!-- Header -->
    <div class="flex items-start justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('ticket.admin') }}"
                class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 text-gray-400 transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">{{ $ticket->subject }}</h1>
                <p class="text-sm text-gray-400 mt-0.5">#{{ $ticket->id }} · {{ $ticket->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @php
                $statusConfig = [
                    0 => ['label' => 'Open',        'class' => 'bg-green-100 text-green-700'],
                    1 => ['label' => 'In Progress', 'class' => 'bg-yellow-100 text-yellow-700'],
                    2 => ['label' => 'Closed',      'class' => 'bg-red-100 text-red-700'],
                ];
                $s = $statusConfig[$ticket->status];
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $s['class'] }}">
                {{ $s['label'] }}
            </span>

            @if($ticket->status !== 2)
                <form action="{{ route('tickets.close', $ticket) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 text-xs font-bold rounded transition-colors">
                        Close Ticket
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT: Conversation -->
        <div class="lg:col-span-2 space-y-4">

            <!-- Original Message -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $ticket->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="ml-auto text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-semibold uppercase">User</span>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $ticket->description }}</p>
                @if($ticket->image)
                    <img src="{{ asset('storage/' . $ticket->image) }}" alt="attachment"
                        class="mt-4 max-w-sm rounded-lg border border-gray-200">
                @endif
            </div>

            <!-- Replies -->
            @foreach($ticket->replies as $reply)
                <div class="bg-white rounded-lg border shadow-sm p-6
                    {{ $reply->is_admin ? 'border-blue-200 bg-blue-50/30' : 'border-gray-200' }}">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0
                            {{ $reply->is_admin ? 'bg-[#001e3e]' : 'bg-blue-500' }}">
                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $reply->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                        </div>
                        @if($reply->is_admin)
                            <span class="ml-auto text-[10px] bg-[#001e3e] text-white px-2 py-0.5 rounded-full font-semibold uppercase">Admin</span>
                        @else
                            <span class="ml-auto text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-semibold uppercase">User</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $reply->message }}</p>
                    @if($reply->image)
                        <img src="{{ asset('storage/' . $reply->image) }}" alt="attachment"
                            class="mt-4 max-w-sm rounded-lg border border-gray-200">
                    @endif
                </div>
            @endforeach

            <!-- Reply Form -->
            @if($ticket->status !== 2)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Reply as Admin</h3>
                    <form action="{{ route('admin.tickets.reply', $ticket->id)  }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea name="message" rows="4"
                            placeholder="Write your reply..."
                            class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm outline-none focus:border-blue-400 transition-colors font-['Outfit'] resize-none mb-4">{{ old('message') }}</textarea>
                        <div class="flex items-center justify-between">
                            <input type="file" name="image" accept="image/*"
                                class="text-sm text-gray-500 font-['Outfit']">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 text-sm font-bold rounded transition-colors">
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 text-sm text-center rounded-lg">
                    This ticket is closed.
                </div>
            @endif

        </div>

        <!-- RIGHT: Ticket Info -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 pb-3 border-b border-gray-100">Ticket Info</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-semibold {{ $s['class'] }} px-2 py-0.5 rounded-full text-xs">{{ $s['label'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">User</span>
                        <span class="font-medium text-gray-700">{{ $ticket->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium text-gray-700 text-xs">{{ $ticket->user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created</span>
                        <span class="font-medium text-gray-700">{{ $ticket->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Replies</span>
                        <span class="font-medium text-gray-700">{{ $ticket->replies->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Last Update</span>
                        <span class="font-medium text-gray-700">{{ $ticket->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
