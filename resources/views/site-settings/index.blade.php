@extends('layouts.app')
@section('content')
<div class="p-4 mx-auto w-full  md:p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Site Settings</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage your site identity</p>
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

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ── Left ──────────────────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Basic Info --}}
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] space-y-5">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Basic Info</h3>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Site Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="site_name"
                            value="{{ old('site_name', $setting->site_name) }}"
                            placeholder="e.g. My Awesome App"
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('site_name') border-red-400 @enderror" />
                        @error('site_name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Slogan
                        </label>
                        <input type="text" name="slogan"
                            value="{{ old('slogan', $setting->slogan) }}"
                            placeholder="e.g. Build something great"
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('slogan') border-red-400 @enderror" />
                        @error('slogan')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Logo --}}
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
                    x-data="{
                        preview: '{{ $setting->logo_url }}',
                        removed: false,
                        handle(e) {
                            const f = e.target.files[0]; if (!f) return;
                            this.removed = false;
                            const r = new FileReader();
                            r.onload = ev => this.preview = ev.target.result;
                            r.readAsDataURL(f);
                        },
                        remove() { this.preview=''; this.removed=true; document.getElementById('logo_input').value=''; }
                     }">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Logo</h3>
                    <input type="hidden" name="remove_logo" :value="removed ? '1' : '0'">

                    <div class="flex items-center gap-5">
                        {{-- Preview --}}
                        <div class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center bg-gray-50 dark:bg-gray-800 overflow-hidden shrink-0">
                            <template x-if="preview">
                                <img :src="preview" alt="Logo" class="w-full h-full object-contain p-2">
                            </template>
                            <template x-if="!preview">
                                <svg class="text-gray-300 dark:text-gray-600" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <path d="M21 15l-5-5L5 21" />
                                </svg>
                            </template>
                        </div>

                        <div class="space-y-2">
                            <button type="button" @click="$refs.logoInput.click()"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12" />
                                </svg>
                                Upload Logo
                            </button>
                            <template x-if="preview">
                                <button type="button" @click="remove()"
                                    class="flex items-center gap-1.5 text-xs text-red-500 hover:text-red-600 transition-colors">
                                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                                    </svg>
                                    Remove
                                </button>
                            </template>
                            <p class="text-xs text-gray-400">JPG, PNG, WEBP, SVG · max 2MB</p>
                        </div>
                    </div>
                    <input type="file" id="logo_input" x-ref="logoInput" name="logo"
                        accept="image/*" class="hidden" @change="handle($event)">
                    @error('logo')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Favicon --}}
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
                    x-data="{
                        preview: '{{ $setting->favicon_url }}',
                        removed: false,
                        handle(e) {
                            const f = e.target.files[0]; if (!f) return;
                            this.removed = false;
                            const r = new FileReader();
                            r.onload = ev => this.preview = ev.target.result;
                            r.readAsDataURL(f);
                        },
                        remove() { this.preview=''; this.removed=true; document.getElementById('favicon_input').value=''; }
                     }">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Favicon</h3>
                    <input type="hidden" name="remove_favicon" :value="removed ? '1' : '0'">

                    <div class="flex items-center gap-5">
                        {{-- Preview --}}
                        <div class="w-16 h-16 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center bg-gray-50 dark:bg-gray-800 overflow-hidden shrink-0">
                            <template x-if="preview">
                                <img :src="preview" alt="Favicon" class="w-full h-full object-contain p-1.5">
                            </template>
                            <template x-if="!preview">
                                <svg class="text-gray-300 dark:text-gray-600" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <path d="M9 9h6M9 12h6M9 15h4" />
                                </svg>
                            </template>
                        </div>

                        <div class="space-y-2">
                            <button type="button" @click="$refs.faviconInput.click()"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12" />
                                </svg>
                                Upload Favicon
                            </button>
                            <template x-if="preview">
                                <button type="button" @click="remove()"
                                    class="flex items-center gap-1.5 text-xs text-red-500 hover:text-red-600 transition-colors">
                                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                                    </svg>
                                    Remove
                                </button>
                            </template>
                            <p class="text-xs text-gray-400">JPG, PNG, WEBP, ICO · max 512KB · recommended 32×32</p>
                        </div>
                    </div>
                    <input type="file" id="favicon_input" x-ref="faviconInput" name="favicon"
                        accept="image/*,.ico" class="hidden" @change="handle($event)">
                    @error('favicon')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

            </div>

            {{-- ── Right: Preview ────────────────────────────────── --}}
            <div class="space-y-5">
                <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sticky top-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Current Settings</h3>
                    <div class="space-y-4 text-sm">

                        {{-- Logo preview --}}
                        <div>
                            <p class="text-xs text-gray-400 mb-2">Logo</p>
                            @if($setting->logo_url)
                            <div class="w-full h-20 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-center p-3">
                                <img src="{{ $setting->logo_url }}" alt="Logo" class="max-h-full max-w-full object-contain">
                            </div>
                            @else
                            <p class="text-xs text-gray-300 dark:text-gray-600 italic">No logo uploaded</p>
                            @endif
                        </div>

                        {{-- Favicon preview --}}
                        <div>
                            <p class="text-xs text-gray-400 mb-2">Favicon</p>
                            @if($setting->favicon_url)
                            <div class="w-10 h-10 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-center p-1.5">
                                <img src="{{ $setting->favicon_url }}" alt="Favicon" class="max-h-full max-w-full object-contain">
                            </div>
                            @else
                            <p class="text-xs text-gray-300 dark:text-gray-600 italic">No favicon uploaded</p>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-gray-100 dark:border-gray-700 space-y-2">
                            <div class="flex justify-between gap-2">
                                <span class="text-gray-400">Site name</span>
                                <span class="font-medium text-gray-700 dark:text-gray-300 text-right">{{ $setting->site_name }}</span>
                            </div>
                            @if($setting->slogan)
                            <div class="flex justify-between gap-2">
                                <span class="text-gray-400">Slogan</span>
                                <span class="font-medium text-gray-700 dark:text-gray-300 text-right">{{ $setting->slogan }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between gap-2">
                                <span class="text-gray-400">Updated</span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $setting->updated_at->diffForHumans()  }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Save --}}
        <div class="flex items-center justify-end mt-5">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 transition-colors">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

@endsection
