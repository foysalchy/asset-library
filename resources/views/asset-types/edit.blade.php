@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto max-w-screen-md md:p-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('asset-types.index') }}" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Asset Types</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium">Edit Type</span>
    </nav>

    <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 class="text-lg font-semibold dark:text-white mb-6">Edit Asset Type</h3>
        
        <form action="{{ route('asset-types.update', $assetType) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Type Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 dark:border-gray-800 text-gray-500">
                             <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path d="M7 2a2 2 0 00-2 2v1H3a1 1 0 000 2h1v10a2 2 0 002 2h8a2 2 0 002-2V7h1a1 1 0 100-2h-2V4a2 2 0 00-2-2H7zM7 4h6v1H7V4zm1 5a1 1 0 112 0v5a1 1 0 11-2 0V9zm5 0a1 1 0 10-2 0v5a1 1 0 102 0V9z" fill="currentColor" />
                            </svg>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name', $assetType->name) }}" required
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700 pt-5">
                    <a href="{{ route('asset-types.index') }}" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-600 transition-colors">
                        Update Asset Type
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection