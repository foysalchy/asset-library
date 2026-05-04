@extends('layouts.app')
@section('content')
<div class="p-4 mx-auto w-full  md:p-6">

    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('roles.index') }}" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Roles</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium">Create New</span>
    </nav>

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Create Role</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Define a new role and assign permissions</p>
    </div>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Left --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Name & Label --}}
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] space-y-5">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Role Info</h3>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Label <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="label" value="{{ old('label') }}"
                               placeholder="e.g. Content Editor"
                               class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('label') border-red-400 @enderror" />
                        @error('label')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Name <span class="text-red-500">*</span>
                            <span class="text-xs font-normal text-gray-400 ml-1">lowercase, underscore only</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               placeholder="e.g. content_editor"
                               class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('name') border-red-400 @enderror" />
                        @error('name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Permissions --}}
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Permissions</h3>
                        <button type="button" onclick="toggleAll()"
                                class="text-xs text-blue-500 hover:underline" id="toggle-all-btn">
                            Select All
                        </button>
                    </div>

                    <div class="space-y-6">
                        @foreach($permissions as $group => $groupPermissions)
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ $group }}
                                    </h4>
                                    <button type="button"
                                            onclick="toggleGroup('{{ Str::slug($group) }}')"
                                            class="text-xs text-gray-400 hover:text-blue-500 transition-colors">
                                        Select group
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" data-group="{{ Str::slug($group) }}">
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-center gap-2.5 rounded-lg border border-gray-200 bg-gray-50/50 p-3 cursor-pointer hover:border-blue-300 dark:border-gray-700 dark:bg-gray-800/40 transition-colors has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 dark:has-[:checked]:border-blue-700">
                                            <input type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                                   class="permission-checkbox w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500 dark:border-gray-600">
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 capitalize">
                                                {{ explode('.', $permission->name)[1] }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right: summary --}}
            <div>
                <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sticky top-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Summary</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Selected permissions will be assigned to this role. Users with this role will inherit all selected permissions.
                    </p>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Selected: <span id="selected-count" class="font-semibold text-gray-700 dark:text-gray-300">0</span> permissions
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex items-center justify-end gap-3 mt-5">
            <a href="{{ route('roles.index') }}"
               class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                Create Role
            </button>
        </div>
    </form>
</div>

<script>
const checkboxes = () => document.querySelectorAll('.permission-checkbox');

function updateCount() {
    const count = document.querySelectorAll('.permission-checkbox:checked').length;
    document.getElementById('selected-count').textContent = count;
}

let allSelected = false;
function toggleAll() {
    allSelected = !allSelected;
    checkboxes().forEach(cb => cb.checked = allSelected);
    document.getElementById('toggle-all-btn').textContent = allSelected ? 'Deselect All' : 'Select All';
    updateCount();
}

function toggleGroup(group) {
    const group_checkboxes = document.querySelectorAll(`[data-group="${group}"] .permission-checkbox`);
    const allChecked = Array.from(group_checkboxes).every(cb => cb.checked);
    group_checkboxes.forEach(cb => cb.checked = !allChecked);
    updateCount();
}

checkboxes().forEach(cb => cb.addEventListener('change', updateCount));
document.addEventListener('DOMContentLoaded', updateCount);
</script>
@endsection