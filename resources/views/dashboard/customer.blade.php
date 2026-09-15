@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')

<style>
    .booking-scroll::-webkit-scrollbar {
        height: 6px;
    }

    .booking-scroll::-webkit-scrollbar-track {
        background: #f5f5f5;
        border-radius: 999px;
    }

    .booking-scroll::-webkit-scrollbar-thumb {
        background: #d8d8d8;
        border-radius: 999px;
    }

    .dashboard-card {
        border: 1px solid #e9e9e9;
        box-shadow: 0 2px 8px rgba(0,0,0,.025);
    }
</style>

@php
    $today = \Carbon\Carbon::today();
@endphp

<div class="min-h-screen bg-[#fafafa] text-[#202020]">

    <div class="max-w-[1500px] mx-auto px-3 sm:px-5 lg:px-8 py-5 lg:py-7">

        <div class="flex flex-col lg:flex-row gap-5 lg:gap-8">


            {{-- ==========================================================
                 SIDEBAR
            =========================================================== --}}
            <aside class="lg:w-[235px] xl:w-[255px] shrink-0">

                <div class="lg:sticky lg:top-6">

                    {{-- MOBILE USER --}}
                    <div class="lg:hidden bg-white dashboard-card rounded-2xl p-4 mb-4">

                        <div class="flex items-center gap-3">

                            <div class="w-11 h-11 rounded-full bg-[#ef6c2f] text-white flex items-center justify-center font-bold overflow-hidden">

                                @if(auth()->user()->avatar)

                                    <img
                                        src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                        class="w-full h-full object-cover"
                                        alt="Avatar">

                                @else

                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                                @endif

                            </div>

                            <div>

                                <div class="font-semibold text-sm">
                                    {{ auth()->user()->name }}
                                </div>

                                <div class="text-xs text-gray-400">
                                    Customer
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- SIDEBAR --}}
                    <div class="bg-white rounded-2xl lg:rounded-3xl p-2.5 lg:p-3 dashboard-card">

                        {{-- MY BOOKINGS --}}
                        <a
                            href="{{ route('customer.bookings.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-xl bg-[#fff1e8] text-[#ed6d32] font-semibold transition">

                            <svg
                                class="w-5 h-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <rect
                                    x="3"
                                    y="4"
                                    width="18"
                                    height="17"
                                    rx="2"
                                    stroke-width="1.7"/>

                                <path
                                    stroke-width="1.7"
                                    d="M16 2v4M8 2v4M3 10h18"/>

                                <path
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                    d="M8 14h3M8 17h5"/>

                            </svg>

                            <span class="text-sm">
                                My Bookings
                            </span>

                            @if($stats['upcoming_bookings'] > 0)

                                <span class="ml-auto min-w-5 h-5 px-1.5 rounded-full bg-[#ed6d32] text-white text-[10px] flex items-center justify-center">
                                    {{ $stats['upcoming_bookings'] }}
                                </span>

                            @endif

                        </a>


                        {{-- PERSONAL DETAILS --}}
                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">

                            <svg
                                class="w-5 h-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <circle
                                    cx="12"
                                    cy="8"
                                    r="4"
                                    stroke-width="1.7"/>

                                <path
                                    stroke-linecap="round"
                                    stroke-width="1.7"
                                    d="M4 21a8 8 0 0 1 16 0"/>

                            </svg>

                            <span class="text-sm">
                                Personal details
                            </span>

                        </a>


                        {{-- PAYMENT INFO --}}
                        <a
                            href="{{ route('customer.bookings.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">

                            <svg
                                class="w-5 h-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <rect
                                    x="2.5"
                                    y="5"
                                    width="19"
                                    height="14"
                                    rx="2"
                                    stroke-width="1.7"/>

                                <path
                                    stroke-width="1.7"
                                    d="M2.5 10h19"/>

                                <path
                                    stroke-linecap="round"
                                    stroke-width="1.7"
                                    d="M6 15h4"/>

                            </svg>

                            <span class="text-sm">
                                Payment Info
                            </span>

                        </a>


                        {{-- SECURITY --}}
                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">

                            <svg
                                class="w-5 h-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <rect
                                    x="5"
                                    y="10"
                                    width="14"
                                    height="11"
                                    rx="2"
                                    stroke-width="1.7"/>

                                <path
                                    stroke-width="1.7"
                                    d="M8 10V7a4 4 0 0 1 8 0v3"/>

                                <circle
                                    cx="12"
                                    cy="15"
                                    r="1"
                                    fill="currentColor"/>

                            </svg>

                            <span class="text-sm">
                                Security & Login
                            </span>

                        </a>


                        {{-- DIVIDER --}}
                        <div class="border-t border-gray-100 my-3"></div>


                        {{-- SAVED --}}
                        <a
                            href="{{ route('customer.saved.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">

                            <svg
                                class="w-5 h-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18l-6-3.5L6 22V4z"/>

                            </svg>

                            <span class="text-sm">
                                Saved Properties
                            </span>

                            @if($stats['saved_properties'] > 0)

                                <span class="ml-auto text-xs text-gray-400">
                                    {{ $stats['saved_properties'] }}
                                </span>

                            @endif

                        </a>


                        {{-- WEBSITE --}}
                        <a
                            href="{{ route('home') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">

                            <svg
                                class="w-5 h-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M3 11.5 12 4l9 7.5M5 10v10h14V10"/>

                            </svg>

                            <span class="text-sm">
                                Back to Website
                            </span>

                        </a>


                        {{-- USER DESKTOP --}}
                        <div class="hidden lg:block border-t border-gray-100 mt-3 pt-4 px-3 pb-2">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-full bg-[#ed6d32] text-white flex items-center justify-center font-bold overflow-hidden shrink-0">

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

                                    <div class="text-sm font-semibold truncate">
                                        {{ auth()->user()->name }}
                                    </div>

                                    <div class="text-[11px] text-gray-400">
                                        Customer
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </aside>


            {{-- ==========================================================
                 MAIN
            =========================================================== --}}
            <main class="flex-1 min-w-0">


                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-5">

                    <div>

                        <div class="flex items-center gap-2">

                            <a
                                href="{{ route('home') }}"
                                class="text-gray-400 hover:text-[#ed6d32] transition">

                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m15 18-6-6 6-6"/>

                                </svg>

                            </a>

                            <h1 class="font-display text-[25px] sm:text-[30px] font-bold text-[#ed6d32]">
                                My Bookings
                            </h1>

                        </div>

                    </div>


                    <a
                        href="{{ route('home') }}"
                        class="hidden sm:inline-flex items-center gap-2 bg-[#ed6d32] hover:bg-[#d95d27] text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition">

                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 5v14M5 12h14"/>

                        </svg>

                        Cari Penginapan

                    </a>

                </div>


                {{-- SUCCESS --}}
                @if(session('success'))

                    <div class="mb-5 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- ======================================================
                     ACTIVE BOOKING
                ======================================================= --}}
                @if($activeBooking)

                    @php

                        $activeProperty = $activeBooking->property;

                        $activeImage = null;

                        if($activeProperty) {

                            $activeImage = $activeProperty->cover_image
                                ? asset('storage/' . $activeProperty->cover_image)
                                : (
                                    $activeProperty->images->first()
                                    ? asset('storage/' . $activeProperty->images->first()->path)
                                    : null
                                );

                        }

                        $checkIn = \Carbon\Carbon::parse($activeBooking->check_in);
                        $checkOut = \Carbon\Carbon::parse($activeBooking->check_out);

                        $daysUntil = max(
                            0,
                            $today->diffInDays($checkIn, false)
                        );

                        $hoursUntil = max(
                            0,
                            $today->copy()->addDays($daysUntil)->diffInHours($checkIn)
                        );

                        $nights = max(
                            1,
                            $checkIn->diffInDays($checkOut)
                        );

                    @endphp


                    <div class="dashboard-card bg-white rounded-2xl overflow-hidden mb-7">


                        {{-- TOP STATUS --}}
                        <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">

                            <div class="font-semibold text-[#ed6d32]">
                                Active
                            </div>

                            <div class="flex items-center gap-2 text-[#5546a8] text-sm font-semibold">

                                <span class="hidden sm:inline">
                                    Booking Confirmed
                                </span>

                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <circle
                                        cx="12"
                                        cy="8"
                                        r="4"
                                        stroke-width="1.6"/>

                                    <path
                                        stroke-width="1.6"
                                        d="M4 21a8 8 0 0 1 16 0"/>

                                </svg>

                            </div>

                        </div>


                        {{-- HERO --}}
                        <div class="grid grid-cols-1 md:grid-cols-[42%_58%]">


                            {{-- IMAGE --}}
                            <div class="relative h-[230px] md:h-[270px] bg-gray-100 overflow-hidden">

                                @if($activeImage)

                                    <img
                                        src="{{ $activeImage }}"
                                        class="absolute inset-0 w-full h-full object-cover"
                                        alt="{{ $activeProperty->name }}">

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/10 to-transparent"></div>

                                @else

                                    <div class="absolute inset-0 bg-gradient-to-br from-[#172a45] to-[#45648c]"></div>

                                @endif


                                {{-- COUNTDOWN --}}
                                <div class="absolute bottom-5 left-5 sm:left-7 text-white">

                                    <div class="flex items-end gap-5">

                                        <div>

                                            <div class="text-4xl sm:text-5xl font-bold leading-none">
                                                {{ str_pad($daysUntil, 2, '0', STR_PAD_LEFT) }}
                                            </div>

                                            <div class="text-[10px] tracking-[0.18em] uppercase mt-1 opacity-90">
                                                Days
                                            </div>

                                        </div>


                                        <div class="pb-0.5 text-3xl font-light opacity-80">
                                            :
                                        </div>


                                        <div>

                                            <div class="text-4xl sm:text-5xl font-bold leading-none">
                                                {{ str_pad($hoursUntil, 2, '0', STR_PAD_LEFT) }}
                                            </div>

                                            <div class="text-[10px] tracking-[0.18em] uppercase mt-1 opacity-90">
                                                Hours
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- BOOKING INFO --}}
                            <div class="flex flex-col">


                                {{-- LOCATION --}}
                                <div class="grid grid-cols-2 divide-x divide-gray-100 flex-1">


                                    {{-- CHECK IN --}}
                                    <div class="p-5 sm:p-6">

                                        <div class="text-[#5546a8] font-bold text-lg sm:text-xl mb-2 truncate">
                                            {{ $activeProperty->city ?? 'Lokasi' }}
                                        </div>

                                        <div class="font-display font-bold text-lg text-gray-900">
                                            {{ $checkIn->translatedFormat('D, d M') }}
                                        </div>

                                        <div class="font-display font-bold text-xl text-gray-900 mt-1">
                                            {{ $checkIn->format('H:i') }}
                                        </div>

                                        <div class="text-[10px] uppercase tracking-wider text-gray-400 mt-1">
                                            Check-in
                                        </div>

                                    </div>


                                    {{-- CHECK OUT --}}
                                    <div class="p-5 sm:p-6">

                                        <div class="text-[#5546a8] font-bold text-lg sm:text-xl mb-2 truncate">
                                            {{ $activeProperty->city ?? 'Lokasi' }}
                                        </div>

                                        <div class="font-display font-bold text-lg text-gray-900">
                                            {{ $checkOut->translatedFormat('D, d M') }}
                                        </div>

                                        <div class="font-display font-bold text-xl text-gray-900 mt-1">
                                            {{ $checkOut->format('H:i') }}
                                        </div>

                                        <div class="text-[10px] uppercase tracking-wider text-gray-400 mt-1">
                                            Check-out
                                        </div>

                                    </div>

                                </div>


                                {{-- PROPERTY NAME --}}
                                <div class="border-t border-gray-100 px-5 sm:px-6 py-4">

                                    <div class="text-[10px] text-gray-400 uppercase tracking-wider">
                                        Property
                                    </div>

                                    <div class="font-display font-bold text-lg text-gray-900 mt-1 truncate">
                                        {{ $activeProperty->name }}
                                    </div>

                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $nights }} malam ·
                                        {{ $activeBooking->guest_count }} tamu
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- BOOKING DETAILS --}}
                        <div class="border-t border-gray-100">


                            <div class="px-5 sm:px-6 py-4 flex items-center justify-between">

                                <div class="flex items-center gap-3">

                                    <div class="w-8 h-8 rounded-lg bg-[#f4f2ff] text-[#5546a8] flex items-center justify-center">

                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                stroke-width="1.6"
                                                d="M6 2h10l3 3v17H6z"/>

                                            <path
                                                stroke-width="1.6"
                                                d="M16 2v4h4M9 11h7M9 15h7M9 19h5"/>

                                        </svg>

                                    </div>

                                    <span class="font-semibold text-sm sm:text-base">
                                        Booking details
                                    </span>

                                </div>


                                <a
                                    href="{{ route('customer.bookings.show', $activeBooking) }}"
                                    class="text-gray-500 hover:text-[#ed6d32] transition">

                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.7"
                                            d="M5 12h14M13 6l6 6-6 6"/>

                                    </svg>

                                </a>

                            </div>


                            {{-- PROGRESS --}}
                            <div class="px-4 sm:px-6 pb-4">

                                <div class="bg-[#f4f2ff] rounded-xl p-4 overflow-x-auto">

                                    <div class="min-w-[650px] grid grid-cols-5 gap-2">


                                        {{-- BOOKING --}}
                                        <div>

                                            <div class="text-center text-xs font-semibold">
                                                Booking
                                            </div>

                                            <div class="text-center text-[11px] text-[#ed6d32] font-semibold mt-2">
                                                Confirmed
                                            </div>

                                            <div class="h-2 bg-[#ed6d32] rounded-full mt-2"></div>

                                        </div>


                                        {{-- PAYMENT --}}
                                        <div>

                                            <div class="text-center text-xs font-semibold">
                                                Payment
                                            </div>

                                            <div class="text-center text-[11px] mt-2
                                                {{ $activeBooking->payment_status === 'paid'
                                                    ? 'text-[#ed6d32]'
                                                    : 'text-gray-500' }}">

                                                {{ $activeBooking->payment_status === 'paid'
                                                    ? 'Paid'
                                                    : ucfirst($activeBooking->payment_status) }}

                                            </div>

                                            <div class="h-2 rounded-full mt-2
                                                {{ $activeBooking->payment_status === 'paid'
                                                    ? 'bg-[#ed6d32]'
                                                    : 'bg-gray-200' }}">
                                            </div>

                                        </div>


                                        {{-- CHECK IN --}}
                                        <div>

                                            <div class="text-center text-xs font-semibold">
                                                Check-in
                                            </div>

                                            <div class="text-center text-[11px] text-[#5546a8] mt-2">
                                                {{ $checkIn->translatedFormat('d M Y') }}
                                            </div>

                                            <div class="h-2 bg-[#dcd8fa] rounded-full mt-2"></div>

                                        </div>


                                        {{-- ON TRIP --}}
                                        <div>

                                            <div class="text-center text-xs font-semibold">
                                                On Trip
                                            </div>

                                            <div class="text-center text-[11px] text-[#5546a8] mt-2">
                                                {{ $nights }} hari
                                            </div>

                                            <div class="h-2 bg-[#dcd8fa] rounded-full mt-2"></div>

                                        </div>


                                        {{-- CHECK OUT --}}
                                        <div>

                                            <div class="text-center text-xs font-semibold">
                                                Check-out
                                            </div>

                                            <div class="text-center text-[11px] text-gray-400 mt-2">
                                                {{ $checkOut->translatedFormat('d M Y') }}
                                            </div>

                                            <div class="h-2 bg-[#dcd8fa] rounded-full mt-2"></div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- BOTTOM --}}
                            <div class="px-5 sm:px-6 pb-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

                                <div>

                                    <div class="text-xs text-gray-500">
                                        Total booking
                                    </div>

                                    <div class="font-display font-bold text-lg">
                                        Rp {{ number_format($activeBooking->total_price, 0, ',', '.') }}
                                    </div>

                                    <div class="text-[10px] text-gray-400">
                                        #{{ $activeBooking->booking_code ?? $activeBooking->id }}
                                    </div>

                                </div>


                                <a
                                    href="{{ route('customer.bookings.show', $activeBooking) }}"
                                    class="inline-flex items-center justify-center bg-[#ed6d32] hover:bg-[#d95d27] text-white font-semibold text-xs px-5 py-3 rounded-xl transition w-full sm:w-auto">

                                    Lihat Detail Booking

                                    <svg
                                        class="w-4 h-4 ml-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 12h14M13 6l6 6-6 6"/>

                                    </svg>

                                </a>

                            </div>

                        </div>

                    </div>

                @else

                    {{-- NO ACTIVE BOOKING --}}
                    <div class="dashboard-card bg-white rounded-2xl p-8 sm:p-12 text-center mb-7">

                        <div class="w-16 h-16 mx-auto rounded-2xl bg-[#fff1e8] text-[#ed6d32] flex items-center justify-center mb-5">

                            <svg
                                class="w-8 h-8"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <rect
                                    x="3"
                                    y="4"
                                    width="18"
                                    height="17"
                                    rx="2"
                                    stroke-width="1.6"/>

                                <path
                                    stroke-width="1.6"
                                    d="M16 2v4M8 2v4M3 10h18"/>

                            </svg>

                        </div>

                        <h2 class="font-display text-xl font-bold">
                            Belum ada booking aktif
                        </h2>

                        <p class="text-sm text-gray-400 mt-2 mb-5">
                            Yuk cari penginapan untuk perjalanan berikutnya.
                        </p>

                        <a
                            href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 bg-[#ed6d32] hover:bg-[#d95d27] text-white font-semibold text-sm px-5 py-3 rounded-xl">

                            Cari Penginapan

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 12h14M13 6l6 6-6 6"/>

                            </svg>

                        </a>

                    </div>

                @endif



                {{-- ======================================================
                     UPCOMING TRIPS
                ======================================================= --}}

                @if($upcomingTrips->count() > 0)

                    <section class="mb-8">

                        <div class="flex items-center justify-between mb-4">

                            <h2 class="font-display text-lg sm:text-xl font-bold text-[#ed6d32]">
                                Upcoming Trips
                            </h2>

                            <a
                                href="{{ route('customer.bookings.index') }}"
                                class="text-xs text-gray-400 hover:text-[#ed6d32]">
                                View all →
                            </a>

                        </div>


                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

                            @foreach($upcomingTrips as $trip)

                                @php

                                    $property = $trip->property;

                                    $image = null;

                                    if($property) {

                                        $image = $property->cover_image
                                            ? asset('storage/' . $property->cover_image)
                                            : (
                                                $property->images->first()
                                                ? asset('storage/' . $property->images->first()->path)
                                                : null
                                            );

                                    }

                                    $tripCheckIn = \Carbon\Carbon::parse($trip->check_in);
                                    $tripCheckOut = \Carbon\Carbon::parse($trip->check_out);

                                    $tripDays = max(
                                        0,
                                        $today->diffInDays($tripCheckIn, false)
                                    );

                                @endphp


                                <a
                                    href="{{ route('customer.bookings.show', $trip) }}"
                                    class="group dashboard-card bg-white rounded-2xl overflow-hidden hover:shadow-md transition">


                                    <div class="flex h-[145px] sm:h-[160px]">


                                        {{-- IMAGE --}}
                                        <div class="relative w-[38%] shrink-0 bg-gray-100 overflow-hidden">

                                            @if($image)

                                                <img
                                                    src="{{ $image }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                                    alt="{{ $property->name ?? 'Property' }}">

                                            @else

                                                <div class="w-full h-full bg-gradient-to-br from-[#263c58] to-[#7592ad]"></div>

                                            @endif

                                        </div>


                                        {{-- CONTENT --}}
                                        <div class="flex-1 min-w-0 p-4 sm:p-5">

                                            <div class="text-[#ed6d32] text-xs font-bold mb-1">

                                                @if($tripDays === 0)
                                                    Today
                                                @elseif($tripDays === 1)
                                                    Tomorrow
                                                else
                                                    In {{ $tripDays }} Days
                                                @endif

                                            </div>


                                            <div class="font-display font-bold text-base sm:text-lg truncate">
                                                {{ $property->name ?? 'Penginapan' }}
                                            </div>


                                            <div class="text-[#5546a8] font-semibold text-sm mt-1">
                                                {{ $property->city ?? 'Lokasi' }}
                                            </div>


                                            <div class="grid grid-cols-2 gap-3 mt-3">

                                                <div>

                                                    <div class="text-[10px] text-gray-400">
                                                        Check-in
                                                    </div>

                                                    <div class="text-xs sm:text-sm font-semibold mt-0.5">
                                                        {{ $tripCheckIn->translatedFormat('d M Y') }}
                                                    </div>

                                                </div>


                                                <div class="border-l border-gray-100 pl-3">

                                                    <div class="text-[10px] text-gray-400">
                                                        Check-out
                                                    </div>

                                                    <div class="text-xs sm:text-sm font-semibold mt-0.5">
                                                        {{ $tripCheckOut->translatedFormat('d M Y') }}
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </a>

                            @endforeach

                        </div>

                    </section>

                @endif



                {{-- ======================================================
                     PAST TRIPS
                ======================================================= --}}

                @if($pastTrips->count() > 0)

                    <section class="mb-8">

                        <div class="flex items-center justify-between mb-4">

                            <h2 class="font-display text-lg sm:text-xl font-bold text-[#5546a8]">
                                Past Trips
                            </h2>

                            <a
                                href="{{ route('customer.bookings.index') }}"
                                class="text-xs text-gray-400 hover:text-[#5546a8]">
                                View all →
                            </a>

                        </div>


                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

                            @foreach($pastTrips as $trip)

                                @php

                                    $property = $trip->property;

                                    $image = null;

                                    if($property) {

                                        $image = $property->cover_image
                                            ? asset('storage/' . $property->cover_image)
                                            : (
                                                $property->images->first()
                                                ? asset('storage/' . $property->images->first()->path)
                                                : null
                                            );

                                    }

                                    $tripCheckIn = \Carbon\Carbon::parse($trip->check_in);
                                    $tripCheckOut = \Carbon\Carbon::parse($trip->check_out);

                                @endphp


                                <a
                                    href="{{ route('customer.bookings.show', $trip) }}"
                                    class="group dashboard-card bg-white rounded-2xl overflow-hidden hover:shadow-md transition">


                                    <div class="flex h-[145px] sm:h-[160px]">


                                        {{-- IMAGE --}}
                                        <div class="relative w-[38%] shrink-0 bg-gray-100 overflow-hidden">

                                            @if($image)

                                                <img
                                                    src="{{ $image }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                                    alt="{{ $property->name ?? 'Property' }}">

                                            @else

                                                <div class="w-full h-full bg-gradient-to-br from-gray-500 to-gray-700"></div>

                                            @endif


                                            @if($trip->status === 'cancelled')

                                                <div class="absolute top-3 left-3 text-[9px] font-bold uppercase tracking-wide bg-red-500 text-white px-2 py-1 rounded-full">
                                                    Cancelled
                                                </div>

                                            @elseif($trip->payment_status === 'paid')

                                                <div class="absolute top-3 left-3 text-[9px] font-bold uppercase tracking-wide bg-green-500 text-white px-2 py-1 rounded-full">
                                                    Paid
                                                </div>

                                            @endif

                                        </div>


                                        {{-- CONTENT --}}
                                        <div class="flex-1 min-w-0 p-4 sm:p-5">

                                            <div class="text-[#5546a8] text-xs font-bold mb-1">
                                                Completed
                                            </div>


                                            <div class="font-display font-bold text-base sm:text-lg truncate">
                                                {{ $property->name ?? 'Penginapan' }}
                                            </div>


                                            <div class="text-[#5546a8] font-semibold text-sm mt-1">
                                                {{ $property->city ?? 'Lokasi' }}
                                            </div>


                                            <div class="grid grid-cols-2 gap-3 mt-3">

                                                <div>

                                                    <div class="text-[10px] text-gray-400">
                                                        Check-in
                                                    </div>

                                                    <div class="text-xs sm:text-sm font-semibold mt-0.5">
                                                        {{ $tripCheckIn->translatedFormat('d M Y') }}
                                                    </div>

                                                </div>


                                                <div class="border-l border-gray-100 pl-3">

                                                    <div class="text-[10px] text-gray-400">
                                                        Check-out
                                                    </div>

                                                    <div class="text-xs sm:text-sm font-semibold mt-0.5">
                                                        {{ $tripCheckOut->translatedFormat('d M Y') }}
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </a>

                            @endforeach

                        </div>

                    </section>

                @endif



                {{-- ======================================================
                     EMPTY STATE
                ======================================================= --}}
                @if(
                    !$activeBooking &&
                    $upcomingTrips->count() === 0 &&
                    $pastTrips->count() === 0
                )

                    <div class="dashboard-card bg-white rounded-2xl p-10 text-center">

                        <div class="w-16 h-16 mx-auto rounded-2xl bg-[#f4f2ff] text-[#5546a8] flex items-center justify-center mb-5">

                            <svg
                                class="w-8 h-8"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <rect
                                    x="3"
                                    y="4"
                                    width="18"
                                    height="17"
                                    rx="2"
                                    stroke-width="1.6"/>

                                <path
                                    stroke-width="1.6"
                                    d="M16 2v4M8 2v4M3 10h18"/>

                            </svg>

                        </div>

                        <h2 class="font-display font-bold text-xl">
                            Belum ada perjalanan
                        </h2>

                        <p class="text-sm text-gray-400 mt-2 mb-5">
                            Booking penginapan pertama kamu dan mulai perjalanan.
                        </p>

                        <a
                            href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 bg-[#ed6d32] hover:bg-[#d95d27] text-white font-semibold text-sm px-5 py-3 rounded-xl">

                            Explore Penginapan

                        </a>

                    </div>

                @endif



                {{-- ======================================================
                     MOBILE SEARCH BUTTON
                ======================================================= --}}
                <div class="sm:hidden mt-5">

                    <a
                        href="{{ route('home') }}"
                        class="flex items-center justify-center gap-2 w-full bg-[#ed6d32] text-white font-semibold text-sm px-5 py-3.5 rounded-xl">

                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <circle
                                cx="11"
                                cy="11"
                                r="7"
                                stroke-width="2"/>

                            <path
                                stroke-linecap="round"
                                stroke-width="2"
                                d="m20 20-4-4"/>

                        </svg>

                        Cari Penginapan

                    </a>

                </div>

            </main>

        </div>

    </div>

</div>

@endsection