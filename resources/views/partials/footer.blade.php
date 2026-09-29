<footer class="relative mt-16 overflow-hidden bg-[#061c24] text-white">

    @php
        $footerSettings = \App\Models\Setting::current();
    @endphp

    {{-- =====================================================
         SUBTLE TOPOGRAPHIC BACKGROUND
         ===================================================== --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.10]">

        <svg
            class="w-full h-full"
            viewBox="0 0 1600 900"
            preserveAspectRatio="none"
            xmlns="http://www.w3.org/2000/svg"
        >

            <g
                fill="none"
                stroke="rgba(255,255,255,0.35)"
                stroke-width="1"
            >

                <path d="M-100 120C80 20 170 220 350 100S620 40 760 150s260 100 420-20 270-130 470 10"/>
                <path d="M-120 180C80 80 180 280 360 160s270-60 410 50 260 100 430-20 280-120 480 20"/>
                <path d="M-150 240C50 140 190 340 380 220s270-50 400 60 270 100 440-20 300-100 480 30"/>

                <path d="M-100 520C80 400 210 620 390 490s250-100 410 20 280 130 450-20 250-150 470-20"/>
                <path d="M-130 580C70 460 220 680 410 550s250-100 410 20 290 120 460-30 260-140 470-10"/>
                <path d="M-160 640C50 520 230 740 430 610s240-90 400 30 300 110 470-40 270-130 480 0"/>

                <path d="M900 -80C780 60 980 170 870 300s-20 270 130 350 220 190 110 350"/>
                <path d="M1010 -100C890 50 1090 160 980 290s-20 280 130 360 220 190 110 350"/>
                <path d="M1120 -100C1000 40 1200 150 1090 280s-10 290 140 370 210 190 100 350"/>

                <path d="M300 -100C180 50 360 150 250 270s-10 250 130 330 190 180 100 300"/>
                <path d="M410 -100C290 40 470 140 360 260s-10 260 130 340 190 180 100 300"/>

            </g>

        </svg>

    </div>


    {{-- Soft glow --}}
    <div
        class="absolute -top-40 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-cyan-500/10 blur-3xl rounded-full pointer-events-none"
    ></div>


    {{-- =====================================================
         MAIN FOOTER
         ===================================================== --}}
    <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 pt-16 pb-12">


        {{-- =================================================
             BRAND
             ================================================= --}}
        <div class="text-center max-w-2xl mx-auto">

            <a
                href="{{ route('home') }}"
                class="inline-flex items-center justify-center gap-3 group"
            >

                {{-- Logo icon --}}
                <span
                    class="w-11 h-11 rounded-xl bg-cyan-500/10 border border-cyan-400/30 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500/20 transition"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 10.5L12 4l9 6.5M5 9.5V20h14V9.5M9 20v-5h6v5"
                        />
                    </svg>

                </span>


                {{-- Brand --}}
                <span class="font-logo text-4xl sm:text-5xl tracking-tight text-white">

                    GUEST HOUSE<span class="text-cyan-400">.</span>

                </span>

            </a>


            <p class="mt-6 text-sm sm:text-base leading-7 text-slate-300/80">
                Platform booking guest house dan kost harian terpercaya
                untuk menemukan tempat menginap yang nyaman di seluruh Indonesia.
            </p>

        </div>


        {{-- =================================================
             CONTENT GRID
             ================================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 mt-16">


            {{-- =================================================
                 CTA / ABOUT
                 ================================================= --}}
            <div class="lg:col-span-5">

                <p class="text-xs uppercase tracking-[0.25em] font-semibold text-cyan-400 mb-4">
                    Temukan Penginapan
                </p>

                <h2 class="font-display text-2xl sm:text-3xl font-bold leading-tight text-white">
                    Cari tempat nyaman untuk perjalananmu.
                </h2>

                <p class="mt-4 text-sm leading-6 text-slate-300/75 max-w-lg">
                    Jelajahi berbagai guest house dan kost harian,
                    bandingkan pilihan yang tersedia, lalu pesan
                    penginapan sesuai kebutuhanmu.
                </p>


                {{-- CTA Button --}}
                <div class="mt-7">

                    <a
                        href="{{ route('home') }}"
                        class="inline-flex items-center gap-3 rounded-full bg-cyan-500 hover:bg-cyan-400 text-white px-6 py-3.5 text-sm font-bold shadow-lg shadow-cyan-950/30 transition duration-200"
                    >

                        Mulai Cari Penginapan

                        <span
                            class="w-7 h-7 rounded-full bg-white/15 flex items-center justify-center"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 12h14m-6-6l6 6-6 6"
                                />
                            </svg>

                        </span>

                    </a>

                </div>

            </div>


            {{-- =================================================
                 JELAJAH
                 ================================================= --}}
            <div class="lg:col-span-2">

                <h3 class="text-base font-bold text-white mb-5">
                    Jelajahi
                </h3>

                <ul class="space-y-4 text-sm">

                    <li>
                        <a
                            href="{{ route('home') }}"
                            class="text-slate-300/75 hover:text-cyan-400 transition"
                        >
                            Cari Properti
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('articles.index') }}"
                            class="text-slate-300/75 hover:text-cyan-400 transition"
                        >
                            Artikel & Tips
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('register') }}"
                            class="text-slate-300/75 hover:text-cyan-400 transition"
                        >
                            Jadi Mitra
                        </a>
                    </li>

                </ul>

            </div>


            {{-- =================================================
                 UNTUK MITRA
                 ================================================= --}}
            <div class="lg:col-span-2">

                <h3 class="text-base font-bold text-white mb-5">
                    Untuk Mitra
                </h3>

                <ul class="space-y-4 text-sm">

                    <li>
                        <a
                            href="{{ route('register') }}"
                            class="text-slate-300/75 hover:text-cyan-400 transition"
                        >
                            Daftarkan Properti
                        </a>
                    </li>

                    <li>
                        <span class="text-slate-300/75">
                            Skema Mandiri
                        </span>

                        <span class="block mt-1 text-xs text-slate-500">
                            Komisi 15%
                        </span>
                    </li>

                    <li>
                        <span class="text-slate-300/75">
                            Skema Dikelola
                        </span>

                        <span class="block mt-1 text-xs text-slate-500">
                            Komisi 45%, termasuk pajak
                        </span>
                    </li>

                </ul>

            </div>


            {{-- =================================================
                 KONTAK
                 HANYA EMAIL + NOMOR TELEPON
                 ================================================= --}}
            <div class="lg:col-span-3">

                <h3 class="text-base font-bold text-white mb-5">
                    Hubungi Kami
                </h3>

                <ul class="space-y-4 text-sm">


                    {{-- =================================================
                         EMAIL
                         ================================================= --}}
                    <li>

                        <div class="flex items-start gap-3">

                            <span class="mt-0.5 text-cyan-400">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>

                            </span>

                            <div>

                                <p class="text-xs text-slate-500 mb-1">
                                    Email
                                </p>

                                <span class="text-slate-300/80">
                                    {{ $footerSettings->contact_email ?: 'support@nginep.co.id' }}
                                </span>

                            </div>

                        </div>

                    </li>


                    {{-- =================================================
                         NOMOR TELEPON / WHATSAPP
                         ================================================= --}}
                    <li>

                        <div class="flex items-start gap-3">

                            <span class="mt-0.5 text-cyan-400">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M22 16.92v3a2 2 0 01-2.18 2
                                        19.79 19.79 0 01-8.63-3.07
                                        19.5 19.5 0 01-6-6
                                        19.79 19.79 0 01-3.07-8.67
                                        A2 2 0 014.11 2h3a2 2 0 012 1.72
                                        12.84 12.84 0 00.7 2.81
                                        2 2 0 01-.45 2.11L8.09 9.91
                                        a16 16 0 006 6l1.27-1.27
                                        a2 2 0 012.11-.45
                                        12.84 12.84 0 002.81.7
                                        A2 2 0 0122 16.92z"
                                    />
                                </svg>

                            </span>

                            <div>

                                <p class="text-xs text-slate-500 mb-1">
                                    WhatsApp
                                </p>

                                <span class="text-slate-300/80">
                                    {{ $footerSettings->contact_phone ?: '08xx-xxxx-xxxx' }}
                                </span>

                            </div>

                        </div>

                    </li>


                    {{-- =================================================
                         QRIS
                         ================================================= --}}
                    <li>

                        <div class="flex items-start gap-3">

                            <span class="mt-0.5 text-cyan-400">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 2h2m2-2h2v2m-4 2h2v2h-2v-2z"
                                    />
                                </svg>

                            </span>

                            <div>

                                <p class="text-xs text-slate-500 mb-1">
                                    Pembayaran
                                </p>

                                <span class="text-slate-300/80">
                                    Aman melalui QRIS
                                </span>

                            </div>

                        </div>

                    </li>

                </ul>

            </div>

        </div>


        {{-- =================================================
             DIVIDER
             ================================================= --}}
        <div class="mt-16 border-t border-white/10"></div>


        {{-- =================================================
             BOTTOM BAR
             ================================================= --}}
        <div class="pt-7 flex flex-col lg:flex-row items-center justify-between gap-6">


            {{-- Copyright --}}
            <div class="text-xs text-slate-400 text-center lg:text-left">

                <p>
                    &copy; {{ date('Y') }}

                    <span class="text-slate-300 font-semibold">
                        NGINEP.
                    </span>

                    All rights reserved.
                </p>

                <p class="mt-1 text-slate-500">
                    Dibuat dengan ❤️ di Indonesia
                </p>

            </div>


            {{-- Middle links --}}
            <div class="flex flex-wrap justify-center items-center gap-x-6 gap-y-2 text-xs">

                <a
                    href="{{ route('home') }}"
                    class="text-slate-400 hover:text-cyan-400 transition"
                >
                    Beranda
                </a>

                <span class="text-white/10">
                    |
                </span>

                <a
                    href="{{ route('articles.index') }}"
                    class="text-slate-400 hover:text-cyan-400 transition"
                >
                    Artikel
                </a>

                <span class="text-white/10">
                    |
                </span>

                <a
                    href="{{ route('register') }}"
                    class="text-slate-400 hover:text-cyan-400 transition"
                >
                    Jadi Mitra
                </a>

            </div>


            {{-- Back to top --}}
            <button
                type="button"
                onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                class="group inline-flex items-center gap-2 rounded-full border border-white/30 hover:border-cyan-400 px-4 py-2.5 text-xs font-semibold text-slate-300 hover:text-white transition"
            >

                <span
                    class="w-6 h-6 rounded-full bg-cyan-500 group-hover:bg-cyan-400 flex items-center justify-center text-white transition"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-3.5 h-3.5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 15l7-7 7 7"
                        />
                    </svg>

                </span>

                Kembali ke atas

            </button>

        </div>

    </div>

</footer>