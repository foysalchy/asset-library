@extends('layouts.app')

@section('content')
<div class="space-y-6  mx-auto  md:p-6 py-4">

  {{-- Stat Cards --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4 mt-4" syle="margin-top:40px">

    {{-- Campaigns --}}
    <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-blue-50 rounded-xl dark:bg-blue-900/20">
        <svg class="text-blue-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
        </svg>
      </div>
      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Campaigns</span>
          <h4 class="mt-2 font-bold text-gray-800 text-2xl dark:text-white/90">{{ $stats['campaigns'] }}</h4>
        </div>
        <a href="{{ route('campaigns.index') }}"
          class="text-xs text-blue-500 hover:underline">View all</a>
      </div>
    </div>

    {{-- Assets --}}
    <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-purple-50 rounded-xl dark:bg-purple-900/20">
        <svg class="text-purple-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2" />
          <circle cx="8.5" cy="8.5" r="1.5" />
          <path d="M21 15l-5-5L5 21" />
        </svg>
      </div>
      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Assets</span>
          <h4 class="mt-2 font-bold text-gray-800 text-2xl dark:text-white/90">{{ $stats['assets'] }}</h4>
        </div>
        <a href="{{ route('assets.index') }}"
          class="text-xs text-purple-500 hover:underline">View all</a>
      </div>
    </div>

    {{-- Users --}}
    <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-green-50 rounded-xl dark:bg-green-900/20">
        <svg class="text-green-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
        </svg>
      </div>
      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Users</span>
          <h4 class="mt-2 font-bold text-gray-800 text-2xl dark:text-white/90">{{ $stats['users'] }}</h4>
        </div>
        <a href="{{ route('users.index') }}"
          class="text-xs text-green-500 hover:underline">View all</a>
      </div>
    </div>

    {{-- Projects --}}
    <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-amber-50 rounded-xl dark:bg-amber-900/20">
        <svg class="text-amber-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 7h18M3 12h18M3 17h18" />
          <path d="M8 3l-5 4 5 4" />
        </svg>
      </div>
      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Projects</span>
          <h4 class="mt-2 font-bold text-gray-800 text-2xl dark:text-white/90">{{ $stats['projects'] }}</h4>
        </div>
        <a href="{{ route('projects.index') }}"
          class="text-xs text-amber-500 hover:underline">View all</a>
      </div>
    </div>

  </div>

  {{-- Middle Row --}}
  <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

    {{-- Recent Campaigns --}}
    <div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Campaigns</h3>
        <a href="{{ route('campaigns.index') }}"
          class="text-xs text-blue-500 hover:underline">View all</a>
      </div>
      <div class="divide-y divide-gray-100 dark:divide-gray-800">
        @forelse($recentCampaigns as $campaign)
        <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">

          {{-- Thumbnail --}}
          @if($campaign->thumbnail)
          <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}"
            class="w-10 h-10 rounded-lg object-cover shrink-0 border border-gray-200 dark:border-gray-700">
          @else
          <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
            <svg class="text-blue-500" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
            </svg>
          </div>
          @endif

          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800 dark:text-white/90 truncate">
              {{ $campaign->title }}
            </p>
            <p class="text-xs text-gray-400 mt-0.5">
              {{ $campaign->created_at->diffForHumans() }}
            </p>
          </div>

          {{-- Status --}}
          @php
          $statusClass = match($campaign->status) {
          'active' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
          'draft' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
          'expired' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
          default => 'bg-gray-100 text-gray-600',
          };
          @endphp
          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize shrink-0 {{ $statusClass }}">
            {{ $campaign->status }}
          </span>

        </div>
        @empty
        <div class="px-6 py-10 text-center">
          <p class="text-sm text-gray-400">No campaigns yet.</p>
        </div>
        @endforelse
      </div>
    </div>

    {{-- Recent Assets --}}
    <div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Assets</h3>
        <a href="{{ route('assets.index') }}"
          class="text-xs text-blue-500 hover:underline">View all</a>
      </div>
      <div class="divide-y divide-gray-100 dark:divide-gray-800">
        @forelse($recentAssets as $asset)
        <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">

          {{-- Media preview --}}
          @if($asset->media->first()?->media_type === 'image')
          <img src="{{ $asset->media->first()->url }}" alt="{{ $asset->title }}"
            class="w-10 h-10 rounded-lg object-cover shrink-0 border border-gray-200 dark:border-gray-700">
          @else
          <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center shrink-0">
            <svg class="text-purple-500" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <path d="M21 15l-5-5L5 21" />
            </svg>
          </div>
          @endif

          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800 dark:text-white/90 truncate">
              {{ $asset->title }}
            </p>
            <p class="text-xs text-gray-400 mt-0.5 truncate">
              {{ $asset->assetType->names }}
            </p>
          </div>

         

        </div>
        @empty
        <div class="px-6 py-10 text-center">
          <p class="text-sm text-gray-400">No assets yet.</p>
        </div>
        @endforelse
      </div>
    </div>

  </div>

  {{-- Recent Activity --}}
  <div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Activity</h3>
      @permission('activity_logs.view')
      <a href="{{ route('activity-logs.index') }}"
        class="text-xs text-blue-500 hover:underline">View all</a>
      @endpermission
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-800">
      @forelse($recentLogs as $log)
      <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">

        {{-- Action icon --}}
        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                        {{ match($log->action) {
                            'created' => 'bg-green-100 dark:bg-green-900/30',
                            'updated' => 'bg-blue-100 dark:bg-blue-900/30',
                            'deleted' => 'bg-red-100 dark:bg-red-900/30',
                            default   => 'bg-gray-100 dark:bg-gray-800',
                        } }}">
          <svg class="{{ match($log->action) {
                            'created' => 'text-green-500',
                            'updated' => 'text-blue-500',
                            'deleted' => 'text-red-500',
                            default   => 'text-gray-400',
                        } }}" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            @if($log->action === 'created')
            <path d="M12 5v14M5 12h14" />
            @elseif($log->action === 'updated')
            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
            @else
            <polyline points="3 6 5 6 21 6" />
            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
            <path d="M10 11v6M14 11v6" />
            @endif
          </svg>
        </div>

        {{-- User avatar --}}
        @if($log->user)
        <img src="{{ $log->user->avatar_url }}" alt="{{ $log->user->name }}"
          class="w-7 h-7 rounded-full object-cover shrink-0">
        @endif

        {{-- Info --}}
        <div class="flex-1 min-w-0">
          <p class="text-sm text-gray-700 dark:text-gray-300 truncate">
            {{ $log->description }}
          </p>
          <p class="text-xs text-gray-400 mt-0.5">
            @if($log->user) {{ $log->user->name }} · @endif
            {{ $log->created_at->diffForHumans() }}
          </p>
        </div>

        {{-- Model badge --}}
        <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300 shrink-0">
          {{ $log->model_type }}
        </span>

      </div>
      @empty
      <div class="px-6 py-10 text-center">
        <p class="text-sm text-gray-400">No activity recorded yet.</p>
      </div>
      @endforelse
    </div>
  </div>

</div>
@endsection