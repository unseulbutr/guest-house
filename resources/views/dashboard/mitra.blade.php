@extends('layouts.app')

@section('title', 'Dashboard Mitra')

@section('content')

{{-- ============ HERO SAMBUTAN — navy gelap, aksen emas ============ --}}
<div class="relative bg-navy-900 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;"></div>
    <div class="absolute -right-20 -top-20 w-72 h-72 bg-brand-yellow/10 rounded-full blur-3xl"></div>
    <div class="absolute -left-16 -bottom-24 w-64 h-64 bg-brand-blue/20 rounded-full blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-24">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-xl font-display font-bold overflow-hidden shrink-0">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="w-8 h-[2px] bg-brand-yellow mb-2"></div>
                    <h1 class="font-display text-2xl sm:text-3xl font-bold leading-snug">Dashboard Mitra</h1>
                    <p class="text-gray-300 text-sm mt-0.5">Halo, {{ auth()->user()->name }} — berikut ringkasan pendapatan Anda.</p>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('mitra.properties.create') }}"
                   class="bg-brand-yellow hover:brightness-95 transition text-navy-900 text-sm font-bold px-5 py-2.5 rounded-full">
                    + Tambah Properti
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="text-sm font-semibold text-white border border-white/30 hover:bg-white/10 transition px-5 py-2.5 rounded-full">
                    Edit Profil
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 pb-16">

    @if (session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 text-sm rounded-xl px-4 py-3 mb-6 shadow-card">
            {{ session('success') }}
        </div>
    @endif

    {{-- ===== Statistik — kartu melayang di atas hero ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="bg-gradient-to-br from-navy-900 to-navy-700 text-white rounded-2xl p-6 shadow-floating relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-brand-yellow/10 rounded-full blur-2xl"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-base mb-3">💰</div>
                <div class="font-display text-xl font-bold leading-tight">Rp {{ number_format($stats['total_earnings'], 0, ',', '.') }}</div>
                <div class="text-xs text-gray-300 mt-1">Total Pendapatan (setelah komisi)</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-floating border border-gray-50">
            <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center text-base mb-3">📉</div>
            <div class="font-display text-xl font-bold text-navy-900 leading-tight">Rp {{ number_format($stats['total_commission_paid'], 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Komisi Terpotong</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-floating border border-gray-50">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-base mb-3">🏠</div>
            <div class="font-display text-xl font-bold text-navy-900 leading-tight">{{ $stats['total_properties'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Properti Saya</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-floating border border-gray-50">
            <div class="w-9 h-9 rounded-xl bg-yellow-50 flex items-center justify-center text-base mb-3">📋</div>
            <div class="font-display text-xl font-bold text-navy-900 leading-tight">{{ $stats['total_bookings'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Booking Masuk</div>
        </div>
    </div>

    {{-- ===== Properti Saya — kartu, bukan baris ===== --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-display font-bold text-lg text-navy-900">Properti Saya</h2>
            <a href="{{ route('mitra.properties.index') }}" class="text-sm text-brand-blue font-semibold hover:underline">Lihat semua →</a>
        </div>

        @if ($properties->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl p-10 text-center shadow-card">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-2xl mx-auto mb-3">🏠</div>
                <p class="font-display font-bold text-navy-900 mb-1">Belum ada properti</p>
                <p class="text-sm text-gray-500 mb-5">Daftarkan properti pertamamu dan mulai terima booking.</p>
                <a href="{{ route('mitra.properties.create') }}"
                   class="inline-block bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-6 py-2.5 rounded-full">
                    + Tambah Properti
                </a>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($properties as $property)
                    @php
                        $statusMap = [
                            'active' => ['Aktif', 'bg-green-50 text-green-600'],
                            'pending' => ['Menunggu Verifikasi', 'bg-yellow-50 text-yellow-600'],
                            'rejected' => ['Ditolak', 'bg-red-50 text-red-500'],
                        ];
                        [$statusLabel, $statusClass] = $statusMap[$property->status] ?? [ucfirst($property->status), 'bg-gray-50 text-gray-500'];
                    @endphp
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-card hover:shadow-floating transition">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <h3 class="font-display font-bold text-navy-900 leading-snug">{{ $property->name }}</h3>
                            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full whitespace-nowrap {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-1">📍 {{ $property->city }}</p>
                        <p class="text-xs text-gray-500 mb-4">
                            {{ $property->management_type === 'dikelola' ? 'Dikelola Platform' : 'Kelola Mandiri' }}
                            · Komisi {{ (float) $property->commission_percentage }}%
                        </p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                            <span class="text-xs text-gray-500">{{ $property->bookings_count }} booking</span>
                            <a href="{{ route('mitra.properties.edit', $property) }}" class="text-xs font-semibold text-brand-blue hover:underline">Kelola →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== Booking Terbaru — kartu, dengan highlight kalau perlu respon ===== --}}
    <div>
        <h2 class="font-display font-bold text-lg text-navy-900 mb-4">Booking Terbaru</h2>

        @if ($recentBookings->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl p-10 text-center shadow-card">
                <div class="text-3xl mb-2">📋</div>
                <p class="text-sm text-gray-500">Belum ada booking masuk.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($recentBookings as $booking)
                    @php
                        $needsResponse = $booking->needsMitraResponse();
                        $statusStyle = match(true) {
                            $needsResponse => ['bg-yellow-50 text-yellow-700', 'border-l-yellow-400'],
                            $booking->status === 'confirmed' => ['bg-green-50 text-green-600', 'border-l-green-400'],
                            $booking->status === 'cancelled' => ['bg-red-50 text-red-500', 'border-l-red-300'],
                            default => ['bg-blue-50 text-brand-blue', 'border-l-brand-blue'],
                        };
                    @endphp
                    <a href="{{ route('mitra.bookings.show', $booking) }}"
                       class="flex items-center justify-between gap-4 bg-white border border-gray-100 border-l-4 {{ $statusStyle[1] }} rounded-2xl px-5 py-4 shadow-card hover:shadow-floating hover:-translate-y-0.5 transition {{ $needsResponse ? 'bg-yellow-50/30' : '' }}">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-navy-900 text-white flex items-center justify-center text-base shrink-0">🛎️</div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[11px] text-gray-400">{{ $booking->booking_code }}</span>
                                    @if ($needsResponse)
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 animate-pulse">Menunggu Respon</span>
                                    @endif
                                </div>
                                <div class="font-display font-bold text-navy-900 truncate">{{ $booking->property->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $booking->customer->name }} · <span class="font-semibold text-navy-900">Rp {{ number_format($booking->mitra_payout_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full whitespace-nowrap {{ $statusStyle[0] }}">
                            {{ $needsResponse ? 'Perlu Respon' : ucfirst($booking->status) }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection