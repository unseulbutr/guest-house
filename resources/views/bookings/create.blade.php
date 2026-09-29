@extends('layouts.app')

@section('title', 'Booking ' . $property->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="text-sm text-gray-500 mb-4">
        <a href="{{ route('properties.show', $property) }}" class="text-brand-blue hover:underline">{{ $property->name }}</a>
        <span class="mx-1">/</span>
        <span>Data Tamu & Booking</span>
    </div>

    <h1 class="text-2xl font-extrabold text-navy-900 mb-6">Lengkapi Data Tamu & Booking</h1>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Form --}}
        <form method="POST" action="{{ route('customer.bookings.store') }}" class="lg:col-span-2 space-y-5">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id }}">

            {{-- =========================================================
                 DATA TAMU
            ========================================================== --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-5">
                <div>
                    <h2 class="text-base font-extrabold text-navy-900">Data Tamu</h2>
                    <p class="text-xs text-gray-500 mt-1">
                        Masukkan data tamu yang akan menginap.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nama Lengkap
                    </label>
                    <input
                        type="text"
                        name="guest_name"
                        required
                        value="{{ old('guest_name', auth()->user()->name) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue"
                        placeholder="Masukkan nama lengkap tamu"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Nomor HP / WhatsApp
                        </label>
                        <input
                            type="text"
                            name="guest_phone"
                            required
                            value="{{ old('guest_phone', auth()->user()->phone ?? '') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue"
                            placeholder="08xxxxxxxxxx"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            name="guest_email"
                            value="{{ old('guest_email', auth()->user()->email) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue"
                            placeholder="nama@email.com"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Alamat
                    </label>
                    <textarea
                        name="guest_address"
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue"
                        placeholder="Masukkan alamat tamu">{{ old('guest_address') }}</textarea>
                </div>
            </div>

            {{-- =========================================================
                 DETAIL BOOKING
            ========================================================== --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Check-in</label>
                        <input type="date" name="check_in" id="check_in" required
                               value="{{ old('check_in') }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Check-out</label>
                        <input type="date" name="check_out" id="check_out" required
                               value="{{ old('check_out') }}"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Tamu</label>
                    <input type="number" name="guest_count" id="guest_count" min="1" max="{{ $property->guest_capacity }}" required
                           value="{{ old('guest_count', 1) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
                    <p class="text-xs text-gray-400 mt-1">Maks {{ $property->guest_capacity }} tamu untuk properti ini.</p>
                </div>
            </div>

            {{-- =========================================================
                 SYARAT & KETENTUAN BOOKING
            ========================================================== --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">
                <div>
                    <h2 class="text-base font-extrabold text-navy-900">Syarat & Ketentuan Booking</h2>
                    <p class="text-xs text-gray-500 mt-1">Harap baca sebelum melanjutkan reservasi.</p>
                </div>

                <div class="rounded-xl bg-gray-50 border border-gray-100 p-4 text-sm text-gray-600 space-y-2 leading-6">
                    <p>• Booking menggunakan sistem pembayaran <strong>DP 50%</strong> dari total harga.</p>
                    <p>• Sisa <strong>50%</strong> dapat dilunasi <strong>kapan saja setelah DP berhasil</strong> dan wajib diselesaikan sebelum check-in.</p>
                    <p>• Jika pelunasan tidak dilakukan sampai batas waktu, booking dapat dibatalkan sesuai ketentuan.</p>
                    <p>• Jika booking dibatalkan karena tidak melakukan pelunasan, refund DP diberikan setelah dikurangi <strong>20% dari nilai DP</strong>.</p>
                    <p>• Pembayaran dilakukan melalui <strong>QRIS</strong> yang tersedia pada halaman detail booking.</p>
                </div>

                <label class="flex items-start gap-3 cursor-pointer select-none">
                    <input
                        type="checkbox"
                        name="terms_accepted"
                        value="1"
                        required
                        {{ old('terms_accepted') ? 'checked' : '' }}
                        class="mt-1 h-4 w-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                    >
                    <span class="text-sm text-gray-700 leading-6">
                        Saya telah membaca, memahami, dan menyetujui Syarat & Ketentuan Booking.
                    </span>
                </label>

                @error('terms_accepted')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-navy-900">
                💳 Pembayaran awal adalah <strong>DP 50%</strong> melalui <strong>QRIS</strong> setelah booking dibuat. Sisa pembayaran dapat dilunasi kapan saja setelah DP berhasil dan wajib lunas sebelum check-in.
            </div>

            <button type="submit"
                    class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3.5 rounded-lg">
                Buat Booking & Bayar DP 50%
            </button>
        </form>

        {{-- Ringkasan properti --}}
        <div>
            <div class="bg-white border border-gray-100 rounded-2xl shadow-lg p-5 sticky top-32">
                <div class="h-36 bg-gray-100 rounded-xl overflow-hidden mb-4">
                    @if ($property->cover_image)
                        <img src="{{ asset('storage/' . $property->cover_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Tidak ada foto</div>
                    @endif
                </div>
                <h3 class="font-bold text-navy-900 mb-1">{{ $property->name }}</h3>
                <p class="text-xs text-gray-500 mb-4">📍 {{ $property->city }}</p>

                <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Harga / malam</span>
                        <span class="text-navy-900 font-medium">Rp {{ number_format($property->price_per_night, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Jumlah malam</span>
                        <span class="text-navy-900 font-medium" id="summary-nights">-</span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-navy-900 pt-2 border-t border-gray-100 mt-2">
                        <span>Estimasi Total</span>
                        <span id="summary-total">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm text-blue-700 font-bold pt-2">
                        <span>DP 50%</span>
                        <span id="summary-dp">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Sisa Pelunasan</span>
                        <span id="summary-remaining">Rp 0</span>
                    </div>
                    <p class="text-[11px] text-gray-400">*Estimasi awal. Total final ditentukan sistem saat booking disimpan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Estimasi harga di sisi client saja (UX) — angka final tetap dihitung ulang di server.
    (function () {
        const pricePerNight = {{ (int) $property->price_per_night }};
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        const nightsEl = document.getElementById('summary-nights');
        const totalEl = document.getElementById('summary-total');
        const dpEl = document.getElementById('summary-dp');
        const remainingEl = document.getElementById('summary-remaining');

        function formatRupiah(num) {
            return 'Rp ' + num.toLocaleString('id-ID');
        }

        function recalc() {
            if (!checkIn.value || !checkOut.value) return;
            const inDate = new Date(checkIn.value);
            const outDate = new Date(checkOut.value);
            const nights = Math.round((outDate - inDate) / (1000 * 60 * 60 * 24));

            if (nights > 0) {
                const total = nights * pricePerNight;
                const dp = total * 0.5;
                const remaining = total - dp;

                nightsEl.textContent = nights + ' malam';
                totalEl.textContent = formatRupiah(total);
                dpEl.textContent = formatRupiah(dp);
                remainingEl.textContent = formatRupiah(remaining);
            } else {
                nightsEl.textContent = '-';
                totalEl.textContent = 'Rp 0';
                dpEl.textContent = 'Rp 0';
                remainingEl.textContent = 'Rp 0';
            }
        }

        checkIn.addEventListener('change', recalc);
        checkOut.addEventListener('change', recalc);
    })();
</script>

@if (session('booking_error'))
    <div id="bookingErrorModal" class="booking-error-overlay">
        <div class="booking-error-modal">

            <div class="booking-error-icon">
                !
            </div>

            <h3>Property sudah dibooking</h3>

            <p>
                Property yang kamu pilih sudah dibooking
                pada tanggal tersebut.
            </p>

            <button type="button" onclick="closeBookingError()">
                Pilih Tanggal Lain
            </button>

        </div>
    </div>

    <script>
        function closeBookingError() {
            document.getElementById('bookingErrorModal').remove();
        }
    </script>
@endif

<style>
    .booking-error-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    }

    .booking-error-modal {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 18px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        animation: bookingModalShow 0.25s ease;
    }

    .booking-error-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 18px;
        border-radius: 50%;
        background: #fff3cd;
        color: #d39e00;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        font-weight: 700;
    }

    .booking-error-modal h3 {
        margin: 0 0 10px;
        font-size: 22px;
        color: #222;
    }

    .booking-error-modal p {
        margin: 0 0 24px;
        color: #666;
        line-height: 1.6;
        font-size: 14px;
    }

    .booking-error-modal button {
        border: none;
        border-radius: 10px;
        padding: 12px 22px;
        background: #222;
        color: white;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }

    .booking-error-modal button:hover {
        opacity: 0.85;
    }

    @keyframes bookingModalShow {
        from {
            opacity: 0;
            transform: translateY(10px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
</style>

@endsection