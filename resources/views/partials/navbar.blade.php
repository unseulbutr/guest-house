@php
    $transparent = trim($__env->yieldContent('nav_variant', 'solid')) === 'transparent';

    // Kelas warna teks/border/hover yang dipakai berulang, beda antara mode
    // transparan (di atas hero, teks putih) dan solid (bg putih, teks gelap).
    $textMain     = $transparent ? 'text-white'    : 'text-navy-900';
    $textMuted    = $transparent ? 'text-gray-200' : 'text-gray-500';
    $textHover    = $transparent ? 'hover:text-white' : 'hover:text-brand-blue';
    $borderBtn    = $transparent ? 'border-white/60 hover:bg-white/10' : 'border-gray-300 hover:bg-gray-50';
    $iconBtnHover = $transparent ? 'hover:bg-white/10' : 'hover:bg-gray-100';

    // ============ Badge notifikasi "butuh tindakan" — berlaku SEMUA role, SEMUA halaman ============
    $navBadgeLabel = null;
    $navBadgeRoute = null;
    $navBadgeCount = 0;

    if (auth()->check()) {
        $user = auth()->user();

        if ($user->hasRole('mitra')) {
            $navBadgeLabel = 'Booking Masuk';
            $navBadgeRoute = route('mitra.bookings.index');
            $navBadgeCount = \App\Models\Booking::whereHas(
                'property', fn ($q) => $q->where('mitra_id', $user->id)
            )->where('status', 'pending')->count();
        } elseif ($user->hasRole('customer')) {
            $navBadgeLabel = 'Booking Saya';
            $navBadgeRoute = route('customer.bookings.index');
            $navBadgeCount = \App\Models\Booking::where('customer_id', $user->id)
                ->where('status', 'pending')
                ->where('payment_status', 'pending')
                ->count();
        } elseif ($user->hasAnyRole(['admin', 'super_admin'])) {
            $navBadgeLabel = 'Verifikasi Properti';
            $navBadgeRoute = route('dashboard');
            $navBadgeCount = \App\Models\Property::where('status', 'pending')->count();
        }
    }
@endphp

<nav id="main-nav"
     class="fixed inset-x-0 top-0 z-[100] transition-transform duration-300 {{ $transparent ? 'bg-transparent' : 'shadow-sm' }}">
    {{-- Baris 1: logo (kiri) + menu & aksi (kanan, desktop) + hamburger (kanan, mobile) --}}
    <div class="{{ $transparent ? 'bg-transparent' : 'bg-white' }} {{ $textMain }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[80px]">

                <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-2xl tracking-tight shrink-0 {{ $textMain }}">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor" class="shrink-0">
                        <path d="M12 2 1 11h3v10h6v-6h4v6h6V11h3L12 2Z"/>
                    </svg>
                    <span>GuestHouse</span>
                </a>

                <div class="hidden lg:flex items-center gap-6">
                    <div class="flex items-center gap-6 text-base font-semibold">
                        <span class="flex items-center gap-1.5 cursor-default {{ $textMuted }}">🌐 IDR | ID</span>
                        <a href="#" class="flex items-center gap-1.5 {{ $textMuted }} {{ $textHover }} transition">% Promo</a>
                        <a href="{{ route('register') }}" class="{{ $textMuted }} {{ $textHover }} transition">Partnership</a>
                        <div class="relative" data-dropdown>
                            <button type="button" data-dropdown-toggle class="flex items-center gap-1 {{ $textMuted }} {{ $textHover }} transition">
                                Bantuan <span class="text-xs">⌄</span>
                            </button>
                            <div data-dropdown-menu class="absolute left-0 top-full pt-2 w-60 hidden">
                                <div class="bg-white text-gray-700 shadow-lg rounded-xl py-2 border border-gray-100">
                                    <a href="{{ route('support.chat') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 transition">
                                        <span class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-brand-blue shrink-0">❓</span>
                                        Pusat Bantuan
                                    </a>
                                    <a href="https://wa.me/6281234567890?text=Halo%20admin%2C%20saya%20mau%20bertanya" target="_blank" rel="noopener"
                                       class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 transition">
                                        <span class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-brand-blue shrink-0">💬</span>
                                        Hubungi Kami
                                    </a>
                                    <a href="https://instagram.com/guesthouseapp" target="_blank" rel="noopener"
                                       class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 transition">
                                        <span class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-brand-blue shrink-0">📥</span>
                                        Inbox
                                    </a>
                                </div>
                            </div>
                        </div>

                        @auth
                            @if ($navBadgeRoute)
                                <a href="{{ $navBadgeRoute }}" class="relative flex items-center gap-1.5 {{ $textMuted }} {{ $textHover }} transition">
                                    {{ $navBadgeLabel }}
                                    @if ($navBadgeCount > 0)
                                        <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none">
                                            {{ $navBadgeCount > 9 ? '9+' : $navBadgeCount }}
                                        </span>
                                    @endif
                                </a>
                            @endif
                        @endauth
                    </div>

                    <div class="flex items-center gap-3">
                        @auth
                            <div class="relative" data-dropdown>
                                <button type="button" data-dropdown-toggle
                                        class="flex items-center gap-2 text-base font-semibold px-5 py-2 rounded-full border {{ $borderBtn }} {{ $textMain }} transition">
                                    <span class="w-7 h-7 rounded-full {{ $transparent ? 'bg-white/20' : 'bg-blue-50' }} flex items-center justify-center text-sm overflow-hidden">
                                        @if (auth()->user()->avatar)
                                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        @endif
                                    </span>
                                    {{ Str::limit(auth()->user()->name, 12) }} ▾
                                </button>

                                <div data-dropdown-menu
                                     class="absolute right-0 top-full pt-2 w-48 hidden">
                                    <div class="bg-white text-gray-700 shadow-lg rounded-xl py-2 border border-gray-100">
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Dashboard</a>
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Profil Saya</a>
                                        @role('customer')
                                            <a href="{{ route('customer.saved.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Properti Disimpan</a>
                                        @endrole
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <button type="button" onclick="openLoginModal()"
                               class="text-base font-semibold px-5 py-2 rounded-full border {{ $borderBtn }} {{ $textMain }} transition">
                                Log In
                            </button>
                            <button type="button" onclick="openRegisterModal()"
                               class="{{ $transparent ? 'bg-blue-100 hover:bg-white text-navy-800' : 'bg-brand-blue hover:bg-blue-700 text-white' }} transition text-base font-semibold px-5 py-2 rounded-full">
                                Register
                            </button>
                        @endauth
                    </div>
                </div>

                <button id="mobile-menu-toggle" type="button"
                        class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg {{ $iconBtnHover }} transition {{ $textMain }}"
                        aria-label="Buka menu">
                    <svg id="icon-hamburger" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Baris 2: tab menu kategori (DESKTOP saja) --}}
    <div class="hidden lg:block {{ $transparent ? 'bg-transparent' : 'bg-white border-t border-gray-100' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8 h-12 text-base font-semibold overflow-x-auto">
                <a href="{{ route('home') }}"
                   class="whitespace-nowrap pb-0.5 transition
                   {{ request()->routeIs('home') && !request('type')
                        ? ($transparent ? 'border-b-2 border-white font-bold text-white' : 'border-b-2 border-brand-blue font-bold text-brand-blue')
                        : $textMuted . ' ' . $textHover }}">
                    Homestay
                </a>
                <a href="{{ route('home', ['type' => 'kost_harian']) }}"
                   class="whitespace-nowrap pb-0.5 transition
                   {{ request('type') === 'kost_harian'
                        ? ($transparent ? 'border-b-2 border-white font-bold text-white' : 'border-b-2 border-brand-blue font-bold text-brand-blue')
                        : $textMuted . ' ' . $textHover }}">
                    Kost Harian
                </a>
                <a href="{{ route('articles.index') }}" class="whitespace-nowrap {{ $textMuted }} {{ $textHover }} transition">Artikel & Tips</a>

                <a href="{{ route('home', ['type' => 'villa']) }}"
                   class="whitespace-nowrap pb-0.5 transition
                   {{ request('type') === 'villa'
                        ? ($transparent ? 'border-b-2 border-white font-bold text-white' : 'border-b-2 border-brand-blue font-bold text-brand-blue')
                        : $textMuted . ' ' . $textHover }}">
                    Villa
                </a>
                <a href="#" class="whitespace-nowrap {{ $textMuted }} {{ $textHover }} transition">Aktivitas & Wisata</a>
            </div>
        </div>
    </div>

    {{-- ============ MOBILE MENU ============ --}}
    <div id="mobile-menu" class="lg:hidden hidden bg-white text-navy-900 border-t border-gray-100 shadow-lg max-h-[calc(100vh-80px)] overflow-y-auto">
        <div class="px-4 py-4 space-y-1">

            @auth
                <div class="flex items-center gap-3 px-2 py-3 mb-2 border-b border-gray-100">
                    <span class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center text-sm overflow-hidden shrink-0">
                        @if (auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </span>
                    <div>
                        <div class="font-semibold text-sm">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-400 capitalize">{{ auth()->user()->getRoleNames()->first() }}</div>
                    </div>
                </div>
            @endauth

            <a href="{{ route('home') }}" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">Homestay</a>
            <a href="{{ route('home', ['type' => 'kost_harian']) }}" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">Kost Harian</a>
            <a href="{{ route('home', ['type' => 'villa']) }}" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">Villa</a>
            <a href="#" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">Aktivitas & Wisata</a>
            <a href="{{ route('articles.index') }}" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">Artikel &amp; Tips</a>
            <a href="#" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">% Promo</a>
            <a href="{{ route('register') }}" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">Partnership</a>
            <div class="px-2 pt-2 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wide">Bantuan</div>
            <a href="{{ route('support.chat') }}" class="flex items-center gap-3 px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">
                <span class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-brand-blue text-sm shrink-0">❓</span>
                Pusat Bantuan
            </a>
            <a href="https://wa.me/6281234567890?text=Halo%20admin%2C%20saya%20mau%20bertanya" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">
                <span class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-brand-blue text-sm shrink-0">💬</span>
                Hubungi Kami
            </a>
            <a href="https://instagram.com/guesthouseapp" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">
                <span class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-brand-blue text-sm shrink-0">📥</span>
                Inbox
            </a>

            @auth
                <div class="border-t border-gray-100 my-2"></div>
                <a href="{{ route('dashboard') }}" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">Profil Saya</a>
                @role('customer')
                    <a href="{{ route('customer.saved.index') }}" class="block px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">Properti Disimpan</a>
                @endrole

                @if ($navBadgeRoute)
                    <a href="{{ $navBadgeRoute }}" class="flex items-center justify-between px-2 py-2.5 rounded-lg hover:bg-gray-50 text-sm font-semibold">
                        <span>{{ $navBadgeLabel }}</span>
                        @if ($navBadgeCount > 0)
                            <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none">
                                {{ $navBadgeCount > 9 ? '9+' : $navBadgeCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="pt-1">
                    @csrf
                    <button type="submit" class="w-full text-left px-2 py-2.5 rounded-lg hover:bg-red-50 text-sm font-semibold text-red-500">Logout</button>
                </form>
            @else
                <div class="border-t border-gray-100 my-2"></div>
                <div class="flex gap-2 px-2 pt-1">
                    <button type="button" onclick="closeMobileMenuThenOpenLogin()"
                       class="flex-1 text-center text-sm font-semibold px-4 py-2.5 rounded-full border border-gray-300 hover:bg-gray-50 transition">
                        Log In
                    </button>
                    <button type="button" onclick="closeMobileMenuThenOpenRegister()"
                       class="flex-1 text-center bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-4 py-2.5 rounded-full">
                        Register
                    </button>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    function closeMobileMenuThenOpenLogin() {
        const menu = document.getElementById('mobile-menu');
        const iconHamburger = document.getElementById('icon-hamburger');
        const iconClose = document.getElementById('icon-close');
        if (menu && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            iconHamburger?.classList.remove('hidden');
            iconClose?.classList.add('hidden');
        }
        openLoginModal();
    }
    function closeMobileMenuThenOpenRegister(role) {
        const menu = document.getElementById('mobile-menu');
        const iconHamburger = document.getElementById('icon-hamburger');
        const iconClose = document.getElementById('icon-close');
        if (menu && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            iconHamburger?.classList.remove('hidden');
            iconClose?.classList.add('hidden');
        }
        openRegisterModal(role);
    }
</script>

<script>
    (function () {
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const menu = document.getElementById('mobile-menu');
        const iconHamburger = document.getElementById('icon-hamburger');
        const iconClose = document.getElementById('icon-close');
        if (!toggleBtn || !menu) return;

        toggleBtn.addEventListener('click', function () {
            const isOpen = !menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            iconHamburger.classList.toggle('hidden', !isOpen);
            iconClose.classList.toggle('hidden', isOpen);
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024 && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                iconHamburger.classList.remove('hidden');
                iconClose.classList.add('hidden');
            }
        });
    })();

    (function () {
        document.querySelectorAll('[data-dropdown]').forEach(function (wrapper) {
            const toggle = wrapper.querySelector('[data-dropdown-toggle]');
            const menu = wrapper.querySelector('[data-dropdown-menu]');
            if (!toggle || !menu) return;

            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('[data-dropdown-menu]').forEach(function (menu) {
                menu.classList.add('hidden');
            });
        });
    })();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nav = document.getElementById('main-nav');
        const anchor = document.getElementById('search-sticky-anchor');
        if (!nav || !anchor) return;

        function onStickyScroll() {
            const anchorTop = anchor.getBoundingClientRect().top;
            nav.style.transform = anchorTop <= 0 ? 'translateY(-100%)' : 'translateY(0)';
        }

        window.addEventListener('scroll', onStickyScroll, { passive: true });
        onStickyScroll();
    });
</script>