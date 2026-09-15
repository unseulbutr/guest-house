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

        // ==========================================================
// IDE TANGGAL MENGINAP
// ==========================================================

function setDateIdea(checkIn, checkOut) {

    startDate = checkIn;
    endDate = checkOut;
    picking = 'start';

    // Masukkan ke input
    checkInInput.value = fmt(startDate);
    checkOutInput.value = fmt(endDate);

    // Update tampilan
    updateDisplay();

    // Render kalender
    renderCalendar();

    // Langsung submit pencarian
    document.getElementById('search-form').submit();
}


// Hari ini
document.getElementById('date-idea-today')?.addEventListener('click', function () {

    const today = new Date();

    today.setHours(0, 0, 0, 0);

    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    setDateIdea(today, tomorrow);
});


// Besok
document.getElementById('date-idea-tomorrow')?.addEventListener('click', function () {

    const tomorrow = new Date();

    tomorrow.setHours(0, 0, 0, 0);
    tomorrow.setDate(tomorrow.getDate() + 1);

    const checkout = new Date(tomorrow);
    checkout.setDate(checkout.getDate() + 1);

    setDateIdea(tomorrow, checkout);
});


// Weekend ini
document.getElementById('date-idea-weekend')?.addEventListener('click', function () {

    const today = new Date();

    today.setHours(0, 0, 0, 0);

    const saturday = new Date(today);

    const day = saturday.getDay();

    const daysUntilSaturday = (6 - day + 7) % 7;

    saturday.setDate(
        saturday.getDate() + daysUntilSaturday
    );

    const sunday = new Date(saturday);

    sunday.setDate(
        sunday.getDate() + 1
    );

    setDateIdea(saturday, sunday);
});


// Weekend depan
document.getElementById('date-idea-next-weekend')?.addEventListener('click', function () {

    const today = new Date();

    today.setHours(0, 0, 0, 0);

    const saturday = new Date(today);

    const day = saturday.getDay();

    let daysUntilSaturday = (6 - day + 7) % 7;

    // Kalau hari ini Sabtu/Minggu,
    // ambil Sabtu minggu berikutnya
    if (daysUntilSaturday === 0) {
        daysUntilSaturday = 7;
    }

    saturday.setDate(
        saturday.getDate() + daysUntilSaturday + 7
    );

    const sunday = new Date(saturday);

    sunday.setDate(
        sunday.getDate() + 1
    );

    setDateIdea(saturday, sunday);
});

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

                {{-- Promo / Hemat --}}
<button
    type="button"
    id="hemat-button"
    class="w-full text-left bg-gradient-to-br from-brand-blue to-navy-800 text-white rounded-xl p-4 relative overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition duration-200"
>
    <div class="flex items-start gap-3">
        <div class="text-2xl shrink-0">🎁</div>

        <div class="flex-1">
            <p class="font-bold text-sm leading-snug">
                Mau lebih hemat?
            </p>

            <p class="text-xs text-blue-100 mt-1">
                Cari penginapan sesuai budget kamu dengan filter harga.
            </p>

            <div class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-white">
                Lihat harga termurah
                <span>→</span>
            </div>
        </div>
    </div>
</button>

                {{-- PETA --}}
<div class="border border-gray-200 rounded-xl overflow-hidden bg-white">

    <div class="h-24 bg-blue-50 flex items-center justify-center relative overflow-hidden">

        <div class="absolute inset-0 opacity-20"
             style="background-image:
             linear-gradient(#3b82f6 1px, transparent 1px),
             linear-gradient(90deg, #3b82f6 1px, transparent 1px);
             background-size: 20px 20px;">
        </div>

        <div class="relative text-4xl">
            🗺️
        </div>

    </div>

    <button
        type="button"
        id="open-map-button"
        class="w-full bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold py-2.5"
    >
        📍 Buka Peta
    </button>

</div>

                {{-- =========================================================
     IDE TANGGAL MENGINAP
     ========================================================= --}}
<div class="border border-gray-200 rounded-xl p-4">

    <p class="text-sm font-bold text-navy-900 mb-3">
        Ide Tanggal Menginap
    </p>

    <div class="grid grid-cols-2 gap-2 text-xs">

        {{-- HARI INI --}}
        <button
            type="button"
            id="date-idea-today"
            class="border border-gray-200 rounded-lg py-2 text-left px-2.5 hover:border-brand-blue hover:bg-blue-50 transition"
        >
            <div class="font-semibold text-navy-900">
                Hari Ini
            </div>

            <div class="text-gray-400">
                {{ now()->translatedFormat('d M') }}
            </div>
        </button>


        {{-- BESOK --}}
        <button
            type="button"
            id="date-idea-tomorrow"
            class="border border-gray-200 rounded-lg py-2 text-left px-2.5 hover:border-brand-blue hover:bg-blue-50 transition"
        >
            <div class="font-semibold text-navy-900">
                Besok
            </div>

            <div class="text-gray-400">
                {{ now()->addDay()->translatedFormat('d M') }}
            </div>
        </button>


        {{-- WEEKEND INI --}}
        <button
            type="button"
            id="date-idea-weekend"
            class="border border-gray-200 rounded-lg py-2 text-left px-2.5 hover:border-brand-blue hover:bg-blue-50 transition"
        >
            <div class="font-semibold text-navy-900">
                Weekend Ini
            </div>

            <div class="text-gray-400">
                {{ now()->next('Saturday')->translatedFormat('d M') }}
            </div>
        </button>


        {{-- WEEKEND DEPAN --}}
        <button
            type="button"
            id="date-idea-next-weekend"
            class="border border-gray-200 rounded-lg py-2 text-left px-2.5 hover:border-brand-blue hover:bg-blue-50 transition"
        >
            <div class="font-semibold text-navy-900">
                Weekend Depan
            </div>

            <div class="text-gray-400">
                {{ now()->next('Saturday')->addWeek()->translatedFormat('d M') }}
            </div>
        </button>

    </div>
</div>



                {{-- =========================================================
     RENTANG HARGA
     ========================================================= --}}
<div
    id="price-filter-section"
    class="border border-gray-200 rounded-xl p-4 transition-all duration-300"
>

    <div class="flex items-center justify-between mb-1">
        <p class="text-sm font-bold text-navy-900">
            Rentang Harga
        </p>

        <a href="{{ route('home', request()->except(['min_price', 'max_price', 'page'])) }}"
           class="text-xs text-brand-blue hover:underline">
            Reset
        </a>
    </div>

    <p class="text-xs text-gray-400 mb-4">
        Per kamar, per malam
    </p>

    @php
        $priceMin = 0;
        $priceMax = 2000000;

        $selectedMin = request('min_price', $priceMin);
        $selectedMax = request('max_price', $priceMax);

        // Pastikan minimum tidak lebih besar dari maksimum
        if ($selectedMin > $selectedMax) {
            $selectedMin = $priceMin;
            $selectedMax = $priceMax;
        }
    @endphp

    {{-- Tampilan angka harga --}}
    <div class="flex gap-2 mb-4">

        <div class="flex-1 border border-gray-200 rounded-lg px-3 py-2">
            <p class="text-[10px] text-gray-400">
                Minimum
            </p>

            <p id="min-price-display"
               class="text-sm font-bold text-navy-900">
                Rp {{ number_format($selectedMin, 0, ',', '.') }}
            </p>
        </div>

        <div class="flex-1 border border-gray-200 rounded-lg px-3 py-2">
            <p class="text-[10px] text-gray-400">
                Maksimum
            </p>

            <p id="max-price-display"
               class="text-sm font-bold text-navy-900">
                Rp {{ number_format($selectedMax, 0, ',', '.') }}
            </p>
        </div>

    </div>

    {{-- SLIDER --}}
    <div class="relative h-8 flex items-center">

        {{-- Track --}}
        <div class="absolute left-0 right-0 h-1.5 bg-gray-200 rounded-full"></div>

        {{-- Track aktif --}}
        <div id="price-range-track"
             class="absolute h-1.5 bg-brand-blue rounded-full">
        </div>

        {{-- Slider minimum --}}
        <input
            type="range"
            id="min-price-slider"
            min="{{ $priceMin }}"
            max="{{ $priceMax }}"
            step="10000"
            value="{{ $selectedMin }}"
            class="price-slider"
        >

        {{-- Slider maksimum --}}
        <input
            type="range"
            id="max-price-slider"
            min="{{ $priceMin }}"
            max="{{ $priceMax }}"
            step="10000"
            value="{{ $selectedMax }}"
            class="price-slider"
        >

    </div>

    {{-- Batas harga --}}
    <div class="flex justify-between text-[11px] text-gray-400 mt-1">
        <span>Rp 0</span>
        <span>Rp 2 jt+</span>
    </div>

    {{-- Form filter --}}
    <form
        id="price-filter-form"
        action="{{ route('home') }}"
        method="GET"
        class="mt-4"
    >

        {{-- Pertahankan filter lain --}}
        @foreach(request()->except(['min_price', 'max_price', 'page']) as $key => $value)

            @if(is_array($value))

                @foreach($value as $item)
                    <input
                        type="hidden"
                        name="{{ $key }}[]"
                        value="{{ $item }}"
                    >
                @endforeach

            @else

                <input
                    type="hidden"
                    name="{{ $key }}"
                    value="{{ $value }}"
                >

            @endif

        @endforeach

        <input
            type="hidden"
            name="min_price"
            id="min-price-input"
            value="{{ $selectedMin }}"
        >

        <input
            type="hidden"
            name="max_price"
            id="max-price-input"
            value="{{ $selectedMax }}"
        >

        <div class="flex gap-2">

            <button
                type="button"
                id="price-reset-button"
                class="flex-1 border border-gray-300 text-gray-500 text-sm font-semibold py-2 rounded-lg hover:border-brand-blue hover:text-brand-blue transition"
            >
                Reset
            </button>

            <button
                type="submit"
                class="flex-1 bg-brand-blue text-white text-sm font-semibold py-2 rounded-lg hover:bg-blue-700 transition"
            >
                Terapkan
            </button>

        </div>

    </form>

</div>

{{-- =========================================================
     KELAS BINTANG
     ========================================================= --}}
<div class="border border-gray-200 rounded-xl p-4">

    <div class="flex items-center justify-between mb-1">

        <p class="text-sm font-bold text-navy-900">
            Bintang
        </p>


        <a
            href="{{ route('home', request()->except(['stars', 'page'])) }}"
            class="text-xs text-brand-blue hover:underline"
        >
            Reset
        </a>

    </div>


    <p class="text-xs text-gray-400 mb-4">
        Pilih kelas kenyamanan properti
    </p>


    <form
        action="{{ route('home') }}"
        method="GET"
    >

        {{-- Pertahankan filter lain --}}
        @foreach(request()->except(['stars', 'page']) as $key => $value)

            @if(is_array($value))

                @foreach($value as $item)

                    <input
                        type="hidden"
                        name="{{ $key }}[]"
                        value="{{ $item }}"
                    >

                @endforeach

            @else

                <input
                    type="hidden"
                    name="{{ $key }}"
                    value="{{ $value }}"
                >

            @endif

        @endforeach


        {{-- PILIHAN BINTANG --}}
        <div class="space-y-1">


            {{-- 1 BINTANG --}}
            <label class="flex items-center gap-3 py-2 cursor-pointer group">

                <input
                    type="checkbox"
                    name="stars[]"
                    value="1"
                    {{ in_array(1, array_map('intval', (array) request('stars', []))) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                >

                <span class="text-sm text-gray-700 group-hover:text-brand-blue">
                    <span class="text-yellow-400">
                        ★
                    </span>
                    <span class="ml-1">
                        1
                    </span>
                </span>

                <span class="text-xs text-gray-400">
                    Sederhana
                </span>

            </label>


            {{-- 2 BINTANG --}}
            <label class="flex items-center gap-3 py-2 cursor-pointer group">

                <input
                    type="checkbox"
                    name="stars[]"
                    value="2"
                    {{ in_array(2, array_map('intval', (array) request('stars', []))) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                >

                <span class="text-sm text-gray-700 group-hover:text-brand-blue">
                    <span class="text-yellow-400">
                        ★★
                    </span>
                    <span class="ml-1">
                        2
                    </span>
                </span>

                <span class="text-xs text-gray-400">
                    Standar
                </span>

            </label>


            {{-- 3 BINTANG --}}
            <label class="flex items-center gap-3 py-2 cursor-pointer group">

                <input
                    type="checkbox"
                    name="stars[]"
                    value="3"
                    {{ in_array(3, array_map('intval', (array) request('stars', []))) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                >

                <span class="text-sm text-gray-700 group-hover:text-brand-blue">
                    <span class="text-yellow-400">
                        ★★★
                    </span>
                    <span class="ml-1">
                        3
                    </span>
                </span>

                <span class="text-xs text-gray-400">
                    Nyaman
                </span>

            </label>


            {{-- 4 BINTANG --}}
            <label class="flex items-center gap-3 py-2 cursor-pointer group">

                <input
                    type="checkbox"
                    name="stars[]"
                    value="4"
                    {{ in_array(4, array_map('intval', (array) request('stars', []))) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                >

                <span class="text-sm text-gray-700 group-hover:text-brand-blue">
                    <span class="text-yellow-400">
                        ★★★★
                    </span>
                    <span class="ml-1">
                        4
                    </span>
                </span>

                <span class="text-xs text-gray-400">
                    Premium
                </span>

            </label>


            {{-- 5 BINTANG --}}
            <label class="flex items-center gap-3 py-2 cursor-pointer group">

                <input
                    type="checkbox"
                    name="stars[]"
                    value="5"
                    {{ in_array(5, array_map('intval', (array) request('stars', []))) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                >

                <span class="text-sm text-gray-700 group-hover:text-brand-blue">
                    <span class="text-yellow-400">
                        ★★★★★
                    </span>
                    <span class="ml-1">
                        5
                    </span>
                </span>

                <span class="text-xs text-gray-400">
                    Mewah
                </span>

            </label>

        </div>


        {{-- BUTTON --}}
        <div class="flex gap-2 mt-4">

            <a
                href="{{ route('home', request()->except(['stars', 'page'])) }}"
                class="flex-1 border border-gray-300 text-gray-500 text-sm font-semibold py-2 rounded-lg text-center hover:border-brand-blue hover:text-brand-blue transition"
            >
                Reset
            </a>


            <button
                type="submit"
                class="flex-1 bg-brand-blue text-white text-sm font-semibold py-2 rounded-lg hover:bg-blue-700 transition"
            >
                Terapkan
            </button>

        </div>

    </form>

</div>

{{-- =========================================================
     RATING DARI TAMU
     ========================================================= --}}

<div class="border border-gray-200 rounded-xl p-4">

    <div class="flex items-center justify-between mb-1">

        <p class="text-sm font-bold text-navy-900">
            Rating dari Tamu
        </p>

        <a
            href="{{ route('home', request()->except(['guest_rating', 'page'])) }}"
            class="text-xs text-brand-blue hover:underline"
        >
            Reset
        </a>

    </div>

    <p class="text-xs text-gray-400 mb-4">
        Penilaian berdasarkan pengalaman customer
    </p>

    <form
        action="{{ route('home') }}"
        method="GET"
    >

        {{-- Pertahankan filter yang lain --}}
        @foreach(request()->except(['guest_rating', 'page']) as $key => $value)

            @if(is_array($value))

                @foreach($value as $item)

                    <input
                        type="hidden"
                        name="{{ $key }}[]"
                        value="{{ $item }}"
                    >

                @endforeach

            @else

                <input
                    type="hidden"
                    name="{{ $key }}"
                    value="{{ $value }}"
                >

            @endif

        @endforeach


        {{-- PILIHAN RATING --}}
        <div class="space-y-1">

            {{-- 7+ NYAMAN --}}
            <label class="flex items-center gap-3 py-2 cursor-pointer group">

                <input
                    type="checkbox"
                    name="guest_rating[]"
                    value="7"
                    {{ in_array('7', $selectedGuestRatings ?? []) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                >

                <span class="flex items-center gap-1.5 text-sm text-gray-700 group-hover:text-brand-blue">

                    <span class="text-brand-blue text-base">
                        ◈
                    </span>

                    <span class="font-semibold">
                        7+
                    </span>

                    <span>
                        Nyaman
                    </span>

                </span>

            </label>


            {{-- 8+ MENGESANKAN --}}
            <label class="flex items-center gap-3 py-2 cursor-pointer group">

                <input
                    type="checkbox"
                    name="guest_rating[]"
                    value="8"
                    {{ in_array('8', $selectedGuestRatings ?? []) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                >

                <span class="flex items-center gap-1.5 text-sm text-gray-700 group-hover:text-brand-blue">

                    <span class="text-brand-blue text-base">
                        ◈
                    </span>

                    <span class="font-semibold">
                        8+
                    </span>

                    <span>
                        Mengesankan
                    </span>

                </span>

            </label>


            {{-- 9+ LUAR BIASA --}}
            <label class="flex items-center gap-3 py-2 cursor-pointer group">

                <input
                    type="checkbox"
                    name="guest_rating[]"
                    value="9"
                    {{ in_array('9', $selectedGuestRatings ?? []) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                >

                <span class="flex items-center gap-1.5 text-sm text-gray-700 group-hover:text-brand-blue">

                    <span class="text-brand-blue text-base">
                        ◈
                    </span>

                    <span class="font-semibold">
                        9+
                    </span>

                    <span>
                        Luar Biasa
                    </span>

                </span>

            </label>

        </div>


        {{-- BUTTON --}}
        <div class="flex gap-2 mt-4">

            <a
                href="{{ route('home', request()->except(['guest_rating', 'page'])) }}"
                class="flex-1 border border-gray-300 text-gray-500 text-sm font-semibold py-2 rounded-lg text-center hover:border-brand-blue hover:text-brand-blue transition"
            >
                Reset
            </a>

            <button
                type="submit"
                class="flex-1 bg-brand-blue text-white text-sm font-semibold py-2 rounded-lg hover:bg-blue-700 transition"
            >
                Terapkan
            </button>

        </div>

    </form>

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

    <style>
    .price-slider {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 32px;
        margin: 0;
        padding: 0;
        background: transparent;
        pointer-events: none;
        appearance: none;
        -webkit-appearance: none;
    }

    .price-slider::-webkit-slider-runnable-track {
        height: 6px;
        background: transparent;
        border-radius: 999px;
    }

    .price-slider::-moz-range-track {
        height: 6px;
        background: transparent;
        border-radius: 999px;
    }

    .price-slider::-webkit-slider-thumb {
        appearance: none;
        -webkit-appearance: none;

        width: 22px;
        height: 22px;

        margin-top: -8px;

        background: white;

        border: 3px solid #0d6efd;

        border-radius: 50%;

        cursor: pointer;

        pointer-events: auto;

        box-shadow: 0 1px 4px rgba(0,0,0,.18);
    }

    .price-slider::-moz-range-thumb {
        width: 22px;
        height: 22px;

        background: white;

        border: 3px solid #0d6efd;

        border-radius: 50%;

        cursor: pointer;

        pointer-events: auto;

        box-shadow: 0 1px 4px rgba(0,0,0,.18);
    }

    #min-price-slider {
        z-index: 3;
    }

    #max-price-slider {
        z-index: 2;
    }
</style>

<script>
(function () {

    const minSlider = document.getElementById('min-price-slider');
    const maxSlider = document.getElementById('max-price-slider');

    const minDisplay = document.getElementById('min-price-display');
    const maxDisplay = document.getElementById('max-price-display');

    const minInput = document.getElementById('min-price-input');
    const maxInput = document.getElementById('max-price-input');

    const track = document.getElementById('price-range-track');

    const resetButton = document.getElementById('price-reset-button');

    if (
        !minSlider ||
        !maxSlider ||
        !minDisplay ||
        !maxDisplay ||
        !minInput ||
        !maxInput ||
        !track
    ) {
        return;
    }


    // Format Rupiah
    function formatRupiah(value) {

        value = parseInt(value);

        return 'Rp ' + value.toLocaleString('id-ID');
    }


    // Update tampilan slider
    function updateSlider() {

        let min = parseInt(minSlider.value);
        let max = parseInt(maxSlider.value);

        // Jangan sampai minimum melewati maksimum
        if (min >= max) {

            if (document.activeElement === minSlider) {
                min = max - 10000;

                if (min < 0) {
                    min = 0;
                }

                minSlider.value = min;

            } else {

                max = min + 10000;

                if (max > 2000000) {
                    max = 2000000;
                }

                maxSlider.value = max;
            }
        }


        // Update angka
        minDisplay.textContent = formatRupiah(min);
        maxDisplay.textContent = formatRupiah(max);


        // Update hidden input
        minInput.value = min;
        maxInput.value = max;


        // Posisi track biru
        const minValue = parseInt(minSlider.min);
        const maxValue = parseInt(minSlider.max);

        const left =
            ((min - minValue) / (maxValue - minValue)) * 100;

        const right =
            ((max - minValue) / (maxValue - minValue)) * 100;


        track.style.left = left + '%';
        track.style.width = (right - left) + '%';
    }


    // Ketika slider digeser
    minSlider.addEventListener('input', updateSlider);

    maxSlider.addEventListener('input', updateSlider);


    // Reset
    resetButton.addEventListener('click', function () {

        minSlider.value = 0;
        maxSlider.value = 2000000;

        updateSlider();

    });


    // Jalankan pertama kali
    updateSlider();

})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ==========================================================
    // MAU LEBIH HEMAT
    // Scroll langsung ke Rentang Harga
    // ==========================================================

    const hematButton = document.getElementById('hemat-button');

    if (hematButton) {

        hematButton.addEventListener('click', function () {

            const priceSection = document.getElementById('price-filter-section');

            if (priceSection) {

                priceSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // Efek highlight sementara
                priceSection.classList.add('ring-2', 'ring-brand-blue');

                setTimeout(function () {
                    priceSection.classList.remove(
                        'ring-2',
                        'ring-brand-blue'
                    );
                }, 1500);

            }

        });

    }


    // ==========================================================
    // BUKA PETA
    // ==========================================================

    const mapButton = document.getElementById('open-map-button');

    if (mapButton) {

        mapButton.addEventListener('click', function () {

            // Ambil lokasi dari search
            const locationInput = document.querySelector(
                'input[name="location"]'
            );

            let location = '';

            if (locationInput && locationInput.value.trim() !== '') {
                location = locationInput.value.trim();
            } else {
                location = 'Yogyakarta';
            }

            // Buka Google Maps berdasarkan lokasi
            const mapUrl =
                'https://www.google.com/maps/search/?api=1&query=' +
                encodeURIComponent(location);

            window.open(mapUrl, '_blank');

        });

    }

});
</script>

@endsection