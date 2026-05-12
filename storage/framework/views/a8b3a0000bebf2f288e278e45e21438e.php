<?php $__env->startSection('content'); ?>
<form method="GET" action="<?php echo e(route('home.filter')); ?>" id="filterForm">
    <section class="container mx-auto px-4 lg:px-8 py-10">
        <h1 class="text-[#0071c5] text-3xl lg:text-4xl font-light mb-6">Your Search</h1>

        <form method="GET" action="<?php echo e(route('home.filter')); ?>">
            <div class="flex flex-col md:flex-row border border-gray-300 rounded-sm mb-5 overflow-hidden bg-white">
                <div class="flex items-center flex-1 px-4 border-b md:border-b-0 md:border-r border-gray-200">
                    <i class="fas fa-search text-gray-400 mr-3 text-sm"></i>
                    <input type="text" name="search" placeholder="Search..." value="<?php echo e(request('search')); ?>"
                        class="w-full py-3.5 outline-none text-sm text-gray-600 bg-transparent" />
                </div>
                <button type="submit" aria-label="search"
                    class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-10 py-3.5 text-sm font-bold transition-colors w-full md:w-auto">
                    Search
                </button>
            </div>
        </form>

        <!-- Results summary (Dynamic) -->
        <p class="text-sm text-gray-600 mb-8">
            We found <span class="font-bold"><?php echo e($assets->total() + $campaigns->total()); ?> results:</span>
            <a href="#assets" class="text-[#0071c5] hover:underline ml-1">(<?php echo e($assets->total()); ?>) Assets</a>,
            <a href="#campaigns" class="text-[#0071c5] hover:underline ml-1">(<?php echo e($campaigns->total()); ?>) Campaigns</a>
        </p>

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <!-- ════ LEFT SIDEBAR: Filters  ════ -->
            <aside class="w-full lg:w-[300px] shrink-0">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Filters</h2>
                    <a href="<?php echo e(route('home.filter')); ?>"
                        class="text-[#0071c5] text-sm font-semibold hover:underline">Reset</a>
                </div>

                <div class="space-y-1">
                    <!-- Topics -->
                    <div class="relative border border-gray-200">
                        <select name="concern" onchange="this.form.submit()" aria-label="concern"
                            class="w-full appearance-none px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 border-none outline-none cursor-pointer">
                            <option value="">Topics</option>
                            <?php $__currentLoopData = \App\Models\Project::CONCERNS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('concern') == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i
                            class="fas fa-chevron-right text-gray-400 text-[10px] absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>

                    <!-- Asset Type -->
                    <div class="relative border border-gray-200">
                        <select name="type" onchange="this.form.submit()" aria-label="type"
                            class="w-full appearance-none px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 border-none outline-none cursor-pointer">
                            <option value="">Asset Type</option>
                            <?php $__currentLoopData = $assetTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>"
                                <?php echo e(request('type') == $type->id ? 'selected' : ''); ?>><?php echo e($type->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i
                            class="fas fa-chevron-right text-gray-400 text-[10px] absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>

                    <!-- Project -->
                    <div class="relative border border-gray-200">
                        <select name="project" onchange="this.form.submit()" aria-label="project"
                            class="w-full appearance-none px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 border-none outline-none cursor-pointer">
                            <option value="">Project</option>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($project->id); ?>"
                                <?php echo e(request('project') == $project->id ? 'selected' : ''); ?>><?php echo e($project->name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i
                            class="fas fa-chevron-right text-gray-400 text-[10px] absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>

                <hr class="border-gray-200 mt-2" />

                <!-- Apply Button  -->
                <button type="submit" aria-label="filter"
                    class="w-full bg-[#0071c5] hover:bg-[#005ea3] text-white font-semibold py-3 text-sm mt-4 transition-colors">
                    Apply Filters
                </button>
            </aside>
            <!-- END SIDEBAR -->


            <!-- ════ RIGHT: Results ════ -->
            <div class="flex-1 w-full min-w-0">

                <!-- Top Action Bar -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-5 gap-4">
                    <!-- Left: Share + Download -->
                    <div class="flex flex-wrap items-center gap-3">
                        <span id="selectedCount" class="hidden text-sm text-gray-500 font-medium">
                            <span id="selectedNum">0</span> selected
                        </span>

                        <button type="button" id="shareBtn" disabled onclick="handleShare()" aria-label="share"
                            class="bg-[#0071c5] text-white px-5 py-2 text-sm font-semibold flex items-center gap-2 transition-colors
                                       opacity-40 cursor-not-allowed"
                            title="Select items to share">
                            <i class="fa-solid fa-share-nodes text-xs"></i> Share
                        </button>

                        <button type="button" id="downloadBtn" disabled onclick="handleDownload()" aria-label="download"
                            class="bg-[#0071c5] text-white px-5 py-2 text-sm font-semibold flex items-center gap-2 transition-colors opacity-40 cursor-not-allowed"
                            title="Select items to download">
                            <i id="downloadIcon" class="fa-solid fa-download text-xs"></i>
                            <span id="downloadText">Download multiple assets</span>
                        </button>
                    </div>

                    <!-- Right: View toggle + per page + sort -->
                    <div class="flex flex-wrap items-center gap-4 justify-between md:justify-end">
                        <!-- Grid/List toggle -->
                        <div class="flex items-center gap-1">
                            <button type="button" id="listViewBtn" onclick="setView('list')" aria-label="Switch to list view"
                                class="p-1.5 text-gray-400 hover:text-[#0071c5] transition-colors">
                                <i class="fas fa-list text-base"></i>
                            </button>
                            <button type="button" id="gridViewBtn" onclick="setView('grid')" aria-label="Switch to list view"
                                class="p-1.5 text-[#0071c5] transition-colors">
                                <i class="fas fa-th-large text-base"></i>
                            </button>
                        </div>

                        <!-- Results per page -->
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <label for="per_page_select" class="sr-only">Select results per page</label>
                            <span class="whitespace-nowrap">Results per page</span>
                            <select name="per_page" onchange="document.getElementById('filterForm').submit()" aria-label="pagination"
                                class="border border-gray-300 text-sm text-gray-700 px-2 py-1.5 bg-white  cursor-pointer min-w-[52px]">
                                <option value="6" <?php echo e(request('per_page', 6) == 6 ? 'selected' : ''); ?>>6</option>
                                <option value="12" <?php echo e(request('per_page') == 12 ? 'selected' : ''); ?>>12</option>
                                <option value="24" <?php echo e(request('per_page') == 24 ? 'selected' : ''); ?>>24</option>
                            </select>
                        </div>

                        <!-- Sort by -->
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <label for="per_page_select" class="sr-only">Sort by</label>
                            <span>Sort by</span>
                            <select name="sort" onchange="document.getElementById('filterForm').submit()"
                                class="border border-gray-300 text-sm text-gray-700 px-2 py-1.5 bg-white  cursor-pointer min-w-[80px]">
                                <option value="latest" <?php echo e(request('sort', 'latest') == 'latest' ? 'selected' : ''); ?>>
                                    Latest</option>
                                <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>Oldest
                                </option>
                                <option value="az" <?php echo e(request('sort') == 'az' ? 'selected' : ''); ?>>A-Z</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ── Assets Section ── -->
                <div id="assets" class="mb-8">
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="text-[22px] font-light text-gray-800">Assets</h2>
                        <button type="button" aria-label="Toggle Assets section"
                            class="w-6 h-6 rounded-full border-2 border-[#0071c5] text-[#0071c5] flex items-center justify-center text-xs hover:bg-blue-50 transition-colors"
                            onclick="toggleSection('assetsGrid', this)">
                            <i class="fas fa-plus text-[10px]"></i>
                        </button>
                    </div>

                    <div id="assetsGrid" class="hidden">
                        <?php if($assets->count() > 0): ?>
                        <div id="assetsCardGrid"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
                            <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal895cdfb360c88ca78237e9e20ebefe47 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal895cdfb360c88ca78237e9e20ebefe47 = $attributes; } ?>
<?php $component = App\View\Components\Frontend\AssetCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('frontend.asset-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Frontend\AssetCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['asset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($asset),'selectable' => true]); ?>
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
                        <!-- Assets Pagination -->
                        <?php echo e($assets->appends(request()->query())->links()); ?>

                        <?php else: ?>
                        <p class="text-gray-400 text-sm py-4">No assets found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <hr class="border-gray-300 border-1 mb-5" />

                <!-- ── Campaigns Section ── -->
                <!-- <div id="campaigns">
                        <div class="flex items-center gap-2 mb-1">
                            <h2 class="text-[22px] font-light text-gray-800">Campaigns</h2>
                            <button type="button" aria-label="Toggle campaign section"
                                class="w-6 h-6 rounded-full border-2 border-[#0071c5] text-[#0071c5] flex items-center justify-center text-xs hover:bg-blue-50 transition-colors"
                                onclick="toggleSection('campaignsGrid', this)">
                                <i class="fas fa-minus text-[10px]"></i>
                            </button>
                        </div>

                        <div id="campaignsGrid">
                            <?php if($campaigns->count() > 0): ?>
                                <div id="campaignsCardGrid"
                                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
                                    <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if (isset($component)) { $__componentOriginal0f89098fc988976a319558d2a570c936 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0f89098fc988976a319558d2a570c936 = $attributes; } ?>
<?php $component = App\View\Components\Frontend\CampaignCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('frontend.campaign-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Frontend\CampaignCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['campaign' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($campaign),'selectable' => true]); ?>
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
                                <?php echo e($campaigns->appends(request()->query())->links()); ?>

                            <?php else: ?>
                                <p class="text-gray-400 text-sm py-4">No campaigns found.</p>
                            <?php endif; ?>
                        </div>
                    </div> -->
                <!-- END CAMPAIGNS SECTION -->

            </div>
            <!-- END RIGHT RESULTS -->

        </div>
</form>
<!-- END BODY -->

</section>

<script>
    // ── Toggle section open/close ──
    function toggleSection(sectionId, btn) {
        const section = document.getElementById(sectionId);
        const icon = btn.querySelector('i');

        section.classList.toggle('hidden');
        const isHidden = section.classList.contains('hidden');

        icon.classList.toggle('fa-plus', isHidden);
        icon.classList.toggle('fa-minus', !isHidden);

        localStorage.setItem(sectionId, isHidden ? 'hidden' : 'visible');
    }
    // ── Selection logic ──
    function updateActionBar() {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        const count = checked.length;

        const shareBtn = document.getElementById('shareBtn');
        const downloadBtn = document.getElementById('downloadBtn');
        const countBadge = document.getElementById('selectedCount');
        const countNum = document.getElementById('selectedNum');

        countNum.textContent = count;

        if (count > 0) {
            // enable buttons
            shareBtn.disabled = false;
            downloadBtn.disabled = false;
            shareBtn.classList.remove('opacity-40', 'cursor-not-allowed');
            shareBtn.classList.add('hover:bg-[#005ea3]');
            downloadBtn.classList.remove('opacity-40', 'cursor-not-allowed');
            downloadBtn.classList.add('hover:bg-[#005ea3]');
            countBadge.classList.remove('hidden');
        } else {
            // disable buttons
            shareBtn.disabled = true;
            downloadBtn.disabled = true;
            shareBtn.classList.add('opacity-40', 'cursor-not-allowed');
            shareBtn.classList.remove('hover:bg-[#005ea3]');
            downloadBtn.classList.add('opacity-40', 'cursor-not-allowed');
            downloadBtn.classList.remove('hover:bg-[#005ea3]');
            countBadge.classList.add('hidden');
        }
    }

    function getSelectedIds() {
        return [...document.querySelectorAll('.item-checkbox:checked')]
            .map(cb => ({
                type: cb.dataset.type,
                id: cb.dataset.id
            }));
    }

    function handleShare() {
        const selected = getSelectedIds();
        if (!selected.length) return;

        // লিঙ্ক তৈরি করা
        const ids = selected.map(s => s.id).join(',');
        const types = selected.map(s => s.type).join(',');
        const shareLink = `${window.location.origin}${window.location.pathname}?share_ids=${ids}&types=${types}`;

        // সরাসরি গ্লোবাল ফাংশন কল করা (যা আপনি কম্পোনেন্টে লিখেছেন)
        openGlobalShareModal(shareLink, 'Shared  Assets');
    }

    function closeShareModal() {
        const modal = document.getElementById('shareModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function copyShareLink() {
        const copyText = document.getElementById('shareUrl');
        copyText.select();
        navigator.clipboard.writeText(copyText.value).then(() => {
            const status = document.getElementById('copyStatus');
            status.classList.remove('hidden');
            setTimeout(() => status.classList.add('hidden'), 2000);
        });
    }

function handleDownload() {
    const selected = getSelectedIds();
    if (!selected.length) return;

    const btn = document.getElementById('downloadBtn');
    const icon = document.getElementById('downloadIcon');
    const text = document.getElementById('downloadText');

    btn.disabled = true;
    btn.classList.add('pointer-events-none');
    icon.className = 'fa-solid fa-spinner fa-spin text-xs';
    text.textContent = 'Starting downloads...';

    selected.forEach((item, index) => {
        setTimeout(() => {
            const url = `/drive/file/${item.type}/${item.id}`;

            // iframe দিয়ে download — browser block করে না
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = url;
            document.body.appendChild(iframe);

            setTimeout(() => document.body.removeChild(iframe), 5000);

            text.textContent = `Downloading ${index + 1} of ${selected.length}...`;

            if (index === selected.length - 1) {
                setTimeout(() => {
                    icon.className = 'fa-solid fa-circle-check text-xs';
                    text.textContent = `${selected.length} file(s) downloaded!`;
                    btn.classList.remove('pointer-events-none');

                    setTimeout(() => {
                        icon.className = 'fa-solid fa-download text-xs';
                        text.textContent = 'Download multiple assets';
                    }, 3000);
                }, 800);
            }
        }, index * 1000);
    });
}
    // Listen to all checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-checkbox')) {
            updateActionBar();
        }
    });

    function setView(view) {
        const grids = document.querySelectorAll('#assetsCardGrid, #campaignsCardGrid');
        const listBtn = document.getElementById('listViewBtn');
        const gridBtn = document.getElementById('gridViewBtn');

        grids.forEach(grid => {
            if (!grid) return;

            if (view === 'list') {
                grid.classList.remove('sm:grid-cols-2', 'lg:grid-cols-3');
                grid.classList.add('grid-cols-1');

                const cards = grid.children;
                for (let card of cards) {
                    const inner = card.querySelector('.flex-col');
                    if (inner) {
                        inner.classList.replace('flex-col', 'flex-row');
                        inner.classList.add('items-center', 'gap-6');

                        const banner = inner.querySelector('.relative');
                        if (banner) {
                            banner.style.width = window.innerWidth < 640 ? '120px' : '280px';
                            banner.style.height = window.innerWidth < 640 ? '100px' : '180px';
                            banner.style.flexShrink = '0';
                        }
                    }
                }
            } else {
                grid.classList.remove('grid-cols-1');
                grid.classList.add('sm:grid-cols-2', 'lg:grid-cols-3');

                const cards = grid.children;
                for (let card of cards) {
                    const inner = card.querySelector('.flex-row');
                    if (inner) {
                        inner.classList.replace('flex-row', 'flex-col');
                        inner.classList.remove('items-center', 'gap-6');

                        const banner = inner.querySelector('.relative');
                        if (banner) {
                            banner.style.width = '';
                            banner.style.height = '';
                            banner.style.flexShrink = '';
                        }
                    }
                }
            }
        });

        if (view === 'list') {
            listBtn.classList.replace('text-gray-400', 'text-[#0071c5]');
            gridBtn.classList.replace('text-[#0071c5]', 'text-gray-400');
        } else {
            gridBtn.classList.replace('text-gray-400', 'text-[#0071c5]');
            listBtn.classList.replace('text-[#0071c5]', 'text-gray-400');
        }

        localStorage.setItem('preferredView', view);

    }

    // Restore saved view on page load
    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('preferredView') || 'grid';
        setView(saved);

        const urlParams = new URLSearchParams(window.location.search);
        const sections = [{
                id: 'assetsGrid',
                param: 'assets_page'
            },
            {
                id: 'campaignsGrid',
                param: 'campaigns_page'
            }
        ];

        sections.forEach(s => {
            const element = document.getElementById(s.id);
            const btn = document.querySelector(`button[onclick*="${s.id}"]`);
            const icon = btn ? btn.querySelector('i') : null;

            const storedState = localStorage.getItem(s.id);
            const shouldShow = urlParams.has(s.param) || storedState === 'visible';

            if (shouldShow) {
                element.classList.remove('hidden');
                if (icon) icon.classList.replace('fa-plus', 'fa-minus');
            }
        });
        const targetSection = urlParams.get('section');

        if (targetSection === 'assets') {
            // Assets
            const assetsGrid = document.getElementById('assetsGrid');
            const assetsBtnIcon = document.querySelector('button[onclick*="assetsGrid"] i');
            if (assetsGrid) assetsGrid.classList.remove('hidden');
            if (assetsBtnIcon) assetsBtnIcon.classList.replace('fa-plus', 'fa-minus');

            // Campaigns
            const campaignsGrid = document.getElementById('campaignsGrid');
            const campaignsBtnIcon = document.querySelector('button[onclick*="campaignsGrid"] i');
            if (campaignsGrid) campaignsGrid.classList.add('hidden');
            if (campaignsBtnIcon) campaignsBtnIcon.classList.replace('fa-minus', 'fa-plus');

            //
            localStorage.setItem('assetsGrid', 'visible');
            localStorage.setItem('campaignsGrid', 'hidden');
        }
        if (targetSection === 'campaigns') {
            //
            const assetsGrid = document.getElementById('assetsGrid');
            const assetsBtnIcon = document.querySelector('button[onclick*="assetsGrid"] i');
            if (assetsGrid) assetsGrid.classList.add('hidden');
            if (assetsBtnIcon) assetsBtnIcon.classList.replace('fa-minus', 'fa-plus');

            //
            const campaignsGrid = document.getElementById('campaignsGrid');
            const campaignsBtnIcon = document.querySelector('button[onclick*="campaignsGrid"] i');
            if (campaignsGrid) campaignsGrid.classList.remove('hidden');
            if (campaignsBtnIcon) campaignsBtnIcon.classList.replace('fa-plus', 'fa-minus');

            //
            localStorage.setItem('assetsGrid', 'hidden');
            localStorage.setItem('campaignsGrid', 'visible');
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.font', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\asset-management\resources\views/frontend/filter.blade.php ENDPATH**/ ?>