@php
$siteSetting = \App\Models\SiteSetting::first();

@endphp

<header class="w-full bg-[#003b7a] text-white sticky top-0 z-50 shadow-md" x-data="{ mobileMenu: false }">
    <div class="container mx-auto px-4 lg:px-6 flex justify-between items-center py-2">
        <!-- ── LOGO ── -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('home.index') }}" class=" px-2 py-2 flex items-center">
                <img src="{{ $siteSetting->logo_url }}" style="  filter: brightness(0) invert(1);" alt="Bhaiya Asset"
                    class="  w-auto h-[40px] block" />
            </a>

        </div>
        <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-2xl p-2 focus:outline-none"
            aria-label="Toggle navigation menu">
            <i class="fa-solid" :class="mobileMenu ? 'fa-xmark' : 'fa-bars'"></i>
        </button>

        <!-- ── NAV ── -->
        <nav class="hidden lg:flex items-stretch gap-0">
            <!-- Home (active) -->
            <a href="{{ route('home.index') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 border-b-2 text-white {{ request()->routeIs('home.index') ? 'border-white' : 'border-transparent hover:border-white/40' }}">
                <i class="fas fa-home text-lg"></i>
                <span class="text-sm tracking-wide">Home</span>
            </a>

            <!-- Campaigns -->
            <!-- <a href="{{ route('home.filter', ['section' => 'campaigns']) }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-bullhorn text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Campaigns </span>
            </a> -->

            <!-- Assets -->
            <a href="{{ route('home.filter', ['section' => 'assets', 'sort' => 'latest']) }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40 {{ request()->fullUrlIs(route('home.filter', ['section' => 'assets', 'sort' => 'latest'])) ? 'border-white' : 'border-transparent hover:border-white/40' }}">
                <i class="fas fa-box text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Assets </span>
            </a>

            <!-- Brand Assets -->
            <a href="{{ route('brand.index') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40 {{ request()->routeIs('brand.index') ? 'border-white' : 'border-transparent hover:border-white/40' }}">
                <i class="fas fa-palette text-lg"></i>
                <span class="text-sm tracking-wide">Brand Assets</span>
            </a>
        </nav>
        <nav class="hidden lg:flex items-stretch gap-0">

            @guest
            <a href="{{ route('frontend.signin') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-sign-in-alt text-lg"></i>
                <span class="text-sm tracking-wide">Sign In</span>
            </a>

            <a href="{{ route('frontend.signup') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-user-plus text-lg"></i>
                <span class="text-sm tracking-wide">Sign Up</span>
            </a>
            @endguest

            @auth
            <a href="{{ route('profile.index') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40 {{ request()->routeIs('profile.index') ? 'border-white' : 'border-transparent hover:border-white/40' }}">


                <img src="{{ Auth::user()->avatar_url ?? asset('./images/user/images.png') }}" alt="Profile"
                    class="w-6 h-6 rounded-full object-cover border border-white/50">

                <span class="text-sm tracking-wide">{{ Auth::user()->name }}</span>
            </a>

            <form id="logout-form" action="{{ route('frontend.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-red-400 transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-power-off text-lg"></i>
                <span class="text-sm tracking-wide">Logout</span>
            </a>
            @endauth
            <a href="{{ route('bookmark.list') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40 {{ request()->routeIs('bookmark.list') ? 'border-white' : 'border-transparent hover:border-white/40' }}">
                <div class="relative">
                    <i class="fa-regular fa-bookmark text-lg"></i>
                    @if ($bookmarkCount > 0)
                    <span
                        class="absolute -top-2 -right-2 bg-white text-[#003b7a] text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
                        {{ $bookmarkCount > 99 ? '99+' : $bookmarkCount }}
                    </span>
                    @endif
                </div>
                <span class="text-sm tracking-wide flex items-center gap-1">Bookmark</span>
            </a>
            <div class="relative" id="notifWrapper">
                <button onclick="toggleNotifDropdown()"
                    class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                    <div class="relative">
                        <i class="fa-regular fa-bell text-lg"></i>
                        @if ($unreadCount > 0)
                        <span id="notifBadge"
                            class="absolute -top-2 -right-2 bg-white text-[#003b7a] text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                        @endif
                    </div>
                    <span class="text-sm tracking-wide">Notification</span>
                </button>

                <!-- Dropdown -->
                <div id="notifDropdown"
                    class="hidden absolute right-0 top-full mt-1 w-80 bg-white shadow-xl border border-gray-100 rounded-sm z-50">

                    <!-- Header -->
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <span class="text-sm font-semibold text-gray-700">Notifications</span>
                        @if ($unreadCount > 0)
                        <button onclick="markAllRead()"
                            class="text-[11px] text-[#0071c5] font-semibold hover:underline">
                            Mark all as read
                        </button>
                        @endif
                    </div>

                    <!-- List -->
                    <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                        @forelse($notifications as $notif)
                        @php
                        // check notification by user
                        $userId = auth()->id();
                        $readBy = $notif->read_by ?? []; //
                        $isRead = in_array($userId, $readBy);
                        @endphp

                        <a href="{{ $notif->url }}" onclick="markRead(event, {{ $notif->id }}, '{{ $notif->url }}')"
                            id="notif-{{ $notif->id }}" {{-- unread --}}
                            class="flex items-start gap-3 px-4 py-4 transition-all border-b border-gray-100
                                            {{ !$isRead ? 'bg-[#f0f7ff] border-l-4 border-l-[#0071c5]' : 'bg-white opacity-50' }} hover:bg-gray-50">

                            <div
                                class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 mt-0.5
                                                {{ $notif->type === 'asset' ? 'bg-blue-100 text-blue-600' : 'bg-teal-100 text-teal-600' }}">
                                <i
                                    class="fa-solid {{ $notif->type === 'asset' ? 'fa-file' : 'fa-bullhorn' }} text-xs"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-[13px] leading-snug {{ !$isRead ? 'font-bold text-gray-900' : 'font-normal text-gray-500' }}">
                                    {{ $notif->title }}
                                </p>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    {{ $notif->created_at->diffForHumans() }}
                                </p>
                            </div>

                            @if (!$isRead)
                            <div class="unread-dot w-2.5 h-2.5 bg-[#0071c5] rounded-full shrink-0 mt-2"></div>
                            @endif
                        </a>
                        @empty
                        <div class="px-4 py-10 text-center text-sm text-gray-400">
                            No notifications yet
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @if(auth()->user()?->isSuperAdmin())
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fa-solid fa-gauge text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Admin Dashboard</span>
            </a>
            @else
            <a href="{{ route('tickets.index') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fa-regular fa-circle-question text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Help</span>
            </a>
            <!-- Tutorial (Desktop) -->
            <a href="javascript:void(0)" id="tutorial-trigger"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fa-brands fa-readme text-lg"></i>
                <span class="text-sm tracking-wide">Tutorial</span>
            </a>
            @endif
          
        </nav>
        <!-- ── MOBILE NAV DRAWER (সব ডিভাইসে নিখুঁত কাজ করবে) ── -->
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full" class="fixed inset-0 z-50 lg:hidden overflow-hidden"
            style="display: none;">

            <!-- ব্যাকগ্রাউন্ড ওভারলে -->
            <div class="absolute inset-0 bg-black/50" @click="mobileMenu = false"></div>

            <!-- মেনু কন্টেন্ট -->
            <div class="absolute right-0 top-0 h-full w-[280px] bg-[#001e3e] shadow-2xl flex flex-col p-6 space-y-6">
                <!-- ক্লোজ বাটন -->
                <div class="flex justify-end">
                    <button @click="mobileMenu = false" class="text-white text-3xl">&times;</button>
                </div>

                <div class="flex flex-col space-y-4 overflow-y-auto">
                    <a href="{{ route('home.index') }}"
                        class="text-lg font-medium border-b border-white/10 pb-2">Home</a>
                    <a href="{{ route('home.filter', ['section' => 'campaigns']) }}"
                        class="text-lg font-medium border-b border-white/10 pb-2">Campaigns</a>
                    <a href="{{ route('home.filter', ['section' => 'assets', 'sort' => 'latest']) }}"
                        class="text-lg font-medium border-b border-white/10 pb-2">Assets</a>
                    <a href="{{ route('brand.index') }}" class="text-lg font-medium border-b border-white/10 pb-2">Brand
                        Assets</a>

                    <div class="pt-6 space-y-4">
                        @guest
                        <a href="{{ route('frontend.signin') }}" class="flex items-center gap-3"><i
                                class="fas fa-sign-in-alt"></i> Sign In</a>
                        @endguest
                        @auth
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3">
                            <img src="{{ Auth::user()->avatar_url ?? asset('./images/user/owner.jpg')}}"
                                class="w-8 h-8 rounded-full">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        @endauth

                        <a href="{{ route('bookmark.list') }}" class="flex items-center justify-between">
                            <span><i class="fa-regular fa-bookmark mr-2"></i> Saved Items</span>
                            @if($bookmarkCount > 0) <span
                                class="bg-red-500 px-2 rounded-full text-xs">{{ $bookmarkCount }}</span> @endif
                        </a>

                        <a href="{{ route('tickets.index') }}" class="flex items-center gap-3"><i
                                class="fa-regular fa-circle-question"></i> Help Center</a>

                        <a href="javascript:void(0)" id="tutorial-trigger-mobile"
                            class="text-lg font-medium border-b border-white/10 pb-2 flex items-center gap-2">
                            <i class="fa-brands fa-readme"></i> Tutorial
                        </a>


                        @auth
                        <button onclick="document.getElementById('logout-form').submit();"
                            class="text-red-400 font-bold pt-4 text-left">
                            <i class="fas fa-power-off mr-2"></i> Logout
                        </button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- MARKETING POPUP WITH YOUTUBE VIDEO -->
<div id="marketing-popup"
    class="fixed inset-0 z-[1001] flex items-center justify-center bg-black/90 transition-opacity duration-700 opacity-0"
    style="display: none;">

    <!-- কন্টেইনারের সাইজ max-w-6xl বা 7xl করলে এটি বড় দেখাবে -->
    <div class="relative w-full max-w-6xl px-4 animate-fade-in-up">

        <!-- Close Button (ভিডিওর ঠিক উপরে ডানে রাখার জন্য পজিশন) -->
        <button id="close-popup-btn"
            class="absolute -top-12 right-4 text-white text-4xl hover:text-gray-300 transition-colors focus:outline-none">
            &times;
        </button>

        <!-- Video Container (Responsive 16:9) -->
        <div class="relative w-full aspect-video bg-black rounded-xl overflow-hidden shadow-2xl border-4 border-white/10">
            <!-- এখানে id="popup-video" অবশ্যই থাকতে হবে -->
            <iframe id="popup-video"
                class="w-full h-full"
                src="https://www.youtube.com/embed/0WxwVOItNFI?si=IdQzZGsZq7jzHrhH"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const splash = document.getElementById("splash-screen");
        const popup = document.getElementById("marketing-popup");
        const closeBtn = document.getElementById("close-popup-btn");
        const videoIframe = document.getElementById("popup-video");

        const tutorialBtn = document.getElementById("tutorial-trigger");
        const tutorialBtnMobile = document.getElementById("tutorial-trigger-mobile");

        // ১. লগইন করা ইউজারের আইডি নেওয়া (Laravel থেকে)
        const userId = "{{ auth()->id() }}";
        const storageKey = "tutorial_seen_user_" + userId;

        // ২. পপআপ দেখানোর ফাংশন
        function showPopup() {
            if (popup) {
                popup.style.display = "flex";
                setTimeout(() => {
                    popup.classList.remove("opacity-0");
                    popup.classList.add("opacity-100");
                }, 100);
            }
        }

        // ৩. অটো-পপআপ লজিক (লগইনের পর প্রথমবার)
        function checkAutoPopup() {
            // যদি ইউজার লগইন অবস্থায় থাকে এবং আগে পপআপ না দেখে থাকে
            if (userId && !localStorage.getItem(storageKey)) {
                showPopup();
                // ব্রাউজারে সেভ করে রাখা যাতে পরে আর অটো না দেখায়
                localStorage.setItem(storageKey, "true");
            }
        }

        // ৪. স্প্ল্যাশ স্ক্রিন লজিক
        if (splash) {
            setTimeout(() => {
                splash.classList.replace("opacity-100", "opacity-0");
                setTimeout(() => {
                    splash.style.display = "none";
                    // স্প্ল্যাশ শেষ হওয়ার পর অটো পপআপ চেক করবে
                    checkAutoPopup();
                }, 1000);
            }, 2500);
        } else {
            // স্প্ল্যাশ না থাকলে সরাসরি চেক করবে
            checkAutoPopup();
        }

        // ৫. ম্যানুয়াল ক্লিক লজিক (বাটনে ক্লিক করলে সবসময় আসবে)
        if (tutorialBtn) {
            tutorialBtn.addEventListener("click", (e) => {
                e.preventDefault();
                showPopup();
            });
        }
        if (tutorialBtnMobile) {
            tutorialBtnMobile.addEventListener("click", (e) => {
                e.preventDefault();
                showPopup();
            });
        }

        // ৬. ক্লোজ বাটন লজিক
        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                popup.classList.replace("opacity-100", "opacity-0");
                if (videoIframe) {
                    let videoSrc = videoIframe.src;
                    videoIframe.src = "";
                    videoIframe.src = videoSrc;
                }
                setTimeout(() => {
                    popup.style.display = "none";
                }, 700);
            });
        }
    });
</script>