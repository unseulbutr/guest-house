@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('dashboard_layout', 'true')

@section('content')

@php
    $user = auth()->user();
    $isSuperAdmin = $user->hasRole('super_admin');

    $firstName = explode(' ', trim($user->name))[0];

    $statusStyles = [
        'pending' => [
            'label' => 'Pending',
            'class' => 'bg-amber-50 text-amber-700 border-amber-100',
            'dot' => 'bg-amber-500',
        ],
        'confirmed' => [
            'label' => 'Confirmed',
            'class' => 'bg-blue-50 text-blue-700 border-blue-100',
            'dot' => 'bg-blue-600',
        ],
        'completed' => [
            'label' => 'Completed',
            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'dot' => 'bg-emerald-500',
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'class' => 'bg-red-50 text-red-600 border-red-100',
            'dot' => 'bg-red-500',
        ],
    ];
@endphp

<style>
    .admin-scrollbar::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    .admin-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .admin-scrollbar::-webkit-scrollbar-thumb {
        background: #dbe5f0;
        border-radius: 999px;
    }

    .admin-sidebar {
        transition: transform .25s ease;
    }

    .admin-overlay {
        transition: opacity .25s ease;
    }

    .admin-chart-bar {
        transition: height .5s ease;
    }

    @media (max-width: 1023px) {
        .admin-sidebar {
            transform: translateX(-100%);
        }

        .admin-sidebar.open {
            transform: translateX(0);
        }
    }
</style>

<div class="min-h-screen bg-[#f7f9fc] text-slate-700">

    {{-- =========================================================
         MOBILE OVERLAY
    ========================================================== --}}
    <div
        id="adminOverlay"
        class="admin-overlay fixed inset-0 bg-slate-950/40 z-40 hidden opacity-0 lg:hidden"
        onclick="closeAdminSidebar()">
    </div>

    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}
    <aside
        id="adminSidebar"
        class="admin-sidebar fixed left-0 top-0 bottom-0 w-[255px] bg-white border-r border-slate-200 z-50 flex flex-col lg:translate-x-0">

        {{-- LOGO --}}
        <div class="h-[82px] px-6 flex items-center border-b border-slate-100">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-blue flex items-center justify-center shadow-lg shadow-blue-100">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                         stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21V9l9-6 9 6v12"/>
                        <path d="M7 21v-7h10v7"/>
                        <path d="M9 10h6"/>
                    </svg>
                </div>

                <div>
                    <div class="font-logo text-[22px] font-bold text-navy-900 leading-none">
                        Guest<span class="text-brand-blue">House</span>
                    </div>
                    <div class="text-[9px] uppercase tracking-[.18em] text-slate-400 font-bold mt-1">
                        Admin Panel
                    </div>
                </div>
            </a>
        </div>

        {{-- ADMIN PROFILE --}}
        <div class="px-4 pt-5 pb-4">
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-blue to-blue-500 text-white flex items-center justify-center font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <div class="text-sm font-bold text-slate-800 truncate">
                        {{ $user->name }}
                    </div>
                    <div class="text-[11px] text-slate-400 mt-0.5">
                        {{ $isSuperAdmin ? 'Super Admin' : 'Administrator' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- NAVIGATION --}}
        <nav class="flex-1 px-4 overflow-y-auto admin-scrollbar">

            <div class="text-[10px] uppercase tracking-[.16em] font-bold text-slate-400 px-3 mb-2">
                Main Menu
            </div>

            <div class="space-y-1">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-blue-50 text-brand-blue font-semibold text-sm">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>

                {{-- Booking --}}
                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition text-sm font-medium">
                    <span class="flex items-center gap-3">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                            <path d="M8 14h2M14 14h2M8 17h2"/>
                        </svg>
                        Booking
                    </span>

                    @if(($stats['pending_bookings'] ?? 0) > 0)
                        <span class="min-w-5 h-5 px-1.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold flex items-center justify-center">
                            {{ $stats['pending_bookings'] }}
                        </span>
                    @endif
                </a>

                {{-- Verifikasi --}}
                <a href="#pending-properties"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition text-sm font-medium">
                    <span class="flex items-center gap-3">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 4 6v6c0 5 3.4 8 8 9 4.6-1 8-4 8-9V6l-8-3Z"/>
                            <path d="m8.5 12 2.2 2.2 4.8-5"/>
                        </svg>
                        Verifikasi Properti
                    </span>

                    @if(($stats['pending_properties'] ?? 0) > 0)
                        <span class="min-w-5 h-5 px-1.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold flex items-center justify-center">
                            {{ $stats['pending_properties'] }}
                        </span>
                    @endif
                </a>

                {{-- Fasilitas --}}
                <a href="{{ route('admin.facilities.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition text-sm font-medium">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h10"/>
                    </svg>
                    Fasilitas
                </a>

                {{-- Artikel --}}
                <a href="{{ route('admin.articles.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition text-sm font-medium">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 5a2 2 0 0 1 2-2h14v18H6a2 2 0 0 0-2 2V5Z"/>
                        <path d="M8 7h8M8 11h8M8 15h5"/>
                    </svg>
                    Artikel
                </a>

            </div>

            <div class="text-[10px] uppercase tracking-[.16em] font-bold text-slate-400 px-3 mt-7 mb-2">
                Management
            </div>

            <div class="space-y-1">

                @if($isSuperAdmin)
                    {{-- Users --}}
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition text-sm font-medium">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="8" r="3.5"/>
                            <path d="M2.5 20c0-3.5 2.8-5.5 6.5-5.5s6.5 2 6.5 5.5"/>
                            <path d="M16 5.5a3.5 3.5 0 0 1 0 6.8M17 14.8c2.7.6 4.5 2.2 4.5 5.2"/>
                        </svg>
                        Manajemen User
                    </a>

                    {{-- Settings --}}
                    <a href="{{ route('admin.settings.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition text-sm font-medium">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.2 2.2-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.1h-3.1v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1-2.2-2.2.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.6-1H3v-3.1h.1a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1 2.2-2.2.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6V3h3.1v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1 2.2 2.2-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.1v3.1h-.1a1.7 1.7 0 0 0-1.6 1Z"/>
                        </svg>
                        Pengaturan
                    </a>
                @endif

            </div>

            <div class="text-[10px] uppercase tracking-[.16em] font-bold text-slate-400 px-3 mt-7 mb-2">
                Account
            </div>

            <div class="space-y-1">

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition text-sm font-medium">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 21c0-4.2 3.5-6.5 8-6.5s8 2.3 8 6.5"/>
                    </svg>
                    Profil Saya
                </a>

                <a href="{{ route('home') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition text-sm font-medium">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 11.5 12 4l9 7.5"/>
                        <path d="M5.5 10v9a1 1 0 0 0 1 1H10v-5.5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1V20h3.5a1 1 0 0 0 1-1v-9"/>
                    </svg>
                    Website
                </a>

            </div>
        </nav>

        {{-- LOGOUT --}}
        <div class="p-4 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:bg-red-50 hover:text-red-600 transition text-sm font-medium">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <path d="m16 17 5-5-5-5"/>
                        <path d="M21 12H9"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}
    <div class="lg:ml-[255px] min-h-screen">

        {{-- =====================================================
             TOPBAR
        ====================================================== --}}
        <header class="h-[82px] bg-white border-b border-slate-200 sticky top-0 z-30">
            <div class="h-full px-5 sm:px-7 lg:px-9 flex items-center justify-between">

                <div class="flex items-center gap-3">

                    {{-- Mobile Menu --}}
                    <button
                        type="button"
                        onclick="openAdminSidebar()"
                        class="lg:hidden w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="hidden sm:block">
                        <div class="text-[11px] text-slate-400 font-medium">
                            Admin Panel
                        </div>
                        <div class="text-sm font-bold text-slate-800">
                            Dashboard Overview
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">

                    {{-- Search --}}
                    <button
                        type="button"
                        class="hidden md:flex w-[220px] lg:w-[280px] h-10 bg-slate-50 border border-slate-100 rounded-xl items-center gap-2.5 px-3.5 text-slate-400">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>
                        <span class="text-xs">Cari sesuatu...</span>
                    </button>

                    {{-- Notification --}}
                    <button
                        type="button"
                        class="relative w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                            <path d="M10 21h4"/>
                        </svg>

                        @if(($stats['pending_properties'] ?? 0) > 0 || ($stats['pending_bookings'] ?? 0) > 0)
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        @endif
                    </button>

                    {{-- Profile --}}
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2.5 pl-1 sm:pl-2">
                        <div class="w-10 h-10 rounded-xl bg-brand-blue text-white flex items-center justify-center font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div class="hidden sm:block text-left">
                            <div class="text-xs font-bold text-slate-800 max-w-[120px] truncate">
                                {{ $user->name }}
                            </div>
                            <div class="text-[10px] text-slate-400">
                                {{ $isSuperAdmin ? 'Super Admin' : 'Admin' }}
                            </div>
                        </div>

                        <svg class="hidden sm:block text-slate-400" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </a>

                </div>
            </div>
        </header>


        {{-- =====================================================
             PAGE
        ====================================================== --}}
        <main class="px-5 sm:px-7 lg:px-9 py-7 lg:py-8 max-w-[1500px]">

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 px-4 py-3.5 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-sm">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center font-bold">
                        ✓
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
            @endif


            {{-- =================================================
                 WELCOME
            ================================================== --}}
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-7">

                <div>
                    <div class="text-xs font-semibold text-brand-blue mb-1.5">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                        Halo, {{ $firstName }} 👋
                    </h1>

                    <p class="text-sm text-slate-500 mt-1.5">
                        Berikut ringkasan aktivitas GuestHouse hari ini.
                    </p>
                </div>

                <a href="{{ route('admin.bookings.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-brand-blue text-white text-sm font-semibold hover:bg-blue-700 transition shadow-sm shadow-blue-200">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                        <path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    Lihat Semua Booking
                </a>

            </div>


            {{-- =================================================
                 KPI CARDS
            ================================================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

                {{-- Booking --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-blue-200 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-medium text-slate-500">
                                Total Booking
                            </div>

                            <div class="text-2xl font-extrabold text-slate-900 mt-2 tabular-nums">
                                {{ number_format($stats['total_bookings']) }}
                            </div>
                        </div>

                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="17" rx="2"/>
                                <path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-1.5 text-xs">
                        <span class="font-semibold text-amber-600">
                            {{ $stats['pending_bookings'] }}
                        </span>
                        <span class="text-slate-400">booking pending</span>
                    </div>
                </div>


                {{-- Property --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-blue-200 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-medium text-slate-500">
                                Properti Aktif
                            </div>

                            <div class="text-2xl font-extrabold text-slate-900 mt-2 tabular-nums">
                                {{ number_format($stats['active_properties']) }}
                            </div>
                        </div>

                        <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 10 9-7 9 7"/>
                                <path d="M5 9v11h14V9"/>
                                <path d="M9 20v-6h6v6"/>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-1.5 text-xs">
                        <span class="font-semibold text-slate-700">
                            {{ $stats['total_properties'] }}
                        </span>
                        <span class="text-slate-400">total properti</span>
                    </div>
                </div>


                {{-- Revenue --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-blue-200 transition">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0">
                            <div class="text-xs font-medium text-slate-500">
                                Total Revenue
                            </div>

                            <div class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-2 tabular-nums truncate">
                                Rp {{ number_format($stats['gross_revenue'], 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-4 text-xs text-slate-400">
                        Berdasarkan transaksi paid
                    </div>
                </div>


                {{-- Commission --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-blue-200 transition">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0">
                            <div class="text-xs font-medium text-slate-500">
                                Komisi Platform
                            </div>

                            <div class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-2 tabular-nums truncate">
                                Rp {{ number_format($stats['total_commission'], 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="w-11 h-11 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                <path d="M7 8h10M7 12h6M7 16h3"/>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-4 text-xs text-slate-400">
                        Payout mitra: Rp {{ number_format($stats['total_mitra_payout'], 0, ',', '.') }}
                    </div>
                </div>

            </div>


            {{-- =================================================
                 CHART + AVAILABILITY
            ================================================== --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

                {{-- Revenue Chart --}}
                <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">
                                Revenue Overview
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">
                                Pendapatan transaksi paid tahun {{ now()->year }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-brand-blue"></span>
                            Revenue
                        </div>
                    </div>

                    @php
                        $chartData = $monthlyRevenue ?? array_fill(1, 12, 0);
                        $maxRevenue = max(1, max($chartData));
                    @endphp

                    <div class="h-[245px] flex items-end gap-2 sm:gap-3">

                        @foreach($chartData as $month => $value)

                            @php
                                $height = $value > 0
                                    ? max(7, ($value / $maxRevenue) * 100)
                                    : 4;
                            @endphp

                            <div class="flex-1 h-full flex flex-col justify-end items-center group">

                                <div class="relative w-full flex items-end justify-center h-[205px]">

                                    <div
                                        class="absolute bottom-0 w-full max-w-[34px] rounded-t-lg bg-blue-50 group-hover:bg-blue-100 transition"
                                        style="height: 100%;">
                                    </div>

                                    <div
                                        class="admin-chart-bar relative w-full max-w-[34px] rounded-t-lg bg-brand-blue group-hover:bg-blue-700 transition"
                                        style="height: {{ $height }}%;">
                                    </div>

                                    <div class="absolute bottom-full mb-2 hidden group-hover:block z-10">
                                        <div class="bg-slate-900 text-white text-[10px] font-semibold px-2.5 py-1.5 rounded-lg whitespace-nowrap">
                                            Rp {{ number_format($value, 0, ',', '.') }}
                                        </div>
                                    </div>

                                </div>

                                <div class="text-[10px] sm:text-[11px] text-slate-400 mt-3">
                                    {{ $monthLabels[$month] ?? $month }}
                                </div>

                            </div>

                        @endforeach

                    </div>
                </div>


                {{-- Property Overview --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">
                                Property Overview
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">
                                Status properti platform
                            </p>
                        </div>

                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 10 9-7 9 7"/>
                                <path d="M5 9v11h14V9"/>
                            </svg>
                        </div>
                    </div>

                    @php
                        $totalPropertyCount = max(
                            1,
                            $stats['total_properties']
                        );

                        $activePercent = round(
                            ($stats['active_properties'] / $totalPropertyCount) * 100
                        );

                        $pendingPercent = round(
                            ($stats['pending_properties'] / $totalPropertyCount) * 100
                        );

                        $rejectedPercent = round(
                            (($stats['total_properties'] - $stats['active_properties'] - $stats['pending_properties']) / $totalPropertyCount) * 100
                        );

                        $rejectedPercent = max(0, $rejectedPercent);
                    @endphp

                    <div class="flex justify-center py-2">

                        <div class="relative w-40 h-40">

                            <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                                <circle
                                    cx="60"
                                    cy="60"
                                    r="45"
                                    fill="none"
                                    stroke="#eef2f7"
                                    stroke-width="14"/>

                                <circle
                                    cx="60"
                                    cy="60"
                                    r="45"
                                    fill="none"
                                    stroke="#0868e0"
                                    stroke-width="14"
                                    stroke-linecap="round"
                                    stroke-dasharray="{{ $activePercent * 2.827 }} 282.7"/>

                                <circle
                                    cx="60"
                                    cy="60"
                                    r="45"
                                    fill="none"
                                    stroke="#f59e0b"
                                    stroke-width="14"
                                    stroke-linecap="round"
                                    stroke-dasharray="{{ $pendingPercent * 2.827 }} 282.7"
                                    stroke-dashoffset="-{{ $activePercent * 2.827 }}"/>

                            </svg>

                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <div class="text-2xl font-extrabold text-slate-900">
                                    {{ $stats['total_properties'] }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    Properties
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="space-y-3 mt-2">

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-blue"></span>
                                <span class="text-xs text-slate-500">Aktif</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800">
                                {{ $stats['active_properties'] }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span class="text-xs text-slate-500">Pending</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800">
                                {{ $stats['pending_properties'] }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                                <span class="text-xs text-slate-500">Lainnya</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800">
                                {{ max(0, $stats['total_properties'] - $stats['active_properties'] - $stats['pending_properties']) }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 QUICK ACTIONS
            ================================================== --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 mb-6">

                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">
                            Quick Access
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Akses cepat ke fitur administrasi
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">

                    @if($isSuperAdmin)
                        <a href="{{ route('admin.users.index') }}"
                           class="group p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition text-center">
                            <div class="mx-auto w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center group-hover:bg-brand-blue group-hover:text-white transition">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                    <circle cx="9" cy="8" r="3.5"/>
                                    <path d="M2.5 20c0-3.5 2.8-5.5 6.5-5.5s6.5 2 6.5 5.5"/>
                                    <path d="M17 11a3 3 0 1 0 0-6"/>
                                </svg>
                            </div>
                            <div class="text-xs font-semibold text-slate-700 mt-2.5">
                                Users
                            </div>
                        </a>
                    @endif

                    <a href="{{ route('admin.bookings.index') }}"
                       class="group p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition text-center">
                        <div class="mx-auto w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center group-hover:bg-brand-blue group-hover:text-white transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <rect x="3" y="4" width="18" height="17" rx="2"/>
                                <path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-700 mt-2.5">
                            Booking
                        </div>
                    </a>

                    <a href="#pending-properties"
                       class="group p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition text-center">
                        <div class="mx-auto w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <circle cx="12" cy="12" r="8"/>
                                <path d="M12 8v5l3 2"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-700 mt-2.5">
                            Verifikasi
                        </div>
                    </a>

                    <a href="{{ route('admin.facilities.index') }}"
                       class="group p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition text-center">
                        <div class="mx-auto w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:bg-sky-600 group-hover:text-white transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <path d="M4 6h16M4 12h16M4 18h10"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-700 mt-2.5">
                            Fasilitas
                        </div>
                    </a>

                    <a href="{{ route('admin.articles.index') }}"
                       class="group p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition text-center">
                        <div class="mx-auto w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <path d="M4 5a2 2 0 0 1 2-2h14v18H6a2 2 0 0 0-2 2V5Z"/>
                                <path d="M8 7h8M8 11h8M8 15h5"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-700 mt-2.5">
                            Artikel
                        </div>
                    </a>

                    <a href="{{ route('home') }}"
                       target="_blank"
                       class="group p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition text-center">
                        <div class="mx-auto w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <path d="M14 3h7v7"/>
                                <path d="M10 14 21 3"/>
                                <path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-700 mt-2.5">
                            Website
                        </div>
                    </a>

                </div>
            </div>


            {{-- =================================================
                 PENDING PROPERTY + RECENT BOOKING
            ================================================== --}}
            <div class="grid grid-cols-1 xl:grid-cols-5 gap-6 mb-6">

                {{-- Pending --}}
                <div id="pending-properties"
                     class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">

                    <div class="flex items-start justify-between mb-5">

                        <div>
                            <h2 class="text-base font-bold text-slate-900">
                                Menunggu Verifikasi
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">
                                Properti dari mitra
                            </p>
                        </div>

                        @if($pendingProperties->count() > 0)
                            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold">
                                {{ $pendingProperties->count() }} pending
                            </span>
                        @endif

                    </div>

                    @if($pendingProperties->isEmpty())

                        <div class="py-12 text-center">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </div>

                            <div class="text-sm font-semibold text-slate-700">
                                Semua sudah beres
                            </div>

                            <p class="text-xs text-slate-400 mt-1">
                                Tidak ada properti yang menunggu verifikasi.
                            </p>
                        </div>

                    @else

                        <div class="space-y-3 max-h-[390px] overflow-y-auto admin-scrollbar pr-1">

                            @foreach($pendingProperties as $property)

                                <div class="border border-slate-100 rounded-xl p-3.5 hover:border-blue-100 hover:bg-blue-50/20 transition">

                                    <div class="flex items-start gap-3">

                                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center shrink-0">
                                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                                <path d="m3 10 9-7 9 7"/>
                                                <path d="M5 9v11h14V9"/>
                                            </svg>
                                        </div>

                                        <div class="min-w-0 flex-1">

                                            <div class="font-semibold text-sm text-slate-800 truncate">
                                                {{ $property->name }}
                                            </div>

                                            <div class="text-[11px] text-slate-400 mt-1 truncate">
                                                {{ $property->city }} · {{ $property->mitra->name ?? 'Mitra' }}
                                            </div>

                                            <div class="flex items-center gap-2 mt-2">
                                                <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[9px] font-bold">
                                                    {{ ucfirst(str_replace('_', ' ', $property->type)) }}
                                                </span>

                                                <span class="text-[10px] text-slate-400">
                                                    {{ ucfirst($property->management_type) }}
                                                </span>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="grid grid-cols-2 gap-2 mt-3">

                                        <form method="POST"
                                              action="{{ route('admin.properties.approve', $property) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="w-full py-2 rounded-lg bg-brand-blue hover:bg-blue-700 text-white text-[11px] font-bold transition">
                                                Setujui
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('admin.properties.reject', $property) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="w-full py-2 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 text-[11px] font-bold transition">
                                                Tolak
                                            </button>
                                        </form>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @endif
                </div>


                {{-- Recent Booking --}}
                <div class="xl:col-span-3 bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">

                    <div class="flex items-center justify-between mb-5">

                        <div>
                            <h2 class="text-base font-bold text-slate-900">
                                Recent Bookings
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">
                                Transaksi terbaru di platform
                            </p>
                        </div>

                        <a href="{{ route('admin.bookings.index') }}"
                           class="text-xs font-semibold text-brand-blue hover:text-blue-700">
                            Lihat semua →
                        </a>

                    </div>

                    @if($recentBookings->isEmpty())

                        <div class="py-12 text-center">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center mb-3">
                                <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                                    <path d="M16 2v4M8 2v4M3 10h18"/>
                                </svg>
                            </div>

                            <div class="text-sm font-semibold text-slate-700">
                                Belum ada booking
                            </div>

                            <p class="text-xs text-slate-400 mt-1">
                                Transaksi booking akan muncul di sini.
                            </p>
                        </div>

                    @else

                        <div class="overflow-x-auto admin-scrollbar">

                            <table class="w-full min-w-[680px]">

                                <thead>
                                    <tr class="border-b border-slate-100">
                                        <th class="text-left py-3 px-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                            Booking
                                        </th>

                                        <th class="text-left py-3 px-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                            Customer
                                        </th>

                                        <th class="text-left py-3 px-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                            Properti
                                        </th>

                                        <th class="text-right py-3 px-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                            Total
                                        </th>

                                        <th class="text-center py-3 px-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                            Status
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-50">

                                    @foreach($recentBookings as $booking)

                                        @php
                                            $status = $statusStyles[$booking->status] ?? [
                                                'label' => ucfirst($booking->status),
                                                'class' => 'bg-slate-50 text-slate-600 border-slate-100',
                                                'dot' => 'bg-slate-400',
                                            ];
                                        @endphp

                                        <tr class="hover:bg-slate-50/70 transition">

                                            <td class="py-3.5 px-2">
                                                <div class="font-mono text-[11px] font-semibold text-brand-blue">
                                                    {{ $booking->booking_code }}
                                                </div>

                                                <div class="text-[10px] text-slate-400 mt-1">
                                                    {{ optional($booking->created_at)->format('d M Y') }}
                                                </div>
                                            </td>

                                            <td class="py-3.5 px-2">
                                                <div class="flex items-center gap-2.5">

                                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-[10px] font-bold">
                                                        {{ strtoupper(substr($booking->customer->name ?? 'C', 0, 1)) }}
                                                    </div>

                                                    <div class="min-w-0">
                                                        <div class="text-xs font-semibold text-slate-700 truncate max-w-[120px]">
                                                            {{ $booking->customer->name ?? 'Customer' }}
                                                        </div>
                                                    </div>

                                                </div>
                                            </td>

                                            <td class="py-3.5 px-2">
                                                <div class="text-xs font-semibold text-slate-700 max-w-[150px] truncate">
                                                    {{ $booking->property->name ?? 'Properti' }}
                                                </div>

                                                <div class="text-[10px] text-slate-400 mt-1">
                                                    {{ optional($booking->check_in)->format('d M') }}
                                                    -
                                                    {{ optional($booking->check_out)->format('d M') }}
                                                </div>
                                            </td>

                                            <td class="py-3.5 px-2 text-right">
                                                <div class="text-xs font-bold text-slate-800 whitespace-nowrap">
                                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                                </div>

                                                <div class="text-[10px] text-slate-400 mt-1 whitespace-nowrap">
                                                    Komisi Rp {{ number_format($booking->commission_amount, 0, ',', '.') }}
                                                </div>
                                            </td>

                                            <td class="py-3.5 px-2 text-center">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full border text-[10px] font-bold {{ $status['class'] }}">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }}"></span>
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 BOTTOM INFO
            ================================================== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>

                        <div>
                            <div class="text-[11px] text-slate-400">
                                Payout Mitra
                            </div>

                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">
                                Rp {{ number_format($stats['total_mitra_payout'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 3"/>
                            </svg>
                        </div>

                        <div>
                            <div class="text-[11px] text-slate-400">
                                Pending Properti
                            </div>

                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">
                                {{ $stats['pending_properties'] }} properti
                            </div>
                        </div>
                    </div>
                </div>


                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>
                        </div>

                        <div>
                            <div class="text-[11px] text-slate-400">
                                Properti Tayang
                            </div>

                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">
                                {{ $stats['active_properties'] }} properti
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>


{{-- =============================================================
     SIDEBAR JAVASCRIPT
============================================================= --}}
<script>
    function openAdminSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');

        sidebar.classList.add('open');
        overlay.classList.remove('hidden');

        setTimeout(() => {
            overlay.classList.remove('opacity-0');
        }, 10);
    }

    function closeAdminSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');

        sidebar.classList.remove('open');
        overlay.classList.add('opacity-0');

        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 250);
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
            closeAdminSidebar();
        }
    });
</script>

@endsection