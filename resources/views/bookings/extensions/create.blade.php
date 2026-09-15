@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="mb-6">

        <a
            href="{{ route('customer.bookings.show', $booking) }}"
            class="text-sm text-blue-600 hover:text-blue-700 font-medium"
        >
            ← Kembali ke Detail Booking
        </a>

        <h1 class="text-2xl font-bold text-slate-900 mt-4">
            Perpanjang Booking
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Ajukan perpanjangan masa menginap Anda.
        </p>

    </div>


    {{-- ERROR --}}
    @if (session('extension_error'))

        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3">

            <p class="text-sm text-red-700">
                {{ session('extension_error') }}
            </p>

        </div>

    @endif


    {{-- VALIDATION ERROR --}}
    @if ($errors->any())

        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3">

            <ul class="text-sm text-red-700 list-disc list-inside">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- BOOKING INFO --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-5">

        <h2 class="font-bold text-lg text-slate-900 mb-5">
            Detail Booking
        </h2>


        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div>

                <p class="text-xs text-gray-500">
                    Kode Booking
                </p>

                <p class="font-semibold text-slate-900 mt-1">
                    {{ $booking->booking_code }}
                </p>

            </div>


            <div>

                <p class="text-xs text-gray-500">
                    Properti
                </p>

                <p class="font-semibold text-slate-900 mt-1">
                    {{ $booking->property->name }}
                </p>

            </div>


            <div>

                <p class="text-xs text-gray-500">
                    Check-in
                </p>

                <p class="font-semibold text-slate-900 mt-1">
                    {{ $booking->check_in->format('d M Y') }}
                </p>

            </div>


            <div>

                <p class="text-xs text-gray-500">
                    Checkout Saat Ini
                </p>

                <p class="font-semibold text-slate-900 mt-1">
                    {{ $booking->check_out->format('d M Y') }}
                </p>

            </div>

        </div>

    </div>


    {{-- PENDING --}}
    @if ($pendingExtension)

        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">

            <div class="flex items-start gap-3">

                <div class="text-xl">
                    ⚠️
                </div>

                <div>

                    <h2 class="font-bold text-yellow-800">
                        Permintaan Sedang Diproses
                    </h2>

                    <p class="text-sm text-yellow-700 mt-1">
                        Anda sudah memiliki permintaan perpanjangan
                        yang sedang menunggu konfirmasi dari mitra.
                    </p>

                </div>

            </div>


            <a
                href="{{ route('customer.bookings.show', $booking) }}"
                class="inline-block mt-5 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition"
            >
                Kembali ke Booking
            </a>

        </div>

    @else


        {{-- FORM --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <h2 class="font-bold text-lg text-slate-900">
                Ajukan Perpanjangan
            </h2>

            <p class="text-sm text-gray-500 mt-1 mb-6">
                Tentukan tanggal checkout baru yang Anda inginkan.
            </p>


            <form
                method="POST"
                action="{{ route('customer.bookings.extension.store', $booking) }}"
            >

                @csrf


                {{-- CHECKOUT BARU --}}
                <div class="mb-5">

                    <label
                        for="new_check_out"
                        class="block text-sm font-semibold text-slate-900 mb-2"
                    >
                        Checkout Baru
                    </label>


                    <input
                        type="date"
                        id="new_check_out"
                        name="new_check_out"
                        value="{{ old('new_check_out') }}"
                        min="{{ $booking->check_out->copy()->addDay()->format('Y-m-d') }}"
                        required
                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >


                    <p class="text-xs text-gray-500 mt-2">
                        Checkout baru harus setelah
                        {{ $booking->check_out->format('d M Y') }}.
                    </p>

                </div>


                {{-- CATATAN --}}
                <div class="mb-5">

                    <label
                        for="customer_note"
                        class="block text-sm font-semibold text-slate-900 mb-2"
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
                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('customer_note') }}</textarea>

                </div>


                {{-- INFO --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">

                    <p class="text-sm text-blue-800 leading-relaxed">

                        <strong>Perhatian:</strong>
                        Permintaan perpanjangan akan diperiksa terlebih dahulu
                        oleh mitra. Jika disetujui, checkout booking akan
                        diperbarui sesuai tanggal yang Anda ajukan.

                    </p>

                </div>


                {{-- BUTTON --}}
                <div class="flex flex-col sm:flex-row gap-3">

                    <a
                        href="{{ route('customer.bookings.show', $booking) }}"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold text-sm text-center hover:bg-gray-50 transition"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="w-full sm:flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition"
                    >
                        Ajukan Perpanjangan
                    </button>

                </div>

            </form>

        </div>

    @endif

</div>

@endsection