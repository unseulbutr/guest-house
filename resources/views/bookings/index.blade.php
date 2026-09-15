@extends('layouts.app')

@section('title', 'Booking')

@section('content')

<style>

    .booking-page {
        min-height: 100vh;
        background:
            radial-gradient(
                circle at 10% 0%,
                rgba(37, 99, 235, .06),
                transparent 30%
            ),
            #f8fafc;
    }

    .booking-container {
        max-width: 1280px;
        margin: auto;
        padding: 35px 24px 70px;
    }

    .booking-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow:
            0 10px 35px rgba(15, 23, 42, .045);
    }

    .calendar-day {
        position: relative;
        min-height: 72px;
        border-radius: 14px;
        transition: background-color .18s ease,
                    transform .18s ease;
    }

    .calendar-day:hover {
        background: #f1f5f9;
    }

    .calendar-day.active {
        background: #0f172a;
        color: #fff;
    }

    .calendar-day.today:not(.active) {
        background: #eff6ff;
    }

    .calendar-day.other-month {
        opacity: .35;
    }

    .booking-dot {
        width: 5px;
        height: 5px;
        min-width: 5px;
        border-radius: 999px;
        background: #2563eb;
    }

    .calendar-day.active .booking-dot {
        background: #fff;
    }

    .booking-item {
        transition: all .18s ease;
    }

    .booking-item:hover {
        transform: translateY(-1px);
        box-shadow:
            0 8px 25px rgba(15, 23, 42, .06);
    }

</style>


<div class="booking-page">

    <div class="booking-container">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="
            flex
            flex-col
            md:flex-row
            md:items-end
            justify-between
            gap-5
            mb-8
        ">

            <div>

                <p class="
                    text-xs
                    font-bold
                    uppercase
                    tracking-widest
                    text-blue-600
                    mb-2
                ">
                    Reservation Management
                </p>

                <h1 class="
                    text-3xl
                    md:text-4xl
                    font-extrabold
                    text-slate-900
                    tracking-tight
                ">
                    Booking
                </h1>

                <p class="
                    text-sm
                    text-gray-500
                    mt-2
                ">
                    Kelola dan lihat seluruh reservasi berdasarkan tanggal.
                </p>

            </div>


            <div class="
                flex
                items-center
                gap-3
            ">

                <div class="
                    bg-white
                    border
                    border-gray-200
                    rounded-xl
                    px-4
                    py-2.5
                ">

                    <p class="
                        text-[10px]
                        uppercase
                        font-bold
                        tracking-wider
                        text-gray-400
                    ">
                        Booking bulan ini
                    </p>

                    <p class="
                        text-xl
                        font-extrabold
                        text-slate-900
                    ">
                        {{ $totalMonthlyBookings }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- SUCCESS MESSAGE --}}
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

                ✓ {{ session('success') }}

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- MAIN GRID --}}
        {{-- ================================================= --}}

        <div class="
            grid
            grid-cols-1
            xl:grid-cols-[440px_1fr]
            gap-6
        ">


            {{-- ================================================= --}}
            {{-- CALENDAR --}}
            {{-- ================================================= --}}

            <div class="
                booking-card
                p-5
                md:p-6
                h-fit
            ">

                {{-- CALENDAR HEADER --}}

                <div class="
                    flex
                    items-center
                    justify-between
                    mb-6
                ">

                    <div>

                        <h2 class="
                            text-xl
                            font-extrabold
                            text-slate-900
                        ">
                            {{ $month->translatedFormat('F Y') }}
                        </h2>

                        <p class="
                            text-xs
                            text-gray-400
                            mt-1
                        ">
                            Pilih tanggal untuk melihat booking
                        </p>

                    </div>


                    <div class="
                        flex
                        items-center
                        gap-2
                    ">

                        {{-- PREVIOUS MONTH --}}

                        <a
                            href="{{ route(
                                request()->route()->getName(),
                                [
                                    'month' =>
                                        $month->copy()
                                            ->subMonth()
                                            ->format('Y-m'),

                                    'date' =>
                                        $month->copy()
                                            ->subMonth()
                                            ->startOfMonth()
                                            ->format('Y-m-d')
                                ]
                            ) }}"
                            class="
                                w-9
                                h-9
                                rounded-xl
                                border
                                border-gray-200
                                flex
                                items-center
                                justify-center
                                text-gray-500
                                hover:bg-gray-50
                                transition
                            "
                        >
                            ←
                        </a>


                        {{-- TODAY --}}

                        <a
                            href="{{ route(
                                request()->route()->getName(),
                                [
                                    'month' => now()->format('Y-m'),
                                    'date' => now()->format('Y-m-d')
                                ]
                            ) }}"
                            class="
                                px-3
                                h-9
                                rounded-xl
                                border
                                border-gray-200
                                flex
                                items-center
                                justify-center
                                text-xs
                                font-bold
                                text-gray-600
                                hover:bg-gray-50
                                transition
                            "
                        >
                            Hari ini
                        </a>


                        {{-- NEXT MONTH --}}

                        <a
                            href="{{ route(
                                request()->route()->getName(),
                                [
                                    'month' =>
                                        $month->copy()
                                            ->addMonth()
                                            ->format('Y-m'),

                                    'date' =>
                                        $month->copy()
                                            ->addMonth()
                                            ->startOfMonth()
                                            ->format('Y-m-d')
                                ]
                            ) }}"
                            class="
                                w-9
                                h-9
                                rounded-xl
                                border
                                border-gray-200
                                flex
                                items-center
                                justify-center
                                text-gray-500
                                hover:bg-gray-50
                                transition
                            "
                        >
                            →
                        </a>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- WEEK DAYS --}}
                {{-- ================================================= --}}

                <div class="
                    grid
                    grid-cols-7
                    mb-2
                ">

                    @foreach (
                        [
                            'Sen',
                            'Sel',
                            'Rab',
                            'Kam',
                            'Jum',
                            'Sab',
                            'Min'
                        ] as $dayName
                    )

                        <div class="
                            text-center
                            text-[10px]
                            font-extrabold
                            uppercase
                            tracking-wider
                            text-gray-400
                            py-2
                        ">
                            {{ $dayName }}
                        </div>

                    @endforeach

                </div>


                {{-- ================================================= --}}
                {{-- CALENDAR DAYS --}}
                {{-- ================================================= --}}

                <div class="
                    grid
                    grid-cols-7
                    gap-1
                ">

                    @php
                        $currentDay = $calendarStart->copy();
                    @endphp


                    @while ($currentDay->lte($calendarEnd))

                        @php
                            /*
                            |--------------------------------------------------------------------------
                            | DATE KEY
                            |--------------------------------------------------------------------------
                            */

                            $dateKey = $currentDay->format('Y-m-d');


                            /*
                            |--------------------------------------------------------------------------
                            | BOOKING COUNT
                            |--------------------------------------------------------------------------
                            */

                            $count = (int) (
                                $bookingCounts[$dateKey] ?? 0
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | DATE STATE
                            |--------------------------------------------------------------------------
                            */

                            $isSelected = $selectedDate->isSameDay(
                                $currentDay
                            );

                            $isToday = now()->isSameDay(
                                $currentDay
                            );

                            $isCurrentMonth = $currentDay->isSameMonth(
                                $month
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | CLASS
                            |--------------------------------------------------------------------------
                            */

                            $dayClasses = 'calendar-day
                                flex
                                flex-col
                                items-center
                                justify-start
                                pt-2.5';


                            if ($isSelected) {
                                $dayClasses .= ' active';
                            }

                            if ($isToday && !$isSelected) {
                                $dayClasses .= ' today';
                            }

                            if (!$isCurrentMonth) {
                                $dayClasses .= ' other-month';
                            }
                        @endphp


                        <a
                            href="{{ route(
                                request()->route()->getName(),
                                [
                                    'month' => $month->format('Y-m'),
                                    'date' => $dateKey
                                ]
                            ) }}"
                            class="{{ $dayClasses }}"
                        >

                            {{-- NOMOR TANGGAL --}}

                            <span class="
                                w-8
                                h-8
                                rounded-full
                                flex
                                items-center
                                justify-center
                                text-sm
                                font-bold
                            ">
                                {{ $currentDay->day }}
                            </span>


                            {{-- BOOKING INDICATOR --}}

                            @if ($count > 0)

                                <div class="
                                    flex
                                    items-center
                                    justify-center
                                    gap-1
                                    mt-1
                                    min-h-[13px]
                                ">

                                    <span class="booking-dot"></span>

                                    <span class="
                                        text-[9px]
                                        font-bold
                                    ">
                                        {{ $count }}
                                    </span>

                                </div>

                            @else

                                {{-- Spacer agar semua tanggal punya tinggi indikator yang sama --}}

                                <div class="
                                    h-[13px]
                                    mt-1
                                "></div>

                            @endif

                        </a>


                        @php
                            $currentDay->addDay();
                        @endphp

                    @endwhile

                </div>


                {{-- ================================================= --}}
                {{-- LEGEND --}}
                {{-- ================================================= --}}

                <div class="
                    mt-6
                    pt-5
                    border-t
                    border-gray-100
                    flex
                    flex-wrap
                    gap-4
                    text-xs
                    text-gray-500
                ">

                    <div class="
                        flex
                        items-center
                        gap-2
                    ">

                        <span class="
                            w-2
                            h-2
                            rounded-full
                            bg-blue-600
                        "></span>

                        Ada booking

                    </div>


                    <div class="
                        flex
                        items-center
                        gap-2
                    ">

                        <span class="
                            w-2
                            h-2
                            rounded-full
                            bg-slate-900
                        "></span>

                        Tanggal dipilih

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- BOOKING LIST --}}
            {{-- ================================================= --}}

            <div class="space-y-5">


                {{-- SELECTED DATE HEADER --}}

                <div class="booking-card p-6">

                    <div class="
                        flex
                        flex-col
                        sm:flex-row
                        sm:items-center
                        justify-between
                        gap-4
                    ">

                        <div>

                            <p class="
                                text-xs
                                font-bold
                                uppercase
                                tracking-widest
                                text-gray-400
                                mb-2
                            ">
                                Booking pada
                            </p>

                            <h2 class="
                                text-2xl
                                md:text-3xl
                                font-extrabold
                                text-slate-900
                            ">
                                {{ $selectedDate->translatedFormat('d F Y') }}
                            </h2>

                            <p class="
                                text-sm
                                text-gray-500
                                mt-1
                            ">
                                {{ $selectedDate->translatedFormat('l') }}
                            </p>

                        </div>


                        <div class="
                            shrink-0
                            rounded-2xl
                            bg-slate-900
                            text-white
                            px-5
                            py-3
                        ">

                            <p class="
                                text-[10px]
                                uppercase
                                tracking-wider
                                text-slate-400
                                font-bold
                            ">
                                Reservasi
                            </p>

                            <p class="
                                text-2xl
                                font-extrabold
                            ">
                                {{ $selectedBookings->count() }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- EMPTY --}}
                {{-- ================================================= --}}

                @if ($selectedBookings->isEmpty())

                    <div class="
                        booking-card
                        p-10
                        text-center
                    ">

                        <div class="
                            w-16
                            h-16
                            mx-auto
                            rounded-2xl
                            bg-slate-50
                            flex
                            items-center
                            justify-center
                            text-2xl
                            mb-5
                        ">
                            📅
                        </div>

                        <h3 class="
                            text-lg
                            font-extrabold
                            text-slate-900
                        ">
                            Tidak ada booking
                        </h3>

                        <p class="
                            text-sm
                            text-gray-500
                            mt-2
                            max-w-sm
                            mx-auto
                        ">
                            Tidak ada reservasi aktif pada tanggal
                            {{ $selectedDate->translatedFormat('d F Y') }}.
                        </p>

                    </div>

                @else

                    {{-- ================================================= --}}
                    {{-- BOOKING ITEMS --}}
                    {{-- ================================================= --}}

                    @foreach ($selectedBookings as $booking)

                        <a
                            href="{{ route(
                                auth()->user()->hasRole('customer')
                                    ? 'customer.bookings.show'
                                    : (
                                        auth()->user()->hasRole('mitra')
                                            ? 'mitra.bookings.show'
                                            : 'admin.bookings.show'
                                    ),
                                $booking
                            ) }}"
                            class="
                                booking-card
                                booking-item
                                block
                                p-5
                            "
                        >

                            <div class="
                                flex
                                flex-col
                                md:flex-row
                                md:items-center
                                gap-5
                            ">


                                {{-- PROPERTY IMAGE --}}

                                <div class="
                                    w-full
                                    md:w-28
                                    h-24
                                    rounded-2xl
                                    overflow-hidden
                                    bg-slate-100
                                    shrink-0
                                ">

                                    @if (
                                        $booking->property
                                        && $booking->property->cover_image
                                    )

                                        <img
                                            src="{{ asset(
                                                'storage/' .
                                                $booking->property->cover_image
                                            ) }}"
                                            alt="{{ $booking->property->name }}"
                                            class="
                                                w-full
                                                h-full
                                                object-cover
                                            "
                                        >

                                    @else

                                        <div class="
                                            w-full
                                            h-full
                                            flex
                                            items-center
                                            justify-center
                                            text-2xl
                                            font-extrabold
                                            text-gray-300
                                        ">
                                            {{ strtoupper(
                                                substr(
                                                    $booking->property->name ?? 'P',
                                                    0,
                                                    1
                                                )
                                            ) }}
                                        </div>

                                    @endif

                                </div>


                                {{-- CONTENT --}}

                                <div class="flex-1 min-w-0">

                                    <div class="
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-2
                                        mb-2
                                    ">

                                        @include(
                                            'bookings.partials.status-badge',
                                            [
                                                'status' =>
                                                    $booking->status
                                            ]
                                        )


                                        @include(
                                            'bookings.partials.payment-badge',
                                            [
                                                'status' =>
                                                    $booking->payment_status
                                            ]
                                        )


                                        @if (
                                            $booking->refund_status === 'pending'
                                        )

                                            <span class="
                                                inline-flex
                                                items-center
                                                gap-1
                                                rounded-full
                                                bg-orange-50
                                                text-orange-700
                                                border
                                                border-orange-100
                                                px-3
                                                py-1.5
                                                text-xs
                                                font-bold
                                            ">
                                                ↻ Refund
                                            </span>

                                        @elseif (
                                            $booking->refund_status === 'completed'
                                        )

                                            <span class="
                                                inline-flex
                                                items-center
                                                gap-1
                                                rounded-full
                                                bg-purple-50
                                                text-purple-700
                                                border
                                                border-purple-100
                                                px-3
                                                py-1.5
                                                text-xs
                                                font-bold
                                            ">
                                                ✓ Refunded
                                            </span>

                                        @endif

                                    </div>


                                    <h3 class="
                                        text-lg
                                        font-extrabold
                                        text-slate-900
                                        truncate
                                    ">
                                        {{ $booking->property->name }}
                                    </h3>


                                    <p class="
                                        text-xs
                                        text-gray-400
                                        font-mono
                                        mt-1
                                    ">
                                        {{ $booking->booking_code }}
                                    </p>


                                    <div class="
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-x-5
                                        gap-y-2
                                        mt-4
                                        text-xs
                                        text-gray-500
                                    ">

                                        <span>
                                            📅
                                            {{ $booking->check_in->format('d M Y') }}
                                            →
                                            {{ $booking->check_out->format('d M Y') }}
                                        </span>

                                        <span>
                                            👤
                                            {{ $booking->guest_count }}
                                            tamu
                                        </span>

                                        <span>
                                            🌙
                                            {{ $booking->check_in->diffInDays(
                                                $booking->check_out
                                            ) }}
                                            malam
                                        </span>

                                    </div>


                                    {{-- CUSTOMER INFO FOR MITRA / ADMIN --}}

                                    @if (
                                        auth()->user()->hasRole('mitra')
                                        ||
                                        auth()->user()->hasRole('admin')
                                        ||
                                        auth()->user()->hasRole('super_admin')
                                    )

                                        <div class="
                                            mt-3
                                            text-xs
                                            text-gray-500
                                        ">

                                            Customer:

                                            <span class="
                                                font-semibold
                                                text-slate-700
                                            ">
                                                {{ $booking->customer->name ?? '-' }}
                                            </span>

                                        </div>

                                    @endif

                                </div>


                                {{-- PRICE --}}

                                <div class="
                                    md:text-right
                                    shrink-0
                                ">

                                    <p class="
                                        text-[10px]
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                        font-bold
                                    ">
                                        Total
                                    </p>

                                    <p class="
                                        text-xl
                                        font-extrabold
                                        text-slate-900
                                        mt-1
                                    ">
                                        Rp {{ number_format(
                                            $booking->total_price,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </p>


                                    <span class="
                                        inline-flex
                                        items-center
                                        gap-1
                                        text-xs
                                        font-semibold
                                        text-blue-600
                                        mt-3
                                    ">
                                        Lihat detail →
                                    </span>

                                </div>

                            </div>

                        </a>

                    @endforeach

                @endif

            </div>

        </div>

    </div>

</div>

@endsection