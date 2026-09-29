@extends('layouts.app')

@section('title', 'Perpanjang Booking')

@section('content')

<div class="min-h-screen bg-gray-50 py-8">

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-6">

            <a
                href="{{ route('customer.bookings.show', $booking) }}"
                class="text-sm font-bold text-blue-600 hover:text-blue-700"
            >
                ← Kembali ke Detail Booking
            </a>

            <h1 class="mt-4 text-2xl font-black text-slate-900">
                Perpanjang Booking
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Pilih tanggal checkout baru yang masih tersedia.
            </p>

        </div>


        {{-- ERROR --}}
        @if (session('extension_error'))

            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4">

                <p class="text-sm font-semibold text-red-700">
                    {{ session('extension_error') }}
                </p>

            </div>

        @endif


        {{-- SUCCESS --}}
        @if (session('success'))

            <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 p-4">

                <p class="text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </p>

            </div>

        @endif


        {{-- VALIDATION --}}
        @if ($errors->any())

            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4">

                <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- BOOKING INFO --}}
        <div class="mb-5 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="text-lg font-black text-slate-900">
                Detail Booking
            </h2>

            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs font-semibold text-gray-500">
                        Kode Booking
                    </p>

                    <p class="mt-1 font-extrabold text-slate-900">
                        {{ $booking->booking_code }}
                    </p>

                </div>


                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs font-semibold text-gray-500">
                        Properti
                    </p>

                    <p class="mt-1 font-extrabold text-slate-900">
                        {{ $booking->property->name }}
                    </p>

                </div>


                <div class="rounded-xl bg-blue-50 p-4">

                    <p class="text-xs font-semibold text-blue-600">
                        Checkout Saat Ini
                    </p>

                    <p class="mt-1 font-extrabold text-blue-700">
                        {{ $booking->check_out->format('d M Y') }}
                    </p>

                </div>

            </div>

        </div>


        {{-- STATUS PEMBAYARAN BOOKING --}}
        <div class="mb-5 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="text-lg font-black text-slate-900">
                Status Pembayaran Booking
            </h2>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs font-semibold text-gray-500">
                        Total Booking
                    </p>

                    <p class="mt-1 text-lg font-black text-slate-900">
                        Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                    </p>

                </div>


                <div class="rounded-xl bg-blue-50 p-4">

                    <p class="text-xs font-semibold text-blue-600">
                        DP 50%
                    </p>

                    <p class="mt-1 text-lg font-black text-blue-700">
                        Rp{{ number_format($booking->dp_amount ?? ($booking->total_price * 0.5), 0, ',', '.') }}
                    </p>

                </div>


                <div class="rounded-xl bg-yellow-50 p-4">

                    <p class="text-xs font-semibold text-yellow-700">
                        Sisa Pembayaran
                    </p>

                    <p class="mt-1 text-lg font-black text-yellow-800">
                        Rp{{ number_format($booking->remaining_amount ?? ($booking->total_price * 0.5), 0, ',', '.') }}
                    </p>

                </div>

            </div>


            @if ($booking->payment_status === 'dp_paid')

                <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4">

                    <p class="text-sm font-black text-blue-900">
                        Booking sudah membayar DP 50%.
                    </p>

                    <p class="mt-1 text-xs leading-relaxed text-blue-800">

                        Perpanjangan tetap mengikuti persetujuan mitra.
                        Pembayaran tambahan perpanjangan dilakukan setelah
                        permintaan disetujui.

                    </p>

                </div>

            @elseif ($booking->payment_status === 'paid')

                <div class="mt-4 rounded-xl border border-green-200 bg-green-50 p-4">

                    <p class="text-sm font-black text-green-900">
                        Booking sudah lunas.
                    </p>

                    <p class="mt-1 text-xs text-green-800">
                        Anda tetap dapat mengajukan perpanjangan jika tersedia.
                    </p>

                </div>

            @else

                <div class="mt-4 rounded-xl border border-yellow-200 bg-yellow-50 p-4">

                    <p class="text-sm font-black text-yellow-900">
                        Pembayaran DP belum dilakukan.
                    </p>

                    <p class="mt-1 text-xs text-yellow-800">
                        Selesaikan pembayaran DP terlebih dahulu sebelum mengajukan
                        perpanjangan.
                    </p>

                </div>

            @endif

        </div>


        @if ($pendingExtension || $approvedExtension)

            {{-- SUDAH ADA REQUEST --}}

            <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-6">

                <div class="flex items-start gap-3">

                    <div class="text-yellow-600">

                        <svg
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />

                        </svg>

                    </div>


                    <div>

                        <h2 class="font-black text-yellow-900">
                            Perpanjangan Belum Selesai
                        </h2>

                        <p class="mt-1 text-sm text-yellow-800">

                            Anda masih memiliki permintaan perpanjangan
                            yang sedang diproses atau menunggu pembayaran.

                        </p>

                    </div>

                </div>


                <a
                    href="{{ route('customer.bookings.show', $booking) }}"
                    class="mt-5 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-black text-white hover:bg-blue-700"
                >
                    Kembali ke Booking
                </a>

            </div>

        @else

            {{-- FORM --}}

            <form
                method="POST"
                action="{{ route('customer.booking-extensions.store', $booking) }}"
                id="extension-form"
            >

                @csrf


                {{-- TANGGAL --}}

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

                    <h2 class="text-lg font-black text-slate-900">
                        Pilih Checkout Baru
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Tanggal yang sudah digunakan booking lain tidak dapat dipilih.
                    </p>


                    {{-- DATE INPUT --}}

                    <div class="mt-5">

                        <label
                            for="new_check_out"
                            class="mb-2 block text-sm font-bold text-slate-900"
                        >
                            Tanggal Checkout Baru
                        </label>


                        <input
                            type="date"
                            id="new_check_out"
                            name="new_check_out"
                            value="{{ old('new_check_out') }}"
                            min="{{ $calendarStart->format('Y-m-d') }}"
                            max="{{ $calendarEnd->format('Y-m-d') }}"
                            required
                            class="w-full rounded-xl border-gray-300 px-4 py-3 text-sm font-semibold focus:border-blue-500 focus:ring-blue-500"
                        >


                        <p class="mt-2 text-xs text-gray-500">

                            Pilihan tersedia dari

                            <strong>
                                {{ $calendarStart->format('d M Y') }}
                            </strong>

                            sampai

                            <strong>
                                {{ $calendarEnd->format('d M Y') }}
                            </strong>.

                        </p>

                    </div>


                    {{-- DAFTAR TANGGAL --}}

                    <div class="mt-6">

                        <div class="mb-3 flex items-center justify-between">

                            <h3 class="text-sm font-black text-slate-900">
                                Tanggal Tersedia
                            </h3>

                            <span class="text-xs text-gray-500">
                                Klik tanggal
                            </span>

                        </div>


                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 md:grid-cols-5">

                            @foreach ($availableDates as $date)

                                @php
                                    $dateCarbon = \Carbon\Carbon::parse($date);
                                @endphp

                                <button
                                    type="button"
                                    class="available-date rounded-xl border border-blue-200 bg-blue-50 px-3 py-3 text-center transition hover:border-blue-500 hover:bg-blue-100"
                                    data-date="{{ $date }}"
                                >

                                    <span class="block text-[11px] font-semibold text-blue-600">
                                        {{ $dateCarbon->format('D') }}
                                    </span>

                                    <span class="mt-1 block text-sm font-black text-blue-800">
                                        {{ $dateCarbon->format('d M') }}
                                    </span>

                                </button>

                            @endforeach

                        </div>

                    </div>


                    {{-- TANGGAL TIDAK TERSEDIA --}}

                    @if (count($blockedDates) > 0)

                        <div class="mt-6 rounded-2xl border border-red-100 bg-red-50 p-4">

                            <div class="flex items-start gap-3">

                                <svg
                                    class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />

                                </svg>


                                <div>

                                    <p class="text-sm font-black text-red-800">
                                        Beberapa tanggal tidak tersedia
                                    </p>

                                    <p class="mt-1 text-xs leading-relaxed text-red-700">

                                        Tanggal tersebut sudah digunakan oleh booking lain
                                        pada properti ini.

                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- HASIL --}}

                    <div
                        id="extension-summary"
                        class="mt-6 hidden rounded-2xl border border-blue-200 bg-blue-50 p-5"
                    >

                        <h3 class="font-black text-blue-900">
                            Ringkasan Perpanjangan
                        </h3>


                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">

                            <div class="rounded-xl bg-white p-4">

                                <p class="text-xs text-gray-500">
                                    Checkout Lama
                                </p>

                                <p class="mt-1 font-bold text-gray-900">
                                    {{ $booking->check_out->format('d M Y') }}
                                </p>

                            </div>


                            <div class="rounded-xl bg-white p-4">

                                <p class="text-xs text-gray-500">
                                    Checkout Baru
                                </p>

                                <p
                                    id="selected-date"
                                    class="mt-1 font-black text-blue-700"
                                >
                                    -
                                </p>

                            </div>


                            <div class="rounded-xl bg-white p-4">

                                <p class="text-xs text-gray-500">
                                    Tambahan Malam
                                </p>

                                <p
                                    id="additional-nights"
                                    class="mt-1 font-black text-gray-900"
                                >
                                    -
                                </p>

                            </div>

                        </div>


                        <div class="mt-3 rounded-xl bg-white p-4">

                            <p class="text-xs text-gray-500">
                                Estimasi Biaya Tambahan
                            </p>

                            <p
                                id="additional-price"
                                class="mt-1 text-2xl font-black text-blue-600"
                            >
                                Rp0
                            </p>

                        </div>

                    </div>

                </div>


                {{-- CATATAN --}}

                <div class="mt-5 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

                    <label
                        for="customer_note"
                        class="block text-sm font-black text-slate-900"
                    >

                        Catatan

                        <span class="font-normal text-gray-400">
                            (opsional)
                        </span>

                    </label>


                    <textarea
                        id="customer_note"
                        name="customer_note"
                        rows="4"
                        maxlength="1000"
                        placeholder="Tulis catatan untuk mitra..."
                        class="mt-2 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('customer_note') }}</textarea>

                </div>


                {{-- SYARAT --}}

                <div class="mt-5 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

                    <h2 class="text-lg font-black text-slate-900">
                        Syarat & Ketentuan Perpanjangan
                    </h2>


                    <div class="mt-4 max-h-64 overflow-y-auto rounded-xl bg-gray-50 p-4">

                        <ol class="list-decimal space-y-3 pl-5 text-sm leading-relaxed text-gray-700">

                            <li>
                                Perpanjangan hanya dapat dilakukan jika tanggal
                                yang dipilih masih tersedia.
                            </li>

                            <li>
                                Permintaan perpanjangan harus mendapatkan
                                persetujuan dari mitra.
                            </li>

                            <li>
                                Pembayaran perpanjangan dilakukan setelah
                                permintaan disetujui oleh mitra.
                            </li>

                            <li>
                                Booking belum berubah sampai pembayaran
                                perpanjangan berhasil.
                            </li>

                            <li>
                                Perpanjangan dianggap berhasil setelah
                                pembayaran dikonfirmasi.
                            </li>

                            <li>
                                Jika pembayaran tidak dilakukan dalam batas
                                waktu yang ditentukan, perpanjangan dapat
                                dianggap tidak berlaku.
                            </li>

                            <li>
                                Tanggal yang telah digunakan booking lain
                                tidak dapat dipilih untuk perpanjangan.
                            </li>

                            <li>
                                Biaya perpanjangan dihitung berdasarkan
                                jumlah malam tambahan dan harga kamar
                                yang berlaku.
                            </li>

                        </ol>

                    </div>


                    <label
                        class="mt-5 flex cursor-pointer items-start gap-3"
                    >

                        <input
                            type="checkbox"
                            name="agree_terms"
                            value="1"
                            required
                            class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            {{ old('agree_terms') ? 'checked' : '' }}
                        >


                        <span class="text-sm font-semibold leading-relaxed text-gray-700">

                            Saya telah membaca, memahami, dan menyetujui
                            Syarat & Ketentuan Perpanjangan.

                        </span>

                    </label>

                </div>


                {{-- BUTTON --}}

                <div class="mt-5 flex flex-col gap-3 sm:flex-row">

                    <a
                        href="{{ route('customer.bookings.show', $booking) }}"
                        class="w-full rounded-xl border border-gray-300 bg-white px-6 py-3 text-center text-sm font-bold text-gray-700 transition hover:bg-gray-50 sm:w-auto"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        id="submit-extension"
                        disabled
                        class="w-full rounded-xl bg-blue-600 px-6 py-3 text-sm font-black text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-gray-300 sm:flex-1"
                    >
                        Ajukan Perpanjangan
                    </button>

                </div>

            </form>

        @endif

    </div>

</div>


{{-- JAVASCRIPT --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const dateInput =
        document.getElementById('new_check_out');

    const summary =
        document.getElementById('extension-summary');

    const selectedDate =
        document.getElementById('selected-date');

    const additionalNights =
        document.getElementById('additional-nights');

    const additionalPrice =
        document.getElementById('additional-price');

    const submitButton =
        document.getElementById('submit-extension');

    const bookingCheckout =
        new Date(
            '{{ $booking->check_out->format('Y-m-d') }}T00:00:00'
        );

    const pricePerNight =
        Number(
            '{{ (float) $booking->property->price_per_night }}'
        );


    function formatRupiah(number) {

        return new Intl.NumberFormat(
            'id-ID',
            {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }
        ).format(number);

    }


    function formatDate(dateString) {

        const date =
            new Date(
                dateString + 'T00:00:00'
            );

        return date.toLocaleDateString(
            'id-ID',
            {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }
        );

    }


    function updateSummary() {

        if (!dateInput.value) {

            summary.classList.add('hidden');

            submitButton.disabled = true;

            return;

        }


        const selected =
            new Date(
                dateInput.value + 'T00:00:00'
            );


        const difference =
            Math.round(
                (
                    selected -
                    bookingCheckout
                ) /
                (
                    1000 *
                    60 *
                    60 *
                    24
                )
            );


        if (difference < 1) {

            summary.classList.add('hidden');

            submitButton.disabled = true;

            return;

        }


        const total =
            difference *
            pricePerNight;


        summary.classList.remove('hidden');


        selectedDate.textContent =
            formatDate(
                dateInput.value
            );


        additionalNights.textContent =
            difference +
            ' malam';


        additionalPrice.textContent =
            formatRupiah(
                total
            );


        submitButton.disabled = false;

    }


    dateInput.addEventListener(
        'change',
        updateSummary
    );


    document
        .querySelectorAll('.available-date')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    dateInput.value =
                        button.dataset.date;

                    updateSummary();


                    document
                        .querySelectorAll('.available-date')
                        .forEach(function (item) {

                            item.classList.remove(
                                'border-blue-600',
                                'bg-blue-600'
                            );

                        });


                    button.classList.add(
                        'border-blue-600',
                        'bg-blue-600'
                    );

                }
            );

        });


    updateSummary();

});

</script>

@endsection