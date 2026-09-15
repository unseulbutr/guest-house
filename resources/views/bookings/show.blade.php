@extends('layouts.app')

@section('title', 'Booking ' . $booking->booking_code)

@section('content')

<style>

    .booking-detail-page {
        min-height: 100vh;

        background:
            radial-gradient(
                circle at 10% 0%,
                rgba(37, 99, 235, .06),
                transparent 30%
            ),

            radial-gradient(
                circle at 90% 5%,
                rgba(15, 23, 42, .05),
                transparent 28%
            ),

            #f8fafc;
    }

    .booking-detail-container {
        max-width: 1120px;
        margin: auto;
        padding: 42px 24px 70px;
    }

    .detail-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow:
            0 10px 35px rgba(15, 23, 42, .045);
    }

    .detail-card-padding {
        padding: 25px;
    }

    .detail-label {
        color: #94a3b8;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .detail-value {
        color: #0f172a;
        font-weight: 700;
    }

    .refund-box {
        border-radius: 18px;
        padding: 18px;
    }

</style>


<div class="booking-detail-page">

    <div class="booking-detail-container">

        {{-- ================================================= --}}
        {{-- BREADCRUMB --}}
        {{-- ================================================= --}}

        <div class="text-sm text-gray-500 mb-6">

            <a
                href="{{ route(
                    auth()->user()->hasRole('mitra')
                        ? 'mitra.bookings.index'
                        : (
                            auth()->user()->hasRole('customer')
                                ? 'customer.bookings.index'
                                : 'admin.bookings.index'
                        )
                ) }}"
                class="hover:text-blue-600 transition"
            >
                Booking
            </a>

            <span class="mx-2">/</span>

            <span>
                {{ $booking->booking_code }}
            </span>

        </div>


        {{-- ================================================= --}}
        {{-- SUCCESS --}}
        {{-- ================================================= --}}

        @if (session('success'))

            <div class="
                mb-6
                rounded-2xl
                border
                border-green-200
                bg-green-50
                px-5
                py-4
                text-sm
                text-green-700
                font-medium
            ">

                ✓
                {{ session('success') }}

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- ERROR --}}
        {{-- ================================================= --}}

        @if (session('error'))

            <div class="
                mb-6
                rounded-2xl
                border
                border-red-200
                bg-red-50
                px-5
                py-4
                text-sm
                text-red-700
                font-medium
            ">

                ✕
                {{ session('error') }}

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- VALIDATION ERROR --}}
        {{-- ================================================= --}}

        @if ($errors->any())

            <div class="
                mb-6
                rounded-2xl
                border
                border-red-200
                bg-red-50
                px-5
                py-4
                text-sm
                text-red-700
            ">

                <p class="font-bold mb-2">
                    Terdapat kesalahan:
                </p>

                <ul class="list-disc list-inside space-y-1">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-5 mb-7">

            <div>

                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                    Detail Reservasi
                </p>

                <h1 class="
                    text-3xl
                    md:text-4xl
                    font-extrabold
                    text-slate-900
                    tracking-tight
                ">
                    {{ $booking->booking_code }}
                </h1>

                <p class="text-sm text-gray-500 mt-2">

                    Dibuat
                    {{ $booking->created_at->translatedFormat('d M Y, H:i') }}

                </p>

            </div>


            <div class="flex flex-wrap gap-2">

                @include(
                    'bookings.partials.status-badge',
                    ['status' => $booking->status]
                )

                @include(
                    'bookings.partials.payment-badge',
                    ['status' => $booking->payment_status]
                )

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- REFUND STATUS --}}
        {{-- ================================================= --}}

        @if ($booking->refund_status === 'pending')

            <div class="
                refund-box
                mb-6
                bg-orange-50
                border
                border-orange-200
            ">

                <div class="flex gap-4">

                    <div class="
                        w-11
                        h-11
                        rounded-xl
                        bg-orange-100
                        text-orange-600
                        flex
                        items-center
                        justify-center
                        shrink-0
                        text-xl
                    ">
                        ↻
                    </div>

                    <div>

                        <h3 class="
                            font-bold
                            text-orange-900
                        ">
                            Refund sedang diproses
                        </h3>

                        <p class="
                            text-sm
                            text-orange-700
                            mt-1
                        ">

                            Dana sebesar

                            <strong>
                                Rp {{ number_format(
                                    $booking->refund_amount ?? $booking->total_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </strong>

                            akan dikembalikan.

                        </p>

                    </div>

                </div>

            </div>

        @elseif ($booking->refund_status === 'completed')

            <div class="
                refund-box
                mb-6
                bg-purple-50
                border
                border-purple-200
            ">

                <div class="flex gap-4">

                    <div class="
                        w-11
                        h-11
                        rounded-xl
                        bg-purple-100
                        text-purple-600
                        flex
                        items-center
                        justify-center
                        shrink-0
                        text-xl
                    ">
                        ✓
                    </div>

                    <div>

                        <h3 class="
                            font-bold
                            text-purple-900
                        ">
                            Refund berhasil
                        </h3>

                        <p class="
                            text-sm
                            text-purple-700
                            mt-1
                        ">

                            Dana sebesar

                            <strong>
                                Rp {{ number_format(
                                    $booking->refund_amount ?? 0,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </strong>

                            telah dikembalikan.

                        </p>

                        @if ($booking->refunded_at)

                            <p class="text-xs text-purple-500 mt-2">

                                {{ $booking->refunded_at->translatedFormat(
                                    'd M Y, H:i'
                                ) }}

                            </p>

                        @endif

                    </div>

                </div>

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- MAIN GRID --}}
        {{-- ================================================= --}}

        <div class="
            grid
            grid-cols-1
            lg:grid-cols-3
            gap-6
        ">


            {{-- ================================================= --}}
            {{-- LEFT --}}
            {{-- ================================================= --}}

            <div class="lg:col-span-2 space-y-6">


                {{-- ================================================= --}}
                {{-- PROPERTY --}}
                {{-- ================================================= --}}

                <div class="detail-card detail-card-padding">

                    <div class="flex gap-5">

                        <div class="
                            w-28
                            h-28
                            rounded-2xl
                            overflow-hidden
                            bg-gray-100
                            shrink-0
                        ">

                            @if ($booking->property->cover_image)

                                <img
                                    src="{{ asset(
                                        'storage/' .
                                        $booking->property->cover_image
                                    ) }}"
                                    class="
                                        w-full
                                        h-full
                                        object-cover
                                    "
                                    alt="{{ $booking->property->name }}"
                                >

                            @endif

                        </div>


                        <div class="min-w-0">

                            <p class="detail-label mb-2">
                                Properti
                            </p>

                            <a
                                href="{{ route(
                                    'properties.show',
                                    $booking->property
                                ) }}"
                                class="
                                    text-xl
                                    font-extrabold
                                    text-slate-900
                                    hover:text-blue-600
                                    transition
                                "
                            >
                                {{ $booking->property->name }}
                            </a>

                            <p class="
                                text-sm
                                text-gray-500
                                mt-2
                            ">
                                📍 {{ $booking->property->city }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- STAY --}}
                {{-- ================================================= --}}

                <div class="detail-card detail-card-padding">

                    <h2 class="
                        text-lg
                        font-extrabold
                        text-slate-900
                        mb-5
                    ">
                        Detail Menginap
                    </h2>


                    <div class="
                        grid
                        grid-cols-1
                        md:grid-cols-3
                        gap-4
                    ">


                        <div class="
                            rounded-2xl
                            bg-slate-50
                            border
                            border-slate-100
                            p-5
                        ">

                            <p class="detail-label mb-2">
                                Check-in
                            </p>

                            <p class="text-lg font-bold text-slate-900">

                                {{ $booking->check_in->format('d M Y') }}

                            </p>

                        </div>


                        <div class="
                            rounded-2xl
                            bg-slate-50
                            border
                            border-slate-100
                            p-5
                        ">

                            <p class="detail-label mb-2">
                                Check-out
                            </p>

                            <p class="text-lg font-bold text-slate-900">

                                {{ $booking->check_out->format('d M Y') }}

                            </p>

                        </div>


                        <div class="
                            rounded-2xl
                            bg-slate-50
                            border
                            border-slate-100
                            p-5
                        ">

                            <p class="detail-label mb-2">
                                Tamu
                            </p>

                            <p class="text-lg font-bold text-slate-900">

                                {{ $booking->guest_count }}
                                Tamu

                            </p>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- REVIEW / RATING CUSTOMER --}}
                {{-- ================================================= --}}

                @role('customer')

                    @php
                        $canReview =
                            (int) $booking->customer_id === (int) auth()->id()
                            && $booking->payment_status === 'paid'
                            && $booking->check_out
                            && !$booking->check_out->isFuture();
                    @endphp


                    @if ($canReview)

                        <div class="detail-card detail-card-padding">

                            <div class="flex items-start justify-between gap-4 mb-6">

                                <div>

                                    <p class="detail-label mb-2">
                                        Pengalaman Menginap
                                    </p>

                                    <h2 class="
                                        text-xl
                                        font-extrabold
                                        text-slate-900
                                    ">
                                        ⭐ Berikan Rating & Ulasan
                                    </h2>

                                    <p class="
                                        text-sm
                                        text-gray-500
                                        mt-2
                                    ">
                                        Bagaimana pengalaman kamu menginap di
                                        <strong>
                                            {{ $booking->property->name }}
                                        </strong>?
                                    </p>

                                </div>

                            </div>


                            @if ($booking->review)

                                {{-- ================================= --}}
                                {{-- SUDAH REVIEW --}}
                                {{-- ================================= --}}

                                <div class="
                                    rounded-2xl
                                    border
                                    border-green-200
                                    bg-green-50
                                    p-5
                                ">

                                    <div class="
                                        flex
                                        flex-col
                                        sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                        gap-4
                                    ">

                                        <div>

                                            <p class="
                                                text-sm
                                                font-bold
                                                text-green-800
                                            ">
                                                ✓ Kamu sudah memberikan ulasan
                                            </p>

                                            <div class="
                                                flex
                                                items-center
                                                gap-3
                                                mt-3
                                            ">

                                                <span class="
                                                    text-3xl
                                                    font-extrabold
                                                    text-yellow-500
                                                ">
                                                    {{ $booking->review->score }}
                                                </span>

                                                <span class="
                                                    text-sm
                                                    text-gray-500
                                                ">
                                                    / 10
                                                </span>

                                                <span class="
                                                    text-yellow-400
                                                    text-lg
                                                ">
                                                    {{ str_repeat(
                                                        '★',
                                                        max(
                                                            1,
                                                            min(
                                                                5,
                                                                (int) round(
                                                                    $booking->review->score / 2
                                                                )
                                                            )
                                                        )
                                                    ) }}
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    @if ($booking->review->comment)

                                        <div class="
                                            mt-5
                                            rounded-xl
                                            bg-white
                                            border
                                            border-green-100
                                            p-4
                                        ">

                                            <p class="
                                                text-sm
                                                text-gray-700
                                                leading-relaxed
                                            ">
                                                "{{ $booking->review->comment }}"
                                            </p>

                                        </div>

                                    @endif


                                    <p class="
                                        text-xs
                                        text-green-700
                                        mt-4
                                    ">
                                        Terima kasih sudah memberikan feedback!
                                    </p>

                                </div>

                            @else

                                {{-- ================================= --}}
                                {{-- FORM REVIEW --}}
                                {{-- ================================= --}}

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'customer.bookings.reviews.store',
                                        $booking
                                    ) }}"
                                    class="space-y-6"
                                >

                                    @csrf


                                    {{-- RATING --}}
                                    <div>

                                        <label class="
                                            block
                                            text-sm
                                            font-bold
                                            text-slate-900
                                            mb-3
                                        ">
                                            Berikan rating kamu
                                        </label>


                                        <div class="
                                            grid
                                            grid-cols-5
                                            sm:grid-cols-10
                                            gap-2
                                        ">

                                            @for ($score = 1; $score <= 10; $score++)

                                                <label class="cursor-pointer">

                                                    <input
                                                        type="radio"
                                                        name="score"
                                                        value="{{ $score }}"
                                                        class="peer sr-only"
                                                        {{ old('score') == $score ? 'checked' : '' }}
                                                        required
                                                    >

                                                    <div class="
                                                        h-12
                                                        flex
                                                        items-center
                                                        justify-center
                                                        rounded-xl
                                                        border
                                                        border-gray-300
                                                        bg-white
                                                        text-gray-700
                                                        font-bold
                                                        transition

                                                        peer-checked:bg-yellow-400
                                                        peer-checked:border-yellow-400
                                                        peer-checked:text-white

                                                        hover:border-yellow-400
                                                        hover:bg-yellow-50
                                                    ">

                                                        {{ $score }}

                                                    </div>

                                                </label>

                                            @endfor

                                        </div>


                                        @error('score')

                                            <p class="
                                                text-sm
                                                text-red-600
                                                mt-2
                                            ">
                                                {{ $message }}
                                            </p>

                                        @enderror


                                        <div class="
                                            flex
                                            justify-between
                                            text-xs
                                            text-gray-400
                                            mt-2
                                        ">

                                            <span>
                                                1 = Sangat buruk
                                            </span>

                                            <span>
                                                10 = Luar biasa
                                            </span>

                                        </div>

                                    </div>


                                    {{-- COMMENT --}}
                                    <div>

                                        <label
                                            for="comment"
                                            class="
                                                block
                                                text-sm
                                                font-bold
                                                text-slate-900
                                                mb-2
                                            "
                                        >
                                            Ceritakan pengalaman kamu

                                            <span class="
                                                font-normal
                                                text-gray-400
                                            ">
                                                (opsional)
                                            </span>

                                        </label>


                                        <textarea
                                            id="comment"
                                            name="comment"
                                            rows="5"
                                            maxlength="2000"
                                            placeholder="Bagaimana pengalaman kamu menginap di tempat ini?"
                                            class="
                                                w-full
                                                rounded-xl
                                                border
                                                border-gray-300
                                                px-4
                                                py-3
                                                text-sm
                                                text-slate-900
                                                placeholder-gray-400
                                                focus:border-yellow-400
                                                focus:ring-2
                                                focus:ring-yellow-100
                                                outline-none
                                                transition
                                            "
                                        >{{ old('comment') }}</textarea>


                                        @error('comment')

                                            <p class="
                                                text-sm
                                                text-red-600
                                                mt-2
                                            ">
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>


                                    {{-- SUBMIT --}}
                                    <button
                                        type="submit"
                                        class="
                                            w-full
                                            sm:w-auto
                                            px-7
                                            py-3.5
                                            rounded-xl
                                            bg-yellow-400
                                            hover:bg-yellow-500
                                            text-white
                                            font-bold
                                            text-sm
                                            transition
                                            shadow-sm
                                        "
                                    >
                                        ⭐ Kirim Rating & Ulasan
                                    </button>

                                </form>

                            @endif

                        </div>

                    @endif

                @endrole


                {{-- ================================================= --}}
                {{-- PRICE --}}
                {{-- ================================================= --}}

                <div class="detail-card detail-card-padding">

                    <h2 class="
                        text-lg
                        font-extrabold
                        text-slate-900
                        mb-5
                    ">
                        Rincian Pembayaran
                    </h2>


                    <div class="space-y-4">

                        <div class="flex justify-between text-sm">

                            <span class="text-gray-500">

                                Subtotal
                                ({{ $booking->check_in->diffInDays(
                                    $booking->check_out
                                ) }} malam)

                            </span>

                            <span class="font-semibold text-slate-900">

                                Rp {{ number_format(
                                    $booking->subtotal,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </span>

                        </div>


                        @role('mitra')

                            <div class="flex justify-between text-sm">

                                <span class="text-gray-500">
                                    Komisi Platform
                                </span>

                                <span class="text-gray-500">

                                    - Rp {{ number_format(
                                        $booking->commission_amount,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </span>

                            </div>

                            <div class="
                                flex
                                justify-between
                                border-t
                                border-gray-100
                                pt-4
                                text-sm
                            ">

                                <span class="font-semibold">
                                    Payout Kamu
                                </span>

                                <span class="
                                    font-extrabold
                                    text-green-600
                                ">

                                    Rp {{ number_format(
                                        $booking->mitra_payout_amount,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </span>

                            </div>

                        @endrole


                        @role('admin|super_admin')

                            <div class="flex justify-between text-sm">

                                <span class="text-gray-500">
                                    Komisi Platform
                                </span>

                                <span class="text-gray-500">

                                    Rp {{ number_format(
                                        $booking->commission_amount,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </span>

                            </div>

                            <div class="flex justify-between text-sm">

                                <span class="text-gray-500">
                                    Payout Mitra
                                </span>

                                <span class="text-gray-500">

                                    Rp {{ number_format(
                                        $booking->mitra_payout_amount,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </span>

                            </div>

                        @endrole


                        <div class="
                            border-t
                            border-gray-100
                            pt-5
                            flex
                            justify-between
                            items-center
                        ">

                            <span class="
                                text-base
                                font-extrabold
                                text-slate-900
                            ">
                                Total
                            </span>

                            <span class="
                                text-2xl
                                font-extrabold
                                text-slate-900
                            ">

                                Rp {{ number_format(
                                    $booking->total_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- RIGHT --}}
            {{-- ================================================= --}}

            <div class="space-y-5">


                {{-- ================================================= --}}
                {{-- PAYMENT --}}
                {{-- ================================================= --}}

                @role('customer')

                    @if (
                        $booking->payment_status === 'pending'
                        &&
                        $booking->status !== 'cancelled'
                    )

                        <div class="detail-card detail-card-padding">

                            <p class="detail-label mb-2">
                                Pembayaran
                            </p>

                            <h2 class="
                                text-xl
                                font-extrabold
                                text-slate-900
                                mb-5
                            ">
                                Selesaikan Pembayaran
                            </h2>


                            <div class="
                                w-44
                                h-44
                                mx-auto
                                bg-white
                                border
                                border-gray-200
                                rounded-2xl
                                p-3
                                mb-5
                            ">

                                <img
                                    src="{{ asset('images/qris.png') }}"
                                    alt="QRIS"
                                    class="
                                        w-full
                                        h-full
                                        object-contain
                                    "
                                >

                            </div>


                            <div class="
                                text-center
                                text-2xl
                                font-extrabold
                                text-slate-900
                                mb-5
                            ">

                                Rp {{ number_format(
                                    $booking->total_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>


                            <form
                                method="POST"
                                action="{{ route(
                                    'customer.bookings.simulate-pay',
                                    $booking
                                ) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="
                                        w-full
                                        bg-slate-900
                                        hover:bg-slate-800
                                        text-white
                                        font-bold
                                        py-3.5
                                        rounded-xl
                                        text-sm
                                        transition
                                    "
                                >
                                    Saya Sudah Bayar
                                </button>

                            </form>


                            <p class="
                                text-[11px]
                                text-gray-400
                                text-center
                                mt-3
                            ">
                                Mode simulasi pembayaran.
                            </p>

                        </div>

                    @endif

                @endrole


                {{-- ================================================= --}}
                {{-- CANCEL / EXTENSION --}}
                {{-- ================================================= --}}

                @role('customer')

                    @if (
                        in_array(
                            $booking->status,
                            ['pending', 'confirmed']
                        )
                    )

                        <div class="detail-card detail-card-padding">

                            <h3 class="
                                font-bold
                                text-slate-900
                                mb-2
                            ">
                                Kelola Booking
                            </h3>

                            <p class="
                                text-xs
                                text-gray-500
                                leading-relaxed
                                mb-4
                            ">

                                Anda dapat memperpanjang masa menginap
                                selama tanggal tambahan masih tersedia.

                                @if (
                                    $booking->payment_status === 'paid'
                                )

                                    Booking sudah dibayar dan dapat
                                    diperpanjang.

                                @else

                                    Booking harus sudah dibayar
                                    sebelum dapat diperpanjang.

                                @endif

                            </p>


                            {{-- PERPANJANG BOOKING --}}

                            @if ($booking->payment_status === 'paid')

                                @php
                                    $hasPendingExtension = $booking->extensions
                                        ->where('status', 'pending')
                                        ->isNotEmpty();
                                @endphp


                                @if ($hasPendingExtension)

                                    <div class="
                                        w-full
                                        rounded-xl
                                        bg-yellow-50
                                        border
                                        border-yellow-200
                                        px-4
                                        py-3
                                        text-sm
                                        text-yellow-700
                                        font-medium
                                        mb-3
                                    ">

                                        ⏳ Permintaan perpanjangan
                                        sedang menunggu konfirmasi mitra.

                                    </div>

                                @else

                                    <a
                                        href="{{ route(
                                            'customer.bookings.extension.create',
                                            $booking
                                        ) }}"
                                        class="
                                            block
                                            w-full
                                            bg-blue-600
                                            hover:bg-blue-700
                                            text-white
                                            font-bold
                                            py-3
                                            rounded-xl
                                            text-sm
                                            text-center
                                            transition
                                            mb-3
                                        "
                                    >

                                        ↗ Perpanjang Booking

                                    </a>

                                @endif

                            @endif


                            {{-- BATALKAN BOOKING --}}

                            <form
                                method="POST"
                                action="{{ route(
                                    'customer.bookings.cancel',
                                    $booking
                                ) }}"
                                onsubmit="return confirm(
                                    'Yakin ingin membatalkan booking ini?'
                                );"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="
                                        w-full
                                        border
                                        border-red-200
                                        text-red-600
                                        hover:bg-red-50
                                        font-bold
                                        py-3
                                        rounded-xl
                                        text-sm
                                        transition
                                    "
                                >

                                    Batalkan Booking

                                </button>

                            </form>

                        </div>

                    @endif

                @endrole


                {{-- ================================================= --}}
                {{-- MITRA --}}
                {{-- ================================================= --}}

                @role('mitra')

                    @if ($booking->status === 'pending')

                        <div class="detail-card detail-card-padding">

                            <h3 class="
                                font-bold
                                text-slate-900
                                mb-2
                            ">
                                Tindakan Booking
                            </h3>

                            <p class="
                                text-xs
                                text-gray-500
                                mb-5
                            ">
                                Periksa ketersediaan properti
                                sebelum menerima booking.
                            </p>


                            <form
                                method="POST"
                                action="{{ route(
                                    'mitra.bookings.confirm',
                                    $booking
                                ) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="
                                        w-full
                                        bg-green-600
                                        hover:bg-green-700
                                        text-white
                                        font-bold
                                        py-3
                                        rounded-xl
                                        text-sm
                                        transition
                                        mb-3
                                    "
                                >

                                    Konfirmasi Booking

                                </button>

                            </form>


                            <form
                                method="POST"
                                action="{{ route(
                                    'mitra.bookings.reject',
                                    $booking
                                ) }}"
                                onsubmit="return confirm(
                                    'Yakin ingin menolak booking ini?'
                                );"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="
                                        w-full
                                        border
                                        border-red-200
                                        text-red-600
                                        hover:bg-red-50
                                        font-bold
                                        py-3
                                        rounded-xl
                                        text-sm
                                        transition
                                    "
                                >

                                    Tolak Booking

                                </button>

                            </form>

                        </div>

                    @endif

                @endrole


                {{-- ================================================= --}}
                {{-- ADMIN REFUND --}}
                {{-- ================================================= --}}

                @role('admin|super_admin')

                    @if (
                        $booking->refund_status === 'pending'
                    )

                        <div class="
                            detail-card
                            detail-card-padding
                            border-orange-200
                        ">

                            <div class="flex items-start gap-3 mb-5">

                                <div class="
                                    w-10
                                    h-10
                                    rounded-xl
                                    bg-orange-100
                                    text-orange-600
                                    flex
                                    items-center
                                    justify-center
                                    shrink-0
                                ">
                                    ↻
                                </div>

                                <div>

                                    <h3 class="
                                        font-bold
                                        text-slate-900
                                    ">
                                        Refund Menunggu
                                    </h3>

                                    <p class="
                                        text-xs
                                        text-gray-500
                                        mt-1
                                    ">
                                        Booking ini membutuhkan
                                        proses refund.
                                    </p>

                                </div>

                            </div>


                            <div class="
                                rounded-xl
                                bg-slate-50
                                p-4
                                mb-4
                            ">

                                <p class="text-xs text-gray-400">
                                    Nominal Refund
                                </p>

                                <p class="
                                    text-xl
                                    font-extrabold
                                    text-slate-900
                                    mt-1
                                ">

                                    Rp {{ number_format(
                                        $booking->refund_amount
                                            ?? $booking->total_price,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </p>

                            </div>


                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.bookings.refund',
                                    $booking
                                ) }}"
                                onsubmit="return confirm(
                                    'Proses refund untuk booking ini?'
                                );"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="
                                        w-full
                                        bg-purple-600
                                        hover:bg-purple-700
                                        text-white
                                        font-bold
                                        py-3
                                        rounded-xl
                                        text-sm
                                        transition
                                    "
                                >

                                    Proses Refund

                                </button>

                            </form>

                        </div>

                    @endif

                @endrole


                {{-- ================================================= --}}
                {{-- PAYMENT INFO --}}
                {{-- ================================================= --}}

                <div class="
                    rounded-2xl
                    border
                    border-gray-200
                    bg-white
                    p-5
                ">

                    <div class="
                        flex
                        justify-between
                        text-xs
                        py-2
                    ">

                        <span class="text-gray-400">
                            Metode
                        </span>

                        <span class="
                            font-semibold
                            text-slate-900
                        ">
                            QRIS
                        </span>

                    </div>


                    @if ($booking->qris_transaction_id)

                        <div class="
                            flex
                            justify-between
                            gap-5
                            text-xs
                            py-2
                        ">

                            <span class="text-gray-400">
                                ID Transaksi
                            </span>

                            <span class="
                                font-mono
                                text-slate-900
                                text-right
                                break-all
                            ">
                                {{ $booking->qris_transaction_id }}
                            </span>

                        </div>

                    @endif


                    @if ($booking->refund_transaction_id)

                        <div class="
                            flex
                            justify-between
                            gap-5
                            text-xs
                            py-2
                        ">

                            <span class="text-gray-400">
                                ID Refund
                            </span>

                            <span class="
                                font-mono
                                text-purple-700
                                text-right
                                break-all
                            ">
                                {{ $booking->refund_transaction_id }}
                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection