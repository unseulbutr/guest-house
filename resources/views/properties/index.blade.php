@extends('layouts.app')

@section('title', 'Booking Guest House & Kost Harian Terpercaya')
@section('meta_description', 'Tersedia ratusan guest house dan kost harian siap dipesan. Jaminan pelayanan dan ketersediaan sesuai aplikasi.')
@section('nav_variant', 'transparent')

@section('content')

    {{-- ============ HERO SECTION (ilustrasi guesthouse + navbar transparan menyatu) ============ --}}
    <section class="relative text-white h-[360px] sm:h-[420px] md:h-[480px] overflow-hidden">
        @include('partials.hero-illustration')

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pb-12 md:pb-14 pt-28">
            <h1 class="font-display text-2xl md:text-3xl font-extrabold mb-1 drop-shadow-sm">
                @if (request('location'))
                    Guest House &amp; Kost Harian di {{ request('location') }}
                @else
                    Guest House &amp; Kost Harian di Yogyakarta
                @endif
            </h1>
            <p class="text-gray-100 max-w-xl text-sm md:text-base drop-shadow-sm">
                Wujudkan perjalananmu yang nyaman, temukan properti terbaik untuk setiap tujuanmu.
            </p>
        </div>

        {{-- Wave divider: transisi halus dari foto ke background halaman, bukan potongan tegas --}}
        <svg class="absolute bottom-0 left-0 w-full text-blue-50/60" viewBox="0 0 1440 60" preserveAspectRatio="none" style="height:48px">
            <path fill="currentColor" d="M0,32 C240,60 480,0 720,16 C960,32 1200,58 1440,24 L1440,60 L0,60 Z"></path>
        </svg>
    </section>

    {{-- Anchor: dipakai script sticky buat tahu kapan search bar sampai di atas --}}
    <div id="search-sticky-anchor"></div>

    {{-- ============ SEARCH CARD (satu kartu menyatu + garis pemisah tipis, ala Traveloka) ============ --}}
    {{-- PENTING: sticky cuma aktif dari breakpoint md ke atas (md:sticky). Di mode HP,
         field-nya numpuk vertikal (flex-col, lihat form di bawah) jadi card-nya jauh
         lebih tinggi — kalau tetap sticky di HP, dia bakal nempel di atas layar dan
         nutupin konten di bawahnya (ini yang bikin "Ide Tanggal Menginap" ketutup).
         Di laptop/desktop field-nya sejajar horizontal (md:flex-row) jadi card-nya
         pendek, aman buat sticky seperti biasa — behavior desktop tidak berubah. --}}
    <div id="search-sticky-wrap" class="md:sticky md:top-0 z-30">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <form id="search-form" action="{{ route('home') }}" method="GET"
              class="relative bg-white/90 backdrop-blur-md rounded-xl shadow-floating flex flex-col md:flex-row items-stretch
                     divide-y divide-gray-200 md:divide-y-0 md:divide-x md:divide-gray-200">

            {{-- Kota / destinasi --}}
            <div class="flex-[1.4] px-5 py-3">
                <label class="block text-xs font-semibold text-gray-500 mb-1">
                    City, destination, or hotel name
                </label>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <input type="text" name="location" placeholder="Mau nginep di mana?"
                           value="{{ request('location') }}"
                           class="w-full bg-transparent outline-none text-[15px] sm:text-base font-bold text-gray-900 placeholder:font-medium placeholder:text-gray-400">
                </div>
            </div>

            {{-- Check-in / Check-out (date range picker) --}}
            <div class="flex-1 px-5 py-3 relative" id="date-field">
                <label class="block text-xs font-semibold text-gray-500 mb-1">
                    Check-In &amp; Check-out Dates
                </label>
                <button type="button" id="date-trigger"
                        class="w-full flex items-center gap-2 text-left focus:outline-none">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <span id="date-display" class="text-[15px] sm:text-base font-bold text-gray-900 truncate">
                        Pilih tanggal check-in &amp; check-out
                    </span>
                </button>
                <input type="hidden" name="check_in" id="check_in_input" value="{{ request('check_in') }}">
                <input type="hidden" name="check_out" id="check_out_input" value="{{ request('check_out') }}">

                {{-- Dropdown kalender 2 bulan --}}
                <div id="date-panel"
                     class="hidden absolute left-0 md:left-auto md:right-0 top-full mt-2 z-30 bg-white rounded-xl shadow-floating border border-gray-100 p-4 w-[92vw] max-w-[640px]">
                    <div class="flex items-center justify-between mb-3">
                        <button type="button" id="cal-prev" class="w-8 h-8 rounded-full hover:bg-gray-100 text-gray-500">‹</button>
                        <div class="flex-1 grid grid-cols-2 gap-4 text-center">
                            <span id="cal-title-0" class="font-display font-bold text-navy-900 text-sm"></span>
                            <span id="cal-title-1" class="font-display font-bold text-navy-900 text-sm"></span>
                        </div>
                        <button type="button" id="cal-next" class="w-8 h-8 rounded-full hover:bg-gray-100 text-gray-500">›</button>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div id="cal-grid-0" class="grid grid-cols-7 gap-y-1 text-center text-xs"></div>
                        <div id="cal-grid-1" class="grid grid-cols-7 gap-y-1 text-center text-xs"></div>
                    </div>
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                        <button type="button" id="date-reset" class="text-sm font-semibold text-gray-500 hover:text-navy-900 px-3 py-2">Reset</button>
                        <button type="button" id="date-apply" class="bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-6 py-2 rounded-full">Apply</button>
                    </div>
                </div>
            </div>

            {{-- Guests & Rooms --}}
            <div class="flex-1 px-5 py-3 relative" id="guest-field">
                <label class="block text-xs font-semibold text-gray-500 mb-1">
                    Guests and Rooms
                </label>
                <button type="button" id="guest-trigger" class="w-full flex items-center justify-between gap-2 focus:outline-none">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span id="guest-display" class="text-[15px] sm:text-base font-bold text-gray-900">2 Adult(s), 0 children, 1 room</span>
                    </span>
                    <span class="text-gray-400 text-xs">⌄</span>
                </button>
                <input type="hidden" name="adults" id="adults_input" value="2">
                <input type="hidden" name="children" id="children_input" value="0">
                <input type="hidden" name="rooms" id="rooms_input" value="1">

                <div id="guest-panel"
                     class="hidden absolute left-0 md:left-auto md:right-0 top-full mt-2 z-30 bg-white rounded-xl shadow-floating border border-gray-100 p-4 w-72">
                    @foreach ([['adults', 'Adults', 2, 1], ['children', 'Children', 0, 0], ['rooms', 'Rooms', 1, 1]] as [$key, $label, $default, $min])
                        <div class="flex items-center justify-between py-2.5 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <span class="text-sm font-medium text-navy-900">{{ $label }}</span>
                            <div class="flex items-center gap-3">
                                <button type="button" data-counter="{{ $key }}" data-action="dec" data-min="{{ $min }}"
                                        class="w-7 h-7 rounded-full border border-gray-300 text-gray-500 hover:border-brand-blue hover:text-brand-blue transition">−</button>
                                <span id="{{ $key }}_value" class="w-4 text-center text-sm font-semibold text-navy-900">{{ $default }}</span>
                                <button type="button" data-counter="{{ $key }}" data-action="inc" data-min="{{ $min }}"
                                        class="w-7 h-7 rounded-full border border-gray-300 text-gray-500 hover:border-brand-blue hover:text-brand-blue transition">+</button>
                            </div>
                        </div>
                    @endforeach
                    <button type="button" id="guest-done" class="mt-3 w-full bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-6 py-2 rounded-full">Done</button>
                </div>
            </div>

            {{-- Tombol Search: pill penuh (rounded-full), ada padding sendiri supaya tidak nempel garis divider --}}
            <div class="p-3 flex items-center">
                <button type="submit"
                        class="w-full md:w-auto shrink-0 bg-brand-blue hover:bg-blue-700 transition text-white font-bold text-[15px] px-8 py-3.5 rounded-full whitespace-nowrap flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    Search
                </button>
            </div>
        </form>
    </section>
    </div>

    <script>
    // Saat search bar nempel di atas (sticky), ganti tampilannya jadi bar solid
    // penuh lebar (bukan floating card rounded) — mirip search bar Traveloka pas discroll.
    (function () {
        const anchor = document.getElementById('search-sticky-anchor');
        const wrap = document.getElementById('search-sticky-wrap');
        const form = document.getElementById('search-form');
        if (!anchor || !wrap || !form || !('IntersectionObserver' in window)) return;

        const floatingClasses = ['bg-white/90', 'backdrop-blur-md', 'border', 'border-white/60', 'rounded-xl', 'shadow-floating'];

        const observer = new IntersectionObserver(([entry]) => {
            if (entry.boundingClientRect.top < 0) {
                wrap.classList.add('bg-white', 'shadow-md');
                form.classList.remove(...floatingClasses);
                form.classList.add('max-w-7xl', 'mx-auto');
            } else {
                wrap.classList.remove('bg-white', 'shadow-md');
                form.classList.add(...floatingClasses);
            }
        }, { threshold: 0 });
        observer.observe(anchor);
    })();
    </script>

    <script>
    (function () {
        // ---------- Date range picker ----------
        const dateField   = document.getElementById('date-field');
        const dateTrigger = document.getElementById('date-trigger');
        const datePanel   = document.getElementById('date-panel');
        const dateDisplay = document.getElementById('date-display');
        const checkInInput  = document.getElementById('check_in_input');
        const checkOutInput = document.getElementById('check_out_input');

        const dayNames = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        let viewDate = new Date(); viewDate.setDate(1);
        let startDate = checkInInput.value ? new Date(checkInInput.value) : null;
        let endDate   = checkOutInput.value ? new Date(checkOutInput.value) : null;
        let picking   = 'start';

        function fmt(d) { return d.toISOString().slice(0,10); }
        function pretty(d) {
            return d.toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' });
        }
        function sameDay(a,b){ return a && b && a.toDateString() === b.toDateString(); }

        function updateDisplay() {
            if (startDate && endDate) {
                const nights = Math.round((endDate - startDate) / 86400000);
                dateDisplay.textContent = `${pretty(startDate)} - ${pretty(endDate)}, ${nights} night${nights > 1 ? 's' : ''}`;
                checkInInput.value = fmt(startDate);
                checkOutInput.value = fmt(endDate);
            } else if (startDate) {
                dateDisplay.textContent = `${pretty(startDate)} - Pilih tanggal pulang`;
            } else {
                dateDisplay.textContent = 'Pilih tanggal check-in & check-out';
            }
        }

        function renderMonth(offset, containerIdx) {
            const d = new Date(viewDate.getFullYear(), viewDate.getMonth() + offset, 1);
            document.getElementById('cal-title-' + containerIdx).textContent = monthNames[d.getMonth()] + ' ' + d.getFullYear();

            const grid = document.getElementById('cal-grid-' + containerIdx);
            grid.innerHTML = '';
            dayNames.forEach((dn, i) => {
                const el = document.createElement('div');
                el.className = 'font-semibold py-1 ' + (i >= 5 ? 'text-red-400' : 'text-gray-400');
                el.textContent = dn.slice(0,3);
                grid.appendChild(el);
            });

            const firstDow = (d.getDay() + 6) % 7; // Monday = 0
            const daysInMonth = new Date(d.getFullYear(), d.getMonth() + 1, 0).getDate();
            const today = new Date(); today.setHours(0,0,0,0);

            for (let i = 0; i < firstDow; i++) grid.appendChild(document.createElement('div'));

            for (let day = 1; day <= daysInMonth; day++) {
                const cellDate = new Date(d.getFullYear(), d.getMonth(), day);
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = day;
                const isPast = cellDate < today;
                const isStart = sameDay(cellDate, startDate);
                const isEnd = sameDay(cellDate, endDate);
                const inRange = startDate && endDate && cellDate > startDate && cellDate < endDate;

                let cls = 'w-8 h-8 mx-auto flex items-center justify-center rounded-full text-xs transition ';
                if (isPast) {
                    cls += 'text-gray-300 cursor-not-allowed';
                } else if (isStart || isEnd) {
                    cls += 'bg-brand-blue text-white font-semibold';
                } else if (inRange) {
                    cls += 'bg-blue-50 text-brand-blue';
                } else {
                    cls += 'text-navy-900 hover:bg-blue-50 cursor-pointer';
                }
                btn.className = cls;
                if (isPast) btn.disabled = true;
                else btn.addEventListener('click', () => onPick(cellDate));
                grid.appendChild(btn);
            }
        }

        function onPick(date) {
            if (picking === 'start' || (startDate && date < startDate)) {
                startDate = date; endDate = null; picking = 'end';
            } else {
                endDate = date; picking = 'start';
            }
            updateDisplay();
            renderCalendar();
        }

        function renderCalendar() {
            renderMonth(0, 0);
            renderMonth(1, 1);
        }

        dateTrigger.addEventListener('click', () => {
            datePanel.classList.toggle('hidden');
            guestPanel.classList.add('hidden');
            renderCalendar();
        });
        document.getElementById('cal-prev').addEventListener('click', () => { viewDate.setMonth(viewDate.getMonth() - 1); renderCalendar(); });
        document.getElementById('cal-next').addEventListener('click', () => { viewDate.setMonth(viewDate.getMonth() + 1); renderCalendar(); });
        document.getElementById('date-reset').addEventListener('click', () => { startDate = null; endDate = null; picking = 'start'; updateDisplay(); renderCalendar(); });
        document.getElementById('date-apply').addEventListener('click', () => { datePanel.classList.add('hidden'); });

        updateDisplay();

        // ---------- Guest & room counter ----------
        const guestTrigger = document.getElementById('guest-trigger');
        const guestPanel   = document.getElementById('guest-panel');
        const guestDisplay = document.getElementById('guest-display');

        function currentCounts() {
            return {
                adults: parseInt(document.getElementById('adults_value').textContent, 10),
                children: parseInt(document.getElementById('children_value').textContent, 10),
                rooms: parseInt(document.getElementById('rooms_value').textContent, 10),
            };
        }
        function updateGuestDisplay() {
            const c = currentCounts();
            guestDisplay.textContent = `${c.adults} Adult(s), ${c.children} children, ${c.rooms} room${c.rooms > 1 ? 's' : ''}`;
            document.getElementById('adults_input').value = c.adults;
            document.getElementById('children_input').value = c.children;
            document.getElementById('rooms_input').value = c.rooms;
        }

        document.querySelectorAll('[data-counter]').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.dataset.counter;
                const min = parseInt(btn.dataset.min, 10);
                const valueEl = document.getElementById(key + '_value');
                let val = parseInt(valueEl.textContent, 10);
                val = btn.dataset.action === 'inc' ? val + 1 : Math.max(min, val - 1);
                valueEl.textContent = val;
                updateGuestDisplay();
            });
        });

        guestTrigger.addEventListener('click', () => {
            guestPanel.classList.toggle('hidden');
            datePanel.classList.add('hidden');
        });
        document.getElementById('guest-done').addEventListener('click', () => guestPanel.classList.add('hidden'));
        updateGuestDisplay();

        // ---------- Close panels when clicking outside ----------
        document.addEventListener('click', (e) => {
            if (!dateField.contains(e.target)) datePanel.classList.add('hidden');
            if (!document.getElementById('guest-field').contains(e.target)) guestPanel.classList.add('hidden');
        });
    })();
    </script>

    {{-- ============ BREADCRUMB ============ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="text-xs sm:text-sm text-gray-500 mb-4 flex flex-wrap items-center gap-1.5">
            <a href="{{ route('home') }}" class="text-brand-blue hover:underline">Homestay</a>
            <span>/</span>
            <a href="{{ route('home') }}" class="text-brand-blue hover:underline">{{ $properties->total() }} Homestay di Indonesia</a>
            @if (request('location'))
                <span>/</span>
                <span class="text-gray-700">{{ $properties->total() }} Homestay di {{ request('location') }}</span>
            @endif
        </div>
    </div>

    {{-- ============ LAYOUT 2 KOLOM: SIDEBAR (kiri) + LISTING (kanan) ============ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex flex-col lg:flex-row gap-6">

            {{-- ===== SIDEBAR KIRI ===== --}}
            <aside class="lg:w-72 shrink-0 space-y-4">

                {{-- Promo box --}}
                <div class="bg-gradient-to-br from-brand-blue to-navy-800 text-white rounded-xl p-4 relative overflow-hidden">
                    <div class="text-xl mb-1">🎁</div>
                    <p class="font-bold text-sm leading-snug">Mau lebih hemat?</p>
                    <p class="text-xs text-blue-100 mt-1">Buka promo khusus pengguna aplikasi — instal sekarang!</p>
                </div>

                {{-- Peta (visual saja, belum fungsional) --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="h-24 bg-blue-50 flex items-center justify-center text-3xl">🗺️</div>
                    <button type="button"
                            class="w-full bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold py-2.5">
                        📍 Buka Peta
                    </button>
                </div>

                {{-- Ide tanggal menginap (visual saja) --}}
                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-sm font-bold text-navy-900 mb-3">Ide Tanggal Menginap</p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <button type="button" class="border border-gray-200 rounded-lg py-2 text-left px-2.5 hover:border-brand-blue transition">
                            <div class="font-semibold text-navy-900">Hari Ini</div>
                            <div class="text-gray-400">{{ now()->translatedFormat('d M') }}</div>
                        </button>
                        <button type="button" class="border border-gray-200 rounded-lg py-2 text-left px-2.5 hover:border-brand-blue transition">
                            <div class="font-semibold text-navy-900">Besok</div>
                            <div class="text-gray-400">{{ now()->addDay()->translatedFormat('d M') }}</div>
                        </button>
                        <button type="button" class="border border-gray-200 rounded-lg py-2 text-left px-2.5 hover:border-brand-blue transition">
                            <div class="font-semibold text-navy-900">Weekend Ini</div>
                            <div class="text-gray-400">{{ now()->next('Saturday')->translatedFormat('d M') }}</div>
                        </button>
                        <button type="button" class="border border-gray-200 rounded-lg py-2 text-left px-2.5 hover:border-brand-blue transition">
                            <div class="font-semibold text-navy-900">Weekend Depan</div>
                            <div class="text-gray-400">{{ now()->next('Saturday')->addWeek()->translatedFormat('d M') }}</div>
                        </button>
                    </div>
                </div>

                {{-- Rentang Harga (visual saja, belum fungsional) --}}
                <div class="border border-gray-200 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-bold text-navy-900">Rentang Harga</p>
                        <button type="button" class="text-xs text-brand-blue hover:underline">Reset</button>
                    </div>
                    <p class="text-xs text-gray-400 mb-3">Per kamar, per malam</p>
                    <input type="range" class="w-full accent-brand-blue" min="0" max="100" value="50" disabled>
                    <div class="flex gap-2 mt-4">
                        <button type="button" class="flex-1 border border-gray-300 text-gray-500 text-sm font-semibold py-2 rounded-lg">Reset</button>
                        <button type="button" class="flex-1 bg-brand-blue text-white text-sm font-semibold py-2 rounded-lg">Terapkan</button>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-2">*Filter harga masih tampilan, belum aktif memfilter data.</p>
                </div>
            </aside>

            {{-- ===== KONTEN KANAN: LISTING ===== --}}
            <div class="flex-1 min-w-0">

                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="font-display text-xl md:text-2xl font-extrabold text-navy-900 mb-1">
                            @if (request('location'))
                                Properti Populer di {{ request('location') }}
                            @else
                                Properti Populer untuk Kamu
                            @endif
                        </h2>
                        <p class="text-sm text-gray-500">Cek properti pilihan yang siap kamu booking hari ini</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-500 hidden sm:inline">Sort by:</label>
                        <select class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-navy-900 font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                            <option>Highest popularity</option>
                            <option>Harga Terendah</option>
                            <option>Harga Tertinggi</option>
                            <option>Terbaru</option>
                        </select>
                        <div class="hidden sm:flex items-center border border-gray-200 rounded-lg overflow-hidden text-sm">
                            <span class="px-3 py-2 bg-blue-50 text-brand-blue border-r border-gray-200">⊞</span>
                            <span class="px-3 py-2 text-gray-400">☰</span>
                        </div>
                    </div>
                </div>

                {{-- Quick filter pills --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach (['Kolam Pribadi', 'Rombongan Besar', '2 Kamar Tidur', '3 Kamar Tidur', 'Dekat Stasiun', 'Di Bawah 300rb', 'Cocok untuk Honeymoon'] as $tag)
                        <a href="{{ route('home', ['tag' => $tag]) }}"
                           class="bg-gray-50 hover:bg-brand-blue hover:text-white transition text-navy-900 text-xs font-medium px-4 py-2 rounded-full border border-gray-200">
                            {{ $tag }}
                        </a>
                    @endforeach
                </div>

                @if ($properties->isEmpty())
                    <div class="text-center py-16 border border-gray-100 rounded-2xl">
                        <div class="text-4xl mb-3">🔍</div>
                        <p class="text-navy-900 font-semibold mb-1">Belum ada properti aktif saat ini</p>
                        <p class="text-gray-500 text-sm">Coba ubah kata kunci pencarian atau lihat lagi nanti.</p>
                    </div>
                @else
                    <div id="property-grid" class="flex flex-col gap-4">
                        @foreach ($properties as $property)
                            @include('properties.partials.card', ['property' => $property])
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $properties->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection