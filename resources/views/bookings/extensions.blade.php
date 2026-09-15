@extends('layouts.app')

@section('title', 'Perpanjang Menginap')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- BACK --}}
    <a
        href="{{ route('customer.bookings.show', $booking) }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-blue mb-6"
    >
        ← Kembali ke Booking
    </a>


    {{-- HEADER --}}
    <div class="mb-8">

        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
            <span class="text-2xl">🛏️</span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-navy-900">
            Perpanjang Menginap
        </h1>

        <p class="text-sm text-gray-500 mt-2">
            Ajukan tambahan malam untuk booking
            <span class="font-semibold text-gray-700">
                #{{ $booking->booking_code }}
            </span>
        </p>

    </div>


    {{-- ERROR --}}
    @if (session('extension_error'))

        <div class="bg-red-50 border border-red-100 text-red-700 rounded-xl p-4 mb-6 text-sm">
            {{ session('extension_error') }}
        </div>

    @endif


    {{-- PENDING --}}
    @if ($pendingExtension)

        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-5 mb-6">

            <div class="flex gap-3">

                <div class="text-xl">
                    ⏳
                </div>

                <div>

                    <h3 class="font-bold text-yellow-800">
                        Permintaan sedang diproses
                    </h3>

                    <p class="text-sm text-yellow-700 mt-1">
                        Kamu masih memiliki permintaan perpanjangan
                        yang menunggu konfirmasi mitra.
                    </p>

                </div>

            </div>

        </div>

    @else

        {{-- BOOKING SUMMARY --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 mb-6">

            <h2 class="font-bold text-navy-900 mb-5">
                Booking Saat Ini
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div>
                    <p class="text-xs text-gray-400 mb-1">
                        Properti
                    </p>

                    <p class="font-semibold text-gray-800">
                        {{ $booking->property->name }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-400 mb-1">
                        Checkout Saat Ini
                    </p>

                    <p class="font-semibold text-gray-800">
                        {{ $booking->check_out->translatedFormat('d F Y') }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-400 mb-1">
                        Harga per Malam
                    </p>

                    <p class="font-semibold text-gray-800">
                        Rp {{ number_format($booking->property->price_per_night, 0, ',', '.') }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-400 mb-1">
                        Status
                    </p>

                    <span class="inline-flex px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                        Sudah Dibayar
                    </span>
                </div>

            </div>

        </div>


        {{-- FORM --}}
        <form
            method="POST"
            action="{{ route('customer.bookings.extension.store', $booking) }}"
            class="bg-white border border-gray-100 rounded-2xl p-6"
        >

            @csrf


            <h2 class="font-bold text-navy-900 mb-5">
                Pilih Checkout Baru
            </h2>


            {{-- DATE --}}
            <div class="mb-6">

                <label
                    for="new_check_out"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Checkout Baru
                </label>

                <input
                    type="date"
                    id="new_check_out"
                    name="new_check_out"
                    value="{{ old('new_check_out') }}"
                    min="{{ $booking->check_out->copy()->addDay()->format('Y-m-d') }}"
                    class="w-full rounded-xl border-gray-200 focus:border-brand-blue focus:ring-brand-blue"
                    required
                >

                @error('new_check_out')

                    <p class="text-sm text-red-500 mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- PREVIEW --}}
            <div
                id="extensionPreview"
                class="hidden bg-blue-50 border border-blue-100 rounded-xl p-5 mb-6"
            >

                <div class="flex justify-between gap-4 mb-2">

                    <span class="text-sm text-blue-700">
                        Tambahan malam
                    </span>

                    <span
                        id="additionalNights"
                        class="font-bold text-blue-900"
                    >
                        0 malam
                    </span>

                </div>


                <div class="flex justify-between gap-4">

                    <span class="text-sm text-blue-700">
                        Estimasi biaya tambahan
                    </span>

                    <span
                        id="additionalAmount"
                        class="font-bold text-blue-900"
                    >
                        Rp 0
                    </span>

                </div>

            </div>


            {{-- NOTE --}}
            <div class="mb-6">

                <label
                    for="customer_note"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Catatan
                    <span class="text-gray-400 font-normal">
                        (opsional)
                    </span>
                </label>

                <textarea
                    id="customer_note"
                    name="customer_note"
                    rows="4"
                    maxlength="1000"
                    placeholder="Contoh: Kami ingin menambah 2 malam karena masih ada keperluan di kota ini."
                    class="w-full rounded-xl border-gray-200 focus:border-brand-blue focus:ring-brand-blue"
                >{{ old('customer_note') }}</textarea>

                @error('customer_note')

                    <p class="text-sm text-red-500 mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- INFO --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-6">

                <p class="text-xs text-gray-500 leading-relaxed">
                    Permintaan ini belum otomatis mengubah tanggal checkout.
                    Mitra akan memeriksa ketersediaan properti terlebih dahulu.
                    Setelah disetujui, tanggal booking dan total harga akan diperbarui.
                </p>

            </div>


            {{-- BUTTON --}}
            <button
                type="submit"
                class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3.5 rounded-xl"
            >
                Ajukan Perpanjangan
            </button>

        </form>

    @endif

</div>


<script>

const checkoutInput =
    document.getElementById('new_check_out');

const preview =
    document.getElementById('extensionPreview');

const nightsElement =
    document.getElementById('additionalNights');

const amountElement =
    document.getElementById('additionalAmount');


const currentCheckout =
    new Date(
        '{{ $booking->check_out->format("Y-m-d") }}T00:00:00'
    );


const pricePerNight =
    Number(
        '{{ $booking->property->price_per_night }}'
    );


checkoutInput?.addEventListener(
    'change',
    function () {

        if (!this.value) {

            preview.classList.add('hidden');

            return;
        }


        const newCheckout =
            new Date(
                this.value + 'T00:00:00'
            );


        const difference =
            Math.round(
                (
                    newCheckout -
                    currentCheckout
                )
                /
                (
                    1000 *
                    60 *
                    60 *
                    24
                )
            );


        if (difference <= 0) {

            preview.classList.add('hidden');

            return;
        }


        const amount =
            difference *
            pricePerNight;


        nightsElement.textContent =
            difference +
            (
                difference === 1
                    ? ' malam'
                    : ' malam'
            );


        amountElement.textContent =
            'Rp ' +
            new Intl.NumberFormat(
                'id-ID'
            ).format(amount);


        preview.classList.remove(
            'hidden'
        );
    }
);

</script>

@endsection