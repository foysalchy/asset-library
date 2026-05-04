@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto w-full  md:p-6">

    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Support Tickets</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage all user support tickets</p>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 dark:border-green-800 dark:bg-green-900/20">
            <svg class="shrink-0 text-green-500" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
            </svg>
            <p class="text-sm font-medium text-green-700 dark:text-green-400">{{ session('success') }}</p>
        </div>
    @endif

    @php
        $statusConfig = [
            0 => ['label' => 'Open',        'class' => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
            1 => ['label' => 'In Progress', 'class' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'],
            2 => ['label' => 'Closed',      'class' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
        ];
        $s = $statusConfig[$ticket->status];
    @endphp

    <div class="grid grid-cols-1 gap-6">

        <!-- ── Chat window ── -->
        <div>
            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-white/[0.03] overflow-hidden flex flex-col" style="height:620px;">

                <!-- Top bar -->
                <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-white/[0.03] shrink-0">
                    <a href="{{ route('ticket.admin') }}"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors shrink-0">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/>
                        </svg>
                    </a>
                    <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium shrink-0">
                        {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $ticket->subject }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">#{{ $ticket->id }} · {{ $ticket->user->name }} · {{ $ticket->created_at->format('d M Y') }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $s['class'] }} shrink-0">
                        {{ $s['label'] }}
                    </span>
                    @if($ticket->status !== 2)
                        <form action="{{ route('tickets.close', $ticket) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-red-500 hover:bg-red-600 px-3 py-1.5 text-xs font-medium text-white transition-colors shrink-0">
                                Close
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4 bg-gray-50 dark:bg-white/[0.01]" id="chatBox">

                    <!-- Original message -->
                    @if($ticket->user_id === auth()->id())
                        <!-- My ticket — right -->
                        <div class="flex items-end gap-2 flex-row-reverse">
                            <div class="w-7 h-7 rounded-full bg-[#001e3e] flex items-center justify-center text-white text-xs font-medium shrink-0">
                                {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                            </div>
                            <div class="max-w-[68%]">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 mr-1 text-right">{{ $ticket->user->name }} · {{ $ticket->created_at->diffForHumans() }}</p>
                                <div class="bg-[#0071c5] px-4 py-2.5 rounded-tl-2xl rounded-bl-2xl rounded-br-2xl">
                                    <p class="text-sm text-white leading-relaxed">{{ $ticket->description }}</p>
                                </div>
                                @if($ticket->image)
                                    <img src="{{ asset('storage/' . $ticket->image) }}" alt="attachment"
                                        class="mt-2 max-w-[200px] rounded-xl ml-auto block">
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Other user ticket — left -->
                        <div class="flex items-end gap-2">
                            <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium shrink-0">
                                {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                            </div>
                            <div class="max-w-[68%]">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 ml-1">{{ $ticket->user->name }} · {{ $ticket->created_at->diffForHumans() }}</p>
                                <div class="bg-white dark:bg-white/10 border border-gray-200 dark:border-gray-700 px-4 py-2.5 rounded-tr-2xl rounded-br-2xl rounded-bl-2xl">
                                    <p class="text-sm text-gray-800 dark:text-white/90 leading-relaxed">{{ $ticket->description }}</p>
                                </div>
                                @if($ticket->image)
                                    <img src="{{ asset('storage/' . $ticket->image) }}" alt="attachment"
                                        class="mt-2 max-w-[200px] rounded-xl border border-gray-100 dark:border-gray-700">
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Replies -->
                    @foreach($ticket->replies as $reply)
                        @if($reply->user_id === auth()->id())
                            <!-- My message — right -->
                            <div class="flex items-end gap-2 flex-row-reverse">
                                <div class="w-7 h-7 rounded-full bg-[#001e3e] flex items-center justify-center text-white text-xs font-medium shrink-0">
                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                </div>
                                <div class="max-w-[68%]">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 mr-1 text-right">{{ $reply->user->name }} · {{ $reply->created_at->diffForHumans() }}</p>
                                    <div class="bg-[#0071c5] px-4 py-2.5 rounded-tl-2xl rounded-bl-2xl rounded-br-2xl">
                                        <p class="text-sm text-white leading-relaxed">{{ $reply->message }}</p>
                                    </div>
                                    @if($reply->image)
                                        <img src="{{ asset('storage/' . $reply->image) }}" alt="attachment"
                                            class="mt-2 max-w-[200px] rounded-xl ml-auto block">
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Other message — left -->
                            <div class="flex items-end gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium shrink-0">
                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                </div>
                                <div class="max-w-[68%]">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 ml-1">{{ $reply->user->name }} · {{ $reply->created_at->diffForHumans() }}</p>
                                    <div class="bg-white dark:bg-white/10 border border-gray-200 dark:border-gray-700 px-4 py-2.5 rounded-tr-2xl rounded-br-2xl rounded-bl-2xl">
                                        <p class="text-sm text-gray-800 dark:text-white/90 leading-relaxed">{{ $reply->message }}</p>
                                    </div>
                                    @if($reply->image)
                                        <img src="{{ asset('storage/' . $reply->image) }}" alt="attachment"
                                            class="mt-2 max-w-[200px] rounded-xl border border-gray-100 dark:border-gray-700">
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>

                <!-- Input -->
                @if($ticket->status !== 2)
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data"
                        class="shrink-0 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-white/[0.03] px-3 py-3 flex items-end gap-2">
                        @csrf

                        <!-- Attach image -->
                        <label class="cursor-pointer inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors shrink-0">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="showFile(this)">
                        </label>

                        <!-- Textarea -->
                        <div class="flex-1">
                            <textarea name="message" id="replyMsg" rows="1"
                                placeholder="Write a reply..."
                                class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 bg-gray-50 dark:bg-white/5 outline-none resize-none leading-relaxed focus:border-blue-300 dark:focus:border-blue-600 transition-colors"
                                oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,120)+'px'">{{ old('message') }}</textarea>
                            <p id="fileName" class="hidden text-xs text-gray-400 mt-1 px-1"></p>
                        </div>

                        <!-- Send -->
                        <button type="submit"
                            class="w-9 h-9 rounded-full bg-[#0071c5] hover:bg-[#005ea3] flex items-center justify-center text-white transition-colors shrink-0">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                            </svg>
                        </button>
                    </form>
                @else
                    <div class="shrink-0 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-white/[0.03] px-4 py-3 text-center text-xs text-red-500">
                        This ticket is closed.
                    </div>
                @endif

            </div>
        </div>



    </div>
</div>

@push('scripts')
<script>
    const chatBox = document.getElementById('chatBox');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    function showFile(input) {
        const el = document.getElementById('fileName');
        if (input.files.length > 0) {
            el.textContent = 'Attached: ' + input.files[0].name;
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
</script>
@endpush

@endsection
