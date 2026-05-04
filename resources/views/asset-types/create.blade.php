@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto max-w-screen-md md:p-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('asset-types.index') }}" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Asset Types</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium">New Type</span>
    </nav>

    <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 class="text-lg font-semibold dark:text-white mb-4">Create Asset Type</h3>
        <form action="{{ route('asset-types.store') }}" method="POST">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Type Name</label>
                <input type="text" name="name" placeholder="e.g. Video, PDF, Image" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('asset-types.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-500 text-white px-5 py-2.5 rounded-lg text-sm font-medium">Save Type</button>
            </div>
        </form>
    </div>
</div>
@endsection