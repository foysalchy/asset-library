@extends('layouts.app')
@section('content')
<div class="p-4 mx-auto w-full  md:p-6">

    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Activity Logs</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Track all system activity</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Filters --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Logs</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $logs->total() }} total records</p>
            </div>
            <form method="GET" action="{{ route('activity-logs.index') }}"
                  class="flex flex-col gap-2 sm:flex-row sm:items-center">

                {{-- User --}}
                <select name="user_id" onchange="this.form.submit()"
                        class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 shadow-theme-xs focus:border-blue-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Model --}}
                <select name="model_type" onchange="this.form.submit()"
                        class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 shadow-theme-xs focus:border-blue-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All Models</option>
                    @foreach($modelTypes as $model)
                        <option value="{{ $model }}" {{ request('model_type') === $model ? 'selected' : '' }}>
                            {{ $model }}
                        </option>
                    @endforeach
                </select>

                {{-- Action --}}
                <select name="action" onchange="this.form.submit()"
                        class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 shadow-theme-xs focus:border-blue-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All Actions</option>
                    <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>

                @if(request()->hasAny(['user_id', 'model_type', 'action']))
                    <a href="{{ route('activity-logs.index') }}"
                       class="h-[42px] inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-500 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
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
                            <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Action</th>
                            <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Description</th>
                            <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Model</th>
                            <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">IP</th>
                            <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">

                                {{-- User --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($log->user)
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $log->user->avatar_url }}" alt="{{ $log->user->name }}"
                                                 class="w-7 h-7 rounded-full object-cover shrink-0">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $log->user->name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">System</span>
                                    @endif
                                </td>

                                {{-- Action badge --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $badgeClass = match($log->action) {
                                            'created' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                            'updated' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'deleted' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                            default   => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize {{ $badgeClass }}">
                                        {{ $log->action }}
                                    </span>
                                </td>

                                {{-- Description --}}
                                <td class="px-4 py-3 max-w-xs">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                        {{ $log->description }}
                                    </p>
                                </td>

                                {{-- Model --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $log->model_type }}
                                    </span>
                                </td>

                                {{-- IP --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm text-gray-400 font-mono">
                                        {{ $log->ip_address ?? '—' }}
                                    </span>
                                </td>

                                {{-- Time --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm text-gray-500 dark:text-gray-400"
                                          title="{{ $log->created_at->format('M d, Y H:i:s') }}">
                                        {{ $log->created_at->diffForHumans() }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <svg class="text-gray-400" width="28" height="28" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">No activity found</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Activity will appear here as users interact with the system.</p>
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
                        <button disabled class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:px-3.5">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="currentColor"/></svg>
                            <span class="hidden sm:inline">Previous</span>
                        </button>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:px-3.5">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="currentColor"/></svg>
                            <span class="hidden sm:inline">Previous</span>
                        </a>
                    @endif

                    <ul class="hidden items-center gap-0.5 sm:flex">
                        @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                            <li>
                                @if($page == $logs->currentPage())
                                    <button class="flex h-10 w-10 items-center justify-center rounded-lg text-theme-sm font-medium bg-blue-500 text-white">{{ $page }}</button>
                                @else
                                    <a href="{{ $url }}" class="flex h-10 w-10 items-center justify-center rounded-lg text-theme-sm font-medium text-gray-700 hover:bg-blue-500/[0.08] hover:text-blue-500 dark:text-gray-400">{{ $page }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
                        Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}
                    </span>

                    @if($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:px-3.5">
                            <span class="hidden sm:inline">Next</span>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/></svg>
                        </a>
                    @else
                        <button disabled class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 opacity-50 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:px-3.5">
                            <span class="hidden sm:inline">Next</span>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>
@endsection