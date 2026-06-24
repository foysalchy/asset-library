@extends('layouts.app')

@section('content')
    <div class="p-4 mx-auto w-full md:p-6">

        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Assets</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage all project assets</p>
            </div>
            <a href="{{ route('assets.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10 3.25C10.4142 3.25 10.75 3.58579 10.75 4V9.25H16C16.4142 9.25 16.75 9.58579 16.75 10C16.75 10.4142 16.4142 10.75 16 10.75H10.75V16C10.75 16.4142 10.4142 16.75 10 16.75C9.58579 16.75 9.25 16.4142 9.25 16V10.75H4C3.58579 10.75 3.25 10.4142 3.25 10C3.25 9.58579 3.58579 9.25 4 9.25H9.25V4C9.25 3.58579 9.58579 3.25 10 3.25Z" />
                </svg>
                New Asset
            </a>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 dark:border-green-800 dark:bg-green-900/20">
                <svg class="shrink-0 text-green-500" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                </svg>
                <p class="text-sm font-medium text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        <div class="rounded-xl border border-gray-100 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]"
            x-data="{
                deleteModal: false,
                deleteId: null,
                deleteTitle: '',
                openDelete(id, title) { 
                    this.deleteId = id;
                    this.deleteTitle = title;
                    this.deleteModal = true; 
                }
            }">

            {{-- Filters --}}
            <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Assets</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $assets->total() }} total assets</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <form method="GET" action="{{ route('assets.index') }}" id="filterForm" class="flex gap-2">
                        <select name="project_id" onchange="document.getElementById('filterForm').submit()"
                            class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 shadow-theme-xs focus:border-blue-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">All Projects</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="asset_type_id" onchange="document.getElementById('filterForm').submit()"
                            class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 shadow-theme-xs focus:border-blue-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">All Types</option>
                            @foreach ($assetTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('asset_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </form>
                    <form method="GET" action="{{ route('assets.index') }}">
                        @if (request('project_id'))
                            <input type="hidden" name="project_id" value="{{ request('project_id') }}">
                        @endif
                        @if (request('asset_type_id'))
                            <input type="hidden" name="asset_type_id" value="{{ request('asset_type_id') }}">
                        @endif
                        <div class="relative">
                            <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
                                <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" />
                                </svg>
                            </button>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search assets..."
                                class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[240px]" />
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-hidden" x-data="dragDropSort()">
                <div class="max-w-full px-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-gray-200 border-y dark:border-gray-700">
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400 w-6">
                                    <svg class="text-gray-400" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM8 12a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM3.5 15a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                        <path d="M15 5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 12a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM10.5 15a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                    </svg>
                                </th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Asset</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Project</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Type</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Media</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">File</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">Download</th>
                                <th class="relative px-4 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700" 
                               @drop="handleDrop($event)" 
                               @dragover.prevent 
                               @dragenter.prevent 
                               x-ref="tableBody">
                            @forelse($assets as $asset)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors cursor-move group" 
                                    draggable="true"
                                    @dragstart="handleDragStart($event, {{ $asset->id }})"
                                    @dragend="handleDragEnd($event)"
                                    @dragover.prevent="handleDragOver($event)"
                                    data-asset-id="{{ $asset->id }}"
                                    x-ref="row_{{ $asset->id }}">
                                    <td class="px-4 py-4 whitespace-nowrap opacity-50 group-hover:opacity-100">
                                        <svg class="text-gray-400 cursor-grab active:cursor-grabbing" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M8 5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM8 12a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM3.5 15a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                            <path d="M15 5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 12a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM10.5 15a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                        </svg>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if ($asset->media->first()?->media_type === 'image')
                                                <img src="{{ $asset->media->first()->url }}" alt="{{ $asset->title }}"
                                                    class="w-10 h-10 rounded-lg object-cover shrink-0 border border-gray-200 dark:border-gray-700">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center shrink-0">
                                                    <svg class="text-purple-500" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $asset->title }}</p>
                                                @if ($asset->asset_id_code)
                                                    <p class="text-xs text-gray-400 mt-0.5">{{ $asset->asset_id_code }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $asset->project->name }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                            {{ $asset->assetType->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $asset->media->count() }} file(s)</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if ($asset->file_path)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $asset->file_size_formatted }}</span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $asset->getTotalDownloadsAttribute() }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            @permission('assets.view')
                                                <a href="{{ route('assets.show', $asset) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/10 transition-colors">
                                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                </a>
                                            @endpermission

                                            @permission('assets.edit')
                                                <a href="{{ route('assets.edit', $asset) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-blue-50 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-blue-900/20 transition-colors">
                                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                            @endpermission

                                            @permission('assets.delete')
                                                <button type="button"
                                                    @click="$dispatch('open-delete-modal', {
                                                        url: '{{ route('assets.destroy', $asset->id) }}',
                                                        title: '{{ addslashes($asset->title) }}'
                                                    })"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-900/20 transition-colors">
                                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                                                    </svg>
                                                </button>
                                            @endpermission
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                <svg class="text-gray-400" width="28" height="28" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">No assets found</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Get started by creating a new asset.</p>
                                            </div>
                                            <a href="{{ route('assets.create') }}"
                                                class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 transition-colors">
                                                Create Asset
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @include('partials.pagination', ['items' => $assets])

        </div>
    </div>

    <script>
        function dragDropSort() {
            return {
                draggedElement: null,
                draggedId: null,
                isSorting: false,

                handleDragStart(event, assetId) {
                    if (this.isSorting) return;
                    
                    this.draggedElement = event.target.closest('tr');
                    this.draggedId = assetId;
                    
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', assetId);
                    
                    this.draggedElement.classList.add('opacity-40', 'bg-blue-50', 'dark:bg-blue-900/20');
                },

                handleDragEnd(event) {
                    if (this.draggedElement) {
                        this.draggedElement.classList.remove('opacity-40', 'bg-blue-50', 'dark:bg-blue-900/20');
                    }
                    this.draggedElement = null;
                    this.draggedId = null;
                },

                handleDragOver(event) {
                    event.preventDefault();
                    if (!this.draggedElement) return;

                    const targetRow = event.target.closest('tbody tr');
                    if (!targetRow || targetRow === this.draggedElement) return;

                    const tbody = this.$refs.tableBody;
                    const rect = targetRow.getBoundingClientRect();
                    
                    // চেক করা হচ্ছে কার্সরের পজিশন টার্গেট রো-এর মাঝামাঝি অংশ অতিক্রম করেছে কিনা
                    const next = (event.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;

                    tbody.insertBefore(this.draggedElement, next ? targetRow.nextSibling : targetRow);
                },

                handleDrop(event) {
                    event.preventDefault();
                    if (!this.draggedId) return;

                    this.updateSortOrder();
                },

                updateSortOrder() {
                    this.isSorting = true;
                    const rows = document.querySelectorAll('tbody tr[data-asset-id]');
                    const updates = [];

                    rows.forEach((row, index) => {
                        const assetId = row.getAttribute('data-asset-id');
                        if (assetId) {
                            updates.push({
                                id: parseInt(assetId),
                                sort_order: index
                            });
                        }
                    });

                    fetch('{{ route("assets.sort") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ assets: updates })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Sort updated successfully:', data);
                    })
                    .catch(error => {
                        console.error('Error updating sort:', error);
                        window.location.reload();
                    })
                    .finally(() => {
                        this.isSorting = false;
                    });
                }
            }
        }
    </script>
@endsection