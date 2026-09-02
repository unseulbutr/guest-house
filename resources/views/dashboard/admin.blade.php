@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ===== Header ===== --}}
    <div class="flex items-start justify-between mb-8 flex-wrap gap-3">
        <div>
            <span class="inline-block text-xs font-bold uppercase tracking-wider text-brand-blue bg-blue-50 px-3 py-1 rounded-full mb-2">
                {{ auth()->user()->hasRole('super_admin') ? 'Super Admin' : 'Admin' }}
            </span>
            <h1 class="font-display text-2xl md:text-3xl font-extrabold text-navy-900">
                Halo, {{ explode(' ', auth()->user()->name)[0] }} 👋
            </h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan transaksi, komisi, dan pajak platform secara real-time.</p>
        </div>
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-1.5 text-sm font-semibold text-navy-900 border border-gray-200 px-4 py-2.5 rounded-full hover:border-brand-blue hover:text-brand-blue transition bg-white">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-6 8-6s8 2 8 6"/>
            </svg>
            Edit Profil
        </a>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-100 text-green-700 text-sm rounded-xl px-4 py-3 mb-6">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif

    {{-- ===== Statistik Keuangan ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        <div class="relative overflow-hidden bg-gradient-to-br from-navy-900 via-navy-800 to-navy-700 text-white rounded-2xl p-5">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-brand-yellow/10 rounded-full blur-xl"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center mb-3 text-brand-yellow">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="text-xl font-extrabold tabular-nums">Rp {{ number_format($stats['gross_revenue'], 0, ',', '.') }}</div>
                <div class="text-xs text-gray-300 mt-1">Total Omzet (Paid)</div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center mb-3 text-brand-blue">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V8H6a2 2 0 0 1 0-4h12v4M4 6v12a2 2 0 0 0 2 2h14v-4M18 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/></svg>
            </div>
            <div class="text-xl font-extrabold text-navy-900 tabular-nums">Rp {{ number_format($stats['total_commission'], 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500 mt-1">Komisi Platform (sudah termasuk pajak)</div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center mb-3 text-green-600">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 9V7a4 4 0 0 0-8 0v2M5 9h14l-1 12H6L5 9Z"/></svg>
            </div>
            <div class="text-xl font-extrabold text-navy-900 tabular-nums">Rp {{ number_format($stats['total_mitra_payout'], 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Payout ke Mitra</div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-lg bg-yellow-50 flex items-center justify-center mb-3 text-yellow-600">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </div>
            <div class="text-xl font-extrabold text-navy-900 tabular-nums">{{ $stats['total_bookings'] }}</div>
            <div class="text-xs text-gray-500 mt-1">
                Total Booking
                @if ($stats['pending_bookings'] > 0)
                    <span class="text-yellow-600 font-semibold">({{ $stats['pending_bookings'] }} pending)</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== Statistik Properti ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-gray-50 flex items-center justify-center text-navy-900 shrink-0">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10v9a1 1 0 0 0 1 1H10v-5.5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1V20h3.5a1 1 0 0 0 1-1v-9"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-navy-900">{{ $stats['total_properties'] }}</div>
                <div class="text-xs text-gray-500">Total Properti</div>
            </div>
        </div>

        <div class="bg-yellow-50/60 border border-yellow-100 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-white flex items-center justify-center text-yellow-600 shrink-0">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-navy-900">{{ $stats['pending_properties'] }}</div>
                <div class="text-xs text-gray-600">Menunggu Verifikasi</div>
            </div>
        </div>

        <div class="bg-green-50/60 border border-green-100 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-white flex items-center justify-center text-green-600 shrink-0">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-navy-900">{{ $stats['active_properties'] }}</div>
                <div class="text-xs text-gray-600">Properti Aktif/Tayang</div>
            </div>
        </div>
    </div>

    {{-- ===== Menu Cepat ===== --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 mb-6">
        <h2 class="font-bold text-navy-900 mb-4 text-sm">Menu Cepat</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">

            @role('super_admin')
                <a href="{{ route('admin.facilities.create') }}" class="group flex flex-col items-center gap-2 text-center p-3 rounded-xl hover:bg-blue-50 transition">
                    <span class="w-11 h-11 rounded-full bg-brand-blue text-white flex items-center justify-center group-hover:scale-105 transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                    </span>
                    <span class="text-xs font-semibold text-navy-900">Tambah Fasilitas</span>
                </a>
            @endrole

            <a href="{{ route('admin.facilities.index') }}" class="group flex flex-col items-center gap-2 text-center p-3 rounded-xl hover:bg-gray-50 transition">
                <span class="w-11 h-11 rounded-full bg-gray-100 text-navy-900 flex items-center justify-center group-hover:scale-105 transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h10"/></svg>
                </span>
                <span class="text-xs font-semibold text-navy-900">Kelola Fasilitas</span>
            </a>

            <a href="{{ route('admin.articles.create') }}" class="group flex flex-col items-center gap-2 text-center p-3 rounded-xl hover:bg-blue-50 transition">
                <span class="w-11 h-11 rounded-full bg-brand-blue text-white flex items-center justify-center group-hover:scale-105 transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </span>
                <span class="text-xs font-semibold text-navy-900">Tulis Artikel</span>
            </a>

            <a href="{{ route('admin.articles.index') }}" class="group flex flex-col items-center gap-2 text-center p-3 rounded-xl hover:bg-gray-50 transition">
                <span class="w-11 h-11 rounded-full bg-gray-100 text-navy-900 flex items-center justify-center group-hover:scale-105 transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z"/></svg>
                </span>
                <span class="text-xs font-semibold text-navy-900">Kelola Artikel</span>
            </a>

            <a href="{{ route('articles.index') }}" target="_blank" class="group flex flex-col items-center gap-2 text-center p-3 rounded-xl hover:bg-gray-50 transition">
                <span class="w-11 h-11 rounded-full bg-gray-100 text-navy-900 flex items-center justify-center group-hover:scale-105 transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
                </span>
                <span class="text-xs font-semibold text-navy-900">Lihat Halaman Publik</span>
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="group flex flex-col items-center gap-2 text-center p-3 rounded-xl hover:bg-gray-50 transition">
                <span class="w-11 h-11 rounded-full bg-gray-100 text-navy-900 flex items-center justify-center group-hover:scale-105 transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </span>
                <span class="text-xs font-semibold text-navy-900">Semua Booking</span>
            </a>

            @role('super_admin')
                <a href="{{ route('admin.users.index') }}" class="group flex flex-col items-center gap-2 text-center p-3 rounded-xl hover:bg-navy-50 transition">
                    <span class="w-11 h-11 rounded-full bg-navy-900 text-brand-yellow flex items-center justify-center group-hover:scale-105 transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-6 8-6s8 2 8 6"/></svg>
                    </span>
                    <span class="text-xs font-semibold text-navy-900">Manajemen User</span>
                </a>

                <a href="{{ route('admin.settings.edit') }}" class="group flex flex-col items-center gap-2 text-center p-3 rounded-xl hover:bg-navy-50 transition">
                    <span class="w-11 h-11 rounded-full bg-navy-900 text-brand-yellow flex items-center justify-center group-hover:scale-105 transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></svg>
                    </span>
                    <span class="text-xs font-semibold text-navy-900">Pengaturan Platform</span>
                </a>
            @endrole
        </div>
    </div>

    {{-- ===== Properti Menunggu Verifikasi ===== --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-navy-900">Properti Menunggu Verifikasi</h2>
            @if (!$pendingProperties->isEmpty())
                <span class="text-xs font-semibold text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-full">{{ $pendingProperties->count() }} menunggu</span>
            @endif
        </div>

        @if ($pendingProperties->isEmpty())
            <div class="text-center py-8">
                <div class="text-3xl mb-2">✅</div>
                <p class="text-sm text-gray-500">Tidak ada properti yang menunggu verifikasi saat ini.</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach ($pendingProperties as $property)
                    <div class="flex items-center justify-between py-4 flex-wrap gap-3">
                        <div class="min-w-0">
                            <div class="font-semibold text-navy-900">{{ $property->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5 flex flex-wrap items-center gap-x-1.5">
                                <span>📍 {{ $property->city }}</span>
                                <span>·</span>
                                <span>Mitra: {{ $property->mitra->name }}</span>
                                <span>·</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-brand-blue font-medium">
                                    {{ ucfirst($property->management_type) }} ({{ rtrim(rtrim(number_format($property->commission_percentage,2),'0'),'.') }}%)
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                @csrf @method('PATCH')
                                <button class="bg-green-600 hover:bg-green-700 transition text-white text-xs font-semibold px-4 py-2 rounded-full">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                                @csrf @method('PATCH')
                                <button class="border border-red-200 text-red-500 hover:bg-red-50 transition text-xs font-semibold px-4 py-2 rounded-full">Tolak</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== Booking Terbaru ===== --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6">
        <h2 class="font-bold text-navy-900 mb-4">Booking Terbaru</h2>

        @if ($recentBookings->isEmpty())
            <div class="text-center py-8">
                <div class="text-3xl mb-2">🗓️</div>
                <p class="text-sm text-gray-500">Belum ada booking.</p>
            </div>
        @else
            @php
                $statusStyles = [
                    'pending' => 'bg-yellow-50 text-yellow-700',
                    'confirmed' => 'bg-blue-50 text-brand-blue',
                    'completed' => 'bg-green-50 text-green-700',
                    'cancelled' => 'bg-red-50 text-red-500',
                ];
            @endphp
            <div class="overflow-x-auto -mx-2">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 text-xs uppercase border-b border-gray-100">
                            <th class="py-2 px-2 font-semibold">Kode</th>
                            <th class="py-2 px-2 font-semibold">Properti</th>
                            <th class="py-2 px-2 font-semibold">Customer</th>
                            <th class="py-2 px-2 font-semibold">Total</th>
                            <th class="py-2 px-2 font-semibold">Komisi</th>
                            <th class="py-2 px-2 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($recentBookings as $booking)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="py-3 px-2 font-mono text-xs text-gray-500">{{ $booking->booking_code }}</td>
                                <td class="py-3 px-2 font-medium text-navy-900">{{ $booking->property->name }}</td>
                                <td class="py-3 px-2 text-gray-600">{{ $booking->customer->name }}</td>
                                <td class="py-3 px-2 font-semibold text-navy-900 whitespace-nowrap">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-2 text-gray-500 whitespace-nowrap">Rp {{ number_format($booking->commission_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-2">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap {{ $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($booking->status) }}
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
@endsection