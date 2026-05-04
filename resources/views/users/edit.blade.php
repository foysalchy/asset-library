{{-- edit.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="p-4 mx-auto w-full  md:p-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('users.index') }}" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Users</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
        </svg>
        <a href="{{ route('users.show', $user) }}" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">{{ $user->name }}</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
        </svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium">Edit</span>
    </nav>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Edit User</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $user->email }}</p>
    </div>
    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            @include('users._form', ['roles' => $roles, 'assignedRoles' => $assignedRoles])
        </div>
        <div class="flex items-center justify-end gap-3 mt-5">
            <a href="{{ route('users.show', $user) }}"
                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                </svg>
                Update User
            </button>
        </div>
    </form>
</div>
@endsection