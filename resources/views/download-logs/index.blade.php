@extends('layouts.app')

@section('content')

    <div class="p-4 mx-auto max-w-screen-2xl md:p-6">



        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 gap-4 mb-6 sm:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Downloads</p>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white mt-1">{{ number_format($stats['total_downloads']) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unique Users</p>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white mt-1">{{ number_format($stats['unique_users']) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaigns</p>
                <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400 mt-1">{{ number_format($stats['campaign_downloads']) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assets</p>
                <p class="text-2xl font-semibold text-purple-600 dark:text-purple-400 mt-1">{{ number_format($stats['asset_downloads']) }}</p>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- Filters --}}
            <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Logs</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $logs->total() }} records</p>
                </div>

                <form method="GET" action="{{ route('download-logs.index') }}"
      id="filter-form"
      class="flex flex-col gap-2 sm:flex-row sm:items-center">

    {{-- Search --}}
    <div class="relative">
        <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
            <svg class="fill-gray-500 dark:fill-gray-400" width="16" height="16" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
            </svg>
        </button>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Enter Title..."
               class="h-[38px] w-full rounded-lg border border-gray-300 bg-transparent py-2 pl-[38px] pr-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 sm:w-[200px]" />
    </div>

    {{-- Model Filter --}}
    <select name="model"
            onchange="document.getElementById('filter-form').submit()"
            class="h-[38px] rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 focus:border-blue-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
        <option value="">All Types</option>
        <option value="campaign" {{ request('model') === 'campaign' ? 'selected' : '' }}>Campaign</option>
        <option value="asset"    {{ request('model') === 'asset'    ? 'selected' : '' }}>Asset</option>
    </select>

    {{-- User Filter --}}
    <select name="user_id"
            onchange="document.getElementById('filter-form').submit()"
            class="h-[38px] rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 focus:border-blue-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
        <option value="">All Users</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>

    {{-- Date From --}}

    {{-- Clear --}}
    @if(request()->hasAny(['search', 'model', 'user_id', 'date_from', 'date_to']))
        <a href="{{ route('download-logs.index') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-medium text-red-500 hover:bg-red-50 dark:border-red-900/40 dark:bg-transparent dark:hover:bg-red-900/20 transition-colors whitespace-nowrap">
            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
            </svg>
            Clear
        </a>
    @endif

</form>
            </div>

            {{-- Table --}}
            <div class="overflow-hidden">
                <div class="max-w-full px-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-gray-200 border-y dark:border-gray-700">
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">User</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Type</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">File</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Downloads</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">IP Address</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Last Download</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">

                                    {{-- User --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                                <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">
                                                    {{ strtoupper(substr($log->user->name ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $log->user->name ?? 'Deleted User' }}</p>
                                                <p class="text-xs text-gray-400">{{ $log->user->email ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Type --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full capitalize
                                            {{ $log->model === 'campaign'
                                                ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' }}">
                                            {{ $log->model }}
                                        </span>
                                    </td>

                                    {{-- File --}}
                                    <td class="px-4 py-3">
                                        @if($log->model === 'campaign' && $log->modelRecord)
                                            <a href="{{ route('campaigns.show', $log->model_id) }}"
                                               class="text-sm text-blue-600 hover:underline dark:text-blue-400 line-clamp-1">
                                                {{ $log->modelRecord->title ?? 'N/A' }}
                                            </a>
                                        @elseif($log->model === 'asset' && $log->modelRecord)
                                            <a href="{{ route('assets.show', $log->model_id) }}"
                                               class="text-sm text-blue-600 hover:underline dark:text-blue-400 line-clamp-1">
                                                {{ $log->modelRecord->title ?? 'N/A' }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400 italic">Deleted</span>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-0.5">ID: {{ $log->model_id }}</p>
                                    </td>

                                    {{-- Download Count --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                                <span class="text-xs font-bold text-green-600 dark:text-green-400">{{ $log->count }}</span>
                                            </div>
                                            <span class="text-xs text-gray-400">times</span>
                                        </div>
                                    </td>

                                    {{-- IP Address --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-sm text-gray-500 dark:text-gray-400 font-mono text-xs">
                                            {{ $log->ip_address ?? '—' }}
                                        </span>
                                    </td>

                                    {{-- Last Download --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $log->updated_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->updated_at->diffForHumans() }}</p>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                <svg class="text-gray-400" width="28" height="28" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">No download logs found</p>
                                                <p class="text-sm text-gray-500 mt-0.5">Try adjusting your filters.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
                    <div class="flex items-center justify-between">
                        @if($logs->onFirstPage())
                            <button disabled class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715Z" fill="currentColor"/></svg>
                                Previous
                            </button>
                        @else
                            <a href="{{ $logs->previousPageUrl() }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715Z" fill="currentColor"/></svg>
                                Previous
                            </a>
                        @endif

                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}
                        </span>

                        @if($logs->hasMorePages())
                            <a href="{{ $logs->nextPageUrl() }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                Next
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715Z" fill="currentColor"/></svg>
                            </a>
                        @else
                            <button disabled class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                Next
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715Z" fill="currentColor"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection