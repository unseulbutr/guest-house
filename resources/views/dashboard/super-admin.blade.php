@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('dashboard_layout', 'true')

@section('content')

@php
    $user = auth()->user();
    $firstName = explode(' ', trim($user->name))[0];

    $statusStyles = [
        'pending' => [
            'bg' => 'bg-amber-50',
            'text' => 'text-amber-700',
            'dot' => 'bg-amber-500',
        ],
        'confirmed' => [
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-700',
            'dot' => 'bg-blue-500',
        ],
        'completed' => [
            'bg' => 'bg-emerald-50',
            'text' => 'text-emerald-700',
            'dot' => 'bg-emerald-500',
        ],
        'cancelled' => [
            'bg' => 'bg-red-50',
            'text' => 'text-red-700',
            'dot' => 'bg-red-500',
        ],
    ];

    $roleTotal = max(
        1,
        $customerCount + $mitraCount + $adminCount + $superAdminCount
    );

    $maxRevenue = max($monthlyRevenue ?: [0]);
    $maxRevenue = $maxRevenue > 0 ? $maxRevenue : 1;
@endphp


<div class="min-h-screen bg-[#f6f7fb] text-slate-900">

    {{-- ================================================================ --}}
    {{-- SIDEBAR --}}
    {{-- ================================================================ --}}

    <aside
        id="superAdminSidebar"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-950 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-2xl shadow-slate-950/20">

        <div class="h-full flex flex-col">

            {{-- LOGO --}}
            <div class="h-20 flex items-center px-6 border-b border-white/10">

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-2xl bg-white text-slate-950 flex items-center justify-center font-black text-xl shadow-lg">
                        G
                    </div>

                    <div>
                        <h1 class="font-extrabold tracking-tight">
                            Guest House
                        </h1>

                        <p class="text-xs text-slate-400 mt-0.5">
                            Super Admin Control
                        </p>
                    </div>

                </a>

            </div>


            {{-- NAVIGATION --}}
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">

                <p class="px-4 mb-3 text-[10px] uppercase tracking-[0.18em] text-slate-500 font-bold">
                    Overview
                </p>


                {{-- DASHBOARD --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-white text-slate-950 font-bold shadow-lg shadow-black/10">

                    <span class="w-8 h-8 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm">
                        ⌂
                    </span>

                    <span>
                        Dashboard
                    </span>

                </a>


                <p class="px-4 pt-6 mb-3 text-[10px] uppercase tracking-[0.18em] text-slate-500 font-bold">
                    Manajemen Sistem
                </p>


                {{-- USERS --}}
                <a
                    href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition">

                    <span class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-sm">
                        U
                    </span>

                    <span>
                        Manajemen User
                    </span>

                </a>


                {{-- PROPERTI --}}
                <a
                    href="{{ route('admin.properties.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition">

                    <span class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-sm">
                        P
                    </span>

                    <span>
                        Properti
                    </span>

                </a>


                {{-- FACILITIES --}}
                <a
                    href="{{ route('admin.facilities.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition">

                    <span class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-sm">
                        F
                    </span>

                    <span>
                        Fasilitas
                    </span>

                </a>


                {{-- ARTICLES --}}
                <a
                    href="{{ route('admin.articles.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition">

                    <span class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-sm">
                        A
                    </span>

                    <span>
                        Artikel
                    </span>

                </a>


                {{-- BOOKINGS --}}
                <a
                    href="{{ route('admin.bookings.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition">

                    <span class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-sm">
                        B
                    </span>

                    <span>
                        Booking
                    </span>

                </a>


                {{-- SETTINGS --}}
                <a
                    href="{{ route('admin.settings.edit') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition">

                    <span class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-sm">
                        S
                    </span>

                    <span>
                        Pengaturan Sistem
                    </span>

                </a>


                <p class="px-4 pt-6 mb-3 text-[10px] uppercase tracking-[0.18em] text-slate-500 font-bold">
                    Akun
                </p>


                {{-- PROFILE --}}
                <a
                    href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition">

                    <span class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-sm">
                        👤
                    </span>

                    <span>
                        Profil
                    </span>

                </a>

            </nav>


            {{-- LOGOUT --}}
            <div class="p-4 border-t border-white/10">

                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-red-300 hover:bg-red-500/10 hover:text-red-200 transition">

                        <span class="w-8 h-8 rounded-xl bg-red-500/10 flex items-center justify-center">
                            ↪
                        </span>

                        <span>
                            Logout
                        </span>

                    </button>

                </form>

            </div>

        </div>

    </aside>


    {{-- MOBILE OVERLAY --}}
    <div
        id="superAdminOverlay"
        class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm hidden lg:hidden">
    </div>


    {{-- ================================================================ --}}
    {{-- MAIN --}}
    {{-- ================================================================ --}}

    <main class="lg:ml-72">


        {{-- ============================================================ --}}
        {{-- TOPBAR --}}
        {{-- ============================================================ --}}

        <header
            class="sticky top-0 z-30 h-20 bg-white/90 backdrop-blur-xl border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-4">

                {{-- MOBILE BUTTON --}}
                <button
                    id="superAdminToggle"
                    type="button"
                    class="lg:hidden w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 transition">

                    ☰

                </button>


                <div>

                    <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                        System Control
                    </p>

                    <h2 class="font-extrabold text-slate-900 mt-0.5">
                        Super Admin Dashboard
                    </h2>

                </div>

            </div>


            {{-- USER --}}
            <div class="flex items-center gap-3">

                <div class="hidden sm:block text-right">

                    <p class="text-sm font-bold text-slate-900">
                        {{ $user->name }}
                    </p>

                    <p class="text-xs text-violet-600 font-semibold mt-0.5">
                        Super Administrator
                    </p>

                </div>


                <div
                    class="w-11 h-11 rounded-2xl bg-gradient-to-br from-slate-950 to-slate-700 text-white flex items-center justify-center font-extrabold shadow-lg shadow-slate-900/15">

                    {{ strtoupper(substr($user->name, 0, 1)) }}

                </div>

            </div>

        </header>


        {{-- ============================================================ --}}
        {{-- CONTENT --}}
        {{-- ============================================================ --}}

        <div class="p-4 sm:p-6 lg:p-8 max-w-[1600px] mx-auto">


            {{-- ======================================================== --}}
            {{-- HERO --}}
            {{-- ======================================================== --}}

            <section
                class="relative overflow-hidden rounded-[28px] bg-slate-950 text-white p-6 sm:p-8 lg:p-10 mb-7 shadow-xl shadow-slate-950/10">

                <div
                    class="absolute -right-24 -top-28 w-72 h-72 rounded-full bg-violet-500/20 blur-3xl">
                </div>

                <div
                    class="absolute -left-20 -bottom-32 w-72 h-72 rounded-full bg-blue-500/10 blur-3xl">
                </div>


                <div
                    class="relative flex flex-col xl:flex-row xl:items-end xl:justify-between gap-8">


                    <div>

                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-xs font-bold tracking-wide mb-5">

                            <span
                                class="w-2 h-2 rounded-full bg-emerald-400 shadow-sm shadow-emerald-400">
                            </span>

                            PLATFORM ONLINE

                        </div>


                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight">
                            Halo, {{ $firstName }}.
                        </h1>


                        <p class="text-slate-400 mt-3 max-w-2xl leading-relaxed">

                            Kelola keseluruhan platform Guest House dari satu pusat kontrol:
                            pengguna, properti, booking, revenue, dan verifikasi.

                        </p>

                    </div>


                    {{-- HERO MINI STATS --}}
                    <div class="grid grid-cols-2 gap-3 min-w-0 xl:min-w-[360px]">

                        <div
                            class="rounded-2xl bg-white/10 border border-white/10 p-4">

                            <p class="text-xs text-slate-400">
                                Tahun aktif
                            </p>

                            <p class="text-xl font-extrabold mt-1">
                                {{ now()->year }}
                            </p>

                        </div>


                        <div
                            class="rounded-2xl bg-white/10 border border-white/10 p-4">

                            <p class="text-xs text-slate-400">
                                User platform
                            </p>

                            <p class="text-xl font-extrabold mt-1">
                                {{ $totalUserCount }}
                            </p>

                        </div>

                    </div>

                </div>

            </section>


            {{-- ======================================================== --}}
            {{-- PRIMARY KPI --}}
            {{-- ======================================================== --}}

            <section class="mb-8">

                <div class="flex items-end justify-between mb-4">

                    <div>

                        <p
                            class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                            Overview
                        </p>

                        <h3 class="text-xl font-extrabold text-slate-900 mt-1">
                            Ringkasan Platform
                        </h3>

                    </div>

                </div>


                <div
                    class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">


                    {{-- TOTAL USERS --}}
                    <div
                        class="group rounded-3xl bg-white border border-slate-200/80 p-5 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition">

                        <div class="flex items-start justify-between">

                            <div
                                class="w-11 h-11 rounded-2xl bg-slate-950 text-white flex items-center justify-center font-bold">
                                U
                            </div>

                            <span class="text-xs font-bold text-slate-400">
                                USERS
                            </span>

                        </div>


                        <p class="text-3xl font-black text-slate-950 mt-6">
                            {{ $totalUserCount }}
                        </p>

                        <p class="text-sm text-slate-500 mt-1">
                            Total pengguna sistem
                        </p>

                    </div>


                    {{-- PROPERTIES --}}
                    <div
                        class="group rounded-3xl bg-white border border-slate-200/80 p-5 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition">

                        <div class="flex items-start justify-between">

                            <div
                                class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                                P
                            </div>

                            <span class="text-xs font-bold text-blue-600">
                                PROPERTI
                            </span>

                        </div>


                        <p class="text-3xl font-black text-slate-950 mt-6">
                            {{ $stats['total_properties'] }}
                        </p>

                        <p class="text-sm text-slate-500 mt-1">
                            {{ $stats['active_properties'] }} properti aktif
                        </p>

                    </div>


                    {{-- PENDING --}}
                    <div
                        class="group rounded-3xl bg-white border border-amber-200/80 p-5 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition">

                        <div class="flex items-start justify-between">

                            <div
                                class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                                !
                            </div>

                            <span class="text-xs font-bold text-amber-600">
                                REVIEW
                            </span>

                        </div>


                        <p class="text-3xl font-black text-amber-600 mt-6">
                            {{ $stats['pending_properties'] }}
                        </p>

                        <p class="text-sm text-slate-500 mt-1">
                            Properti menunggu verifikasi
                        </p>

                    </div>


                    {{-- REVENUE --}}
                    <div
                        class="group rounded-3xl bg-gradient-to-br from-violet-600 to-indigo-700 text-white p-5 shadow-xl shadow-violet-600/15 hover:-translate-y-0.5 transition">

                        <div class="flex items-start justify-between">

                            <div
                                class="w-11 h-11 rounded-2xl bg-white/15 flex items-center justify-center font-bold">
                                Rp
                            </div>

                            <span class="text-xs font-bold text-violet-200">
                                REVENUE
                            </span>

                        </div>


                        <p class="text-2xl sm:text-3xl font-black mt-6">
                            Rp {{ number_format($stats['gross_revenue'], 0, ',', '.') }}
                        </p>

                        <p class="text-sm text-violet-200 mt-1">
                            Total transaksi paid
                        </p>

                    </div>

                </div>

            </section>


            {{-- ======================================================== --}}
            {{-- USER COMPOSITION --}}
            {{-- ======================================================== --}}

            <section class="mb-8">

                <div class="flex items-end justify-between mb-4">

                    <div>

                        <p
                            class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                            User Composition
                        </p>

                        <h3 class="text-xl font-extrabold text-slate-900 mt-1">
                            Komposisi Pengguna
                        </h3>

                    </div>


                    <a
                        href="{{ route('admin.users.index') }}"
                        class="text-sm font-bold text-slate-700 hover:text-slate-950">

                        Kelola User →

                    </a>

                </div>


                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">


                    {{-- CUSTOMER --}}
                    <div
                        class="rounded-3xl bg-white border border-slate-200/80 p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <span
                                class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                                C
                            </span>

                            <span class="text-xs font-bold text-emerald-600">
                                CUSTOMER
                            </span>

                        </div>


                        <p class="text-2xl font-black mt-5">
                            {{ $customerCount }}
                        </p>


                        <div
                            class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">

                            <div
                                class="h-full bg-emerald-500 rounded-full"
                                style="width: {{ min(100, ($customerCount / $roleTotal) * 100) }}%">
                            </div>

                        </div>

                    </div>


                    {{-- MITRA --}}
                    <div
                        class="rounded-3xl bg-white border border-slate-200/80 p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <span
                                class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                                M
                            </span>

                            <span class="text-xs font-bold text-blue-600">
                                MITRA
                            </span>

                        </div>


                        <p class="text-2xl font-black mt-5">
                            {{ $mitraCount }}
                        </p>


                        <div
                            class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">

                            <div
                                class="h-full bg-blue-500 rounded-full"
                                style="width: {{ min(100, ($mitraCount / $roleTotal) * 100) }}%">
                            </div>

                        </div>

                    </div>


                    {{-- ADMIN --}}
                    <div
                        class="rounded-3xl bg-white border border-slate-200/80 p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <span
                                class="w-10 h-10 rounded-2xl bg-violet-50 text-violet-700 flex items-center justify-center font-bold">
                                A
                            </span>

                            <span class="text-xs font-bold text-violet-600">
                                ADMIN
                            </span>

                        </div>


                        <p class="text-2xl font-black mt-5">
                            {{ $adminCount }}
                        </p>


                        <div
                            class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">

                            <div
                                class="h-full bg-violet-500 rounded-full"
                                style="width: {{ min(100, ($adminCount / $roleTotal) * 100) }}%">
                            </div>

                        </div>

                    </div>


                    {{-- SUPER ADMIN --}}
                    <div
                        class="rounded-3xl bg-white border border-slate-200/80 p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <span
                                class="w-10 h-10 rounded-2xl bg-red-50 text-red-700 flex items-center justify-center font-bold">
                                S
                            </span>

                            <span class="text-xs font-bold text-red-600">
                                SUPER ADMIN
                            </span>

                        </div>


                        <p class="text-2xl font-black mt-5">
                            {{ $superAdminCount }}
                        </p>


                        <div
                            class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">

                            <div
                                class="h-full bg-red-500 rounded-full"
                                style="width: {{ min(100, ($superAdminCount / $roleTotal) * 100) }}%">
                            </div>

                        </div>

                    </div>

                </div>

            </section>


            {{-- ======================================================== --}}
            {{-- ANALYTICS --}}
            {{-- ======================================================== --}}

            <section
                class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">


                {{-- REVENUE CHART --}}
                <div
                    class="xl:col-span-2 bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">

                    <div
                        class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-7">

                        <div>

                            <p
                                class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                                Financial Analytics
                            </p>

                            <h3
                                class="text-xl font-extrabold text-slate-900 mt-1">
                                Revenue Bulanan
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                Transaksi paid pada tahun {{ now()->year }}.
                            </p>

                        </div>


                        <div
                            class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-600">

                            Gross Revenue

                        </div>

                    </div>


                    <div
                        class="h-64 flex items-end gap-2 sm:gap-3">

                        @foreach($monthlyRevenue as $month => $revenue)

                            @php
                                $barHeight = max(
                                    4,
                                    ($revenue / $maxRevenue) * 100
                                );
                            @endphp


                            <div
                                class="flex-1 h-full flex flex-col justify-end items-center gap-2 group">


                                <div
                                    class="relative w-full max-w-12 h-[90%] flex items-end">

                                    <div
                                        class="w-full rounded-t-xl bg-slate-900 group-hover:bg-violet-600 transition-all duration-300"
                                        style="height: {{ $barHeight }}%;">
                                    </div>


                                    @if($revenue > 0)

                                        <div
                                            class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition whitespace-nowrap px-2 py-1 rounded-lg bg-slate-950 text-white text-[10px] font-bold">

                                            Rp {{ number_format($revenue, 0, ',', '.') }}

                                        </div>

                                    @endif

                                </div>


                                <span
                                    class="text-[10px] sm:text-xs text-slate-400 font-medium">

                                    {{ $monthLabels[$month] }}

                                </span>

                            </div>

                        @endforeach

                    </div>

                </div>


                {{-- BOOKING STATUS --}}
                <div
                    class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">

                    <div class="mb-7">

                        <p
                            class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                            Operations
                        </p>

                        <h3
                            class="text-xl font-extrabold text-slate-900 mt-1">
                            Status Booking
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Ringkasan seluruh transaksi.
                        </p>

                    </div>


                    <div class="space-y-3">

                        @foreach($bookingStatus as $status => $total)

                            @php
                                $style = $statusStyles[$status];
                            @endphp


                            <div
                                class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-100">

                                <div class="flex items-center gap-3">

                                    <span
                                        class="w-3 h-3 rounded-full {{ $style['dot'] }}">
                                    </span>

                                    <span
                                        class="text-sm font-semibold text-slate-700 capitalize">

                                        {{ $status }}

                                    </span>

                                </div>


                                <span
                                    class="text-lg font-black text-slate-950">

                                    {{ $total }}

                                </span>

                            </div>

                        @endforeach

                    </div>


                    <div
                        class="mt-5 p-4 rounded-2xl bg-slate-950 text-white">

                        <p class="text-xs text-slate-400">
                            Total Booking
                        </p>

                        <p class="text-2xl font-black mt-1">
                            {{ $stats['total_bookings'] }}
                        </p>

                    </div>

                </div>

            </section>


            {{-- ======================================================== --}}
            {{-- QUICK MANAGEMENT --}}
            {{-- ======================================================== --}}

            <section class="mb-8">

                <div class="mb-4">

                    <p
                        class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                        Control Center
                    </p>

                    <h3
                        class="text-xl font-extrabold text-slate-900 mt-1">
                        Akses Cepat
                    </h3>

                </div>


                <div
                    class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">


                    {{-- USER --}}
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="group rounded-3xl bg-slate-950 text-white p-5 hover:-translate-y-1 transition shadow-lg shadow-slate-950/10">

                        <div class="flex items-center justify-between">

                            <div
                                class="w-11 h-11 rounded-2xl bg-white/10 flex items-center justify-center font-bold">
                                U
                            </div>

                            <span
                                class="text-slate-500 group-hover:text-white transition">
                                ↗
                            </span>

                        </div>


                        <h4 class="font-extrabold mt-6">
                            Manajemen User
                        </h4>

                        <p class="text-sm text-slate-400 mt-1">
                            Kelola akun dan role pengguna.
                        </p>

                    </a>


                    {{-- PROPERTY --}}
                    <a
                        href="{{ route('admin.properties.index') }}"
                        class="group rounded-3xl bg-white border border-slate-200/80 p-5 hover:-translate-y-1 hover:shadow-xl transition">

                        <div class="flex items-center justify-between">

                            <div
                                class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                                P
                            </div>

                            <span
                                class="text-slate-300 group-hover:text-slate-900 transition">
                                ↗
                            </span>

                        </div>


                        <h4 class="font-extrabold mt-6">
                            Properti
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            Pantau data dan status properti.
                        </p>

                    </a>


                    {{-- BOOKING --}}
                    <a
                        href="{{ route('admin.bookings.index') }}"
                        class="group rounded-3xl bg-white border border-slate-200/80 p-5 hover:-translate-y-1 hover:shadow-xl transition">

                        <div class="flex items-center justify-between">

                            <div
                                class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                                B
                            </div>

                            <span
                                class="text-slate-300 group-hover:text-slate-900 transition">
                                ↗
                            </span>

                        </div>


                        <h4 class="font-extrabold mt-6">
                            Booking
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            Pantau aktivitas transaksi.
                        </p>

                    </a>


                    {{-- SETTINGS --}}
                    <a
                        href="{{ route('admin.settings.edit') }}"
                        class="group rounded-3xl bg-white border border-slate-200/80 p-5 hover:-translate-y-1 hover:shadow-xl transition">

                        <div class="flex items-center justify-between">

                            <div
                                class="w-11 h-11 rounded-2xl bg-violet-50 text-violet-700 flex items-center justify-center font-bold">
                                S
                            </div>

                            <span
                                class="text-slate-300 group-hover:text-slate-900 transition">
                                ↗
                            </span>

                        </div>


                        <h4 class="font-extrabold mt-6">
                            Pengaturan Sistem
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            Konfigurasi utama platform.
                        </p>

                    </a>

                </div>

            </section>


            {{-- ======================================================== --}}
            {{-- LATEST USER + PROPERTY --}}
            {{-- ======================================================== --}}

            <section
                class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">


                {{-- USER TERBARU --}}
                <div
                    class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">

                    <div
                        class="p-6 border-b border-slate-100 flex items-center justify-between gap-4">

                        <div>

                            <p
                                class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                                Latest Activity
                            </p>

                            <h3
                                class="text-lg font-extrabold text-slate-900 mt-1">
                                User Terbaru
                            </h3>

                        </div>


                        <a
                            href="{{ route('admin.users.index') }}"
                            class="text-xs sm:text-sm font-bold text-slate-700 hover:text-slate-950">

                            Lihat Semua →

                        </a>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse($latestUsers as $latestUser)

                            <div
                                class="p-5 flex items-center justify-between gap-4 hover:bg-slate-50 transition">

                                <div
                                    class="flex items-center gap-3 min-w-0">

                                    <div
                                        class="w-10 h-10 shrink-0 rounded-2xl bg-slate-950 text-white flex items-center justify-center font-bold">

                                        {{ strtoupper(substr($latestUser->name, 0, 1)) }}

                                    </div>


                                    <div class="min-w-0">

                                        <p
                                            class="font-bold text-slate-900 truncate">

                                            {{ $latestUser->name }}

                                        </p>

                                        <p
                                            class="text-xs text-slate-500 truncate mt-0.5">

                                            {{ $latestUser->email }}

                                        </p>

                                    </div>

                                </div>


                                <span
                                    class="shrink-0 text-[10px] uppercase tracking-wide px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-bold">

                                    User

                                </span>

                            </div>

                        @empty

                            <div
                                class="p-8 text-center text-slate-500">

                                Belum ada user.

                            </div>

                        @endforelse

                    </div>

                </div>


                {{-- PROPERTI TERBARU --}}
                <div
                    class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">

                    <div
                        class="p-6 border-b border-slate-100 flex items-center justify-between gap-4">

                        <div>

                            <p
                                class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                                Latest Activity
                            </p>

                            <h3
                                class="text-lg font-extrabold text-slate-900 mt-1">
                                Properti Terbaru
                            </h3>

                        </div>


                        <a
                            href="{{ route('admin.properties.index') }}"
                            class="text-xs sm:text-sm font-bold text-slate-700 hover:text-slate-950">

                            Lihat Semua →

                        </a>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse($latestProperties as $latestProperty)

                            <div
                                class="p-5 flex items-center justify-between gap-4 hover:bg-slate-50 transition">

                                <div class="min-w-0">

                                    <p
                                        class="font-bold text-slate-900 truncate">

                                        {{ $latestProperty->name }}

                                    </p>

                                    <p
                                        class="text-xs text-slate-500 mt-1 truncate">

                                        Mitra:
                                        {{ $latestProperty->mitra->name ?? '-' }}

                                    </p>

                                </div>


                                @if($latestProperty->status === 'active')

                                    <span
                                        class="shrink-0 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-extrabold uppercase">

                                        Aktif

                                    </span>

                                @elseif($latestProperty->status === 'pending')

                                    <span
                                        class="shrink-0 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-extrabold uppercase">

                                        Pending

                                    </span>

                                @else

                                    <span
                                        class="shrink-0 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-extrabold uppercase">

                                        {{ ucfirst($latestProperty->status) }}

                                    </span>

                                @endif

                            </div>

                        @empty

                            <div
                                class="p-8 text-center text-slate-500">

                                Belum ada properti.

                            </div>

                        @endforelse

                    </div>

                </div>

            </section>


            {{-- ======================================================== --}}
            {{-- VERIFICATION --}}
            {{-- ======================================================== --}}

            <section
                class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden mb-8 shadow-sm">


                <div
                    class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>

                        <p
                            class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                            Action Required
                        </p>

                        <h3
                            class="text-xl font-extrabold text-slate-900 mt-1">
                            Verifikasi Properti
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Properti mitra yang membutuhkan pemeriksaan.
                        </p>

                    </div>


                    <span
                        class="inline-flex w-fit px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-extrabold">

                        {{ $stats['pending_properties'] }} Pending

                    </span>

                </div>


                @if($pendingProperties->count())

                    <div class="divide-y divide-slate-100">

                        @foreach($pendingProperties as $property)

                            <div
                                class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-5 hover:bg-slate-50/70 transition">


                                <div
                                    class="flex items-start gap-4 min-w-0">

                                    <div
                                        class="w-12 h-12 shrink-0 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-black">

                                        !

                                    </div>


                                    <div class="min-w-0">

                                        <h4
                                            class="font-extrabold text-slate-900 truncate">

                                            {{ $property->name }}

                                        </h4>


                                        <p
                                            class="text-sm text-slate-500 mt-1 truncate">

                                            Mitra:
                                            {{ $property->mitra->name ?? '-' }}

                                        </p>


                                        <p
                                            class="text-xs text-slate-400 mt-1">

                                            Ditambahkan
                                            {{ optional($property->created_at)->format('d M Y') }}

                                        </p>

                                    </div>

                                </div>


                                <div
                                    class="flex gap-2 shrink-0">


                                    {{-- APPROVE --}}
                                    <form
                                        method="POST"
                                        action="{{ route('admin.properties.approve', $property) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold transition shadow-sm">

                                            Approve

                                        </button>

                                    </form>


                                    {{-- REJECT --}}
                                    <form
                                        method="POST"
                                        action="{{ route('admin.properties.reject', $property) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="px-4 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 text-sm font-bold transition">

                                            Tolak

                                        </button>

                                    </form>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="p-10 text-center">

                        <div
                            class="mx-auto w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-black">

                            ✓

                        </div>


                        <p
                            class="font-extrabold text-slate-900 mt-4">

                            Tidak ada properti pending

                        </p>


                        <p
                            class="text-sm text-slate-500 mt-1">

                            Semua properti sudah diperiksa.

                        </p>

                    </div>

                @endif

            </section>


            {{-- ======================================================== --}}
            {{-- RECENT BOOKINGS --}}
            {{-- ======================================================== --}}

            <section
                class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">


                <div
                    class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>

                        <p
                            class="text-[11px] uppercase tracking-[0.16em] text-slate-400 font-bold">
                            Transactions
                        </p>

                        <h3
                            class="text-xl font-extrabold text-slate-900 mt-1">
                            Aktivitas Booking Terbaru
                        </h3>

                        <p
                            class="text-sm text-slate-500 mt-1">

                            Monitoring aktivitas transaksi platform.

                        </p>

                    </div>


                    <a
                        href="{{ route('admin.bookings.index') }}"
                        class="text-sm font-bold text-slate-700 hover:text-slate-950">

                        Lihat Semua →

                    </a>

                </div>


                <div class="divide-y divide-slate-100">

                    @forelse($recentBookings as $booking)

                        @php
                            $bookingStyle = $statusStyles[$booking->status] ?? [
                                'bg' => 'bg-slate-100',
                                'text' => 'text-slate-600',
                                'dot' => 'bg-slate-400',
                            ];
                        @endphp


                        <div
                            class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4 hover:bg-slate-50/70 transition">


                            <div
                                class="flex items-center gap-4 min-w-0">

                                <div
                                    class="w-11 h-11 shrink-0 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">

                                    B

                                </div>


                                <div class="min-w-0">

                                    <p
                                        class="font-bold text-slate-900 truncate">

                                        {{ $booking->customer->name ?? 'Customer' }}

                                    </p>


                                    <p
                                        class="text-sm text-slate-500 mt-0.5 truncate">

                                        {{ $booking->property->name ?? 'Properti' }}

                                    </p>

                                </div>

                            </div>


                            <div
                                class="flex items-center justify-between sm:justify-end gap-4">


                                <div class="text-right">

                                    <p
                                        class="font-extrabold text-slate-900">

                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}

                                    </p>


                                    @if($booking->created_at)

                                        <p
                                            class="text-xs text-slate-400 mt-0.5">

                                            {{ $booking->created_at->format('d M Y, H:i') }}

                                        </p>

                                    @endif

                                </div>


                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full {{ $bookingStyle['bg'] }} {{ $bookingStyle['text'] }} text-xs font-extrabold capitalize">

                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $bookingStyle['dot'] }}">
                                    </span>

                                    {{ $booking->status }}

                                </span>

                            </div>

                        </div>

                    @empty

                        <div
                            class="p-10 text-center text-slate-500">

                            Belum ada booking.

                        </div>

                    @endforelse

                </div>

            </section>


        </div>

    </main>

</div>


{{-- ================================================================ --}}
{{-- MOBILE SIDEBAR SCRIPT --}}
{{-- ================================================================ --}}

<script>

    const sidebar = document.getElementById('superAdminSidebar');
    const overlay = document.getElementById('superAdminOverlay');
    const toggle = document.getElementById('superAdminToggle');

    if (toggle && sidebar && overlay) {

        toggle.addEventListener('click', function () {

            sidebar.classList.remove('-translate-x-full');

            overlay.classList.remove('hidden');

        });


        overlay.addEventListener('click', function () {

            sidebar.classList.add('-translate-x-full');

            overlay.classList.add('hidden');

        });

    }

</script>

@endsection