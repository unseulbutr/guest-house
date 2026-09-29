@extends('layouts.app')

@section('title', 'Pembayaran Perpanjangan')

@section('content')

<div class="min-h-screen bg-gray-50 py-8">

    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">

        <div class="mb-6">

            <a
                href="{{ route('customer.bookings.show', $extension->booking) }}"
                class="text-sm font-bold text-blue-600 hover:text-blue-700"
            >
                ← Kembali ke Detail Booking
            </a>

            <h1 class="mt-4 text-2xl font-black text-slate-900">
                Pembayaran Perpanjangan
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Selesaikan pembayaran untuk mengaktifkan perpanjangan.
            </p>

        </div>


        {{-- INFO --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="text-lg font-black text-gray-900">
                Detail Perpanjangan
            </h2>


            <div class="mt-5 space-y-3">

                <div class="flex justify-between gap-4">

                    <span class="text-sm text-gray-500">
                        Properti
                    </span>

                    <span class="text-right text-sm font-bold text-gray-900">
                        {{ $extension->booking->property->name }}
                    </span>

                </div>


                <div class="flex justify-between gap-4">

                    <span class="text-sm text-gray-500">
                        Checkout Lama
                    </span>

                    <span class="text-sm font-bold text-gray-900">
                        {{ $extension->old_check_out->format('d M Y') }}
                    </span>

                </div>


                <div class="flex justify-between gap-4">

                    <span class="text-sm text-gray-500">
                        Checkout Baru
                    </span>

                    <span class="text-sm font-black text-blue-600">
                        {{ $extension->new_check_out->format('d M Y') }}
                    </span>

                </div>


                <div class="flex justify-between gap-4">

                    <span class="text-sm text-gray-500">
                        Tambahan
                    </span>

                    <span class="text-sm font-bold text-gray-900">
                        {{ $extension->additional_nights }} malam
                    </span>

                </div>


                <div class="border-t border-gray-100 pt-4">

                    <div class="flex items-center justify-between gap-4">

                        <span class="font-black text-gray-900">
                            Total Pembayaran
                        </span>

                        <span class="text-2xl font-black text-blue-600">
                            Rp {{ number_format($extension->additional_amount, 0, ',', '.') }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- QRIS --}}
        <div class="mt-5 rounded-2xl border border-blue-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg
                        class="h-6 w-6"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect
                            x="3"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                        />

                        <rect
                            x="14"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                        />

                        <rect
                            x="3"
                            y="14"
                            width="7"
                            height="7"
                            rx="1"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14 14h3v3h-3zm4 4h3v3h-3zm-1-4h4"
                        />
                    </svg>

                </div>


                <h2 class="mt-4 text-xl font-black text-gray-900">
                    Bayar dengan QRIS
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Scan QRIS berikut menggunakan aplikasi pembayaran Anda.
                </p>

            </div>


            <div class="mt-6 flex justify-center">

                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">

                    <img
                        src="{{ asset('images/qris.png') }}"
                        alt="QRIS Pembayaran Perpanjangan"
                        class="h-64 w-64 object-contain"
                    >

                </div>

            </div>


            @if ($extension->payment_deadline)

                <div class="mt-5 rounded-xl bg-yellow-50 p-4 text-center">

                    <p class="text-xs font-semibold text-yellow-700">
                        Batas Pembayaran
                    </p>

                    <p class="mt-1 font-black text-yellow-900">
                        {{ $extension->payment_deadline->format('d M Y H:i') }}
                    </p>

                </div>

            @endif


            {{-- SIMULASI --}}
            <form
                action="{{ route('customer.booking-extensions.simulate-pay', $extension) }}"
                method="POST"
                class="mt-5"
            >

                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-black text-white transition hover:bg-blue-700"
                >
                    Saya Sudah Membayar
                </button>

            </form>


            <p class="mt-3 text-center text-xs text-gray-400">
                Mode simulasi pembayaran untuk development.
            </p>

        </div>


        {{-- INFO --}}
        <div class="mt-5 rounded-2xl border border-gray-200 bg-white p-5">

            <h3 class="font-black text-gray-900">
                Penting
            </h3>

            <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-relaxed text-gray-600">

                <li>
                    Booking belum diperpanjang sebelum pembayaran berhasil.
                </li>

                <li>
                    Setelah pembayaran berhasil, checkout booking akan
                    otomatis berubah ke tanggal baru.
                </li>

                <li>
                    Jika tanggal sudah tidak tersedia saat pembayaran,
                    pembayaran tidak akan diterapkan ke booking.
                </li>

            </ul>

        </div>

    </div>

</div>

@endsection