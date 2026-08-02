<?php
    use App\Helpers\MenuHelper;
    $menuGroups = MenuHelper::getMenuGroups();

    // Get current path
    $currentPath = request()->path();
    $siteSetting = \App\Models\SiteSetting::first();

?>

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200"
    <!-- Logo Section -->
    <div class="pt-8 pb-7 flex"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
        'xl:justify-center' :
        'justify-start'">
        <a href="<?php echo e(route('dashboard')); ?>">

            
            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="flex items-center gap-2">
                 
                    <img src="/logo.png" alt="<?php echo e($siteSetting->site_name); ?>"
                        class="h-[50px]  " />
                
               
            </span>

            
            <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                
                    <img src="/favicon.ico" alt="<?php echo e($siteSetting->site_name); ?>"
                        class="w-8 h-8 object-contain" />
                 
            </span>

        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">
                <?php $__currentLoopData = $menuGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $menuGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        

                        <!-- Menu Items -->
                        <ul class="flex flex-col gap-1">
                            <?php $__currentLoopData = $menuGroup['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <?php if(isset($item['subItems'])): ?>
                                        <!-- Menu Item with Submenu -->
                                        <button @click="toggleSubmenu(<?php echo e($groupIndex); ?>, <?php echo e($itemIndex); ?>)"
                                            class="menu-item group w-full"
                                            :class="[
                                                isSubmenuOpen(<?php echo e($groupIndex); ?>, <?php echo e($itemIndex); ?>) ?
                                                'menu-item-active' : 'menu-item-inactive',
                                                !$store.sidebar.isExpanded && !$store.sidebar.isHovered ?
                                                'xl:justify-center' : 'xl:justify-start'
                                            ]">

                                            <!-- Icon -->
                                            <span
                                                :class="isSubmenuOpen(<?php echo e($groupIndex); ?>, <?php echo e($itemIndex); ?>) ?
                                                    'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                                <?php echo MenuHelper::getIconSvg($item['icon']); ?>

                                            </span>

                                            <!-- Text -->
                                            <span
                                                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="menu-item-text flex items-center gap-2">
                                                <?php echo e($item['name']); ?>

                                                <?php if(!empty($item['new'])): ?>
                                                    <span class="absolute right-10"
                                                        :class="isActive('<?php echo e($item['path'] ?? ''); ?>') ?
                                                            'menu-dropdown-badge menu-dropdown-badge-active' :
                                                            'menu-dropdown-badge menu-dropdown-badge-inactive'">
                                                        new
                                                    </span>
                                                <?php endif; ?>
                                            </span>

                                            <!-- Chevron Down Icon -->
                                            <svg x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="ml-auto w-5 h-5 transition-transform duration-200"
                                                :class="{
                                                    'rotate-180 text-brand-500': isSubmenuOpen(<?php echo e($groupIndex); ?>,
                                                        <?php echo e($itemIndex); ?>)
                                                }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <!-- Submenu -->
                                        <div
                                            x-show="isSubmenuOpen(<?php echo e($groupIndex); ?>, <?php echo e($itemIndex); ?>) && ($store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen)">
                                            <ul class="mt-2 space-y-1 ml-9">
                                                <?php $__currentLoopData = $item['subItems']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                        <a href="<?php echo e($subItem['path']); ?>" class="menu-dropdown-item"
                                                            :class="isActive('<?php echo e($subItem['path']); ?>') ?
                                                                'menu-dropdown-item-active' :
                                                                'menu-dropdown-item-inactive'">
                                                            <?php echo e($subItem['name']); ?>

                                                            <span class="flex items-center gap-1 ml-auto">
                                                                <?php if(!empty($subItem['new'])): ?>
                                                                    <span
                                                                        :class="isActive('<?php echo e($subItem['path']); ?>') ?
                                                                            'menu-dropdown-badge menu-dropdown-badge-active' :
                                                                            'menu-dropdown-badge menu-dropdown-badge-inactive'">
                                                                        new
                                                                    </span>
                                                                <?php endif; ?>
                                                                <?php if(!empty($subItem['pro'])): ?>
                                                                    <span
                                                                        :class="isActive('<?php echo e($subItem['path']); ?>') ?
                                                                            'menu-dropdown-badge-pro menu-dropdown-badge-pro-active' :
                                                                            'menu-dropdown-badge-pro menu-dropdown-badge-pro-inactive'">
                                                                        pro
                                                                    </span>
                                                                <?php endif; ?>
                                                            </span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <!-- Simple Menu Item -->
                                        <a href="<?php echo e($item['path']); ?>" class="menu-item group"
                                            :class="[
                                                isActive('<?php echo e($item['path']); ?>') ? 'menu-item-active' :
                                                'menu-item-inactive',
                                                (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store
                                                    .sidebar.isMobileOpen) ?
                                                'xl:justify-center' : 'justify-start'
                                            ]">

                                            
                                            <span class="menu-item-icon"
                                                :class="isActive('<?php echo e($item['path']); ?>') ?
                                                    'menu-item-icon-active' :
                                                    'menu-item-icon-inactive'">
                                                <?php echo MenuHelper::getIconSvg($item['icon']); ?>

                                            </span>

                                            
                                            <span
                                                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="menu-item-text flex items-center gap-2">
                                                <?php echo e($item['name']); ?>

                                                <?php if(!empty($item['new'])): ?>
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-brand-500 text-white">
                                                        new
                                                    </span>
                                                <?php endif; ?>
                                            </span>
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </nav>

        <!-- Sidebar Widget -->
        <div x-data x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
            x-transition class="mt-auto">
            <?php echo $__env->make('layouts.sidebar-widget', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

    </div>
</aside>

<!-- Mobile Overlay -->
<div x-show="$store.sidebar.isMobileOpen" @click="$store.sidebar.setMobileOpen(false)"
    class="fixed z-50 h-screen w-full bg-gray-900/50"></div>
<?php /**PATH C:\laragon\www\asset-management\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>