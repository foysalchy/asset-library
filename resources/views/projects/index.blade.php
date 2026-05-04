@extends('layouts.app')
@section('content')
<div class="p-4 mx-auto w-full  md:p-6">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold dark:text-white">Projects</h1>
        <a href="{{ route('projects.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium">New Project</a>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-sm font-medium text-gray-500">Project</th>
                        <th class="px-5 py-3 text-sm font-medium text-gray-500">Concern</th>
                        <th class="px-5 py-3 text-sm font-medium text-gray-500">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($projects as $project)
                    <tr>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $project->logo ? asset('storage/'.$project->logo) : asset('placeholder.png') }}" class="w-10 h-10 rounded object-cover">
                                <span class="font-medium dark:text-white">{{ $project->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 dark:text-gray-400">{{ $project->concern }}</td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 rounded-full text-xs {{ $project->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">

                                @permission('projects.edit')
                                <a href="{{ route('projects.edit', $project) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-blue-50 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-blue-900/20 transition-colors">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                @endpermission

                                @permission('projects.delete')
                                <button type="button"
                                    @click="$dispatch('open-delete-modal', { 
                url: '{{ route('projects.destroy', $project->id) }}', 
                title: '{{ addslashes($project->name) }}' 
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
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('partials.pagination', ['items' => $projects])
    </div>
</div>
@endsection