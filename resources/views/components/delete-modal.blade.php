{{-- resources/views/components/delete-modal.blade.php --}}
<div x-data="{ 
        show: false, 
        url: '', 
        title: '',
        openModal(event) {
            this.url = event.detail.url;
            this.title = event.detail.title;
            this.show = true;
        }
    }" 
    @open-delete-modal.window="openModal($event)"
    x-show="show" 
    style="display:none;"
    class="fixed inset-0 z-50 flex items-center justify-center p-4">
    
    <!-- Backdrop -->
    <div x-show="show" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
    
    <!-- Modal -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-gray-900">
        
        <div class="flex flex-col items-center text-center gap-4">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center dark:bg-red-900/30">
                <svg class="text-red-500" width="28" height="28" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Confirm Deletion</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Are you sure you want to delete <span class="font-bold text-gray-700 dark:text-gray-300" x-text="`'${title}'`"></span>?
                    <br>This action cannot be undone.
                </p>
            </div>
            <div class="flex gap-3 w-full mt-2">
                <button type="button" @click="show = false"
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
                    Cancel
                </button>
                
                <!-- Dynamic Form -->
                <form :action="url" method="POST" class="flex-1">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>