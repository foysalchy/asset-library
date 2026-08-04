<?php $__env->startSection('content'); ?>
<div class="space-y-6  mx-auto  md:p-6 py-4">

  
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4 mt-4" syle="margin-top:40px">

  
<div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
  <div class="flex items-center justify-center w-12 h-12 bg-blue-50 rounded-xl dark:bg-blue-900/20">
    <svg class="text-blue-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
      <path d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16" />
    </svg>
  </div>
  <div class="flex items-end justify-between mt-5">
    <div>
      <span class="text-sm text-gray-500 dark:text-gray-400">Total Downloads</span>
      <h4 class="mt-2 font-bold text-gray-800 text-2xl dark:text-white/90"><?php echo e($stats['total_downloads']); ?></h4>
    </div>
  </div>
</div>

    
    <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-purple-50 rounded-xl dark:bg-purple-900/20">
        <svg class="text-purple-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2" />
          <circle cx="8.5" cy="8.5" r="1.5" />
          <path d="M21 15l-5-5L5 21" />
        </svg>
      </div>
      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Assets</span>
          <h4 class="mt-2 font-bold text-gray-800 text-2xl dark:text-white/90"><?php echo e($stats['assets']); ?></h4>
        </div>
        <a href="<?php echo e(route('assets.index')); ?>"
          class="text-xs text-purple-500 hover:underline">View all</a>
      </div>
    </div>

    
    <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-green-50 rounded-xl dark:bg-green-900/20">
        <svg class="text-green-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
        </svg>
      </div>
      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Users</span>
          <h4 class="mt-2 font-bold text-gray-800 text-2xl dark:text-white/90"><?php echo e($stats['users']); ?></h4>
        </div>
        <a href="<?php echo e(route('users.index')); ?>"
          class="text-xs text-green-500 hover:underline">View all</a>
      </div>
    </div>

    
    <div class="rounded-xl border border-gray-100 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-amber-50 rounded-xl dark:bg-amber-900/20">
        <svg class="text-amber-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 7h18M3 12h18M3 17h18" />
          <path d="M8 3l-5 4 5 4" />
        </svg>
      </div>
      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Projects</span>
          <h4 class="mt-2 font-bold text-gray-800 text-2xl dark:text-white/90"><?php echo e($stats['projects']); ?></h4>
        </div>
        <a href="<?php echo e(route('projects.index')); ?>"
          class="text-xs text-amber-500 hover:underline">View all</a>
      </div>
  </div>

  </div>

  
  <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


<div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
  <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Top Downloaders</h3>
  </div>
  <div class="divide-y divide-gray-100 dark:divide-gray-800">
    <?php $__empty_1 = true; $__currentLoopData = $topDownloaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">

      
      <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0
          <?php echo e($index === 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'); ?>">
        <?php echo e($index + 1); ?>

      </span>

      
      <?php if($entry->user?->avatar_url): ?>
      <img src="<?php echo e($entry->user->avatar_url); ?>" alt="<?php echo e($entry->user->name); ?>"
        class="w-9 h-9 rounded-full object-cover shrink-0 border border-gray-200 dark:border-gray-700">
      <?php else: ?>
      <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0 text-blue-600 dark:text-blue-400 text-sm font-semibold">
        <?php echo e(strtoupper(substr($entry->user->name ?? '?', 0, 1))); ?>

      </div>
      <?php endif; ?>

      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-gray-800 dark:text-white/90 truncate">
          <?php echo e($entry->user->name ?? 'Unknown User'); ?>

        </p>
        <p class="text-xs text-gray-400 mt-0.5">
          <?php echo e($entry->user->email ?? ''); ?>

        </p>
      </div>

      
      <span class="text-sm font-semibold text-blue-600 dark:text-blue-400 shrink-0">
        <?php echo e($entry->total_downloads); ?> <span class="text-xs font-normal text-gray-400">downloads</span>
      </span>

    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="px-6 py-10 text-center">
      <p class="text-sm text-gray-400">No downloads recorded yet.</p>
    </div>
    <?php endif; ?>
  </div>
</div>

    
    <div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Assets</h3>
        <a href="<?php echo e(route('assets.index')); ?>"
          class="text-xs text-blue-500 hover:underline">View all</a>
      </div>
      <div class="divide-y divide-gray-100 dark:divide-gray-800">
        <?php $__empty_1 = true; $__currentLoopData = $recentAssets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">

          
          <?php if($asset->media->first()?->media_type === 'image'): ?>
          <img src="<?php echo e($asset->media->first()->url); ?>" alt="<?php echo e($asset->title); ?>"
            class="w-10 h-10 rounded-lg object-cover shrink-0 border border-gray-200 dark:border-gray-700">
          <?php else: ?>
          <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center shrink-0">
            <svg class="text-purple-500" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <path d="M21 15l-5-5L5 21" />
            </svg>
          </div>
          <?php endif; ?>

          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800 dark:text-white/90 truncate">
              <?php echo e($asset->title); ?>

            </p>
            <p class="text-xs text-gray-400 mt-0.5 truncate">
              <?php echo e($asset->assetType->names); ?>

            </p>
          </div>

         

        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="px-6 py-10 text-center">
          <p class="text-sm text-gray-400">No assets yet.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>

  </div>

  
  <div class="rounded-xl border border-gray-100 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Activity</h3>
      <?php if(auth()->check() && auth()->user()->hasPermission('activity_logs.view')): ?>
      <a href="<?php echo e(route('activity-logs.index')); ?>"
        class="text-xs text-blue-500 hover:underline">View all</a>
      <?php endif; ?>
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-800">
      <?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">

        
        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                        <?php echo e(match($log->action) {
                            'created' => 'bg-green-100 dark:bg-green-900/30',
                            'updated' => 'bg-blue-100 dark:bg-blue-900/30',
                            'deleted' => 'bg-red-100 dark:bg-red-900/30',
                            default   => 'bg-gray-100 dark:bg-gray-800',
                        }); ?>">
          <svg class="<?php echo e(match($log->action) {
                            'created' => 'text-green-500',
                            'updated' => 'text-blue-500',
                            'deleted' => 'text-red-500',
                            default   => 'text-gray-400',
                        }); ?>" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <?php if($log->action === 'created'): ?>
            <path d="M12 5v14M5 12h14" />
            <?php elseif($log->action === 'updated'): ?>
            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
            <?php else: ?>
            <polyline points="3 6 5 6 21 6" />
            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
            <path d="M10 11v6M14 11v6" />
            <?php endif; ?>
          </svg>
        </div>

        
        <?php if($log->user): ?>
        <img src="<?php echo e($log->user->avatar_url); ?>" alt="<?php echo e($log->user->name); ?>"
          class="w-7 h-7 rounded-full object-cover shrink-0">
        <?php endif; ?>

        
        <div class="flex-1 min-w-0">
          <p class="text-sm text-gray-700 dark:text-gray-300 truncate">
            <?php echo e($log->description); ?>

          </p>
          <p class="text-xs text-gray-400 mt-0.5">
            <?php if($log->user): ?> <?php echo e($log->user->name); ?> · <?php endif; ?>
            <?php echo e($log->created_at->diffForHumans()); ?>

          </p>
        </div>

        
        <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300 shrink-0">
          <?php echo e($log->model_type); ?>

        </span>

      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="px-6 py-10 text-center">
        <p class="text-sm text-gray-400">No activity recorded yet.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views/dashboard/dashboard.blade.php ENDPATH**/ ?>