@extends('frontend.layouts.font')

@section('content')
<section class="max-w-screen-2xl mx-auto px-4 lg:px-8 py-10 font-['Outfit']">

    <!-- Header Section -->
    <div class="flex items-start justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('tickets.index') }}" class="text-[#0071c5] hover:opacity-70 transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-light text-[#0071c5]">{{ $ticket->subject }}</h1>
                <p class="text-xs text-gray-400 mt-1">Ticket #{{ $ticket->id }} · Opened on {{ $ticket->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border {{ $ticket->status_badge }}">
                {{ $ticket->status_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- ── LEFT: Chat Window (Designing like Admin) ── -->
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col" style="height:650px;">

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6 bg-gray-50/50" id="chatBox">

                    {{-- 1. Original User Message (The starting of ticket) --}}
                    <div class="flex items-end gap-2 {{ $ticket->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-[10px] font-bold shrink-0 {{ $ticket->user_id === auth()->id() ? 'bg-[#001e3e]' : 'bg-[#0071c5]' }}">
                            {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                        </div>
                        <div class="max-w-[75%]">
                            <p class="text-[10px] text-gray-400 mb-1 {{ $ticket->user_id === auth()->id() ? 'text-right mr-1' : 'ml-1' }}">
                                {{ $ticket->user->name }} · {{ $ticket->created_at->diffForHumans() }}
                            </p>
                            <div class="px-4 py-3 shadow-sm {{ $ticket->user_id === auth()->id() ? 'bg-[#0071c5] text-white rounded-tl-2xl rounded-bl-2xl rounded-br-2xl' : 'bg-white border border-gray-200 text-gray-800 rounded-tr-2xl rounded-br-2xl rounded-bl-2xl' }}">
                                <p class="text-sm leading-relaxed">{{ $ticket->description }}</p>
                            </div>
                            @if($ticket->image)
                                <img src="{{ $ticket->image_url }}" alt="attachment" class="mt-2 max-w-[250px] rounded-xl {{ $ticket->user_id === auth()->id() ? 'ml-auto' : '' }} block border border-gray-100">
                            @endif
                        </div>
                    </div>

                    {{-- 2. Back and Forth Replies --}}
                    @foreach($ticket->replies as $reply)
                        <div class="flex items-end gap-2 {{ $reply->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-[10px] font-bold shrink-0 {{ $reply->user_id === auth()->id() ? 'bg-[#001e3e]' : 'bg-[#0071c5]' }}">
                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                            </div>
                            <div class="max-w-[75%]">
                                <p class="text-[10px] text-gray-400 mb-1 {{ $reply->user_id === auth()->id() ? 'text-right mr-1' : 'ml-1' }}">
                                    {{ $reply->user->name }} @if($reply->is_admin) <span class="text-[#0071c5] font-black uppercase text-[8px] ml-1">Staff</span> @endif · {{ $reply->created_at->diffForHumans() }}
                                </p>
                                <div class="px-4 py-3 shadow-sm {{ $reply->user_id === auth()->id() ? 'bg-[#0071c5] text-white rounded-tl-2xl rounded-bl-2xl rounded-br-2xl' : 'bg-white border border-gray-200 text-gray-800 rounded-tr-2xl rounded-br-2xl rounded-bl-2xl' }}">
                                    <p class="text-sm leading-relaxed">{{ $reply->message }}</p>
                                </div>
                                @if($reply->image)
                                    <img src="{{ asset('storage/' . $reply->image) }}" alt="attachment" class="mt-2 max-w-[250px] rounded-xl {{ $reply->user_id === auth()->id() ? 'ml-auto' : '' }} block border border-gray-100">
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Input Area (Only if ticket is not closed) -->
                @if($ticket->status !== 2)
                    <form action="{{ route('tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data"
                        class="shrink-0 border-t border-gray-100 bg-white px-4 py-4 flex items-end gap-3">
                        @csrf

                        <!-- Attach file -->
                        <label class="cursor-pointer inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-400 hover:bg-gray-100 transition-colors shrink-0">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                            </svg>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="showFile(this)">
                        </label>

                        <!-- Textarea -->
                        <div class="flex-1">
                            <textarea name="message" id="replyMsg" rows="1" required
                                placeholder="Type your response here..."
                                class="w-full border border-gray-200 rounded-2xl px-5 py-2.5 text-sm text-gray-700 bg-gray-50 outline-none resize-none leading-relaxed focus:border-[#0071c5] transition-all"
                                oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,150)+'px'">{{ old('message') }}</textarea>
                            <p id="fileName" class="hidden text-[10px] text-blue-600 mt-1 px-2 font-bold uppercase"></p>
                        </div>

                        <!-- Send Button -->
                        <button type="submit"
                            class="w-10 h-10 rounded-full bg-[#0071c5] hover:bg-[#001e3e] flex items-center justify-center text-white transition-all shadow-md shrink-0">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
                        </button>
                    </form>
                @else
                    <div class="p-4 bg-red-50 text-center text-xs font-bold text-red-500 uppercase tracking-widest">
                        This ticket is closed. No further replies can be sent.
                    </div>
                @endif

            </div>
        </div>

        <!-- ── RIGHT: Sidebar Info ── -->
        <div class="space-y-6">
            <div class="bg-white border border-gray-100 shadow-sm p-6 rounded-xl">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 pb-3 border-b border-gray-50">Ticket Summary</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 uppercase font-medium">Status</span>
                        <span class="text-xs font-bold {{ $ticket->status_badge }} px-2 py-0.5 rounded uppercase">{{ $ticket->status_label }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 uppercase font-medium">Messages</span>
                        <span class="text-sm font-bold text-gray-800">{{ $ticket->replies->count() + 1 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 uppercase font-medium">Last Activity</span>
                        <span class="text-xs text-gray-700 font-medium">{{ $ticket->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- Support Note --}}
            <div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100/50">
                <p class="text-xs text-[#0071c5] leading-relaxed">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Our technical support team typically responds within 24 business hours. Please ensure your descriptions are clear for faster resolution.
                </p>
            </div>
        </div>

    </div>
</section>

@push('scripts')
<script>
    const chatBox = document.getElementById('chatBox');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    function showFile(input) {
        const el = document.getElementById('fileName');
        if (input.files.length > 0) {
            el.textContent = 'Selected: ' + input.files[0].name;
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
