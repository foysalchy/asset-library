@php
    $siteSetting = \App\Models\SiteSetting::first();

@endphp

<header class="w-full bg-[#003b7a] text-white sticky top-0 z-30 shadow-md">
    <div class="container mx-auto mx-auto flex justify-between items-center py-2">
        <!-- ── LOGO ── -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('home.index') }}" class="bg-white px-2 py-2 flex items-center">
                <img src="{{ $siteSetting->logo_url }}" alt="Intel" class="h-[22px] w-auto block" />
            </a>
            <div class="flex flex-col justify-center leading-[0.9] text-white">
                <p class="text-xs uppercase tracking-[1.2px] mb-0.5 opacity-90">
                    partner <br />
                    marketing <br />
                    studio
                </p>
            </div>
        </div>

        <!-- ── NAV ── -->
        <nav class="hidden lg:flex items-stretch gap-0">
            <!-- Home (active) -->
            <a href="{{ route('home.index') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 border-b-2 border-white text-white">
                <i class="fas fa-home text-lg"></i>
                <span class="text-sm tracking-wide">Home</span>
            </a>

            <!-- Campaigns -->
            <a href="{{ route('home.filter', ['section' => 'campaigns']) }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-bullhorn text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Campaigns </span>
            </a>

            <!-- Assets -->
            <a href="{{ route('home.filter', ['section' => 'assets', 'sort' => 'latest']) }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fas fa-box text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Assets </span>
            </a>

            <!-- Brand Assets -->
            <a href="{{ route('brand.index') }}"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
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
                <a href="#"
                    class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">

                    @if (Auth::user()->getAttributes()['avatar'])
                        <img src="{{ Auth::user()->avatar }}" alt="Profile"
                            class="w-6 h-6 rounded-full object-cover border border-white/50">
                    @else
                        <img src="{{ asset('images/user/user-36.jpg') }}" alt="Profile"
                            class="w-6 h-6 rounded-full object-cover border border-white/50">
                    @endif

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
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
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
            <a href="#"
                class="flex flex-col items-center justify-center gap-[5px] px-5 py-2.5 text-white hover:text-white transition-colors border-b-2 border-transparent hover:border-white/40">
                <i class="fa-regular fa-circle-question text-lg"></i>
                <span class="text-sm tracking-wide flex items-center gap-1">Help </span>
            </a>
        </nav>
    </div>
</header>
