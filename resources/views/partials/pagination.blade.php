@if($items->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
        <div class="flex items-center justify-between">
            {{-- Previous --}}
            @if($items->onFirstPage())
                <button disabled class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-400 opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800">
                    Previous
                </button>
            @else
                <a href="{{ $items->previousPageUrl() }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Previous
                </a>
            @endif

            <ul class="hidden items-center gap-1 sm:flex">
                @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                    <li>
                        <a href="{{ $url }}" class="flex h-9 w-9 items-center justify-center rounded-lg text-sm font-medium {{ $page == $items->currentPage() ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                            {{ $page }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Next --}}
            @if($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Next
                </a>
            @else
                <button disabled class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-400 opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800">
                    Next
                </button>
            @endif
        </div>
    </div>
@endif