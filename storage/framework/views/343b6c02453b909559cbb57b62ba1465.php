<?php $__env->startSection('content'); ?>
<div class="p-4 mx-auto w-full  md:p-6">

    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="<?php echo e(route('users.index')); ?>" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Users</a>
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
        </svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium"><?php echo e($user->name); ?></span>
    </nav>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        
        <div class="space-y-5">
            <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-col items-center text-center gap-4">
                <img src="<?php echo e($user->avatar_url); ?>" alt="<?php echo e($user->name); ?>"
                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                <div>
                    <div class="flex items-center justify-center gap-2 flex-wrap">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?php echo e($user->name); ?></h2>
                        <?php if($user->isSuperAdmin()): ?>
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                            Super Admin
                        </span>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5"><?php echo e($user->email); ?></p>
                    <?php if($user->phone): ?>
                    <p class="text-sm text-gray-400 mt-0.5"><?php echo e($user->phone); ?></p>
                    <?php endif; ?>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold capitalize <?php echo e($user->status_badge_class); ?>">
                    <?php echo e($user->status); ?>

                </span>
                <?php if(auth()->check() && auth()->user()->hasPermission('users.edit')): ?>
                <a href="<?php echo e(route('users.edit', $user)); ?>"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit User
                </a>
                <?php endif; ?>
            </div>

            
            <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] space-y-3 text-sm">

                <div class="flex justify-between gap-2">
                    <span class="text-gray-500 dark:text-gray-400">Joining Date</span>
                    <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo e($user->created_at->format('M d, Y')); ?></span>
                </div>
                <?php if($user->creator): ?>
                <div class="flex justify-between gap-2">
                    <span class="text-gray-500 dark:text-gray-400">Created by</span>
                    <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo e($user->creator->name); ?></span>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Roles</h3>
                <div class="flex flex-wrap gap-2">
                    <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <span class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                        <?php echo e($role->label); ?>

                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-400">No roles assigned</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2 space-y-5">

            
            <?php if(!$user->isSuperAdmin() && $user->roles->count()): ?>
            <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Permissions</h3>
                <?php
                $groupedPermissions = $user->roles
                ->flatMap(fn($r) => $r->permissions)
                ->unique('id')
                ->groupBy('group');
                ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $groupedPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><?php echo e($group); ?></p>
                        <div class="flex flex-wrap gap-1.5">
                            <?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">
                                <?php echo e(explode('.', $perm->name)[1]); ?>

                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php elseif($user->isSuperAdmin()): ?>
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-800 dark:bg-amber-900/10">
                <p class="text-sm font-medium text-amber-700 dark:text-amber-400">
                    Super admin has access to all permissions.
                </p>
            </div>
            <?php endif; ?>

            
            
            <?php if(auth()->check() && auth()->user()->hasPermission('activity_logs.view')): ?>
            <div
                class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]"
                x-data="{
        offset: <?php echo e($downloadLogs->count()); ?>,
        hasMore: <?php echo e($downloadLogsTotal > $downloadLogs->count() ? 'true' : 'false'); ?>,
        loading: false,
        loadMore() {
            this.loading = true;
            fetch(`<?php echo e(route('users.download-logs.more', $user)); ?>?offset=${this.offset}`)
                .then(res => res.json())
                .then(data => {
                    this.$refs.downloadRows.insertAdjacentHTML('beforeend', data.html);
                    this.offset += <?php echo e(\App\Http\Controllers\UserController::LOGS_PER_PAGE ?? 10); ?>;
                    this.hasMore = data.hasMore;
                    this.loading = false;
                });
        }
    }">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Download Log</h3>

                <?php if($downloadLogs->count()): ?>
                <div class="space-y-3" x-ref="downloadRows">
                    <?php echo $__env->make('users.partials.download-log-rows', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <div class="mt-4 text-center" x-show="hasMore" x-cloak>
                    <button
                        type="button"
                        @click="loadMore()"
                        :disabled="loading"
                        class="text-sm font-medium text-blue-600 hover:text-blue-700 disabled:opacity-50 dark:text-blue-400">
                        <span x-show="!loading">Load More</span>
                        <span x-show="loading">Loading...</span>
                    </button>
                </div>
                <?php else: ?>
                <p class="text-sm text-gray-400">No downloads recorded yet.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <?php if(auth()->check() && auth()->user()->hasPermission('activity_logs.view')): ?>
            <div
                class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]"
                x-data="{
        offset: <?php echo e($logs->count()); ?>,
        hasMore: <?php echo e($logsTotal > $logs->count() ? 'true' : 'false'); ?>,
        loading: false,
        loadMore() {
            this.loading = true;
            fetch(`<?php echo e(route('users.activity-logs.more', $user)); ?>?offset=${this.offset}`)
                .then(res => res.json())
                .then(data => {
                    this.$refs.activityRows.insertAdjacentHTML('beforeend', data.html);
                    this.offset += <?php echo e(\App\Http\Controllers\UserController::LOGS_PER_PAGE ?? 10); ?>;
                    this.hasMore = data.hasMore;
                    this.loading = false;
                });
        }
    }">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Recent Activity</h3>

                <?php if($logs->count()): ?>
                <div class="space-y-3" x-ref="activityRows">
                    <?php echo $__env->make('users.partials.activity-log-rows', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <div class="mt-4 text-center" x-show="hasMore" x-cloak>
                    <button
                        type="button"
                        @click="loadMore()"
                        :disabled="loading"
                        class="text-sm font-medium text-blue-600 hover:text-blue-700 disabled:opacity-50 dark:text-blue-400">
                        <span x-show="!loading">Load More</span>
                        <span x-show="loading">Loading...</span>
                    </button>
                </div>
                <?php else: ?>
                <p class="text-sm text-gray-400">No activity recorded yet.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views/users/show.blade.php ENDPATH**/ ?>