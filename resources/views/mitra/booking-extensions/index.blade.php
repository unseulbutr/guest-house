@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-900">
            Permintaan Perpanjangan
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Kelola permintaan perpanjangan menginap dari customer.
        </p>

    </div>


    {{-- SUCCESS --}}
    @if (session('success'))

        <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3">

            <p class="text-sm text-green-700">
                {{ session('success') }}
            </p>

        </div>

    @endif


    {{-- ERROR --}}
    @if (session('error'))

        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3">

            <p class="text-sm text-red-700">
                {{ session('error') }}
            </p>

        </div>

    @endif


    {{-- LIST --}}
    @if ($extensions->count())

        <div class="space-y-4">

            @foreach ($extensions as $extension)

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">


                        {{-- INFO --}}
                        <div class="flex-1">

                            <div class="flex flex-wrap items-center gap-3 mb-4">

                                <h2 class="text-lg font-bold text-slate-900">
                                    {{ $extension->booking->property->name }}
                                </h2>


                                @if ($extension->status === 'pending')

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                        Menunggu Konfirmasi
                                    </span>

                                @elseif ($extension->status === 'approved')

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Disetujui
                                    </span>

                                @elseif ($extension->status === 'rejected')

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Ditolak
                                    </span>

                                @else

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        {{ ucfirst($extension->status) }}
                                    </span>

                                @endif

                            </div>


                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">


                                {{-- CUSTOMER --}}
                                <div>

                                    <p class="text-xs text-gray-500">
                                        Customer
                                    </p>

                                    <p class="font-semibold text-sm text-slate-900 mt-1">
                                        {{ $extension->booking->customer->name }}
                                    </p>

                                </div>


                                {{-- BOOKING --}}
                                <div>

                                    <p class="text-xs text-gray-500">
                                        Kode Booking
                                    </p>

                                    <p class="font-semibold text-sm text-slate-900 mt-1">
                                        {{ $extension->booking->booking_code }}
                                    </p>

                                </div>


                                {{-- TANGGAL --}}
                                <div>

                                    <p class="text-xs text-gray-500">
                                        Perubahan Checkout
                                    </p>

                                    <p class="font-semibold text-sm text-slate-900 mt-1">

                                        {{ $extension->old_check_out->format('d M Y') }}

                                        <span class="text-blue-600">
                                            →
                                        </span>

                                        {{ $extension->new_check_out->format('d M Y') }}

                                    </p>

                                </div>


                                {{-- TAMBAHAN --}}
                                <div>

                                    <p class="text-xs text-gray-500">
                                        Tambahan
                                    </p>

                                    <p class="font-semibold text-sm text-slate-900 mt-1">
                                        {{ $extension->additional_nights }} malam
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Rp {{ number_format($extension->additional_amount, 0, ',', '.') }}
                                    </p>

                                </div>

                            </div>


                            {{-- NOTE --}}
                            @if ($extension->customer_note)

                                <div class="mt-4 bg-gray-50 rounded-xl p-4">

                                    <p class="text-xs font-semibold text-gray-500 mb-1">
                                        Catatan Customer
                                    </p>

                                    <p class="text-sm text-gray-700">
                                        {{ $extension->customer_note }}
                                    </p>

                                </div>

                            @endif

                        </div>


                        {{-- DETAIL BUTTON --}}
                        <div class="lg:w-40">

                            <a
                                href="{{ route('mitra.booking-extensions.show', $extension) }}"
                                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-3 rounded-xl transition"
                            >
                                Lihat Pengajuan
                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- PAGINATION --}}
        <div class="mt-6">

            {{ $extensions->links() }}

        </div>


    @else

        <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">

            <div class="text-4xl mb-3">
                📋
            </div>

            <h2 class="font-bold text-lg text-slate-900">
                Belum Ada Permintaan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Belum ada customer yang mengajukan perpanjangan
                untuk properti Anda.
            </p>

        </div>

    @endif

</div>

@endsection