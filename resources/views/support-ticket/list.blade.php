@extends('layouts.app')

@section('content')
    <div class="p-4 mx-auto w-full  md:p-6">

        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Support Tickets</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage all user support tickets</p>
            </div>
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
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Tickets</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $tickets->total() }} total tickets</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <form method="GET" action="{{ route('ticket.admin') }}" id="filterForm" class="flex gap-2">
                        <select name="status" onchange="document.getElementById('filterForm').submit()"
                            class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 shadow-theme-xs focus:border-blue-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">All Status</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Open</option>
                            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </form>
                    <form method="GET" action="{{ route('ticket.admin') }}">
                        @if (request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
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
                                placeholder="Search tickets..."
                                class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[240px]" />
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-hidden">
                <div class="max-w-full px-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-gray-200 border-y dark:border-gray-700">
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                    Ticket</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                    User</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                    Status</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                    Replies</th>
                                <th class="px-4 py-3 font-normal text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                    Date</th>
                                <th class="relative px-4 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">

                                    {{-- Ticket --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                                <svg class="text-blue-500" width="18" height="18" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $ticket->subject }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">#{{ $ticket->id }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- User --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->user->name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $ticket->user->email }}</p>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-medium
                                                bg-{{ $ticket->status_color }}-50 text-{{ $ticket->status_color }}-700
                                                dark:bg-{{ $ticket->status_color }}-900/30 dark:text-{{ $ticket->status_color }}-400 border border-{{ $ticket->status_color }}-100 dark:border-{{ $ticket->status_color }}-800/50">
                                            {{ $ticket->status_label }}
                                        </span>
                                    </td>

                                    {{-- Replies --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->replies_count }}
                                            replies</span>
                                    </td>

                                    {{-- Date --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->created_at->format('d M Y') }}</span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">

                                            {{-- View --}}
                                            <a href="{{ route('admin.tickets.show', $ticket) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/10 transition-colors">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <button type="button"
                                                @click="openDelete({{ $ticket->id }}, '{{ addslashes($ticket->subject) }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-900/20 transition-colors">
                                                <svg width="16" height="16" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                                                </svg>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div
                                                class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                <svg class="text-gray-400" width="28" height="28"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">No tickets
                                                    found</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">No support
                                                    tickets have been submitted yet.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @include('partials.pagination', ['items' => $tickets])

            {{-- Delete Modal (same as asset) --}}
            <div x-show="deleteModal" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div @click.outside="deleteModal = false"
                    class="bg-white dark:bg-gray-900 rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                            <svg class="text-red-500" width="22" height="22" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white">Delete Ticket</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">This action cannot be undone.</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Are you sure you want to delete <span class="font-semibold text-gray-800 dark:text-white"
                            x-text="'#' + deleteId + ' - ' + deleteTitle"></span>?
                    </p>
                    <div class="flex items-center justify-end gap-3">
                        <button @click="deleteModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/10 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <form :action="'{{ route('ticket.admin') }}/' + deleteId" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
