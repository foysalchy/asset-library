    <!-- ── recommended ── -->
    <section class="container mx-auto px-6 py-12">
        <div class="flex items-center gap-3 mb-8">
            <h2 class="text-xl lg:text-3xl text-[#0071c5] underline">
                Recommended Assets for You
            </h2>
        </div>
        <div class="relative group container mx-auto px-6">
            <div class="absolute -left-5 top-1/2 -translate-y-1/2 z-30   lg:flex">
                <div
                    class="swiper-button-prev-recommend w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                    <i class="fa-solid fa-chevron-left text-lg"></i>
                </div>
            </div>
            <div class="swiper recommendSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ($recommendedAssets as $asset)
                        <x-frontend.asset-card :asset="$asset" :swiper="true" />
                    @endforeach
                </div>
            </div>

            <div class="absolute -right-5 top-1/2 -translate-y-1/2 z-30   lg:flex">
                <div
                    class="swiper-button-next-recommend w-11 h-11 bg-white border border-gray-200 rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-[#0071c5] cursor-pointer transition-all">
                    <i class="fa-solid fa-chevron-right text-lg"></i>
                </div>
            </div>
        </div>
    </section>