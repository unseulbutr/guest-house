@php
    $transparent = trim($__env->yieldContent('nav_variant', 'solid')) === 'transparent';

    /*
    |--------------------------------------------------------------------------
    | WARNA NAVBAR
    |--------------------------------------------------------------------------
    | Transparent  = navbar menyatu dengan hero
    | Solid       = navbar putih
    */
    $textMain = $transparent ? 'text-white' : 'text-gray-900';
    $textMuted = $transparent ? 'text-white/90' : 'text-gray-600';
    $textHover = $transparent
        ? 'hover:text-white'
        : 'hover:text-brand-blue';

    $borderBtn = $transparent
        ? 'border-white/70 hover:bg-white/10'
        : 'border-brand-blue hover:bg-blue-50';

    $iconBtnHover = $transparent
        ? 'hover:bg-white/10'
        : 'hover:bg-gray-100';


    // =========================================================================
    // BADGE NOTIFIKASI
    // =========================================================================

    $navBadgeLabel = null;
    $navBadgeRoute = null;
    $navBadgeCount = 0;

    if (auth()->check()) {
        $user = auth()->user();

        if ($user->hasRole('mitra')) {

            $navBadgeLabel = 'Booking Masuk';
            $navBadgeRoute = route('mitra.bookings.index');

            $navBadgeCount = \App\Models\Booking::whereHas(
                'property',
                fn ($q) => $q->where('mitra_id', $user->id)
            )
            ->where('status', 'pending')
            ->count();

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

            $navBadgeCount = \App\Models\Property::where('status', 'pending')
                ->count();
        }
    }
@endphp


{{-- =========================================================================
   NAVBAR UTAMA
   ========================================================================= --}}
<nav id="main-nav"
     class="fixed inset-x-0 top-0 z-[100] transition-transform duration-300
     {{ $transparent ? 'bg-transparent' : 'bg-white shadow-sm' }}">


    {{-- =========================================================================
       BARIS UTAMA
       ========================================================================= --}}
    <div class="{{ $transparent ? 'bg-transparent' : 'bg-white' }} {{ $textMain }}">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between h-[72px] lg:h-[80px]">


                {{-- =============================================================
                   LOGO
                   ============================================================= --}}
                <a href="{{ route('home') }}"
                   class="flex items-center gap-2 shrink-0
                          font-extrabold text-[23px] lg:text-[25px]
                          tracking-[-0.5px]
                          {{ $textMain }}">

                    {{-- Icon rumah --}}
                    <svg width="27"
                         height="27"
                         viewBox="0 0 24 24"
                         fill="currentColor"
                         class="shrink-0">

                        <path d="M12 2 1 11h3v10h6v-6h4v6h6V11h3L12 2Z"/>

                    </svg>

                    <span>GuestHouse</span>

                </a>


                {{-- =============================================================
                   DESKTOP MENU
                   ============================================================= --}}
                <div class="hidden lg:flex items-center gap-7">


                    {{-- =========================================================
                       MENU KIRI / TENGAH
                       ========================================================= --}}
                    <div class="flex items-center gap-6 text-[15px] font-bold">


                        {{-- Currency --}}
                        <span class="flex items-center gap-1.5
                                     cursor-default
                                     {{ $textMuted }}">

                            <span class="text-[16px]">🇮🇩</span>

                            <span>IDR | ID</span>

                            <span class="text-xs opacity-80">⌄</span>

                        </span>


                        {{-- Promo --}}
                        <a href="#"
                           class="flex items-center gap-1.5
                                  {{ $textMuted }}
                                  {{ $textHover }}
                                  transition-colors duration-200">

                            <span class="text-base">%</span>
                            Promo

                        </a>


                        {{-- Partnership --}}
                        <a href="{{ route('register') }}"
                           class="{{ $textMuted }}
                                  {{ $textHover }}
                                  transition-colors duration-200">

                            Partnership

                        </a>


                        {{-- =====================================================
                           BANTUAN DROPDOWN
                           ===================================================== --}}
                        <div class="relative" data-dropdown>

                            <button type="button"
                                    data-dropdown-toggle
                                    class="flex items-center gap-1
                                           {{ $textMuted }}
                                           {{ $textHover }}
                                           transition-colors duration-200">

                                Bantuan

                                <span class="text-xs opacity-80">
                                    ⌄
                                </span>

                            </button>


                            <div data-dropdown-menu
                                 class="absolute left-0 top-full pt-3
                                        w-60 hidden">

                                <div class="bg-white text-gray-700
                                            shadow-xl rounded-xl
                                            py-2
                                            border border-gray-100">


                                    {{-- Pusat Bantuan --}}
                                    <a href="{{ route('support.chat') }}"
                                       class="flex items-center gap-3
                                              px-4 py-3
                                              text-sm font-semibold
                                              hover:bg-gray-50
                                              transition">

                                        <span class="w-9 h-9 rounded-full
                                                     bg-blue-50
                                                     flex items-center
                                                     justify-center
                                                     text-brand-blue
                                                     shrink-0">

                                            ❓

                                        </span>

                                        <span>
                                            Pusat Bantuan
                                        </span>

                                    </a>


                                    {{-- WhatsApp --}}
                                    <a href="https://wa.me/6281234567890?text=Halo%20admin%2C%20saya%20mau%20bertanya"
                                       target="_blank"
                                       rel="noopener"
                                       class="flex items-center gap-3
                                              px-4 py-3
                                              text-sm font-semibold
                                              hover:bg-gray-50
                                              transition">

                                        <span class="w-9 h-9 rounded-full
                                                     bg-blue-50
                                                     flex items-center
                                                     justify-center
                                                     text-brand-blue
                                                     shrink-0">

                                            💬

                                        </span>

                                        <span>
                                            Hubungi Kami
                                        </span>

                                    </a>


                                    {{-- Instagram --}}
                                    <a href="https://instagram.com/guesthouseapp"
                                       target="_blank"
                                       rel="noopener"
                                       class="flex items-center gap-3
                                              px-4 py-3
                                              text-sm font-semibold
                                              hover:bg-gray-50
                                              transition">

                                        <span class="w-9 h-9 rounded-full
                                                     bg-blue-50
                                                     flex items-center
                                                     justify-center
                                                     text-brand-blue
                                                     shrink-0">

                                            📥

                                        </span>

                                        <span>
                                            Inbox
                                        </span>

                                    </a>

                                </div>

                            </div>

                        </div>


                        {{-- =====================================================
                           BADGE BOOKING
                           ===================================================== --}}
                        @auth

                            @if ($navBadgeRoute)

                                <a href="{{ $navBadgeRoute }}"
                                   class="relative flex items-center gap-1.5
                                          {{ $textMuted }}
                                          {{ $textHover }}
                                          transition-colors duration-200">

                                    {{ $navBadgeLabel }}

                                    @if ($navBadgeCount > 0)

                                        <span class="inline-flex items-center
                                                     justify-center
                                                     min-w-[18px]
                                                     h-[18px]
                                                     px-1
                                                     rounded-full
                                                     bg-red-500
                                                     text-white
                                                     text-[10px]
                                                     font-bold
                                                     leading-none">

                                            {{ $navBadgeCount > 9 ? '9+' : $navBadgeCount }}

                                        </span>

                                    @endif

                                </a>

                            @endif

                        @endauth

                    </div>


                    {{-- =========================================================
                       BUTTON AKUN
                       ========================================================= --}}
                    <div class="flex items-center gap-3">


                        @auth

                            {{-- =================================================
                               USER LOGIN
                               ================================================= --}}
                            <div class="relative" data-dropdown>

                                <button type="button"
                                        data-dropdown-toggle
                                        class="flex items-center gap-2
                                               text-[15px]
                                               font-bold
                                               px-4
                                               py-2
                                               rounded-full
                                               border
                                               {{ $borderBtn }}
                                               {{ $textMain }}
                                               transition">

                                    <span class="w-7 h-7 rounded-full
                                                 {{ $transparent
                                                    ? 'bg-white/20'
                                                    : 'bg-blue-50' }}
                                                 flex items-center
                                                 justify-center
                                                 text-sm
                                                 overflow-hidden">

                                        @if (auth()->user()->avatar)

                                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                                 class="w-full h-full object-cover">

                                        @else

                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                                        @endif

                                    </span>

                                    {{ Str::limit(auth()->user()->name, 12) }}

                                    <span class="text-xs">
                                        ▾
                                    </span>

                                </button>


                                {{-- User Dropdown --}}
                                <div data-dropdown-menu
                                     class="absolute right-0 top-full pt-3
                                            w-52 hidden">

                                    <div class="bg-white
                                                text-gray-700
                                                shadow-xl
                                                rounded-xl
                                                py-2
                                                border border-gray-100">


                                        <a href="{{ route('dashboard') }}"
                                           class="block px-4 py-3
                                                  text-sm font-semibold
                                                  hover:bg-gray-50
                                                  transition">

                                            Dashboard

                                        </a>


                                        <a href="{{ route('profile.edit') }}"
                                           class="block px-4 py-3
                                                  text-sm font-semibold
                                                  hover:bg-gray-50
                                                  transition">

                                            Profil Saya

                                        </a>


                                        @role('customer')

                                            <a href="{{ route('customer.saved.index') }}"
                                               class="block px-4 py-3
                                                      text-sm font-semibold
                                                      hover:bg-gray-50
                                                      transition">

                                                Properti Disimpan

                                            </a>

                                        @endrole


                                        <form method="POST"
                                              action="{{ route('logout') }}">

                                            @csrf

                                            <button type="submit"
                                                    class="w-full
                                                           text-left
                                                           px-4 py-3
                                                           text-sm
                                                           font-semibold
                                                           text-red-500
                                                           hover:bg-red-50
                                                           transition">

                                                Logout

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>


                        @else


                            {{-- =================================================
                               LOGIN BUTTON
                               ================================================= --}}
                            <button type="button"
                                    onclick="openLoginModal()"
                                    class="flex items-center
                                           gap-2
                                           text-[15px]
                                           font-bold
                                           px-5
                                           py-2.5
                                           rounded-full
                                           border-2
                                           transition
                                           {{ $transparent
                                                ? 'bg-white text-brand-blue border-white hover:bg-blue-50'
                                                : 'bg-white text-brand-blue border-brand-blue hover:bg-blue-50' }}">


                                <svg class="w-4 h-4"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24"
                                     stroke-width="2.2">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>

                                </svg>

                                Log In

                            </button>


                            {{-- =================================================
                               REGISTER BUTTON
                               ================================================= --}}
                            <button type="button"
                                    onclick="openRegisterModal()"
                                    class="bg-brand-blue
                                           hover:bg-blue-700
                                           transition
                                           text-white
                                           text-[15px]
                                           font-bold
                                           px-6
                                           py-2.5
                                           rounded-full
                                           shadow-sm">

                                Register

                            </button>

                        @endauth

                    </div>

                </div>


                {{-- =============================================================
                   MOBILE HAMBURGER
                   ============================================================= --}}
                <button id="mobile-menu-toggle"
                        type="button"
                        class="lg:hidden
                               flex items-center
                               justify-center
                               w-10 h-10
                               rounded-lg
                               {{ $iconBtnHover }}
                               transition
                               {{ $textMain }}"
                        aria-label="Buka menu">

                    {{-- Hamburger --}}
                    <svg id="icon-hamburger"
                         class="w-6 h-6"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>

                    </svg>


                    {{-- Close --}}
                    <svg id="icon-close"
                         class="w-6 h-6 hidden"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>

        </div>

    </div>



    {{-- =========================================================================
       BARIS KEDUA — KATEGORI
       ========================================================================= --}}
    <div class="hidden lg:block
                {{ $transparent
                    ? 'bg-transparent'
                    : 'bg-white border-t border-gray-100' }}">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center
                        gap-8
                        h-12
                        text-[15px]
                        font-bold
                        overflow-x-auto">


                {{-- Homestay --}}
                <a href="{{ route('home') }}"
                   class="whitespace-nowrap
                          pb-1
                          transition-colors duration-200
                          {{ request()->routeIs('home') && !request('type')
                                ? ($transparent
                                    ? 'border-b-2 border-white text-white'
                                    : 'border-b-2 border-brand-blue text-brand-blue')
                                : $textMuted . ' ' . $textHover }}">

                    Homestay

                </a>


                {{-- Kost Harian --}}
                <a href="{{ route('home', ['type' => 'kost_harian']) }}"
                   class="whitespace-nowrap
                          pb-1
                          transition-colors duration-200
                          {{ request('type') === 'kost_harian'
                                ? ($transparent
                                    ? 'border-b-2 border-white text-white'
                                    : 'border-b-2 border-brand-blue text-brand-blue')
                                : $textMuted . ' ' . $textHover }}">

                    Kost Harian

                </a>


                {{-- Artikel --}}
                <a href="{{ route('articles.index') }}"
                   class="whitespace-nowrap
                          pb-1
                          {{ $textMuted }}
                          {{ $textHover }}
                          transition-colors duration-200">

                    Artikel & Tips

                </a>


                {{-- Villa --}}
                <a href="{{ route('home', ['type' => 'villa']) }}"
                   class="whitespace-nowrap
                          pb-1
                          transition-colors duration-200
                          {{ request('type') === 'villa'
                                ? ($transparent
                                    ? 'border-b-2 border-white text-white'
                                    : 'border-b-2 border-brand-blue text-brand-blue')
                                : $textMuted . ' ' . $textHover }}">

                    Villa

                </a>


                {{-- Aktivitas --}}
                <a href="#"
                   class="whitespace-nowrap
                          pb-1
                          {{ $textMuted }}
                          {{ $textHover }}
                          transition-colors duration-200">

                    Aktivitas & Wisata

                </a>

            </div>

        </div>

    </div>



    {{-- =========================================================================
       MOBILE MENU
       ========================================================================= --}}
    <div id="mobile-menu"
         class="lg:hidden
                hidden
                bg-white
                text-navy-900
                border-t
                border-gray-100
                shadow-xl
                max-h-[calc(100vh-72px)]
                overflow-y-auto">

        <div class="px-4 py-4 space-y-1">


            {{-- =============================================================
               USER INFO
               ============================================================= --}}
            @auth

                <div class="flex items-center
                            gap-3
                            px-2 py-3
                            mb-2
                            border-b
                            border-gray-100">

                    <span class="w-9 h-9
                                 rounded-full
                                 bg-blue-50
                                 flex items-center
                                 justify-center
                                 text-sm
                                 overflow-hidden
                                 shrink-0">

                        @if (auth()->user()->avatar)

                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                 class="w-full h-full object-cover">

                        @else

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                        @endif

                    </span>


                    <div>

                        <div class="font-bold text-sm">
                            {{ auth()->user()->name }}
                        </div>

                        <div class="text-xs text-gray-400 capitalize">
                            {{ auth()->user()->getRoleNames()->first() }}
                        </div>

                    </div>

                </div>

            @endauth


            {{-- =============================================================
               MOBILE LINKS
               ============================================================= --}}

            <a href="{{ route('home') }}"
               class="block px-2 py-3 rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                Homestay

            </a>


            <a href="{{ route('home', ['type' => 'kost_harian']) }}"
               class="block px-2 py-3 rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                Kost Harian

            </a>


            <a href="{{ route('home', ['type' => 'villa']) }}"
               class="block px-2 py-3 rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                Villa

            </a>


            <a href="#"
               class="block px-2 py-3 rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                Aktivitas & Wisata

            </a>


            <a href="{{ route('articles.index') }}"
               class="block px-2 py-3 rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                Artikel &amp; Tips

            </a>


            <a href="#"
               class="block px-2 py-3 rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                % Promo

            </a>


            <a href="{{ route('register') }}"
               class="block px-2 py-3 rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                Partnership

            </a>


            {{-- =============================================================
               BANTUAN
               ============================================================= --}}
            <div class="px-2 pt-3 pb-1
                        text-[11px]
                        font-bold
                        text-gray-400
                        uppercase
                        tracking-wide">

                Bantuan

            </div>


            <a href="{{ route('support.chat') }}"
               class="flex items-center
                      gap-3
                      px-2 py-3
                      rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                <span class="w-8 h-8
                             rounded-full
                             bg-blue-50
                             flex items-center
                             justify-center
                             text-brand-blue
                             text-sm
                             shrink-0">

                    ❓

                </span>

                Pusat Bantuan

            </a>


            <a href="https://wa.me/6281234567890?text=Halo%20admin%2C%20saya%20mau%20bertanya"
               target="_blank"
               rel="noopener"
               class="flex items-center
                      gap-3
                      px-2 py-3
                      rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                <span class="w-8 h-8
                             rounded-full
                             bg-blue-50
                             flex items-center
                             justify-center
                             text-brand-blue
                             text-sm
                             shrink-0">

                    💬

                </span>

                Hubungi Kami

            </a>


            <a href="https://instagram.com/guesthouseapp"
               target="_blank"
               rel="noopener"
               class="flex items-center
                      gap-3
                      px-2 py-3
                      rounded-lg
                      hover:bg-gray-50
                      text-sm font-bold">

                <span class="w-8 h-8
                             rounded-full
                             bg-blue-50
                             flex items-center
                             justify-center
                             text-brand-blue
                             text-sm
                             shrink-0">

                    📥

                </span>

                Inbox

            </a>


            {{-- =============================================================
               USER MENU
               ============================================================= --}}
            @auth

                <div class="border-t border-gray-100 my-2"></div>


                <a href="{{ route('dashboard') }}"
                   class="block px-2 py-3
                          rounded-lg
                          hover:bg-gray-50
                          text-sm font-bold">

                    Dashboard

                </a>


                <a href="{{ route('profile.edit') }}"
                   class="block px-2 py-3
                          rounded-lg
                          hover:bg-gray-50
                          text-sm font-bold">

                    Profil Saya

                </a>


                @role('customer')

                    <a href="{{ route('customer.saved.index') }}"
                       class="block px-2 py-3
                              rounded-lg
                              hover:bg-gray-50
                              text-sm font-bold">

                        Properti Disimpan

                    </a>

                @endrole


                @if ($navBadgeRoute)

                    <a href="{{ $navBadgeRoute }}"
                       class="flex items-center
                              justify-between
                              px-2 py-3
                              rounded-lg
                              hover:bg-gray-50
                              text-sm font-bold">

                        <span>
                            {{ $navBadgeLabel }}
                        </span>


                        @if ($navBadgeCount > 0)

                            <span class="inline-flex
                                         items-center
                                         justify-center
                                         min-w-[18px]
                                         h-[18px]
                                         px-1
                                         rounded-full
                                         bg-red-500
                                         text-white
                                         text-[10px]
                                         font-bold
                                         leading-none">

                                {{ $navBadgeCount > 9 ? '9+' : $navBadgeCount }}

                            </span>

                        @endif

                    </a>

                @endif


                {{-- Logout --}}
                <form method="POST"
                      action="{{ route('logout') }}"
                      class="pt-1">

                    @csrf

                    <button type="submit"
                            class="w-full
                                   text-left
                                   px-2 py-3
                                   rounded-lg
                                   hover:bg-red-50
                                   text-sm
                                   font-bold
                                   text-red-500">

                        Logout

                    </button>

                </form>


            @else

                {{-- =========================================================
                   MOBILE LOGIN / REGISTER
                   ========================================================= --}}
                <div class="border-t border-gray-100 my-2"></div>


                <div class="flex gap-2 px-2 pt-2">


                    {{-- Login --}}
                    <button type="button"
                            onclick="closeMobileMenuThenOpenLogin()"
                            class="flex-1
                                   flex items-center
                                   justify-center
                                   gap-2
                                   text-center
                                   text-sm
                                   font-bold
                                   px-4
                                   py-2.5
                                   rounded-full
                                   border-2
                                   border-brand-blue
                                   text-brand-blue
                                   hover:bg-blue-50
                                   transition">

                        <svg class="w-4 h-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24"
                             stroke-width="2.2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>

                        </svg>

                        Log In

                    </button>


                    {{-- Register --}}
                    <button type="button"
                            onclick="closeMobileMenuThenOpenRegister()"
                            class="flex-1
                                   text-center
                                   bg-brand-blue
                                   hover:bg-blue-700
                                   transition
                                   text-white
                                   text-sm
                                   font-bold
                                   px-4
                                   py-2.5
                                   rounded-full">

                        Register

                    </button>

                </div>

            @endauth

        </div>

    </div>

</nav>



{{-- =========================================================================
   MOBILE MENU FUNCTIONS
   ========================================================================= --}}
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



{{-- =========================================================================
   MOBILE TOGGLE
   ========================================================================= --}}
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

            iconHamburger.classList.toggle(
                'hidden',
                !isOpen
            );

            iconClose.classList.toggle(
                'hidden',
                isOpen
            );

        });


        window.addEventListener('resize', function () {

            if (
                window.innerWidth >= 1024 &&
                !menu.classList.contains('hidden')
            ) {

                menu.classList.add('hidden');

                iconHamburger.classList.remove('hidden');
                iconClose.classList.add('hidden');

            }

        });

    })();

</script>



{{-- =========================================================================
   DROPDOWN
   ========================================================================= --}}
<script>

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

            document
                .querySelectorAll('[data-dropdown-menu]')
                .forEach(function (menu) {

                    menu.classList.add('hidden');

                });

        });

    })();

</script>



{{-- =========================================================================
   HIDE NAVBAR SAAT SEARCH BAR STICKY
   ========================================================================= --}}
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const nav = document.getElementById('main-nav');
        const anchor = document.getElementById('search-sticky-anchor');

        if (!nav || !anchor) return;


        function onStickyScroll() {

            const anchorTop = anchor.getBoundingClientRect().top;

            if (anchorTop <= 0) {

                nav.style.transform = 'translateY(-100%)';

            } else {

                nav.style.transform = 'translateY(0)';

            }

        }


        window.addEventListener(
            'scroll',
            onStickyScroll,
            { passive: true }
        );


        onStickyScroll();

    });

</script>