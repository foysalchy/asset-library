@extends('layouts.app')
@section('content')
<div class="p-4 mx-auto max-w-screen-2xl md:p-6">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold dark:text-white">Projects</h1>
        <a href="{{ route('projects.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium">New Project</a>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
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
                        <td class="px-5 py-4 text-right flex justify-end gap-2">
                            <a href="{{ route('projects.edit', $project) }}" class="text-blue-500 hover:underline">Edit</a>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-red-500">Delete</button>
                            </form>
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

