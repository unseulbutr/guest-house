@extends('layouts.app')

@section('title', 'Booking ' . $booking->booking_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="text-sm text-gray-500 mb-4">
        <a href="{{ route(auth()->user()->hasRole('mitra') ? 'mitra.bookings.index' : (auth()->user()->hasRole('customer') ? 'customer.bookings.index' : 'admin.bookings.index')) }}"
           class="text-brand-blue hover:underline">
            Booking
        </a>
        <span class="mx-1">/</span>
        <span>{{ $booking->booking_code }}</span>
    </div>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">{{ session('success') }}</div>
    @endif

    <div class="flex items-start justify-between flex-wrap gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-navy-900">{{ $booking->booking_code }}</h1>
            <p class="text-sm text-gray-500">Dibuat {{ $booking->created_at->translatedFormat('d M Y, H:i') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @include('bookings.partials.status-badge', ['status' => $booking->status])
            @include('bookings.partials.payment-badge', ['status' => $booking->payment_status])
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            {{-- Info Properti --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <h2 class="font-bold text-navy-900 mb-4">Detail Properti</h2>
                <div class="flex gap-4">
                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                        @if ($booking->property->cover_image)
                            <img src="{{ asset('storage/' . $booking->property->cover_image) }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('properties.show', $booking->property) }}" class="font-semibold text-navy-900 hover:text-brand-blue transition">
                            {{ $booking->property->name }}
                        </a>
                        <p class="text-sm text-gray-500">📍 {{ $booking->property->city }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $booking->check_in->format('d M Y') }} – {{ $booking->check_out->format('d M Y') }}
                            · {{ $booking->guest_count }} tamu
                        </p>
                    </div>
                </div>
            </div>

            {{-- Rincian Harga --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <h2 class="font-bold text-navy-900 mb-4">Rincian Harga</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal ({{ $booking->check_in->diffInDays($booking->check_out) }} malam)</span>
                        <span>Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span>
                    </div>

                    @role('mitra')
                        <div class="flex justify-between text-gray-400">
                            <span>Komisi Platform ({{ rtrim(rtrim(number_format($booking->commission_percentage, 2), '0'), '.') }}%)</span>
                            <span>- Rp {{ number_format($booking->commission_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-green-600 font-semibold pt-2 border-t border-gray-100">
                            <span>Payout untuk Kamu</span>
                            <span>Rp {{ number_format($booking->mitra_payout_amount, 0, ',', '.') }}</span>
                        </div>
                    @endrole

                    @role('admin')
                        <div class="flex justify-between text-gray-400">
                            <span>Komisi Platform ({{ rtrim(rtrim(number_format($booking->commission_percentage, 2), '0'), '.') }}%)</span>
                            <span>Rp {{ number_format($booking->commission_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Payout Mitra</span>
                            <span>Rp {{ number_format($booking->mitra_payout_amount, 0, ',', '.') }}</span>
                        </div>
                    @endrole
                    @role('super_admin')
                        <div class="flex justify-between text-gray-400">
                            <span>Komisi Platform ({{ rtrim(rtrim(number_format($booking->commission_percentage, 2), '0'), '.') }}%)</span>
                            <span>Rp {{ number_format($booking->commission_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Payout Mitra</span>
                            <span>Rp {{ number_format($booking->mitra_payout_amount, 0, ',', '.') }}</span>
                        </div>
                    @endrole

                    <div class="flex justify-between text-base font-extrabold text-navy-900 pt-3 border-t border-gray-100">
                        <span>Total Dibayar Customer</span>
                        <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom kanan: pembayaran & aksi --}}
        <div class="space-y-4">

            @role('customer')
                @if ($booking->payment_status === 'pending' && $booking->status !== 'cancelled')
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center">
                        <h3 class="font-bold text-navy-900 mb-1">Bayar via QRIS</h3>
                        <p class="text-xs text-gray-500 mb-4">Scan QR di bawah dengan aplikasi e-wallet/mobile banking kamu.</p>

                        {{-- Placeholder QR — ganti dengan gambar QR asli dari payment gateway --}}
                        <div class="w-44 h-44 mx-auto bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center text-4xl mb-4">
                            ▦
                        </div>

                        <div class="text-2xl font-extrabold text-navy-900 mb-4">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </div>

                        {{-- MODE SIMULASI: tombol ini menandai booking "sudah dibayar" tanpa payment
                             gateway asli, supaya alur bisa dites. Hapus/nonaktifkan di production
                             dan ganti dengan polling status dari webhook payment gateway. --}}
                        <form method="POST" action="{{ route('customer.bookings.simulate-pay', $booking) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg text-sm">
                                Saya Sudah Bayar (Simulasi)
                            </button>
                        </form>
                        <p class="text-[11px] text-gray-400 mt-2">Mode simulasi — integrasi QRIS asli menyusul.</p>
                    </div>
                @endif

                @if ($booking->status === 'pending' || $booking->status === 'confirmed')
                    <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}"
                          onsubmit="return confirm('Yakin ingin membatalkan booking ini?');">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="w-full border border-red-200 text-red-500 hover:bg-red-50 transition font-semibold py-3 rounded-lg text-sm">
                            Batalkan Booking
                        </button>
                    </form>
                @endif
            @endrole

            @role('mitra')
                @if ($booking->status === 'pending')
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-3">
                        <h3 class="font-bold text-navy-900 mb-1">Booking Masuk</h3>
                        <p class="text-xs text-gray-500 mb-3">Konfirmasi ketersediaan properti untuk tanggal ini.</p>

                        <form method="POST" action="{{ route('mitra.bookings.confirm', $booking) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 transition text-white font-semibold py-3 rounded-lg text-sm">
                                Konfirmasi Booking
                            </button>
                        </form>
                        <form method="POST" action="{{ route('mitra.bookings.reject', $booking) }}"
                              onsubmit="return confirm('Yakin ingin menolak booking ini?');">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full border border-red-200 text-red-500 hover:bg-red-50 transition font-semibold py-3 rounded-lg text-sm">
                                Tolak Booking
                            </button>
                        </form>
                    </div>
                @endif
            @endrole

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 text-xs text-gray-500 space-y-1">
                <div class="flex justify-between"><span>Metode</span><span class="text-navy-900 font-medium">QRIS</span></div>
                @if ($booking->qris_transaction_id)
                    <div class="flex justify-between"><span>ID Transaksi</span><span class="text-navy-900 font-medium">{{ $booking->qris_transaction_id }}</span></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection