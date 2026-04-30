@extends('frontend.layouts.font')
@section('content')
    <section class="container mx-auto px-8 py-10">
      <!-- Page Title -->
      <h1 class="text-[#0071c5] text-4xl font-light mb-6">Your Search</h1>

      <!-- Search Bar -->
      <div
        class="flex items-center border border-gray-300 rounded-sm mb-5 overflow-hidden"
      >
        <div class="flex items-center flex-1 px-4">
          <i class="fas fa-search text-gray-400 mr-3 text-sm"></i>
          <input
            type="text"
            placeholder="Search devices, products and more..."
            value=""
            class="w-full py-3.5 outline-none text-sm text-gray-600 font-['Outfit'] placeholder-gray-400 bg-transparent"
          />
        </div>
        <button
          class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-10 py-3.5 text-sm font-bold font-['Outfit'] transition-colors shrink-0"
        >
          Search
        </button>
      </div>

      <!-- Results summary -->
      <p class="text-sm text-gray-600 mb-8">
        We found <span class="font-bold">1867 results:</span>
        <a href="#" class="text-[#0071c5] hover:underline ml-1">(1814) Assets</a
        >,
        <a href="#" class="text-[#0071c5] hover:underline ml-1"
          >(49) Campaigns</a
        >,
        <a href="#" class="text-[#0071c5] hover:underline ml-1"
          >(4) Resources</a
        >
      </p>

      <!-- ── BODY: Sidebar + Results ── -->
      <div class="flex gap-8 items-start">
        <!-- ════ LEFT SIDEBAR: Filters ════ -->
        <aside class="w-[300px] shrink-0">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Filters</h2>
            <button
              class="text-[#0071c5] text-sm font-semibold hover:underline"
            >
              Reset
            </button>
          </div>

          <!-- Filter Items -->
          <div class="space-y-1">
            <button
              class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors text-left"
            >
              <span>Topics</span>
              <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>
            <button
              class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors text-left"
            >
              <span>Asset Type</span>
              <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>
            <button
              class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors text-left"
            >
              <span>Device</span>
              <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>
            <button
              class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors text-left"
            >
              <span>Product</span>
              <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>
            <button
              class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors text-left"
            >
              <span>Language</span>
              <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>
            <button
              class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors text-left"
            >
              <span>Shopper Journey</span>
              <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>
            <button
              class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors text-left mb-2"
            >
              <span>Customizable</span>
              <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </button>
          </div>
          <hr class="border-gray-300" />
          <!-- Apply Button -->
          <button
            class="w-full bg-gray-400 hover:bg-gray-500 text-white font-semibold py-3 text-sm mt-2 transition-colors"
          >
            Apply
          </button>
        </aside>
        <!-- END SIDEBAR -->

        <!-- ════ RIGHT: Results ════ -->
        <div class="flex-1 min-w-0">
          <!-- Top Action Bar -->
          <div class="flex items-center justify-between mb-5">
            <!-- Left: Share + Download -->
            <div class="flex items-center gap-3">
              <button
                class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-5 py-2 text-sm font-semibold flex items-center gap-2 transition-colors"
              >
                <i class="fa-solid fa-share-nodes text-xs"></i> Share
              </button>
              <button
                class="bg-[#0071c5] hover:bg-[#005ea3] text-white px-5 py-2 text-sm font-semibold flex items-center gap-2 transition-colors"
              >
                <i class="fa-solid fa-download text-xs"></i> Download multiple
                assets
              </button>
            </div>

            <!-- Right: View toggle + per page + sort -->
            <div class="flex items-center gap-4">
              <!-- Grid/List toggle -->
              <div class="flex items-center gap-1">
                <button
                  class="p-1.5 text-gray-400 hover:text-[#0071c5] transition-colors"
                >
                  <i class="fas fa-list text-base"></i>
                </button>
                <button class="p-1.5 text-[#0071c5]">
                  <i class="fas fa-th-large text-base"></i>
                </button>
              </div>

              <!-- Results per page -->
              <div class="flex items-center gap-2 text-sm text-gray-600">
                <span class="whitespace-nowrap">Results per page</span>
                <select
                  class="border border-gray-300 text-sm text-gray-700 px-2 py-1.5 bg-white font-['Outfit'] cursor-pointer min-w-[52px]"
                >
                  <option>6</option>
                  <option>12</option>
                  <option>24</option>
                </select>
              </div>

              <!-- Sort by -->
              <div class="flex items-center gap-2 text-sm text-gray-600">
                <span>Sort by</span>
                <select
                  class="border border-gray-300 text-sm text-gray-700 px-2 py-1.5 bg-white font-['Outfit'] cursor-pointer min-w-[80px]"
                >
                  <option>Latest</option>
                  <option>Oldest</option>
                  <option>A-Z</option>
                </select>
              </div>
            </div>
          </div>

          <!-- ── Assets Section ── -->
          <div class="mb-8">
            <div class="flex items-center gap-2 mb-1">
              <h2 class="text-[22px] font-light text-gray-800">Assets</h2>
              <button
                class="w-6 h-6 rounded-full border-2 border-[#0071c5] text-[#0071c5] flex items-center justify-center text-xs hover:bg-blue-50 transition-colors"
              >
                <i class="fas fa-plus text-[10px]"></i>
              </button>
            </div>
            <hr class="border-gray-300 mb-5" />
          </div>

          <!-- ── Campaigns Section ── -->
          <div>
            <div class="flex items-center gap-2 mb-1">
              <h2 class="text-[22px] font-light text-gray-800">Campaigns</h2>
              <button
                class="w-6 h-6 rounded-full border-2 border-[#0071c5] text-[#0071c5] flex items-center justify-center text-xs hover:bg-blue-50 transition-colors"
              >
                <i class="fas fa-minus text-[10px]"></i>
              </button>
            </div>
            <hr class="border-gray-300 mb-5" />

            <!-- Campaign Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
              <!-- Card 1 -->
              <div
                class="bg-white shadow-md hover:shadow-xl transition-all duration-300 group cursor-pointer border border-gray-100"
              >
                <div
                  class="relative h-[220px] bg-[#0071c5] flex items-center justify-center overflow-visible"
                >
                  <div class="px-4 md:px-8">
                    <img
                      src="../assets/images/images.jpg"
                      alt="Campaign Poster"
                      class="h-[85%] w-auto shadow-2xl object-contain"
                    />
                  </div>

                  <button
                    class="absolute top-4 right-4 text-[#00aeef] hover:scale-110 transition-transform"
                  >
                    <i class="fa-regular fa-bookmark text-2xl"></i>
                  </button>

                  <div
                    class="absolute -bottom-4 left-4 bg-[#fdbb30] text-white px-5 py-2 rounded-2xl text-[12px] font-bold shadow-md z-20"
                  >
                    Featured
                  </div>
                </div>

                <div class="p-6 pt-10">
                  <h3
                    class="text-[#005da4] text-[19px] font-medium leading-snug mb-8 min-h-[56px] group-"
                  >
                    Upgrade your everyday with Intel® Core™ Series 3 processors
                  </h3>

                  <p class="text-[#757575] text-[14px] mb-3">
                    Topics: <span class="font-normal">Consumer</span>
                  </p>

                  <div class="mb-10">
                    <span
                      class="border border-[#005da4] text-[#005da4] px-4 py-1 rounded-full text-[12px] font-medium"
                    >
                      English
                    </span>
                  </div>

                  <div
                    class="flex items-center gap-2.5 text-[#005da4] text-[12px] font-bold tracking-[1.2px] uppercase pt-4"
                  >
                    <i class="fa-solid fa-magnifying-glass text-[14px]"></i>
                    <span>More Details</span>
                  </div>
                </div>
              </div>

              <!-- Card 2 -->
              <div
                class="bg-white shadow-md hover:shadow-xl transition-all duration-300 group cursor-pointer border border-gray-100"
              >
                <div
                  class="relative h-[220px] bg-[#0071c5] flex items-center justify-center overflow-visible"
                >
                  <div class="px-4 md:px-8">
                    <img
                      src="../assets/images/images1.jpg"
                      alt="Campaign Poster"
                      class="h-[85%] w-auto shadow-2xl object-contain"
                    />
                  </div>

                  <button
                    class="absolute top-4 right-4 text-[#00aeef] hover:scale-110 transition-transform"
                  >
                    <i class="fa-regular fa-bookmark text-2xl"></i>
                  </button>

                  <div
                    class="absolute -bottom-4 left-4 bg-[#fdbb30] text-white px-5 py-2 rounded-2xl text-[12px] font-bold shadow-md z-20"
                  >
                    Featured
                  </div>
                </div>

                <div class="p-6 pt-10">
                  <h3
                    class="text-[#005da4] text-[19px] font-medium leading-snug mb-8 min-h-[56px] group-"
                  >
                    Upgrade your everyday with Intel® Core™ Series 3 processors
                  </h3>

                  <p class="text-[#757575] text-[14px] mb-3">
                    Topics: <span class="font-normal">Consumer</span>
                  </p>

                  <div class="mb-10">
                    <span
                      class="border border-[#005da4] text-[#005da4] px-4 py-1 rounded-full text-[12px] font-medium"
                    >
                      English
                    </span>
                  </div>

                  <div
                    class="flex items-center gap-2.5 text-[#005da4] text-[12px] font-bold tracking-[1.2px] uppercase pt-4"
                  >
                    <i class="fa-solid fa-magnifying-glass text-[14px]"></i>
                    <span>More Details</span>
                  </div>
                </div>
              </div>

              <!-- Card 3 -->
              <div
                class="bg-white shadow-md hover:shadow-xl transition-all duration-300 group cursor-pointer border border-gray-100"
              >
                <div
                  class="relative h-[220px] bg-[#0071c5] flex items-center justify-center overflow-visible"
                >
                  <div class="px-4 md:px-8">
                    <img
                      src="../assets/images/images2.jpg"
                      alt="Campaign Poster"
                      class="h-[85%] w-auto shadow-2xl object-contain"
                    />
                  </div>

                  <button
                    class="absolute top-4 right-4 text-[#00aeef] hover:scale-110 transition-transform"
                  >
                    <i class="fa-regular fa-bookmark text-2xl"></i>
                  </button>

                  <div
                    class="absolute -bottom-4 left-4 bg-[#fdbb30] text-white px-5 py-2 rounded-2xl text-[12px] font-bold shadow-md z-20"
                  >
                    Featured
                  </div>
                </div>

                <div class="p-6 pt-10">
                  <h3
                    class="text-[#005da4] text-[19px] font-medium leading-snug mb-8 min-h-[56px] group-"
                  >
                    Upgrade your everyday with Intel® Core™ Series 3 processors
                  </h3>

                  <p class="text-[#757575] text-[14px] mb-3">
                    Topics: <span class="font-normal">Consumer</span>
                  </p>

                  <div class="mb-10">
                    <span
                      class="border border-[#005da4] text-[#005da4] px-4 py-1 rounded-full text-[12px] font-medium"
                    >
                      English
                    </span>
                  </div>

                  <div
                    class="flex items-center gap-2.5 text-[#005da4] text-[12px] font-bold tracking-[1.2px] uppercase pt-4"
                  >
                    <i class="fa-solid fa-magnifying-glass text-[14px]"></i>
                    <span>More Details</span>
                  </div>
                </div>
              </div>
            </div>
            <!-- END CARDS -->
          </div>
          <!-- END CAMPAIGNS SECTION -->
        </div>
        <!-- END RIGHT RESULTS -->
      </div>
      <!-- END BODY -->
    </section>
@endsection
