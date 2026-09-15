@extends('layouts.app')

@section('title', 'Dashboard Mitra')

@section('dashboard_layout', 'true')

@section('content')

<div class="min-h-screen bg-[#f5f7f8] text-slate-800">

    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 hidden w-[245px] border-r border-slate-200 bg-white lg:flex lg:flex-col">

        {{-- LOGO --}}
        <div class="flex h-[82px] items-center gap-3 border-b border-slate-100 px-6">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#0c1830] text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"/>
                </svg>
            </div>

            <div>
                <div class="font-display text-[17px] font-extrabold tracking-tight text-[#0c1830]">
                    GuestHouse
                </div>

                <div class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-400">
                    Mitra Panel
                </div>
            </div>

        </div>


        {{-- MENU --}}
        <div class="flex-1 overflow-y-auto px-4 py-6">

            <div class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">
                Menu
            </div>

            <nav class="space-y-1">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 rounded-xl bg-emerald-50 px-3.5 py-3 text-sm font-semibold text-emerald-700">

                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor"
                         stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 13h6V4H4v9Zm10 7h6v-9h-6v9ZM4 20h6v-3H4v3Zm10-12h6V4h-6v4Z"/>
                    </svg>

                    Dashboard
                </a>


                {{-- Properti --}}
                <a href="{{ route('mitra.properties.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-[#0c1830]">

                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor"
                         stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-7h6v7"/>
                    </svg>

                    Properti Saya
                </a>


                {{-- Booking --}}
                <a href="{{ route('mitra.bookings.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-[#0c1830]">

                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor"
                         stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                        <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>

                    Booking Masuk
                </a>


                {{-- Perpanjangan --}}
                <a href="{{ route('mitra.booking-extensions.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-[#0c1830]">

                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor"
                         stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 7h16M4 12h10M4 17h7"/>
                    </svg>

                    Perpanjangan
                </a>

            </nav>


            <div class="mb-3 mt-9 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">
                Akun
            </div>

            <nav class="space-y-1">

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-[#0c1830]">

                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor"
                         stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 9a7 7 0 0 0-14 0"/>
                    </svg>

                    Profil Saya
                </a>


                <a href="{{ route('home') }}"
                   class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-[#0c1830]">

                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor"
                         stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 11.5 12 4l9 7.5M5 10v10h14V10"/>
                    </svg>

                    Lihat Website
                </a>

            </nav>

        </div>


        {{-- BOTTOM SIDEBAR --}}
        <div class="border-t border-slate-100 p-4">

            <div class="rounded-2xl bg-[#0c1830] p-4 text-white">

                <div class="mb-2 text-xs font-semibold text-white/60">
                    GuestHouse Mitra
                </div>

                <div class="text-sm font-semibold leading-relaxed">
                    Kelola properti dan booking Anda dengan mudah.
                </div>

                <a href="{{ route('mitra.properties.create') }}"
                   class="mt-4 flex items-center justify-center rounded-xl bg-yellow-400 px-3 py-2.5 text-xs font-bold text-[#0c1830] transition hover:bg-yellow-300">

                    + Tambah Properti

                </a>

            </div>

        </div>

    </aside>


    {{-- =========================================================
         MOBILE TOP BAR
    ========================================================== --}}
    <div class="sticky top-0 z-40 flex h-[70px] items-center justify-between border-b border-slate-200 bg-white px-4 lg:hidden">

        <div class="flex items-center gap-2">

            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#0c1830] text-white">

                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                     stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"/>
                </svg>

            </div>

            <span class="font-display font-extrabold text-[#0c1830]">
                GuestHouse
            </span>

        </div>

        <a href="{{ route('profile.edit') }}"
           class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full bg-slate-100 font-bold text-[#0c1830]">

            @if (auth()->user()->avatar)
                <img
                    src="{{ asset('storage/' . auth()->user()->avatar) }}"
                    class="h-full w-full object-cover"
                    alt="Avatar">
            @else
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            @endif

        </a>

    </div>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}
    <main class="lg:ml-[245px]">

        {{-- HEADER --}}
        <header class="border-b border-slate-200 bg-white">

            <div class="flex min-h-[82px] items-center justify-between gap-5 px-5 py-4 sm:px-8">

                <div>
                    <p class="text-xs font-medium text-slate-400">
                        Dashboard Mitra
                    </p>

                    <h1 class="mt-0.5 font-display text-xl font-extrabold tracking-tight text-[#0c1830] sm:text-2xl">
                        Selamat datang kembali, {{ auth()->user()->name }} 👋
                    </h1>

                    <p class="mt-1 hidden text-xs text-slate-500 sm:block">
                        Pantau properti, booking, dan pendapatan Anda.
                    </p>
                </div>


                <div class="flex items-center gap-2">

                    {{-- Search visual --}}
                    <div class="hidden h-11 w-56 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 text-xs text-slate-400 xl:flex">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                             stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="m20 20-4-4"/>
                        </svg>

                        Cari properti...

                    </div>


                    <a href="{{ route('mitra.properties.create') }}"
                       class="flex h-11 items-center gap-2 rounded-xl bg-emerald-600 px-4 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-700">

                        <span class="text-lg leading-none">+</span>

                        <span class="hidden sm:inline">
                            Tambah Properti
                        </span>

                    </a>


                    {{-- Avatar --}}
                    <a href="{{ route('profile.edit') }}"
                       class="hidden h-11 w-11 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 font-bold text-[#0c1830] sm:flex">

                        @if (auth()->user()->avatar)
                            <img
                                src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                class="h-full w-full object-cover"
                                alt="Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif

                    </a>

                </div>

            </div>

        </header>


        {{-- CONTENT --}}
        <div class="px-5 py-7 sm:px-8 sm:py-9">

            @if (session('success'))

                <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">

                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                        ✓
                    </div>

                    <span>{{ session('success') }}</span>

                </div>

            @endif


            {{-- =====================================================
                 STATISTICS
            ====================================================== --}}
            <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">

                {{-- Pendapatan --}}
                <div class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-lg">

                    <div class="flex items-start justify-between">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                 stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 17l6-6 4 4 8-9"/>
                                <path stroke-linecap="round" d="M17 6h4v4"/>
                            </svg>

                        </div>

                        <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-bold text-emerald-600">
                            Paid
                        </span>

                    </div>

                    <p class="mt-5 text-xs font-medium text-slate-400">
                        Total Pendapatan
                    </p>

                    <p class="mt-1 break-words font-display text-xl font-extrabold text-[#0c1830]">
                        Rp {{ number_format($stats['total_earnings'], 0, ',', '.') }}
                    </p>

                    <p class="mt-2 text-[11px] text-slate-400">
                        Setelah komisi platform
                    </p>

                </div>


                {{-- Komisi --}}
                <div class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-lg">

                    <div class="flex items-start justify-between">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-500">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                 stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 3v18M17 7c0-2-2-3-5-3s-5 1-5 3 2 3 5 3 5 1 5 3-2 3-5 3-5-1-5-3"/>
                            </svg>

                        </div>

                        <span class="rounded-full bg-rose-50 px-2 py-1 text-[10px] font-bold text-rose-500">
                            Fee
                        </span>

                    </div>

                    <p class="mt-5 text-xs font-medium text-slate-400">
                        Total Komisi
                    </p>

                    <p class="mt-1 break-words font-display text-xl font-extrabold text-[#0c1830]">
                        Rp {{ number_format($stats['total_commission_paid'], 0, ',', '.') }}
                    </p>

                    <p class="mt-2 text-[11px] text-slate-400">
                        Komisi yang terpotong
                    </p>

                </div>


                {{-- Properti --}}
                <div class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-lg">

                    <div class="flex items-start justify-between">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                 stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-7h6v7"/>
                            </svg>

                        </div>

                        <span class="rounded-full bg-blue-50 px-2 py-1 text-[10px] font-bold text-blue-600">
                            Property
                        </span>

                    </div>

                    <p class="mt-5 text-xs font-medium text-slate-400">
                        Properti Saya
                    </p>

                    <p class="mt-1 font-display text-2xl font-extrabold text-[#0c1830]">
                        {{ $stats['total_properties'] }}
                    </p>

                    <p class="mt-2 text-[11px] text-slate-400">
                        Semua properti terdaftar
                    </p>

                </div>


                {{-- Booking --}}
                <div class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-lg">

                    <div class="flex items-start justify-between">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                 stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="17" rx="2"/>
                                <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>

                        </div>

                        <span class="rounded-full bg-amber-50 px-2 py-1 text-[10px] font-bold text-amber-600">
                            Booking
                        </span>

                    </div>

                    <p class="mt-5 text-xs font-medium text-slate-400">
                        Total Booking
                    </p>

                    <p class="mt-1 font-display text-2xl font-extrabold text-[#0c1830]">
                        {{ $stats['total_bookings'] }}
                    </p>

                    <p class="mt-2 text-[11px] text-slate-400">
                        Semua booking masuk
                    </p>

                </div>

            </div>


            {{-- =====================================================
                 CHART SECTION
            ====================================================== --}}
            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">

                {{-- Revenue Chart --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">

                    <div class="flex items-center justify-between gap-3">

                        <div>
                            <h2 class="font-display text-base font-bold text-[#0c1830]">
                                Tren Pendapatan
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Pendapatan booking berbayar tahun {{ now()->year }}
                            </p>
                        </div>

                        <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-[11px] font-bold text-emerald-600">
                            Tahun ini
                        </span>

                    </div>

                    <div class="mt-6 h-[290px]">
                        <canvas id="revenueChart"></canvas>
                    </div>

                </div>


                {{-- Property Distribution --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">

                    <div>
                        <h2 class="font-display text-base font-bold text-[#0c1830]">
                            Distribusi Properti
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Berdasarkan tipe properti
                        </p>
                    </div>

                    <div class="mx-auto mt-5 h-[190px] max-w-[220px]">
                        <canvas id="propertyTypeChart"></canvas>
                    </div>


                    <div class="mt-5 space-y-3">

                        @foreach ($propertyTypeDistribution as $type)
                            <div class="flex items-center justify-between">

                                <div class="flex items-center gap-2.5">

                                    <span
                                        class="h-2.5 w-2.5 rounded-full"
                                        style="background: {{ $type['color'] }}">
                                    </span>

                                    <span class="text-xs font-medium text-slate-600">
                                        {{ $type['label'] }}
                                    </span>

                                </div>

                                <span class="text-xs font-bold text-[#0c1830]">
                                    {{ $type['count'] }}
                                </span>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 PROPERTI
            ====================================================== --}}
            <div class="mt-8">

                <div class="mb-4 flex items-center justify-between">

                    <div>
                        <h2 class="font-display text-lg font-extrabold text-[#0c1830]">
                            Properti Saya
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Properti yang sedang Anda kelola
                        </p>
                    </div>

                    <a href="{{ route('mitra.properties.index') }}"
                       class="text-xs font-bold text-emerald-600 transition hover:text-emerald-700">
                        Lihat semua →
                    </a>

                </div>


                @if ($properties->isEmpty())

                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                            <svg class="h-7 w-7" fill="none" stroke="currentColor"
                                 stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-7h6v7"/>
                            </svg>

                        </div>

                        <h3 class="mt-4 font-display font-bold text-[#0c1830]">
                            Belum ada properti
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Tambahkan properti pertama Anda untuk mulai menerima booking.
                        </p>

                        <a href="{{ route('mitra.properties.create') }}"
                           class="mt-5 inline-flex rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white transition hover:bg-emerald-700">
                            + Tambah Properti
                        </a>

                    </div>

                @else

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">

                        @foreach ($properties->take(6) as $property)

                            @php

                                $statusMap = [
                                    'active' => [
                                        'Aktif',
                                        'bg-emerald-50 text-emerald-600'
                                    ],
                                    'pending' => [
                                        'Menunggu Verifikasi',
                                        'bg-amber-50 text-amber-600'
                                    ],
                                    'rejected' => [
                                        'Ditolak',
                                        'bg-rose-50 text-rose-600'
                                    ],
                                ];

                                [$statusLabel, $statusClass] =
                                    $statusMap[$property->status]
                                    ?? [
                                        ucfirst($property->status),
                                        'bg-slate-50 text-slate-500'
                                    ];

                                $imagePath = $property->cover_image
                                    ? asset('storage/' . $property->cover_image)
                                    : null;

                            @endphp


                            <div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-xl">

                                {{-- IMAGE --}}
                                <div class="relative h-[185px] overflow-hidden bg-slate-100">

                                    @if ($imagePath)

                                        <img
                                            src="{{ $imagePath }}"
                                            alt="{{ $property->name }}"
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105">

                                    @else

                                        <div class="flex h-full items-center justify-center">

                                            <svg class="h-12 w-12 text-slate-300"
                                                 fill="none" stroke="currentColor"
                                                 stroke-width="1.4"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-7h6v7"/>
                                            </svg>

                                        </div>

                                    @endif


                                    {{-- Status --}}
                                    <span class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-[10px] font-bold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>


                                    {{-- Edit --}}
                                    <a href="{{ route('mitra.properties.edit', $property) }}"
                                       class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-sm backdrop-blur transition hover:bg-white">

                                        <svg class="h-4 w-4" fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.8"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="m16.862 3.487 3.651 3.651M5 20h4l11-11a2.121 2.121 0 0 0-3-3L6 17v3Z"/>
                                        </svg>

                                    </a>

                                </div>


                                {{-- INFO --}}
                                <div class="p-4">

                                    <h3 class="truncate font-display text-sm font-bold text-[#0c1830]">
                                        {{ $property->name }}
                                    </h3>

                                    <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">

                                        <svg class="h-3.5 w-3.5 shrink-0"
                                             fill="none" stroke="currentColor"
                                             stroke-width="1.8"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z"/>
                                            <circle cx="12" cy="9" r="2.3"/>
                                        </svg>

                                        {{ $property->city }}

                                    </div>


                                    <div class="mt-3 flex items-center gap-2 text-[11px] text-slate-500">

                                        <span>
                                            {{ $property->type === 'villa' ? 'Villa' : ($property->type === 'kost_harian' ? 'Kost Harian' : 'Guest House') }}
                                        </span>

                                        <span class="text-slate-300">•</span>

                                        <span>
                                            {{ $property->bookings_count }} booking
                                        </span>

                                    </div>


                                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">

                                        <div>

                                            <div class="text-[10px] text-slate-400">
                                                Harga / malam
                                            </div>

                                            <div class="mt-0.5 font-display text-sm font-extrabold text-[#0c1830]">
                                                Rp {{ number_format($property->price_per_night, 0, ',', '.') }}
                                            </div>

                                        </div>

                                        <a href="{{ route('mitra.properties.edit', $property) }}"
                                           class="rounded-lg bg-slate-50 px-3 py-2 text-[11px] font-bold text-[#0c1830] transition hover:bg-emerald-50 hover:text-emerald-600">
                                            Kelola
                                        </a>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- =====================================================
                 BOOKING TERBARU
            ====================================================== --}}
            <div class="mt-8">

                <div class="mb-4 flex items-center justify-between">

                    <div>
                        <h2 class="font-display text-lg font-extrabold text-[#0c1830]">
                            Booking Terbaru
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Aktivitas booking terbaru dari properti Anda
                        </p>
                    </div>

                    <a href="{{ route('mitra.bookings.index') }}"
                       class="text-xs font-bold text-emerald-600 transition hover:text-emerald-700">
                        Lihat semua →
                    </a>

                </div>


                @if ($recentBookings->isEmpty())

                    <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center">

                        <div class="text-sm text-slate-500">
                            Belum ada booking masuk.
                        </div>

                    </div>

                @else

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

                        @foreach ($recentBookings as $booking)

                            @php

                                $needsResponse = $booking->needsMitraResponse();

                                $statusData = match(true) {

                                    $needsResponse => [
                                        'label' => 'Perlu Respon',
                                        'class' => 'bg-amber-50 text-amber-700'
                                    ],

                                    $booking->status === 'confirmed' => [
                                        'label' => 'Confirmed',
                                        'class' => 'bg-emerald-50 text-emerald-700'
                                    ],

                                    $booking->status === 'cancelled' => [
                                        'label' => 'Cancelled',
                                        'class' => 'bg-rose-50 text-rose-600'
                                    ],

                                    default => [
                                        'label' => ucfirst($booking->status),
                                        'class' => 'bg-blue-50 text-blue-600'
                                    ]

                                };

                            @endphp


                            <a href="{{ route('mitra.bookings.show', $booking) }}"
                               class="flex flex-col gap-4 border-b border-slate-100 px-5 py-4 transition last:border-b-0 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between">

                                <div class="flex min-w-0 items-center gap-4">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#0c1830] text-white">

                                        <svg class="h-5 w-5" fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.7"
                                             viewBox="0 0 24 24">
                                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                                            <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/>
                                        </svg>

                                    </div>


                                    <div class="min-w-0">

                                        <div class="flex flex-wrap items-center gap-2">

                                            <span class="font-mono text-[10px] text-slate-400">
                                                {{ $booking->booking_code }}
                                            </span>

                                            @if ($needsResponse)

                                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-bold text-amber-700">
                                                    Menunggu Respon
                                                </span>

                                            @endif

                                        </div>


                                        <div class="mt-1 truncate text-sm font-bold text-[#0c1830]">
                                            {{ $booking->property->name }}
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $booking->customer->name }}

                                            <span class="mx-1 text-slate-300">•</span>

                                            {{ $booking->check_in->format('d M Y') }}
                                            -
                                            {{ $booking->check_out->format('d M Y') }}
                                        </div>

                                    </div>

                                </div>


                                <div class="flex items-center justify-between gap-4 sm:justify-end">

                                    <div class="text-right">

                                        <div class="text-[10px] text-slate-400">
                                            Pendapatan
                                        </div>

                                        <div class="mt-0.5 text-sm font-extrabold text-[#0c1830]">
                                            Rp {{ number_format($booking->mitra_payout_amount, 0, ',', '.') }}
                                        </div>

                                    </div>


                                    <span class="rounded-full px-3 py-1.5 text-[10px] font-bold {{ $statusData['class'] }}">
                                        {{ $statusData['label'] }}
                                    </span>

                                </div>

                            </a>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </main>

</div>


{{-- =============================================================
     CHART.JS
============================================================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       REVENUE CHART
    ========================================================== */

    const revenueCanvas = document.getElementById('revenueChart');

    if (revenueCanvas) {

        new Chart(revenueCanvas, {

            type: 'line',

            data: {

                labels: @json($revenueChart['labels']),

                datasets: [{
                    label: 'Pendapatan',

                    data: @json($revenueChart['data']),

                    borderColor: '#059669',

                    backgroundColor: 'rgba(5, 150, 105, 0.08)',

                    borderWidth: 2.5,

                    fill: true,

                    tension: 0.4,

                    pointRadius: 3,

                    pointHoverRadius: 5,

                    pointBackgroundColor: '#059669',

                    pointBorderWidth: 0
                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        backgroundColor: '#0c1830',

                        padding: 12,

                        cornerRadius: 10,

                        displayColors: false,

                        callbacks: {

                            label: function(context) {

                                return 'Rp ' +
                                    new Intl.NumberFormat('id-ID')
                                    .format(context.raw);

                            }

                        }

                    }

                },

                scales: {

                    x: {

                        grid: {
                            display: false
                        },

                        border: {
                            display: false
                        },

                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 10
                            }
                        }

                    },

                    y: {

                        beginAtZero: true,

                        grid: {
                            color: '#f1f5f9'
                        },

                        border: {
                            display: false
                        },

                        ticks: {

                            color: '#94a3b8',

                            font: {
                                size: 10
                            },

                            callback: function(value) {

                                if (value >= 1000000) {
                                    return 'Rp ' +
                                        (value / 1000000).toFixed(1) +
                                        ' jt';
                                }

                                if (value >= 1000) {
                                    return 'Rp ' +
                                        (value / 1000).toFixed(0) +
                                        ' rb';
                                }

                                return 'Rp ' + value;

                            }

                        }

                    }

                }

            }

        });

    }


    /* =========================================================
       PROPERTY TYPE DONUT
    ========================================================== */

    const propertyCanvas =
        document.getElementById('propertyTypeChart');

    if (propertyCanvas) {

        new Chart(propertyCanvas, {

            type: 'doughnut',

            data: {

                labels: @json($propertyTypeDistribution->pluck('label')),

                datasets: [{

                    data: @json($propertyTypeDistribution->pluck('count')),

                    backgroundColor:
                        @json($propertyTypeDistribution->pluck('color')),

                    borderWidth: 0,

                    hoverOffset: 5

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '66%',

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        backgroundColor: '#0c1830',

                        padding: 10,

                        cornerRadius: 8

                    }

                }

            }

        });

    }

});

</script>

@endsection