@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="bg-[#f7f8f7] min-h-[calc(100vh-130px)]">

    <div class="max-w-[1500px] mx-auto flex">

        {{-- =====================================================
             SIDEBAR
        ====================================================== --}}
        <aside class="hidden lg:flex w-[235px] shrink-0 bg-white border-r border-gray-100 min-h-[calc(100vh-130px)]">

            <div class="w-full">

                {{-- Sidebar Menu --}}
                <div class="p-5">

                    <div class="text-[10px] font-bold tracking-[0.15em] text-gray-400 uppercase px-3 mb-4">
                        Menu
                    </div>

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#fff4cc] text-navy-900 font-semibold mb-1">

                        <svg class="w-[19px] h-[19px] shrink-0"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M3 11.5 12 4l9 7.5M5 10v10h14V10"/>
                        </svg>

                        <span class="text-sm">Dashboard</span>

                    </a>


                    {{-- Booking --}}
                    <a href="{{ route('customer.bookings.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-navy-900 transition">

                        <svg class="w-[19px] h-[19px] shrink-0"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <rect x="3"
                                  y="4"
                                  width="18"
                                  height="17"
                                  rx="2"
                                  stroke-width="1.8"/>

                            <path stroke-width="1.8"
                                  d="M16 2v4M8 2v4M3 10h18"/>

                        </svg>

                        <span class="text-sm">
                            Booking Saya
                        </span>

                    </a>


                    {{-- Saved --}}
                    <a href="{{ route('customer.saved.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-navy-900 transition">

                        <svg class="w-[19px] h-[19px] shrink-0"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18l-6-3.5L6 22V4z"/>

                        </svg>

                        <span class="text-sm">
                            Properti Disimpan
                        </span>

                    </a>


                    {{-- Divider --}}
                    <div class="border-t border-gray-100 my-6"></div>


                    {{-- Account --}}
                    <div class="text-[10px] font-bold tracking-[0.15em] text-gray-400 uppercase px-3 mb-4">
                        Account
                    </div>


                    {{-- Profile --}}
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-navy-900 transition">

                        <svg class="w-[19px] h-[19px]"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <circle cx="12"
                                    cy="8"
                                    r="4"
                                    stroke-width="1.8"/>

                            <path stroke-linecap="round"
                                  stroke-width="1.8"
                                  d="M4 21a8 8 0 0 1 16 0"/>

                        </svg>

                        <span class="text-sm">
                            Profil Saya
                        </span>

                    </a>


                    {{-- Home --}}
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-navy-900 transition">

                        <svg class="w-[19px] h-[19px]"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M3 11.5 12 4l9 7.5M5 10v10h14V10"/>

                        </svg>

                        <span class="text-sm">
                            Kembali ke Website
                        </span>

                    </a>

                </div>


                {{-- User --}}
                <div class="border-t border-gray-100 p-5 mt-2">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold overflow-hidden shrink-0">

                            @if(auth()->user()->avatar)

                                <img
                                    src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                    class="w-full h-full object-cover"
                                    alt="Avatar">

                            @else

                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                            @endif

                        </div>

                        <div class="min-w-0">

                            <div class="text-sm font-semibold text-navy-900 truncate">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="text-[11px] text-gray-400">
                                Customer
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </aside>


        {{-- =====================================================
             MAIN CONTENT
        ====================================================== --}}
        <main class="flex-1 min-w-0">


            <div class="p-5 sm:p-7 lg:p-8">


                {{-- =================================================
                     HEADER DASHBOARD
                ================================================== --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">

                    <div>

                        <div class="flex items-center gap-2 mb-1">

                            <div class="w-6 h-[3px] bg-brand-yellow rounded-full"></div>

                            <span class="text-[11px] uppercase tracking-widest text-gray-400 font-bold">
                                Customer Area
                            </span>

                        </div>

                        <h1 class="font-display text-[28px] sm:text-[32px] font-bold text-navy-900">
                            Dashboard
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Selamat datang kembali, {{ auth()->user()->name }}.
                        </p>

                    </div>


                    <a href="{{ route('home') }}"
                       class="inline-flex items-center justify-center gap-2 bg-brand-yellow hover:brightness-95 text-navy-900 font-bold text-sm px-5 py-3 rounded-xl transition shadow-sm">

                        <span class="text-base">+</span>

                        Cari Penginapan

                    </a>

                </div>


                {{-- SUCCESS --}}
                @if(session('success'))

                    <div class="bg-green-50 border border-green-100 text-green-700 text-sm rounded-xl px-4 py-3 mb-6">

                        {{ session('success') }}

                    </div>

                @endif


                {{-- =================================================
                     STATISTICS
                ================================================== --}}
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">


                    {{-- Total --}}
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">

                        <div class="flex items-center justify-between mb-4">

                            <div class="text-xs font-medium text-gray-500">
                                Total Booking
                            </div>

                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">

                                <svg class="w-5 h-5"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <rect x="5"
                                          y="3"
                                          width="14"
                                          height="18"
                                          rx="2"
                                          stroke-width="1.8"/>

                                    <path stroke-width="1.8"
                                          d="M9 7h6M9 11h6M9 15h3"/>

                                </svg>

                            </div>

                        </div>

                        <div class="font-display text-3xl font-bold text-navy-900">
                            {{ $stats['total_bookings'] }}
                        </div>

                        <div class="text-[11px] text-gray-400 mt-1">
                            Semua booking Anda
                        </div>

                    </div>


                    {{-- Upcoming --}}
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">

                        <div class="flex items-center justify-between mb-4">

                            <div class="text-xs font-medium text-gray-500">
                                Akan Datang
                            </div>

                            <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-600">

                                <svg class="w-5 h-5"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <rect x="3"
                                          y="4"
                                          width="18"
                                          height="17"
                                          rx="2"
                                          stroke-width="1.8"/>

                                    <path stroke-width="1.8"
                                          d="M16 2v4M8 2v4M3 10h18"/>

                                </svg>

                            </div>

                        </div>

                        <div class="font-display text-3xl font-bold text-navy-900">
                            {{ $stats['upcoming_bookings'] }}
                        </div>

                        <div class="text-[11px] text-gray-400 mt-1">
                            Perjalanan berikutnya
                        </div>

                    </div>


                    {{-- Completed --}}
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">

                        <div class="flex items-center justify-between mb-4">

                            <div class="text-xs font-medium text-gray-500">
                                Booking Selesai
                            </div>

                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600">

                                <svg class="w-5 h-5"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="m5 12 4 4L19 6"/>

                                </svg>

                            </div>

                        </div>

                        <div class="font-display text-3xl font-bold text-navy-900">
                            {{ $stats['completed_bookings'] }}
                        </div>

                        <div class="text-[11px] text-gray-400 mt-1">
                            Perjalanan selesai
                        </div>

                    </div>


                    {{-- Spending --}}
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">

                        <div class="flex items-center justify-between mb-4">

                            <div class="text-xs font-medium text-gray-500">
                                Total Pengeluaran
                            </div>

                            <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-700 font-bold text-xs">
                                IDR
                            </div>

                        </div>

                        <div class="font-display text-[23px] font-bold text-navy-900 leading-tight">

                            Rp {{ number_format($stats['total_spending'], 0, ',', '.') }}

                        </div>

                        <div class="text-[11px] text-gray-400 mt-2">
                            Dari pembayaran berhasil
                        </div>

                    </div>

                </div>


                {{-- =================================================
                     BOOKING + SUMMARY
                ================================================== --}}
                <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,2fr)_minmax(280px,1fr)] gap-5 mb-6">


                    {{-- =================================================
                         UPCOMING BOOKING
                    ================================================== --}}
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

                        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

                            <div>

                                <h2 class="font-display font-bold text-lg text-navy-900">
                                    Booking Terdekat
                                </h2>

                                <p class="text-xs text-gray-400 mt-1">
                                    Perjalanan Anda berikutnya
                                </p>

                            </div>

                            @if($upcomingBooking)

                                <span class="text-[10px] font-bold uppercase tracking-wide bg-green-50 text-green-600 px-3 py-1.5 rounded-full">
                                    Confirmed
                                </span>

                            @endif

                        </div>


                        @if($upcomingBooking)

                            <div class="p-6">

                                <div class="flex flex-col md:flex-row gap-5">


                                    {{-- IMAGE --}}
                                    <div class="w-full md:w-[190px] h-[145px] shrink-0 rounded-xl bg-gradient-to-br from-navy-900 to-blue-700 flex items-center justify-center">

                                        <svg class="w-12 h-12 text-white/80"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="1.5"
                                                  d="M3 21h18M4 21V10l8-6 8 6v11M8 21v-5h8v5M8 11h.01M12 11h.01M16 11h.01"/>

                                        </svg>

                                    </div>


                                    {{-- DETAIL --}}
                                    <div class="flex-1 min-w-0">

                                        <h3 class="font-display text-xl font-bold text-navy-900 truncate">
                                            {{ $upcomingBooking->property->name }}
                                        </h3>

                                        <p class="text-xs text-gray-400 mt-1">
                                            Booking #{{ $upcomingBooking->id }}
                                        </p>


                                        <div class="grid grid-cols-2 gap-5 mt-6">

                                            <div>

                                                <div class="text-[10px] uppercase tracking-wider text-gray-400 mb-1">
                                                    Check-in
                                                </div>

                                                <div class="text-sm font-semibold text-navy-900">

                                                    {{ \Carbon\Carbon::parse($upcomingBooking->check_in)->translatedFormat('d M Y') }}

                                                </div>

                                            </div>


                                            <div>

                                                <div class="text-[10px] uppercase tracking-wider text-gray-400 mb-1">
                                                    Check-out
                                                </div>

                                                <div class="text-sm font-semibold text-navy-900">

                                                    {{ \Carbon\Carbon::parse($upcomingBooking->check_out)->translatedFormat('d M Y') }}

                                                </div>

                                            </div>

                                        </div>


                                        <div class="flex items-center justify-between gap-4 mt-5 pt-4 border-t border-gray-100">

                                            <div>

                                                <div class="text-[10px] text-gray-400">
                                                    Total
                                                </div>

                                                <div class="font-display font-bold text-lg text-navy-900">

                                                    Rp {{ number_format($upcomingBooking->total_price, 0, ',', '.') }}

                                                </div>

                                            </div>


                                            <a href="{{ route('customer.bookings.show', $upcomingBooking) }}"
                                               class="text-xs font-bold bg-navy-900 hover:bg-navy-800 text-white px-4 py-2.5 rounded-xl transition">

                                                Detail →

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="p-10 text-center">

                                <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-50 flex items-center justify-center mb-4">

                                    <svg class="w-7 h-7 text-gray-400"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <rect x="3"
                                              y="4"
                                              width="18"
                                              height="17"
                                              rx="2"
                                              stroke-width="1.6"/>

                                        <path stroke-width="1.6"
                                              d="M16 2v4M8 2v4M3 10h18"/>

                                    </svg>

                                </div>

                                <h3 class="font-semibold text-navy-900">
                                    Belum ada booking mendatang
                                </h3>

                                <p class="text-xs text-gray-400 mt-1 mb-5">
                                    Yuk cari penginapan untuk perjalanan berikutnya.
                                </p>

                                <a href="{{ route('home') }}"
                                   class="inline-flex bg-navy-900 text-white text-xs font-semibold px-5 py-2.5 rounded-xl">

                                    Cari Penginapan

                                </a>

                            </div>

                        @endif

                    </div>


                    {{-- =================================================
                         SUMMARY
                    ================================================== --}}
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

                        <div class="px-6 py-5 border-b border-gray-100">

                            <h2 class="font-display font-bold text-lg text-navy-900">
                                Ringkasan Booking
                            </h2>

                            <p class="text-xs text-gray-400 mt-1">
                                Status perjalanan Anda
                            </p>

                        </div>


                        <div class="p-6 space-y-5">

                            @php
                                $totalBookings = max($stats['total_bookings'], 1);

                                $upcomingPercent =
                                    ($stats['upcoming_bookings'] / $totalBookings) * 100;

                                $completedPercent =
                                    ($stats['completed_bookings'] / $totalBookings) * 100;

                                $cancelledPercent =
                                    ($stats['cancelled_bookings'] / $totalBookings) * 100;
                            @endphp


                            {{-- Upcoming --}}
                            <div>

                                <div class="flex justify-between mb-2">

                                    <span class="text-xs text-gray-500">
                                        Akan Datang
                                    </span>

                                    <span class="text-xs font-bold text-navy-900">
                                        {{ $stats['upcoming_bookings'] }}
                                    </span>

                                </div>

                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">

                                    <div
                                        class="h-full bg-green-400 rounded-full"
                                        style="width: {{ min($upcomingPercent, 100) }}%">
                                    </div>

                                </div>

                            </div>


                            {{-- Completed --}}
                            <div>

                                <div class="flex justify-between mb-2">

                                    <span class="text-xs text-gray-500">
                                        Selesai
                                    </span>

                                    <span class="text-xs font-bold text-navy-900">
                                        {{ $stats['completed_bookings'] }}
                                    </span>

                                </div>

                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">

                                    <div
                                        class="h-full bg-brand-blue rounded-full"
                                        style="width: {{ min($completedPercent, 100) }}%">
                                    </div>

                                </div>

                            </div>


                            {{-- Cancelled --}}
                            <div>

                                <div class="flex justify-between mb-2">

                                    <span class="text-xs text-gray-500">
                                        Dibatalkan
                                    </span>

                                    <span class="text-xs font-bold text-navy-900">
                                        {{ $stats['cancelled_bookings'] }}
                                    </span>

                                </div>

                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">

                                    <div
                                        class="h-full bg-red-300 rounded-full"
                                        style="width: {{ min($cancelledPercent, 100) }}%">
                                    </div>

                                </div>

                            </div>


                            {{-- Saved --}}
                            <div class="border-t border-gray-100 pt-5">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <div class="text-[10px] uppercase tracking-wider text-gray-400">
                                            Properti Disimpan
                                        </div>

                                        <div class="font-display text-2xl font-bold text-navy-900 mt-1">
                                            {{ $stats['saved_properties'] }}
                                        </div>

                                    </div>


                                    <a href="{{ route('customer.saved.index') }}"
                                       class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100 transition">

                                        <svg class="w-5 h-5"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="1.8"
                                                  d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18l-6-3.5L6 22V4z"/>

                                        </svg>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     RECENT BOOKINGS
                ================================================== --}}
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">


                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

                        <div>

                            <h2 class="font-display font-bold text-lg text-navy-900">
                                Booking Terbaru
                            </h2>

                            <p class="text-xs text-gray-400 mt-1">
                                Aktivitas booking terakhir Anda
                            </p>

                        </div>


                        @if(!$recentBookings->isEmpty())

                            <a href="{{ route('customer.bookings.index') }}"
                               class="text-xs font-bold text-brand-blue hover:underline">

                                Lihat Semua →

                            </a>

                        @endif

                    </div>


                    @if($recentBookings->isEmpty())

                        <div class="p-12 text-center">

                            <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-50 flex items-center justify-center mb-4">

                                <svg class="w-7 h-7 text-gray-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.7"
                                          d="M3 7h18M5 7v13h14V7M8 7V4h8v3"/>

                                </svg>

                            </div>

                            <p class="font-semibold text-navy-900">
                                Belum ada booking
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                Booking Anda akan muncul di sini.
                            </p>

                        </div>

                    @else

                        <div class="overflow-x-auto">

                            <table class="w-full min-w-[700px]">

                                <thead>

                                    <tr class="bg-gray-50/70">

                                        <th class="px-6 py-3.5 text-left text-[10px] uppercase tracking-wider text-gray-400 font-bold">
                                            Properti
                                        </th>

                                        <th class="px-6 py-3.5 text-left text-[10px] uppercase tracking-wider text-gray-400 font-bold">
                                            Check-in
                                        </th>

                                        <th class="px-6 py-3.5 text-left text-[10px] uppercase tracking-wider text-gray-400 font-bold">
                                            Check-out
                                        </th>

                                        <th class="px-6 py-3.5 text-left text-[10px] uppercase tracking-wider text-gray-400 font-bold">
                                            Total
                                        </th>

                                        <th class="px-6 py-3.5 text-left text-[10px] uppercase tracking-wider text-gray-400 font-bold">
                                            Status
                                        </th>

                                        <th class="px-6 py-3.5"></th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-gray-100">

                                    @foreach($recentBookings as $booking)

                                        @php

                                            $statusStyle = match($booking->status) {

                                                'confirmed'
                                                    => 'bg-green-50 text-green-600',

                                                'pending'
                                                    => 'bg-yellow-50 text-yellow-600',

                                                'cancelled'
                                                    => 'bg-red-50 text-red-500',

                                                default
                                                    => 'bg-blue-50 text-brand-blue',

                                            };

                                        @endphp


                                        <tr class="hover:bg-gray-50 transition">


                                            {{-- Property --}}
                                            <td class="px-6 py-4">

                                                <div class="flex items-center gap-3">

                                                    <div class="w-9 h-9 rounded-lg bg-navy-900 text-white flex items-center justify-center shrink-0">

                                                        <svg class="w-4 h-4"
                                                             fill="none"
                                                             stroke="currentColor"
                                                             viewBox="0 0 24 24">

                                                            <path stroke-linecap="round"
                                                                  stroke-width="1.7"
                                                                  d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"/>

                                                        </svg>

                                                    </div>


                                                    <div class="min-w-0">

                                                        <div class="text-sm font-semibold text-navy-900 truncate max-w-[220px]">

                                                            {{ $booking->property->name }}

                                                        </div>

                                                        <div class="text-[10px] text-gray-400">
                                                            #{{ $booking->id }}
                                                        </div>

                                                    </div>

                                                </div>

                                            </td>


                                            {{-- Check in --}}
                                            <td class="px-6 py-4">

                                                <span class="text-xs text-gray-600 font-medium">

                                                    {{ \Carbon\Carbon::parse($booking->check_in)->translatedFormat('d M Y') }}

                                                </span>

                                            </td>


                                            {{-- Check out --}}
                                            <td class="px-6 py-4">

                                                <span class="text-xs text-gray-600 font-medium">

                                                    {{ \Carbon\Carbon::parse($booking->check_out)->translatedFormat('d M Y') }}

                                                </span>

                                            </td>


                                            {{-- Price --}}
                                            <td class="px-6 py-4">

                                                <span class="text-sm font-bold text-navy-900">

                                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}

                                                </span>

                                            </td>


                                            {{-- Status --}}
                                            <td class="px-6 py-4">

                                                <div class="flex flex-col items-start gap-1">

                                                    <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full {{ $statusStyle }}">

                                                        {{ ucfirst($booking->status) }}

                                                    </span>


                                                    @if($booking->payment_status === 'paid')

                                                        <span class="text-[10px] text-green-600">
                                                            ✓ Pembayaran Lunas
                                                        </span>

                                                    @elseif($booking->payment_status === 'pending')

                                                        <span class="text-[10px] text-yellow-600">
                                                            Menunggu Pembayaran
                                                        </span>

                                                    @elseif($booking->payment_status === 'failed')

                                                        <span class="text-[10px] text-red-500">
                                                            Pembayaran Gagal
                                                        </span>

                                                    @endif

                                                </div>

                                            </td>


                                            {{-- Detail --}}
                                            <td class="px-6 py-4 text-right">

                                                <a href="{{ route('customer.bookings.show', $booking) }}"
                                                   class="text-xs font-semibold text-brand-blue hover:underline">

                                                    Detail

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif

                </div>

            </div>

        </main>

    </div>

</div>

@endsection