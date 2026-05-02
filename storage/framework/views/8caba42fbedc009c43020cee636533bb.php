<?php $__env->startSection('content'); ?>
    <section class="relative w-full bg-gradient-to-br from-[#003b7a] via-[#0090a8] to-[#00c7b1] pt-10 pb-28 px-6">
        <div class="container mx-auto mx-auto flex flex-col md:flex-row justify-between items-start md:items-center">
            <!-- Welcome Info Section -->
            <div class="flex items-start gap-8">
                <div class="flex flex-col gap-5 shrink-0">
                    <!-- Intel Badge -->
                    <div class="relative w-[105px] h-[105px] flex items-center justify-center">
                        <div class="absolute top-0 right-0 w-[90%] h-[7.5px] bg-[#3293e3]"></div>
                        <div class="absolute top-0 right-0 w-[7.5px] h-[90%] bg-[#3293e3]"></div>

                        <div class="absolute bottom-0 left-0 w-[90%] h-[7.5px] bg-[#48b5e6]"></div>
                        <div class="absolute bottom-0 left-0 w-[7.5px] h-[90%] bg-[#48b5e6]"></div>
                        <div class="absolute bottom-[-11px] left-[-11px] w-[12px] h-[12px] bg-[#3293e3] z-20"></div>
                        <div class="relative z-10 flex flex-col items-center justify-center">
                            <span class="text-3xl text-white font-bold italic leading-none tracking-tighter">intel</span>
                            <p class="text-sm text-blue-300 leading-none mt-1 opacity-95">
                                partner
                            </p>
                        </div>
                    </div>

                    <div class="bg-white px-3 py-2 shadow-md w-fit leading-tight ml-1">
                        <p class="text-[#00609d] text-xs font-medium">
                            Not your correct tier?
                        </p>
                        <a href="#" class="text-[#3293e3] text-xs font-bold underline hover:no-underline">Contact ISC
                            for support</a>
                    </div>
                </div>

                <div class="pt-4">
                    <h1 class="text-4xl text-white leading-none tracking-tight">
                        <?php echo e($user->name ?? 'Welcome !'); ?>

                    </h1>
                    <p class="text-sm text-white/75 font-bold uppercase tracking-[3px] mt-2">
                        PARTNER | BUILDER
                    </p>
                </div>
            </div>

            <a href="#"
                class="flex items-center gap-4 bg-white/10 backdrop-blur-md border-2 border-white/30 px-4 py-2.5 hover:bg-white/20 transition-all text-white group">
                <!-- Partner Alliance Button -->
                <div
                    class="absolute -top-2.5 -left-2.5 w-[20px] h-[20px] bg-white rounded-full flex items-center justify-center shadow-md z-20 border border-gray-100">
                    <i class="fa-regular fa-circle-question text-xs text-[#0071c5]"></i>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-3xl font-medium italic leading-none tracking-tighter">intel</span>

                    <div class="flex flex-col text-xs leading-[1.2] tracking-tight opacity-95">
                        <span>partner</span>
                        <span>alliance</span>
                    </div>
                </div>

                <div class="ml-2">
                    <i class="fa-solid fa-arrow-right text-[14px] transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>
        </div>

        <!-- SEARCH BAR — overlapping bottom -->
        <div class="absolute bottom-0 left-0 right-0 px-6 mb-6">
            <form action="<?php echo e(route('home.filter')); ?>" method="GET"
                class="container mx-auto bg-white border border-gray-200 flex items-center flex-wrap shadow-lg">

                <!-- Search Input -->
                <div class="flex-1 flex items-center px-5 border-r border-gray-200 min-w-[220px]">
                    <i class="fas fa-search text-[#3293e3] mr-3 text-lg"></i>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                        placeholder="Search devices, products and more..."
                        class="w-full py-4 outline-none text-sm text-gray-600 placeholder-gray-400 bg-transparent" />
                </div>

                <!-- Dropdowns -->
                <div class="flex items-center divide-x divide-gray-200">
                    <!-- 1. Concern -->
                    <div class="px-5">
                        <select name="concern"
                            class="outline-none text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-4 min-w-[90px]">
                            <option value="">Concern</option>
                            <?php $__currentLoopData = $concerns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(request('concern') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- 2. Project -->
                    <div class="px-5">
                        <select name="project"
                            class="outline-none text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-4 min-w-[90px]">
                            <option value="">Project</option>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>"
                                    <?php echo e(request('project') == $project->id ? 'selected' : ''); ?>><?php echo e($project->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- 3. Asset Type -->
                    <div class="px-5">
                        <select name="type"
                            class="outline-none text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-4 min-w-[100px]">
                            <option value="">Asset Type</option>
                            <?php $__currentLoopData = $assetTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php echo e(request('type') == $type->id ? 'selected' : ''); ?>>
                                    <?php echo e($type->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- 4. Language -->
                    <div class="px-5">
                        <select name="lang"
                            class="outline-none text-sm font-medium text-gray-600 bg-transparent cursor-pointer py-4 min-w-[90px]">
                            <option value="">Language</option>
                            <?php $__currentLoopData = $allLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lang); ?>" <?php echo e(request('lang') == $lang ? 'selected' : ''); ?>>
                                    <?php echo e(strtoupper($lang)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="ml-auto flex items-center gap-4 px-5 py-3">
                    <button type="submit"
                        class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-8 py-2.5 text-sm font-bold tracking-wide transition-colors">
                        Search
                    </button>
                    <a href="<?php echo e(route('home.index')); ?>"
                        class="text-[#0071c5] text-sm font-semibold whitespace-nowrap hover:underline">
                        Reset filters
                    </a>
                </div>
            </form>
        </div>
    </section>
    <!-- END HERO -->

    <!-- ── PAGE  Campaigns ── -->
    <section class="container mx-auto mx-auto px-6 py-10">
        <div class="flex items-center gap-3 mb-6">
            <a href="<?php echo e(route('home.filter', ['section' => 'campaigns'])); ?>"
                class="text-2xl md:text-3xl text-[#0071c5] underline">
                Campaigns to Sell the Latest Products
            </a>
            <a href="<?php echo e(route('home.filter', ['section' => 'campaigns'])); ?>"
                class="bg-[#3293e3] text-white w-8 h-8 flex items-center justify-center shrink-0">
                <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
            <?php $__currentLoopData = $featuredCampaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal0f89098fc988976a319558d2a570c936 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0f89098fc988976a319558d2a570c936 = $attributes; } ?>
<?php $component = App\View\Components\Frontend\CampaignCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('frontend.campaign-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Frontend\CampaignCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['campaign' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($campaign)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0f89098fc988976a319558d2a570c936)): ?>
<?php $attributes = $__attributesOriginal0f89098fc988976a319558d2a570c936; ?>
<?php unset($__attributesOriginal0f89098fc988976a319558d2a570c936); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0f89098fc988976a319558d2a570c936)): ?>
<?php $component = $__componentOriginal0f89098fc988976a319558d2a570c936; ?>
<?php unset($__componentOriginal0f89098fc988976a319558d2a570c936); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <!-- ── Latest Marketing Assets Section ── -->
    <section class="container mx-auto px-6 py-12">
        <div class="flex items-center gap-3 mb-8">
            <a href="<?php echo e(route('home.filter', ['section' => 'assets', 'sort' => 'latest'])); ?>">
                <h2 class="text-3xl text-[#0071c5] underline cursor-pointer">
                    Latest Marketing Assets
                </h2>
            </a>

            <a href="<?php echo e(route('home.filter', ['section' => 'assets', 'sort' => 'latest'])); ?>"
                class="bg-[#00aeef] text-white w-7 h-7 flex items-center justify-center">
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="relative group container mx-auto px-6">
            <div class="swiper mySwiper overflow-hidden">
                <div class="swiper-wrapper">
                    <?php $__currentLoopData = $latestAssets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal895cdfb360c88ca78237e9e20ebefe47 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal895cdfb360c88ca78237e9e20ebefe47 = $attributes; } ?>
<?php $component = App\View\Components\Frontend\AssetCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('frontend.asset-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Frontend\AssetCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['asset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($asset),'swiper' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal895cdfb360c88ca78237e9e20ebefe47)): ?>
<?php $attributes = $__attributesOriginal895cdfb360c88ca78237e9e20ebefe47; ?>
<?php unset($__attributesOriginal895cdfb360c88ca78237e9e20ebefe47); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal895cdfb360c88ca78237e9e20ebefe47)): ?>
<?php $component = $__componentOriginal895cdfb360c88ca78237e9e20ebefe47; ?>
<?php unset($__componentOriginal895cdfb360c88ca78237e9e20ebefe47); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Swiper Next Button -->
            <div class="absolute -right-5 top-1/2 -translate-y-1/2 z-30">
                <div
                    class="swiper-button-next-custom w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                    <i class="fa-solid fa-chevron-right text-lg"></i>
                </div>
            </div>
        </div>
    </section>
    <!-- ── recommended ── -->
    <section class="container mx-auto px-6 py-12">
        <div class="flex items-center gap-3 mb-8">
            <h2 class="text-3xl text-gray-800 font-light cursor-pointer">
                Recommended Assets for You
            </h2>
        </div>
        <div class="relative group container mx-auto px-6">
            <div class="swiper recommendSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    <?php $__currentLoopData = $recommendedAssets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal895cdfb360c88ca78237e9e20ebefe47 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal895cdfb360c88ca78237e9e20ebefe47 = $attributes; } ?>
<?php $component = App\View\Components\Frontend\AssetCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('frontend.asset-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Frontend\AssetCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['asset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($asset),'swiper' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal895cdfb360c88ca78237e9e20ebefe47)): ?>
<?php $attributes = $__attributesOriginal895cdfb360c88ca78237e9e20ebefe47; ?>
<?php unset($__attributesOriginal895cdfb360c88ca78237e9e20ebefe47); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal895cdfb360c88ca78237e9e20ebefe47)): ?>
<?php $component = $__componentOriginal895cdfb360c88ca78237e9e20ebefe47; ?>
<?php unset($__componentOriginal895cdfb360c88ca78237e9e20ebefe47); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Next Button -->
            <div class="absolute -right-5 top-1/2 -translate-y-1/2 z-30">
                <div
                    class="swiper-button-next-recommend w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                    <i class="fa-solid fa-chevron-right text-lg"></i>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-library\resources\views/frontend/index.blade.php ENDPATH**/ ?>