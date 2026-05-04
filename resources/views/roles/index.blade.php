@extends('layouts.app')
@section('content')
<div class="p-4 mx-auto w-full  md:p-6">

    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Roles</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage roles and permissions</p>
        </div>
        @permission('roles.create')
        <a href="{{ route('roles.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 3.25C10.4142 3.25 10.75 3.58579 10.75 4V9.25H16C16.4142 9.25 16.75 9.58579 16.75 10C16.75 10.4142 16.4142 10.75 16 10.75H10.75V16C10.75 16.4142 10.4142 16.75 10 16.75C9.58579 16.75 9.25 16.4142 9.25 16V10.75H4C3.58579 10.75 3.25 10.4142 3.25 10C3.25 9.58579 3.58579 9.25 4 9.25H9.25V4C9.25 3.58579 9.58579 3.25 10 3.25Z" />
            </svg>
            New Role
        </a>
        @endpermission
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

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="mb-5 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-900/20">
        <svg class="shrink-0 text-red-500" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
        </svg>
        <p class="text-sm font-medium text-red-700 dark:text-red-400">{{ session('error') }}</p>
    </div>
    @endif

    <div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
        x-data="{ deleteModal: false, deleteId: null, deleteTitle: '',
            openDelete(id, title) { this.deleteId = id; this.deleteTitle = title; this.deleteModal = true; } }">

        <div class="overflow-hidden">
            <div class="max-w-full overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-gray-200 border-y dark:border-gray-700">
                            <th class="px-6 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Role</th>
                            <th class="px-6 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Permissions</th>
                            <th class="px-6 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Users</th>
                            <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($roles as $role)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0
                                            {{ $role->is_super_admin ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-blue-100 dark:bg-blue-900/30' }}">
                                        <svg class="{{ $role->is_super_admin ? 'text-amber-500' : 'text-blue-500' }}" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $role->label }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $role->name }}</p>
                                    </div>
                                    @if($role->is_super_admin)
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                        Super Admin
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($role->is_super_admin)
                                <span class="text-sm text-gray-500 dark:text-gray-400">All permissions</span>
                                @else
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $role->permissions_count }}</span>
                                <span class="text-sm text-gray-400"> permissions</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $role->users_count }}</span>
                                <span class="text-sm text-gray-400"> users</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$role->is_super_admin)
                                    @permission('roles.edit')
                                    <a href="{{ route('roles.edit', $role) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-blue-50 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-blue-900/20 transition-colors">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    @endpermission
                                    @permission('roles.delete')
                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', { 
            url: '{{ route('roles.destroy', $role->id) }}', 
            title: '{{ addslashes($role->label) }}' 
        })"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-900/20 transition-colors">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                                        </svg>
                                    </button>
                                    @endpermission
                                    @else
                                    <span class="text-xs text-gray-300 dark:text-gray-600 pr-2">Protected</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No roles found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div x-show="deleteModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="deleteModal = false"></div>
            <div class="relative w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-gray-900"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex flex-col items-center text-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center dark:bg-red-900/30">
                        <svg class="text-red-500" width="28" height="28" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Delete Role</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Are you sure you want to delete <span class="font-medium text-gray-700 dark:text-gray-300" x-text='"' + deleteTitle + '"'"></span>?
                        </p>
                    </div>
                    <div class=" flex gap-3 w-full">
                                <button type="button" @click="deleteModal = false"
                                    class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
                                    Cancel
                                </button>
                                <form :action="'/roles/' + deleteId" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition-colors">
                                        Delete
                                    </button>
                                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection