@php $isEdit = isset($project); @endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-5">
        {{-- Name --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Project Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $isEdit ? $project->name : '') }}" 
                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
            @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Logo Upload (Alpine.js logic same as Campaign) --}}
        <div x-data="{ preview: '{{ $isEdit && $project->logo ? $project->logoUrl : '' }}', removed: false }">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Logo</label>
            <input type="hidden" name="remove_logo" :value="removed ? '1' : '0'">
            
            <div @click="$refs.logoInput.click()" class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-4 cursor-pointer hover:border-blue-400 dark:border-gray-700 dark:bg-gray-800/40">
                <template x-if="preview">
                    <div class="relative">
                        <img :src="preview" class="h-32 w-32 object-contain rounded-lg">
                        <button type="button" @click.stop="preview=''; removed=true" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1">
                            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                        </button>
                    </div>
                </template>
                <template x-if="!preview">
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Click to upload logo</p>
                    </div>
                </template>
            </div>
            <input type="file" x-ref="logoInput" name="logo" class="hidden" @change="const file = $event.target.files[0]; if(file){ removed=false; const reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(file); }">
        </div>
    </div>

    <div class="space-y-5">
        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/40 space-y-4">
            {{-- Concern --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Concern</label>
                <select name="concern" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    @foreach(\App\Models\Project::CONCERNS as $key => $label)
                        <option value="{{ $key }}" {{ old('concern', $isEdit ? $project->concern : '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="active" {{ old('status', $isEdit ? $project->status : '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $isEdit ? $project->status : '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>
</div>