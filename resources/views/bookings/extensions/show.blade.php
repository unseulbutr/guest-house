@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- BACK --}}
    <div class="mb-6">
        <a
            href="{{ route('mitra.booking-extensions.index') }}"
            class="text-sm text-blue-600 hover:text-blue-700 font-medium"
        >
            ← Kembali ke Permintaan Perpanjangan
        </a>
    </div>


    {{-- HEADER --}}
    <div class="mb-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Detail Perpanjangan
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Tinjau permintaan perpanjangan dari customer.
                </p>
            </div>


            {{-- STATUS --}}
            @if ($extension->status === 'pending')

                <span class="inline-flex w-fit px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                    Menunggu Konfirmasi
                </span>

            @elseif ($extension->status === 'approved')

                <span class="inline-flex w-fit px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                    Disetujui
                </span>

            @elseif ($extension->status === 'rejected')

                <span class="inline-flex w-fit px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                    Ditolak
                </span>

            @else

                <span class="inline-flex w-fit px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                    {{ ucfirst($extension->status) }}
                </span>

            @endif

        </div>

    </div>


    {{-- FLASH --}}
    @if (session('success'))

        <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3">

            <p class="text-sm text-green-700">
                {{ session('success') }}
            </p>

        </div>

    @endif


    @if (session('error'))

        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3">

            <p class="text-sm text-red-700">
                {{ session('error') }}
            </p>

        </div>

    @endif


    {{-- PROPERTY --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-5">

        <h2 class="text-lg font-bold text-slate-900 mb-5">
            Informasi Properti
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div>
                <p class="text-xs text-gray-500">
                    Properti
                </p>

                <p class="font-semibold text-slate-900 mt-1">
                    {{ $extension->booking->property->name }}
                </p>
            </div>


            <div>
                <p class="text-xs text-gray-500">
                    Kode Booking
                </p>

                <p class="font-semibold text-slate-900 mt-1">
                    {{ $extension->booking->booking_code }}
                </p>
            </div>

        </div>

    </div>


    {{-- CUSTOMER --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-5">

        <h2 class="text-lg font-bold text-slate-900 mb-5">
            Informasi Customer
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div>
                <p class="text-xs text-gray-500">
                    Nama Customer
                </p>

                <p class="font-semibold text-slate-900 mt-1">
                    {{ $extension->booking->customer->name }}
                </p>
            </div>


            <div>
                <p class="text-xs text-gray-500">
                    Email
                </p>

                <p class="font-semibold text-slate-900 mt-1">
                    {{ $extension->booking->customer->email }}
                </p>
            </div>

        </div>

    </div>


    {{-- EXTENSION DETAIL --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-5">

        <h2 class="text-lg font-bold text-slate-900 mb-5">
            Detail Perpanjangan
        </h2>


        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

            {{-- OLD CHECKOUT --}}
            <div class="bg-gray-50 rounded-xl p-4">

                <p class="text-xs text-gray-500">
                    Checkout Saat Ini
                </p>

                <p class="font-bold text-slate-900 mt-2">
                    {{ $extension->old_check_out->format('d M Y') }}
                </p>

            </div>


            {{-- NEW CHECKOUT --}}
            <div class="bg-blue-50 rounded-xl p-4">

                <p class="text-xs text-blue-600">
                    Checkout Baru
                </p>

                <p class="font-bold text-blue-700 mt-2">
                    {{ $extension->new_check_out->format('d M Y') }}
                </p>

            </div>


            {{-- NIGHTS --}}
            <div class="bg-gray-50 rounded-xl p-4">

                <p class="text-xs text-gray-500">
                    Tambahan Menginap
                </p>

                <p class="font-bold text-slate-900 mt-2">
                    {{ $extension->additional_nights }} malam
                </p>

            </div>

        </div>


        {{-- AMOUNT --}}
        <div class="mt-5 border-t border-gray-100 pt-5">

            <p class="text-xs text-gray-500">
                Biaya Tambahan
            </p>

            <p class="text-2xl font-bold text-slate-900 mt-1">
                Rp {{ number_format($extension->additional_amount, 0, ',', '.') }}
            </p>

        </div>


        {{-- CUSTOMER NOTE --}}
        @if ($extension->customer_note)

            <div class="mt-5 bg-gray-50 rounded-xl p-4">

                <p class="text-xs font-semibold text-gray-500 mb-2">
                    Catatan Customer
                </p>

                <p class="text-sm text-gray-700 leading-relaxed">
                    {{ $extension->customer_note }}
                </p>

            </div>

        @endif

    </div>


    {{-- ACTION --}}
    @if ($extension->status === 'pending')

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

            <h2 class="text-lg font-bold text-slate-900">
                Proses Permintaan
            </h2>

            <p class="text-sm text-gray-500 mt-1 mb-6">
                Pastikan tanggal tersebut masih tersedia sebelum
                menyetujui permintaan.
            </p>


            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                {{-- APPROVE --}}
                <form
                    method="POST"
                    action="{{ route('mitra.booking-extensions.approve', $extension) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin menyetujui perpanjangan ini?')"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl text-sm transition"
                    >
                        ✓ Setujui Perpanjangan
                    </button>

                </form>


                {{-- REJECT BUTTON --}}
                <button
                    type="button"
                    onclick="document.getElementById('reject-form').classList.toggle('hidden')"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl text-sm transition"
                >
                    ✕ Tolak Perpanjangan
                </button>

            </div>


            {{-- REJECT FORM --}}
            <div
                id="reject-form"
                class="hidden mt-5 border-t border-gray-100 pt-5"
            >

                <form
                    method="POST"
                    action="{{ route('mitra.booking-extensions.reject', $extension) }}"
                >

                    @csrf
                    @method('PATCH')


                    <label
                        for="rejection_reason"
                        class="block text-sm font-semibold text-slate-900 mb-2"
                    >
                        Alasan Penolakan
                    </label>

                    <textarea
                        id="rejection_reason"
                        name="rejection_reason"
                        rows="4"
                        maxlength="1000"
                        required
                        placeholder="Masukkan alasan mengapa permintaan perpanjangan ditolak..."
                        class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                    >{{ old('rejection_reason') }}</textarea>


                    @error('rejection_reason')

                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>

                    @enderror


                    <button
                        type="submit"
                        class="mt-3 w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl text-sm transition"
                    >
                        Konfirmasi Penolakan
                    </button>

                </form>

            </div>

        </div>

    @elseif ($extension->status === 'rejected')

        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">

            <h2 class="font-bold text-red-800">
                Permintaan Ditolak
            </h2>

            @if ($extension->rejection_reason)

                <p class="text-sm text-red-700 mt-2">
                    {{ $extension->rejection_reason }}
                </p>

            @endif

        </div>

    @elseif ($extension->status === 'approved')

        <div class="bg-green-50 border border-green-200 rounded-2xl p-6">

            <h2 class="font-bold text-green-800">
                Perpanjangan Berhasil Disetujui
            </h2>

            <p class="text-sm text-green-700 mt-2">
                Checkout booking telah diperpanjang sampai
                {{ $extension->new_check_out->format('d M Y') }}.
            </p>

        </div>

    @endif

</div>

@endsection