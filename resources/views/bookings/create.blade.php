@extends('layouts.app')

@section('title', 'Booking ' . $property->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="text-sm text-gray-500 mb-4">
        <a href="{{ route('properties.show', $property) }}" class="text-brand-blue hover:underline">{{ $property->name }}</a>
        <span class="mx-1">/</span>
        <span>Booking</span>
    </div>

    <h1 class="text-2xl font-extrabold text-navy-900 mb-6">Lengkapi Detail Booking</h1>

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

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-navy-900">
                💳 Pembayaran dilakukan via <strong>QRIS</strong> setelah booking dibuat. Kamu akan diarahkan ke halaman pembayaran.
            </div>

            <button type="submit"
                    class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3.5 rounded-lg">
                Lanjutkan Booking
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
                    <p class="text-[11px] text-gray-400">*Estimasi awal, total final ditentukan sistem saat booking disimpan.</p>
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

        function formatRupiah(num) {
            return 'Rp ' + num.toLocaleString('id-ID');
        }

        function recalc() {
            if (!checkIn.value || !checkOut.value) return;
            const inDate = new Date(checkIn.value);
            const outDate = new Date(checkOut.value);
            const nights = Math.round((outDate - inDate) / (1000 * 60 * 60 * 24));

            if (nights > 0) {
                nightsEl.textContent = nights + ' malam';
                totalEl.textContent = formatRupiah(nights * pricePerNight);
            } else {
                nightsEl.textContent = '-';
                totalEl.textContent = 'Rp 0';
            }
        }

        checkIn.addEventListener('change', recalc);
        checkOut.addEventListener('change', recalc);
    })();
</script>
@endsection