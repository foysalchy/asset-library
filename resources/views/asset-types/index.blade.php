@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto max-w-screen-xl md:p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Asset Types</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage categories for your media assets</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Side: Add Form --}}
        <div class="h-fit rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Create New Type</h3>

            <form action="{{ route('asset-types.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
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
                            <input type="text" name="name" id="name" required
                                placeholder="e.g. Video, Image, PDF"
                                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-300 focus:ring-3 focus:ring-blue-500/10 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>
                        @error('name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10 3.25C10.4142 3.25 10.75 3.58579 10.75 4V9.25H16C16.4142 9.25 16.75 9.58579 16.75 10C16.75 10.4142 16.4142 10.75 16 10.75H10.75V16C10.75 16.4142 10.4142 16.75 10 16.75C9.58579 16.75 9.25 16.4142 9.25 16V10.75H4C3.58579 10.75 3.25 10.4142 3.25 10C3.25 9.58579 3.58579 9.25 4 9.25H9.25V4C9.25 3.58579 9.58579 3.25 10 3.25Z" fill="currentColor" />
                        </svg>
                        Add Asset Type
                    </button>
                </div>
            </form>
        </div>

        {{-- Right Side: List Table --}}
        <div class="lg:col-span-2 rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50/50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th scope="col" class="px-5 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Name</th>
                        <th scope="col" class="px-5 py-3 text-end text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($assetTypes as $type)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $type->name }}</span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Edit --}}
                                <a href="{{ route('asset-types.edit', $type) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-blue-50 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-blue-900/20 transition-colors">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                <button type="button"
                                    @click="$dispatch('open-delete-modal', { 
                url: '{{ route('asset-types.destroy', $type->id) }}', 
                title: '{{ addslashes($type->name) }}' 
            })"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-900/20 transition-colors">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No asset types found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @include('partials.pagination', ['items' => $assetTypes])
        </div>
    </div>
</div>
@endsection