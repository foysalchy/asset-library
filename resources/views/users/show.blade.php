@extends('layouts.app')
@section('content')
<div class="p-4 mx-auto w-full  md:p-6">

    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('users.index') }}" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Users</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $user->name }}</span>
    </nav>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Profile Card --}}
        <div class="space-y-5">
            <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-col items-center text-center gap-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                <div>
                    <div class="flex items-center justify-center gap-2 flex-wrap">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $user->name }}</h2>
                        @if($user->isSuperAdmin())
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                Super Admin
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-sm text-gray-400 mt-0.5">{{ $user->phone }}</p>
                    @endif
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold capitalize {{ $user->status_badge_class }}">
                    {{ $user->status }}
                </span>
                @permission('users.edit')
                <a href="{{ route('users.edit', $user) }}"
                   class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                    Edit User
                </a>
                @endpermission
            </div>

            {{-- Meta --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] space-y-3 text-sm">
                
                <div class="flex justify-between gap-2">
                    <span class="text-gray-500 dark:text-gray-400">Joined</span>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                @if($user->creator)
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500 dark:text-gray-400">Created by</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $user->creator->name }}</span>
                    </div>
                @endif
            </div>

            {{-- Roles --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Roles</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                        <span class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $role->label }}
                        </span>
                    @empty
                        <p class="text-sm text-gray-400">No roles assigned</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right: Permissions + Activity --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Permissions --}}
            @if(!$user->isSuperAdmin() && $user->roles->count())
                <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Permissions</h3>
                    @php
                        $groupedPermissions = $user->roles
                            ->flatMap(fn($r) => $r->permissions)
                            ->unique('id')
                            ->groupBy('group');
                    @endphp
                    <div class="space-y-4">
                        @foreach($groupedPermissions as $group => $perms)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ $group }}</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($perms as $perm)
                                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">
                                            {{ explode('.', $perm->name)[1] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($user->isSuperAdmin())
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-800 dark:bg-amber-900/10">
                    <p class="text-sm font-medium text-amber-700 dark:text-amber-400">
                        Super admin has access to all permissions.
                    </p>
                </div>
            @endif

            {{-- Activity Log --}}
            @permission('activity_logs.view')
            <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Recent Activity</h3>
                @if($logs->count())
                    <div class="space-y-3">
                        @foreach($logs as $log)
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5
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
                                    } }}" width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                                        @if($log->action === 'created')
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10 3.25C10.4142 3.25 10.75 3.58579 10.75 4V9.25H16C16.4142 9.25 16.75 9.58579 16.75 10C16.75 10.4142 16.4142 10.75 16 10.75H10.75V16C10.75 16.4142 10.4142 16.75 10 16.75C9.58579 16.75 9.25 16.4142 9.25 16V10.75H4C3.58579 10.75 3.25 10.4142 3.25 10C3.25 9.58579 3.58579 9.25 4 9.25H9.25V4C9.25 3.58579 9.58579 3.25 10 3.25Z"/>
                                        @elseif($log->action === 'updated')
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        @else
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $log->description }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs text-gray-400 shrink-0">{{ $log->model_type }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400">No activity recorded yet.</p>
                @endif
            </div>
            @endpermission

        </div>
    </div>
</div>
@endsection 