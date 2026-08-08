
<?php $__env->startSection('content'); ?>
<div class="p-4 mx-auto w-full md:p-6">


    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Download Report</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                <?php echo e($startDate->format('d M Y')); ?> — <?php echo e($endDate->format('d M Y')); ?>

            </p>
        </div>

        
        <a href="<?php echo e(route('reports.downloads.pdf', request()->query())); ?>"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition-colors">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" />
            </svg>
            Download PDF
        </a>
    </div>

    
    <div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4 mb-5">
        <form method="GET" action="<?php echo e(route('reports.downloads')); ?>" id="filterForm">
            <div class="flex flex-wrap gap-2 mb-4">
                <?php
                $filters = [
                'today' => 'Today',
                'yesterday' => 'Yesterday',
                'last_7_days' => 'Last 7 Days',
                'this_month' => 'This Month',
                'custom' => 'Custom Range',
                ];
                ?>
                <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="submit" name="filter" value="<?php echo e($key); ?>"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                <?php echo e($filter === $key
                    ? 'bg-blue-500 text-white'
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700'); ?>">
                    <?php echo e($label); ?>

                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="flex flex-wrap items-center gap-3 <?php echo e($filter !== 'custom' ? 'hidden' : ''); ?>" id="customRangeBox">
                
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">From</label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from', $startDate->format('Y-m-d'))); ?>"
                        class="h-10 rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">To</label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to', $endDate->format('Y-m-d'))); ?>"
                        class="h-10 rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <button type="submit" name="filter" value="custom"
                    class="h-10 mt-5 px-4 rounded-lg bg-blue-500 text-white text-sm font-medium hover:bg-blue-600">
                    Apply
                </button>
            </div>

            
            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                
                <?php if($filter === 'custom'): ?>
                <input type="hidden" name="date_from" value="<?php echo e(request('date_from')); ?>">
                <input type="hidden" name="date_to" value="<?php echo e(request('date_to')); ?>">
                <?php endif; ?>


             <div class="relative" x-data="{
        open: false,
        search: '<?php echo e(optional($users->firstWhere('id', request('user_id')))->name ?? ''); ?>',
        selectedId: '<?php echo e(request('user_id')); ?>',
        users: <?php echo e($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email])->toJson()); ?>,

        get filteredUsers() {
            if (!this.search) return this.users;
            return this.users.filter(u =>
                u.name.toLowerCase().includes(this.search.toLowerCase()) ||
                u.email.toLowerCase().includes(this.search.toLowerCase())
            );
        },

        selectUser(user) {
            this.search = user.name;
            this.selectedId = user.id;
            this.open = false;
            document.getElementById('userIdInput').value = user.id;
            document.getElementById('filterForm').submit();
        },

        clearSelection() {
            this.search = '';
            this.selectedId = '';
            document.getElementById('userIdInput').value = '';
            document.getElementById('filterForm').submit();
        }
     }"
     @click.outside="open = false">

    <input type="text" x-model="search" @focus="open = true" @input="open = true"
        placeholder="Search & select user..."
        autocomplete="off"
        class="h-10 rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 w-80">

    <input type="hidden" name="user_id" id="userIdInput" :value="selectedId">

    <div x-show="open" x-cloak
        class="absolute z-50 mt-1 w-80 max-h-60 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900">

        <template x-if="filteredUsers.length === 0">
            <p class="px-3 py-2 text-sm text-gray-400">No users found.</p>
        </template>

        <template x-for="user in filteredUsers" :key="user.id">
            <div @click="selectUser(user)"
                class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300">
                <p class="font-medium" x-text="user.name"></p>
                <p class="text-xs text-gray-400" x-text="user.email"></p>
            </div>
        </template>

        <template x-if="selectedId">
            <div @click="clearSelection()"
                class="px-3 py-2 text-sm cursor-pointer text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 border-t border-gray-100 dark:border-gray-800">
                Clear selection
            </div>
        </template>
    </div>
</div>
                
                <input type="hidden" name="current_filter" value="<?php echo e($filter); ?>">

                <a href="<?php echo e(route('reports.downloads', ['filter' => $filter])); ?>"
                    class="h-10 flex items-center px-4 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    Reset Filter
                </a>

                <?php if(request()->anyFilled(['search', 'model'])): ?>
                <a href="<?php echo e(route('reports.downloads', ['filter' => $filter])); ?>"
                    class="h-10 flex items-center px-3 text-sm text-red-500 hover:underline">
                    Clear
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>


    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-center w-12 h-12 bg-blue-50 rounded-xl dark:bg-blue-900/20">
                <svg class="text-blue-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16" />
                </svg>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Downloads</span>
                <h4 class="mt-1 font-bold text-gray-800 text-2xl dark:text-white/90"><?php echo e($totalDownloads); ?></h4>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-center w-12 h-12 bg-purple-50 rounded-xl dark:bg-purple-900/20">
                <svg class="text-purple-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                </svg>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">Unique Users</span>
                <h4 class="mt-1 font-bold text-gray-800 text-2xl dark:text-white/90"><?php echo e($uniqueUsers); ?></h4>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-center w-12 h-12 bg-green-50 rounded-xl dark:bg-green-900/20">
                <svg class="text-green-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <path d="M9 9h6v6H9z" />
                </svg>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">Unique Assets</span>
                <h4 class="mt-1 font-bold text-gray-800 text-2xl dark:text-white/90"><?php echo e($uniqueAssets); ?></h4>
            </div>
        </div>
    </div>

    
    <div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-gray-500 dark:text-gray-400">
                        <th class="py-3 px-4">User</th>
                        <th class="py-3 px-4">File Name</th>
                        <th class="py-3 px-4">Count</th>
                        <th class="py-3 px-4">IP Address</th>
                        <th class="py-3 px-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <?php if($log->user?->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $log->user->avatar)); ?>" class="w-7 h-7 rounded-full object-cover">
                                <?php else: ?>
                                <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xs font-semibold">
                                    <?php echo e(strtoupper(substr($log->user->name ?? '?', 0, 1))); ?>

                                </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white/90"><?php echo e($log->user->name ?? 'Unknown'); ?></p>
                                    <p class="text-xs text-gray-400"><?php echo e($log->user->email ?? ''); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-700 dark:text-gray-300"><?php echo e($log->display_name); ?></td>
                        <td class="py-3 px-4 font-semibold text-blue-600 dark:text-blue-400"><?php echo e($log->count); ?></td>
                        <td class="py-3 px-4 text-gray-400 text-xs"><?php echo e($log->ip_address ?? '—'); ?></td>
                        <td class="py-3 px-4 text-gray-400 text-xs"><?php echo e($log->updated_at->format('d M Y, h:i A')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-400">No downloads found for this period.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        <?php echo e($logs->links()); ?>

    </div>

</div>

<script>
    document.querySelectorAll('button[name="filter"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.value !== 'custom') {
                document.getElementById('customRangeBox')?.classList.add('hidden');
            }
        });
    });

    function submitWithCurrentFilter() {
        const form = document.getElementById('filterForm');
        const filterInput = document.createElement('input');
        filterInput.type = 'hidden';
        filterInput.name = 'filter';
        filterInput.value = form.querySelector('[name="current_filter"]').value;
        form.appendChild(filterInput);
        form.submit();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userSearchInput = document.getElementById('userSearchInput');
        const userIdInput = document.getElementById('userIdInput');
        const userList = document.getElementById('userList');

        const usersMap = {};
        Array.from(userList.options).forEach(opt => {
            usersMap[opt.value] = opt.dataset.id;
        });

        userSearchInput.addEventListener('input', function() {
            const matchedId = usersMap[this.value];

            if (matchedId) {
                userIdInput.value = matchedId;
                document.getElementById('filterForm').submit(); // ✅ user select korার সাথে সাথেই filter apply
            } else {
                userIdInput.value = '';
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views/reports/downloads.blade.php ENDPATH**/ ?>