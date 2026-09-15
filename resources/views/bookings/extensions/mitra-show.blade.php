@extends('layouts.app')

@section('title', 'Detail Perpanjangan')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <a
        href="{{ route('mitra.booking-extensions.index') }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-blue mb-6"
    >
        ← Kembali
    </a>


    @if (session('success'))

        <div class="bg-green-50 text-green-700 rounded-xl p-4 mb-6 text-sm">
            {{ session('success') }}
        </div>

    @endif


    @if (session('error'))

        <div class="bg-red-50 text-red-700 rounded-xl p-4 mb-6 text-sm">
            {{ session('error') }}
        </div>

    @endif


    <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-8">

        <div class="flex items-start justify-between gap-4 mb-8">

            <div>

                <div class="text-2xl mb-3">
                    🛏️
                </div>

                <h1 class="text-2xl font-extrabold text-navy-900">
                    Permintaan Perpanjangan
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    #{{ $extension->booking->booking_code }}
                </p>

            </div>


            @php

                $statusMap = [
                    'pending' => [
                        'bg-yellow-50 text-yellow-700',
                        'Menunggu'
                    ],
                    'approved' => [
                        'bg-green-50 text-green-700',
                        'Disetujui'
                    ],
                    'rejected' => [
                        'bg-red-50 text-red-600',
                        'Ditolak'
                    ],
                    'cancelled' => [
                        'bg-gray-100 text-gray-500',
                        'Dibatalkan'
                    ],
                ];

                [
                    $statusClass,
                    $statusLabel
                ] =
                    $statusMap[$extension->status]
                    ??
                    [
                        'bg-gray-50 text-gray-500',
                        ucfirst($extension->status)
                    ];

            @endphp


            <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                {{ $statusLabel }}
            </span>

        </div>


        {{-- CUSTOMER --}}
        <div class="bg-gray-50 rounded-xl p-5 mb-6">

            <p class="text-xs text-gray-400 mb-1">
                Customer
            </p>

            <p class="font-bold text-gray-800">
                {{ $extension->booking->customer->name }}
            </p>

            @if ($extension->booking->customer->email)

                <p class="text-sm text-gray-500 mt-1">
                    {{ $extension->booking->customer->email }}
                </p>

            @endif

        </div>


        {{-- PROPERTY --}}
        <div class="mb-6">

            <p class="text-xs text-gray-400 mb-1">
                Properti
            </p>

            <p class="font-bold text-navy-900">
                {{ $extension->booking->property->name }}
            </p>

        </div>


        {{-- DATES --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">

            <div>

                <p class="text-xs text-gray-400">
                    Checkout Sekarang
                </p>

                <p class="font-semibold text-gray-800 mt-1">
                    {{ $extension->old_check_out->format('d M Y') }}
                </p>

            </div>


            <div>

                <p class="text-xs text-gray-400">
                    Checkout Baru
                </p>

                <p class="font-semibold text-blue-700 mt-1">
                    {{ $extension->new_check_out->format('d M Y') }}
                </p>

            </div>


            <div>

                <p class="text-xs text-gray-400">
                    Tambahan
                </p>

                <p class="font-semibold text-gray-800 mt-1">
                    {{ $extension->additional_nights }} malam
                </p>

            </div>

        </div>


        {{-- PRICE --}}
        <div class="border-t border-gray-100 pt-5 mb-6">

            <div class="flex justify-between">

                <span class="text-gray-500">
                    Tambahan Harga
                </span>

                <span class="text-lg font-extrabold text-navy-900">
                    Rp {{ number_format($extension->additional_amount, 0, ',', '.') }}
                </span>

            </div>

        </div>


        {{-- NOTE --}}
        @if ($extension->customer_note)

            <div class="bg-blue-50 rounded-xl p-4 mb-6">

                <p class="text-xs font-semibold text-blue-700 mb-1">
                    Catatan Customer
                </p>

                <p class="text-sm text-blue-900">
                    {{ $extension->customer_note }}
                </p>

            </div>

        @endif


        {{-- ACTION --}}
        @if ($extension->status === 'pending')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                <form
                    method="POST"
                    action="{{ route('mitra.booking-extensions.approve', $extension) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Yakin ingin menyetujui perpanjangan ini?');"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl"
                    >
                        ✓ Setujui
                    </button>

                </form>


                <button
                    type="button"
                    onclick="document.getElementById('rejectBox').classList.toggle('hidden')"
                    class="w-full border border-red-200 text-red-500 hover:bg-red-50 font-semibold py-3 rounded-xl"
                >
                    Tolak
                </button>

            </div>


            <div
                id="rejectBox"
                class="hidden mt-4 bg-red-50 border border-red-100 rounded-xl p-5"
            >

                <form
                    method="POST"
                    action="{{ route('mitra.booking-extensions.reject', $extension) }}"
                >

                    @csrf
                    @method('PATCH')


                    <label
                        class="block text-sm font-semibold text-red-800 mb-2"
                    >
                        Alasan Penolakan
                    </label>


                    <textarea
                        name="rejection_reason"
                        rows="4"
                        required
                        maxlength="1000"
                        placeholder="Contoh: Tanggal tersebut sudah digunakan untuk booking lain."
                        class="w-full rounded-xl border-red-200 focus:border-red-400 focus:ring-red-400"
                    ></textarea>


                    @error('rejection_reason')

                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>

                    @enderror


                    <button
                        type="submit"
                        class="w-full mt-3 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl"
                    >
                        Konfirmasi Penolakan
                    </button>

                </form>

            </div>

        @endif

    </div>

</div>

@endsection