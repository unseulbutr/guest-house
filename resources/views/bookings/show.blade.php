@extends('layouts.app')

@section('title', 'Booking ' . $booking->booking_code)

@section('content')

@php
    $user = auth()->user();

    $isCustomer = $user && $user->hasRole('customer');
    $isMitra = $user && $user->hasRole('mitra');
    $isAdmin = $user && ($user->hasRole('admin') || $user->hasRole('super_admin'));

    /*
    |--------------------------------------------------------------------------
    | EXTENSION
    |--------------------------------------------------------------------------
    */
    $extensions = $booking->extensions ?? collect();

    $latestExtension = $extensions
        ->sortByDesc('created_at')
        ->first();

    $pendingExtension = null;
    $approvedExtension = null;
    $paidExtension = null;

    if ($latestExtension) {
        if ($latestExtension->status === 'pending') {
            $pendingExtension = $latestExtension;
        } elseif ($latestExtension->status === 'approved') {
            if ($latestExtension->payment_status === 'paid') {
                $paidExtension = $latestExtension;
            } else {
                $approvedExtension = $latestExtension;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REVIEW
    |--------------------------------------------------------------------------
    */
    $canReview =
        $isCustomer
        && (int) $booking->customer_id === (int) auth()->id()
        && $booking->payment_status === 'paid'
        && $booking->check_out
        && !$booking->check_out->isFuture();

    /*
    |--------------------------------------------------------------------------
    | ROUTE
    |--------------------------------------------------------------------------
    */
    $bookingIndexRoute = $isMitra
        ? 'mitra.bookings.index'
        : ($isAdmin ? 'admin.bookings.index' : 'customer.bookings.index');

    /*
    |--------------------------------------------------------------------------
    | DP & PELUNASAN
    |--------------------------------------------------------------------------
    */
    $dpAmount = $booking->dp_amount !== null
        ? (float) $booking->dp_amount
        : round((float) ($booking->total_price ?? 0) * 0.5, 2);

    $remainingAmount = $booking->remaining_amount !== null
        ? (float) $booking->remaining_amount
        : max((float) ($booking->total_price ?? 0) - $dpAmount, 0);

    $isDpPaid = $booking->payment_status === 'dp_paid';
    $isFullyPaid = $booking->payment_status === 'paid';

    $settlementDeadline = $booking->settlement_deadline
        ? \Carbon\Carbon::parse($booking->settlement_deadline)
        : ($booking->check_in
            ? \Carbon\Carbon::parse($booking->check_in)->subDay()->endOfDay()
            : null);

    $settlementStart = $booking->check_in
        ? \Carbon\Carbon::parse($booking->check_in)->subDay()->startOfDay()
        : null;

    $settlementIsOpen = method_exists($booking, 'settlementIsOpen')
        ? $booking->settlementIsOpen()
        : false;

    $settlementBeforeH1 = $settlementStart
        && now()->lt($settlementStart);

    $settlementExpired = $settlementDeadline
        && now()->gt($settlementDeadline);

    $transactionId = $booking->qris_transaction_id
        ?? $booking->transaction_id
        ?? null;
@endphp


<style>
    /* =========================================================
       LUXURY BOOKING PAGE
    ========================================================= */

    .luxury-booking-page {
        min-height: 100vh;
        background:
            radial-gradient(
                circle at 15% 0%,
                rgba(176, 141, 87, .08),
                transparent 30%
            ),
            radial-gradient(
                circle at 100% 15%,
                rgba(15, 23, 42, .05),
                transparent 30%
            ),
            #f7f5f0;
        color: #18181b;
    }

    .luxury-container {
        width: min(1180px, calc(100% - 32px));
        margin: 0 auto;
        padding: 38px 0 80px;
    }

    /* =========================================================
       TYPOGRAPHY
    ========================================================= */

    .luxury-eyebrow {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2.2px;
        text-transform: uppercase;
        color: #a17d45;
    }

    .luxury-heading {
        color: #18181b;
        font-weight: 800;
        letter-spacing: -.035em;
    }

    .luxury-muted {
        color: #71717a;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .luxury-card {
        background: rgba(255, 255, 255, .94);
        border: 1px solid rgba(24, 24, 27, .08);
        border-radius: 28px;
        box-shadow:
            0 25px 70px rgba(24, 24, 27, .055),
            0 4px 18px rgba(24, 24, 27, .025);
    }

    .luxury-card-padding {
        padding: 30px;
    }

    /* =========================================================
       HERO
    ========================================================= */

    .luxury-hero {
        position: relative;
        overflow: hidden;
        min-height: 470px;
        border-radius: 30px;
        background: #18181b;
        box-shadow:
            0 30px 80px rgba(24, 24, 27, .13);
    }

    .luxury-hero-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .luxury-hero-overlay {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(
                180deg,
                rgba(0,0,0,.12) 0%,
                rgba(0,0,0,.20) 40%,
                rgba(0,0,0,.78) 100%
            );
    }

    .luxury-hero-content {
        position: relative;
        z-index: 2;
        min-height: 470px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 38px;
    }

    .luxury-booking-code {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 8px 13px;
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 999px;
        background: rgba(0,0,0,.20);
        backdrop-filter: blur(12px);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.4px;
    }

    .luxury-hero-title {
        margin-top: 16px;
        color: white;
        font-size: clamp(30px, 5vw, 52px);
        line-height: 1.02;
        font-weight: 800;
        letter-spacing: -.045em;
    }

    .luxury-hero-meta {
        margin-top: 14px;
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
        color: rgba(255,255,255,.82);
        font-size: 13px;
    }

    .luxury-hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .luxury-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        backdrop-filter: blur(12px);
    }

    .luxury-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* =========================================================
       SECTION
    ========================================================= */

    .luxury-section-title {
        color: #18181b;
        font-size: 19px;
        font-weight: 800;
        letter-spacing: -.025em;
    }

    .luxury-section-subtitle {
        margin-top: 5px;
        color: #71717a;
        font-size: 13px;
        line-height: 1.6;
    }

    .luxury-divider {
        height: 1px;
        background: #e8e4dc;
    }

    /* =========================================================
       STAY INFO
    ========================================================= */

    .stay-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        margin-top: 25px;
        border-top: 1px solid #e8e4dc;
        border-bottom: 1px solid #e8e4dc;
    }

    .stay-item {
        padding: 22px 18px;
        border-right: 1px solid #e8e4dc;
    }

    .stay-item:first-child {
        padding-left: 0;
    }

    .stay-item:last-child {
        border-right: 0;
    }

    .stay-label {
        color: #a1a1aa;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .stay-date {
        margin-top: 7px;
        color: #18181b;
        font-size: 18px;
        font-weight: 800;
    }

    .stay-time {
        margin-top: 3px;
        color: #71717a;
        font-size: 12px;
    }

    /* =========================================================
       PROPERTY
    ========================================================= */

    .property-mini-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 20px;
    }

    .property-placeholder {
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background: #eeece7;
        color: #a1a1aa;
        font-size: 12px;
    }

    .gold-line {
        width: 40px;
        height: 2px;
        margin: 14px 0;
        background: #b08d57;
    }

    /* =========================================================
       REVIEW
    ========================================================= */

    .review-display {
        display: flex;
        gap: 20px;
        padding: 24px;
        border-radius: 22px;
        background: #faf9f6;
        border: 1px solid #e8e4dc;
    }

    .review-number {
        min-width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background: #18181b;
        color: #d8b77a;
        font-size: 23px;
        font-weight: 800;
    }

    .review-stars {
        display: flex;
        gap: 3px;
        margin-top: 8px;
    }

    .review-stars span {
        color: #d8d4cc;
        font-size: 22px;
        line-height: 1;
    }

    .review-stars span.active {
        color: #b08d57;
    }

    /* =========================================================
       CLICKABLE STAR RATING
    ========================================================= */

    .star-rating-wrapper {
        margin-top: 18px;
        padding: 22px;
        border-radius: 20px;
        background: white;
        border: 1px solid #e8e4dc;
    }

    .star-rating {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-top: 10px;
    }

    .star-button {
        width: 48px;
        height: 48px;
        padding: 0;
        border: 0;
        background: transparent;
        color: #d4d0c8;
        font-size: 38px;
        line-height: 1;
        cursor: pointer;
        transition:
            transform .18s ease,
            color .18s ease;
    }

    .star-button:hover {
        transform: scale(1.12);
    }

    .star-button.active,
    .star-button.preview {
        color: #b08d57;
    }

    .star-button:focus-visible {
        outline: 2px solid #b08d57;
        outline-offset: 4px;
        border-radius: 8px;
    }

    .rating-description {
        min-height: 20px;
        margin-top: 9px;
        color: #a17d45;
        font-size: 12px;
        font-weight: 800;
    }

    .rating-score-text {
        margin-left: 8px;
        color: #71717a;
        font-size: 12px;
        font-weight: 700;
    }

    .review-form {
        padding: 25px;
        border-radius: 22px;
        background: #faf9f6;
        border: 1px solid #e8e4dc;
    }

    /* =========================================================
       TEXTAREA
    ========================================================= */

    .luxury-textarea {
        width: 100%;
        resize: vertical;
        border: 1px solid #ddd8cf;
        border-radius: 18px;
        background: white;
        padding: 14px 16px;
        color: #27272a;
        font-size: 13px;
        outline: none;
        transition: .2s;
    }

    .luxury-textarea:focus {
        border-color: #b08d57;
        box-shadow: 0 0 0 3px rgba(176,141,87,.10);
    }

    /* =========================================================
       PRICE
    ========================================================= */

    .price-card {
        background: #18181b;
        color: white;
        border-radius: 26px;
        padding: 27px;
        box-shadow: 0 25px 60px rgba(24,24,27,.15);
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,.09);
    }

    .price-row:last-child {
        border-bottom: 0;
    }

    .price-total {
        margin-top: 18px;
        padding-top: 18px;
        border-top: 1px solid rgba(255,255,255,.15);
    }

    .price-total-value {
        margin-top: 5px;
        color: #d8b77a;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -.035em;
    }

    /* =========================================================
       PAYMENT
    ========================================================= */

    .payment-card {
        border-radius: 24px;
        background: #fff;
        border: 1px solid #e8e4dc;
        padding: 25px;
    }

    .qris-container {
        margin-top: 20px;
        padding: 18px;
        border-radius: 20px;
        background: #faf9f6;
        text-align: center;
    }

    .qris-image {
        width: 210px;
        max-width: 100%;
        margin: 0 auto;
        padding: 8px;
        border-radius: 14px;
        background: white;
    }

    /* =========================================================
       BUTTON
    ========================================================= */

    .luxury-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 13px 18px;
        border-radius: 13px;
        background: #18181b;
        color: white;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .2px;
        transition: .2s;
    }

    .luxury-button:hover {
        background: #29292d;
        transform: translateY(-1px);
    }

    .gold-button {
        background: #b08d57;
        color: white;
    }

    .gold-button:hover {
        background: #987541;
    }

    .danger-button {
        background: #fff5f5;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .danger-button:hover {
        background: #fee2e2;
    }

    /* =========================================================
       INFO
    ========================================================= */

    .luxury-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding: 13px 0;
        border-bottom: 1px solid #eeeae3;
    }

    .luxury-info-row:last-child {
        border-bottom: 0;
    }

    .luxury-info-label {
        color: #a1a1aa;
        font-size: 11px;
    }

    .luxury-info-value {
        max-width: 60%;
        text-align: right;
        color: #27272a;
        font-size: 12px;
        font-weight: 800;
    }

    /* =========================================================
       EXTENSION
    ========================================================= */

    .extension-box {
        padding: 23px;
        border-radius: 22px;
    }

    .extension-pending {
        background: #fffbeb;
        border: 1px solid #f3dfab;
    }

    .extension-approved {
        background: #f5f8fc;
        border: 1px solid #dbe5f0;
    }

    .extension-paid {
        background: #f5faf6;
        border: 1px solid #d5ead9;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .luxury-container {
            width: min(100% - 20px, 1180px);
            padding-top: 20px;
        }

        .luxury-hero,
        .luxury-hero-content {
            min-height: 390px;
        }

        .luxury-hero-content {
            padding: 25px;
        }

        .luxury-card-padding {
            padding: 21px;
        }

        .stay-grid {
            grid-template-columns: 1fr;
        }

        .stay-item {
            padding: 17px 0;
            border-right: 0;
            border-bottom: 1px solid #e8e4dc;
        }

        .stay-item:last-child {
            border-bottom: 0;
        }

        .review-display {
            flex-direction: column;
        }

        .star-rating {
            gap: 2px;
        }

        .star-button {
            width: 42px;
            height: 42px;
            font-size: 32px;
        }
    }
</style>


<div class="luxury-booking-page">

    <div class="luxury-container">

        {{-- =========================================================
             BREADCRUMB
        ========================================================== --}}

        <div class="mb-6 flex flex-wrap items-center gap-2 text-xs">

            <a
                href="{{ route('dashboard') }}"
                class="font-bold text-zinc-400 transition hover:text-zinc-800"
            >
                Dashboard
            </a>

            <span class="text-zinc-300">/</span>

            <a
                href="{{ route($bookingIndexRoute) }}"
                class="font-bold text-zinc-400 transition hover:text-zinc-800"
            >
                Booking
            </a>

            <span class="text-zinc-300">/</span>

            <span class="font-bold text-zinc-800">
                {{ $booking->booking_code }}
            </span>

        </div>


        {{-- =========================================================
             ALERTS
        ========================================================== --}}

        @if(session('success'))

            <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>

        @endif


        @if(session('error'))

            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>

        @endif


        @if($errors->any())

            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

                <div class="mb-2 font-bold">
                    Terdapat kesalahan:
                </div>

                <ul class="list-disc space-y-1 pl-5">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
             HERO
        ========================================================== --}}

        <div class="luxury-hero mb-7">

            @if(optional($booking->property)->cover_image)

                <img
                    src="{{ asset('storage/' . $booking->property->cover_image) }}"
                    alt="{{ $booking->property->name ?? 'Guest House' }}"
                    class="luxury-hero-image"
                >

            @else

                <div class="luxury-hero-image bg-zinc-800"></div>

            @endif

            <div class="luxury-hero-overlay"></div>

            <div class="luxury-hero-content">

                <div class="flex flex-wrap gap-2">

                    <span class="luxury-booking-code">
                        BOOKING {{ $booking->booking_code }}
                    </span>

                    @include('bookings.partials.status-badge', [
                        'status' => $booking->status
                    ])

                    @include('bookings.partials.payment-badge', [
                        'status' => $booking->payment_status
                    ])

                </div>


                <h1 class="luxury-hero-title">
                    {{ $booking->property->name ?? 'Guest House' }}
                </h1>


                <div class="luxury-hero-meta">

                    @if(optional($booking->property)->location)

                        <span>
                            📍 {{ $booking->property->location }}
                        </span>

                    @endif


                    @if(optional($booking->property)->type)

                        <span>
                            · {{ ucfirst($booking->property->type) }}
                        </span>

                    @endif


                    @if(optional($booking->property)->city)

                        <span>
                            · {{ $booking->property->city }}
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- =========================================================
             MAIN GRID
        ========================================================== --}}

        <div class="grid grid-cols-1 gap-7 lg:grid-cols-3">


            {{-- =====================================================
                 LEFT
            ====================================================== --}}

            <div class="space-y-7 lg:col-span-2">


                {{-- =================================================
                     RESERVATION
                ================================================== --}}

                <div class="luxury-card luxury-card-padding">

                    <div class="luxury-eyebrow">
                        Your Reservation
                    </div>

                    <div class="mt-2 luxury-section-title">
                        Detail Masa Menginap
                    </div>

                    <div class="luxury-section-subtitle">
                        Jadwal reservasi dan informasi waktu menginap kamu.
                    </div>


                    <div class="stay-grid">

                        <div class="stay-item">

                            <div class="stay-label">
                                Check In
                            </div>

                            <div class="stay-date">
                                {{ optional($booking->check_in)->format('d M Y') ?? '-' }}
                            </div>

                            <div class="stay-time">
                                {{ $booking->check_in ? $booking->check_in->format('H:i') : '-' }}
                            </div>

                        </div>


                        <div class="stay-item">

                            <div class="stay-label">
                                Check Out
                            </div>

                            <div class="stay-date">
                                {{ optional($booking->check_out)->format('d M Y') ?? '-' }}
                            </div>

                            <div class="stay-time">
                                {{ $booking->check_out ? $booking->check_out->format('H:i') : '-' }}
                            </div>

                        </div>


                        <div class="stay-item">

                            <div class="stay-label">
                                Booking Dibuat
                            </div>

                            <div class="stay-date">
                                {{ optional($booking->created_at)->format('d M Y') ?? '-' }}
                            </div>

                            <div class="stay-time">
                                {{ optional($booking->created_at)->format('H:i') ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PROPERTY
                ================================================== --}}

                <div class="luxury-card luxury-card-padding">

                    <div class="luxury-eyebrow">
                        Your Destination
                    </div>

                    <div class="mt-2 luxury-section-title">
                        Properti Pilihan
                    </div>


                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div>

                            @if(optional($booking->property)->cover_image)

                                <img
                                    src="{{ asset('storage/' . $booking->property->cover_image) }}"
                                    alt="{{ $booking->property->name ?? 'Property' }}"
                                    class="property-mini-image"
                                >

                            @else

                                <div class="property-placeholder">
                                    Foto properti belum tersedia
                                </div>

                            @endif

                        </div>


                        <div class="flex flex-col justify-center">

                            <div class="luxury-eyebrow">
                                {{ $booking->property->type ?? 'Guest House' }}
                            </div>

                            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-zinc-900">
                                {{ $booking->property->name ?? '-' }}
                            </h2>

                            <div class="gold-line"></div>

                            <div class="text-sm leading-7 text-zinc-500">

                                @if(optional($booking->property)->location)

                                    📍 {{ $booking->property->location }}

                                @endif

                                @if(optional($booking->property)->city)

                                    <br>
                                    {{ $booking->property->city }}

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     REVIEW
                ================================================== --}}

                @if($isCustomer)

                    <div class="luxury-card luxury-card-padding">

                        <div class="luxury-eyebrow">
                            Your Experience
                        </div>

                        <div class="mt-2 luxury-section-title">
                            Rating & Ulasan
                        </div>

                        <div class="luxury-section-subtitle">
                            Bagikan pengalaman menginap kamu.
                        </div>


                        {{-- =================================================
                             EXISTING REVIEW
                        ================================================== --}}

                        @if($booking->review)

                            @php
                                $existingScore = (int) ($booking->review->score ?? 0);

                                /*
                                 * Backend tetap menggunakan score 1-10.
                                 * Tampilan dikonversi menjadi 5 bintang.
                                 */
                                $existingStars = (int) ceil($existingScore / 2);
                            @endphp


                            <div class="review-display mt-6">

                                <div class="review-number">
                                    {{ $existingScore }}
                                </div>

                                <div class="flex-1">

                                    <div class="text-xs font-bold uppercase tracking-widest text-zinc-400">
                                        Your Rating
                                    </div>


                                    <div class="mt-1 text-lg font-extrabold text-zinc-900">
                                        {{ $existingScore }}/10
                                    </div>


                                    <div class="review-stars">

                                        @for($star = 1; $star <= 5; $star++)

                                            <span class="{{ $star <= $existingStars ? 'active' : '' }}">
                                                ★
                                            </span>

                                        @endfor

                                    </div>


                                    @if($booking->review->comment)

                                        <div class="mt-4 border-l-2 border-[#b08d57] pl-4 text-sm leading-7 text-zinc-600">
                                            "{{ $booking->review->comment }}"
                                        </div>

                                    @endif


                                    <div class="mt-4 text-xs text-zinc-400">
                                        Terima kasih telah memberikan ulasan.
                                    </div>

                                </div>

                            </div>


                        {{-- =================================================
                             REVIEW FORM
                        ================================================== --}}

                        @elseif($canReview)

                            @php
                                /*
                                 * Jika sebelumnya ada old score dari validation,
                                 * konversi score 1-10 menjadi bintang 1-5.
                                 */
                                $oldScore = old('score');
                                $oldStar = $oldScore
                                    ? (int) ceil(((int) $oldScore) / 2)
                                    : 0;
                            @endphp


                            <form
                                action="{{ route('customer.bookings.review.store', $booking) }}"
                                method="POST"
                                class="review-form mt-6"
                                id="review-form-{{ $booking->id }}"
                            >

                                @csrf


                                {{-- Hidden score untuk backend --}}
                                <input
                                    type="hidden"
                                    name="score"
                                    id="rating-score-{{ $booking->id }}"
                                    value="{{ $oldScore ?? '' }}"
                                    required
                                >


                                {{-- STAR SELECTOR --}}

                                <div class="star-rating-wrapper">

                                    <label class="block text-sm font-bold text-zinc-800">
                                        Bagaimana pengalaman kamu?
                                    </label>


                                    <div
                                        class="star-rating"
                                        id="star-rating-{{ $booking->id }}"
                                        data-selected="{{ $oldStar }}"
                                    >

                                        @for($star = 1; $star <= 5; $star++)

                                            <button
                                                type="button"
                                                class="star-button {{ $star <= $oldStar ? 'active' : '' }}"
                                                data-star="{{ $star }}"
                                                aria-label="Beri {{ $star }} bintang"
                                                aria-pressed="{{ $star == $oldStar ? 'true' : 'false' }}"
                                            >
                                                ★
                                            </button>

                                        @endfor


                                        <span
                                            class="rating-score-text"
                                            id="rating-score-text-{{ $booking->id }}"
                                        >
                                            @if($oldStar)
                                                {{ $oldStar }}/5
                                            @else
                                                Pilih rating
                                            @endif
                                        </span>

                                    </div>


                                    <div
                                        class="rating-description"
                                        id="rating-description-{{ $booking->id }}"
                                    >
                                        @if($oldStar == 1)
                                            Sangat buruk
                                        @elseif($oldStar == 2)
                                            Kurang
                                        @elseif($oldStar == 3)
                                            Cukup
                                        @elseif($oldStar == 4)
                                            Bagus
                                        @elseif($oldStar == 5)
                                            Sangat bagus
                                        @endif
                                    </div>

                                </div>


                                {{-- COMMENT --}}

                                <div class="mb-5 mt-5">

                                    <label
                                        for="comment-{{ $booking->id }}"
                                        class="mb-2 block text-sm font-bold text-zinc-800"
                                    >
                                        Ceritakan pengalaman kamu
                                    </label>


                                    <textarea
                                        id="comment-{{ $booking->id }}"
                                        name="comment"
                                        rows="5"
                                        class="luxury-textarea"
                                        placeholder="Tulis pengalaman kamu selama menginap..."
                                    >{{ old('comment') }}</textarea>

                                </div>


                                <button
                                    type="submit"
                                    class="luxury-button gold-button"
                                    id="submit-review-{{ $booking->id }}"
                                >
                                    <span>★</span>
                                    Kirim Rating & Ulasan
                                </button>

                            </form>


                        @else

                            <div class="mt-6 rounded-2xl border border-zinc-200 bg-[#faf9f6] p-5">

                                <div class="font-bold text-zinc-800">
                                    Rating belum tersedia
                                </div>

                                <p class="mt-1 text-sm leading-6 text-zinc-500">
                                    Rating dapat diberikan setelah pembayaran selesai
                                    dan masa menginap telah berakhir.
                                </p>

                            </div>

                        @endif

                    </div>

                @endif


                {{-- =================================================
                     EXTENSION
                ================================================== --}}

                @if($isCustomer)

                    <div class="luxury-card luxury-card-padding">

                        <div class="luxury-eyebrow">
                            Extend Your Stay
                        </div>

                        <div class="mt-2 luxury-section-title">
                            Perpanjangan Menginap
                        </div>

                        <div class="luxury-section-subtitle">
                            Tambahkan waktu menginap tanpa membuat booking baru.
                        </div>


                        {{-- PENDING --}}

                        @if($pendingExtension)

                            @php
                                $pendingNewCheckout = $pendingExtension->new_check_out
                                    ? \Carbon\Carbon::parse($pendingExtension->new_check_out)
                                    : null;
                            @endphp


                            <div class="extension-box extension-pending mt-6">

                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                                    <div>

                                        <div class="font-extrabold text-amber-900">
                                            Menunggu Persetujuan
                                        </div>

                                        <p class="mt-1 text-sm text-amber-800">
                                            Pengajuan kamu sedang diperiksa oleh mitra.
                                        </p>

                                    </div>

                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                        PENDING
                                    </span>

                                </div>


                                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                    <div class="rounded-xl bg-white/70 p-4">

                                        <div class="luxury-eyebrow">
                                            Check Out Baru
                                        </div>

                                        <div class="mt-1 font-bold text-zinc-900">
                                            {{ $pendingNewCheckout?->format('d M Y H:i') ?? '-' }}
                                        </div>

                                    </div>


                                    <div class="rounded-xl bg-white/70 p-4">

                                        <div class="luxury-eyebrow">
                                            Tambahan
                                        </div>

                                        <div class="mt-1 font-bold text-zinc-900">
                                            {{ $pendingExtension->additional_nights ?? 0 }} malam
                                        </div>

                                    </div>

                                </div>

                            </div>


                        {{-- APPROVED --}}

                        @elseif($approvedExtension)

                            @php
                                $oldCheckout = $approvedExtension->old_check_out
                                    ? \Carbon\Carbon::parse($approvedExtension->old_check_out)
                                    : null;

                                $newCheckout = $approvedExtension->new_check_out
                                    ? \Carbon\Carbon::parse($approvedExtension->new_check_out)
                                    : null;

                                $paymentDeadline = $approvedExtension->payment_deadline
                                    ? \Carbon\Carbon::parse($approvedExtension->payment_deadline)
                                    : null;

                                $deadlineExpired = $paymentDeadline
                                    ? now()->greaterThan($paymentDeadline)
                                    : false;
                            @endphp


                            <div class="extension-box extension-approved mt-6">

                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                                    <div>

                                        <div class="font-extrabold text-zinc-900">
                                            Perpanjangan Disetujui
                                        </div>

                                        <p class="mt-1 text-sm text-zinc-600">
                                            Pembayaran diperlukan untuk mengaktifkan perpanjangan.
                                        </p>

                                    </div>

                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-zinc-700">
                                        WAITING PAYMENT
                                    </span>

                                </div>


                                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                    <div class="rounded-xl bg-white/70 p-4">

                                        <div class="luxury-eyebrow">
                                            Check Out Lama
                                        </div>

                                        <div class="mt-1 font-bold text-zinc-900">
                                            {{ $oldCheckout?->format('d M Y') ?? '-' }}
                                        </div>

                                    </div>


                                    <div class="rounded-xl bg-white/70 p-4">

                                        <div class="luxury-eyebrow">
                                            Check Out Baru
                                        </div>

                                        <div class="mt-1 font-bold text-zinc-900">
                                            {{ $newCheckout?->format('d M Y') ?? '-' }}
                                        </div>

                                    </div>


                                    <div class="rounded-xl bg-white/70 p-4">

                                        <div class="luxury-eyebrow">
                                            Tambahan
                                        </div>

                                        <div class="mt-1 font-bold text-zinc-900">
                                            {{ $approvedExtension->additional_nights ?? 0 }} malam
                                        </div>

                                    </div>


                                    <div class="rounded-xl bg-white/70 p-4">

                                        <div class="luxury-eyebrow">
                                            Biaya
                                        </div>

                                        <div class="mt-1 font-bold text-[#a17d45]">
                                            Rp {{ number_format($approvedExtension->amount ?? 0, 0, ',', '.') }}
                                        </div>

                                    </div>

                                </div>


                                @if($paymentDeadline)

                                    <div class="mt-4 rounded-xl bg-white p-4">

                                        <div class="luxury-eyebrow">
                                            Payment Deadline
                                        </div>

                                        <div class="mt-1 font-bold {{ $deadlineExpired ? 'text-red-600' : 'text-zinc-900' }}">
                                            {{ $paymentDeadline->format('d M Y, H:i') }}
                                        </div>

                                    </div>

                                @endif


                                @if(!$deadlineExpired)

                                    <div class="qris-container">

                                        <div class="mb-3 text-xs font-bold uppercase tracking-widest text-zinc-500">
                                            Scan To Pay
                                        </div>


                                        @if(file_exists(public_path('images/qris.png')))

                                            <img
                                                src="{{ asset('images/qris.png') }}"
                                                alt="QRIS"
                                                class="qris-image"
                                            >

                                        @else

                                            <div class="mx-auto flex h-44 w-44 items-center justify-center rounded-xl border border-zinc-200 bg-white text-4xl text-zinc-300">
                                                ▦
                                            </div>

                                        @endif

                                    </div>


                                    <form
                                        action="{{ route('customer.booking-extensions.simulate-pay', $approvedExtension) }}"
                                        method="POST"
                                        class="mt-5"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="luxury-button gold-button"
                                        >
                                            Bayar Perpanjangan
                                        </button>

                                    </form>

                                @endif

                            </div>


                        {{-- PAID --}}

                        @elseif($paidExtension)

                            @php
                                $paidOldCheckout = $paidExtension->old_check_out
                                    ? \Carbon\Carbon::parse($paidExtension->old_check_out)
                                    : null;

                                $paidNewCheckout = $paidExtension->new_check_out
                                    ? \Carbon\Carbon::parse($paidExtension->new_check_out)
                                    : null;
                            @endphp


                            <div class="extension-box extension-paid mt-6">

                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                                    <div>

                                        <div class="font-extrabold text-green-900">
                                            Perpanjangan Berhasil
                                        </div>

                                        <p class="mt-1 text-sm text-green-800">
                                            Masa menginap kamu telah diperpanjang.
                                        </p>

                                    </div>

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                        PAID
                                    </span>

                                </div>


                                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                    <div class="rounded-xl bg-white/70 p-4">

                                        <div class="luxury-eyebrow">
                                            Check Out Lama
                                        </div>

                                        <div class="mt-1 font-bold text-zinc-900">
                                            {{ $paidOldCheckout?->format('d M Y') ?? '-' }}
                                        </div>

                                    </div>


                                    <div class="rounded-xl bg-white/70 p-4">

                                        <div class="luxury-eyebrow">
                                            Check Out Baru
                                        </div>

                                        <div class="mt-1 font-bold text-zinc-900">
                                            {{ $paidNewCheckout?->format('d M Y') ?? '-' }}
                                        </div>

                                    </div>


                                    <div class="rounded-xl bg-white/70 p-4">

                                        <div class="luxury-eyebrow">
                                            Total
                                        </div>

                                        <div class="mt-1 font-bold text-green-700">
                                            Rp {{ number_format($paidExtension->amount ?? 0, 0, ',', '.') }}
                                        </div>

                                    </div>


                                    @if($paidExtension->qris_transaction_id)

                                        <div class="rounded-xl bg-white/70 p-4">

                                            <div class="luxury-eyebrow">
                                                Transaction ID
                                            </div>

                                            <div class="mt-1 break-all text-xs font-bold text-zinc-900">
                                                {{ $paidExtension->qris_transaction_id }}
                                            </div>

                                        </div>

                                    @endif

                                </div>

                            </div>


                        {{-- NONE --}}

                        @else

                            @if($booking->payment_status === 'paid')

                                <div class="mt-6 rounded-2xl border border-zinc-200 bg-[#faf9f6] p-6">

                                    <div class="luxury-eyebrow">
                                        Stay Longer
                                    </div>

                                    <div class="mt-2 text-lg font-extrabold text-zinc-900">
                                        Ingin memperpanjang masa menginap?
                                    </div>

                                    <p class="mt-1 text-sm leading-6 text-zinc-500">
                                        Ajukan tambahan malam kepada mitra.
                                    </p>

                                    <a
                                        href="{{ route('customer.booking-extensions.create', $booking) }}"
                                        class="luxury-button gold-button mt-5"
                                    >
                                        Ajukan Perpanjangan
                                    </a>

                                </div>

                            @else

                                <div class="mt-6 rounded-2xl border border-zinc-200 bg-[#faf9f6] p-5 text-sm text-zinc-500">
                                    Perpanjangan tersedia setelah pembayaran booking selesai.
                                </div>

                            @endif

                        @endif


                        {{-- CANCEL --}}

                        @if(
                            $booking->status !== 'cancelled'
                            && $booking->status !== 'completed'
                            && $booking->payment_status !== 'paid'
                        )

                            <div class="mt-6 border-t border-zinc-200 pt-6">

                                @if($isDpPaid)
                                    <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs leading-5 text-amber-800">
                                        Jika booking dibatalkan setelah DP dibayar, refund DP mengikuti ketentuan pembatalan. Biaya pembatalan sebesar 20% dari DP akan dipotong dari refund.
                                    </div>
                                @endif

                                <form
                                    action="{{ route('customer.bookings.cancel', $booking) }}"
                                    method="POST"
                                    id="cancelBookingForm"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="button"
                                        class="luxury-button danger-button"
                                        onclick="openCancelBookingModal()"
                                    >
                                        Batalkan Booking
                                    </button>

                                </form>

                            </div>

                        @endif

                    </div>

                @endif


                {{-- =================================================
                     MITRA
                ================================================== --}}

                @if($isMitra && $booking->status === 'pending')

                    <div class="luxury-card luxury-card-padding">

                        <div class="luxury-eyebrow">
                            Host Management
                        </div>

                        <div class="mt-2 luxury-section-title">
                            Aksi Booking
                        </div>

                        <div class="luxury-section-subtitle">
                            Kelola permintaan reservasi customer.
                        </div>


                        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">

                            <form
                                action="{{ route('mitra.bookings.confirm', $booking) }}"
                                method="POST"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="luxury-button"
                                >
                                    Konfirmasi Booking
                                </button>

                            </form>


                            <form
                                action="{{ route('mitra.bookings.reject', $booking) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menolak booking ini?')"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="luxury-button danger-button"
                                >
                                    Tolak Booking
                                </button>

                            </form>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                 RIGHT SIDEBAR
            ====================================================== --}}

            <div class="space-y-7">


                {{-- =================================================
                     PRICE
                ================================================== --}}

                <div class="price-card">

                    <div class="luxury-eyebrow" style="color:#d8b77a;">
                        Reservation Summary
                    </div>

                    <div class="mt-2 text-lg font-extrabold">
                        Ringkasan Harga
                    </div>


                    <div class="mt-5">

                        <div class="price-row">

                            <span class="text-xs text-zinc-400">
                                Accommodation
                            </span>

                            <span class="text-xs font-bold">
                                Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                            </span>

                        </div>


                        <div class="price-row">

                            <span class="text-xs text-zinc-400">
                                DP 50%
                            </span>

                            <span class="text-xs font-bold">
                                Rp {{ number_format($dpAmount, 0, ',', '.') }}
                            </span>

                        </div>


                        <div class="price-row">

                            <span class="text-xs text-zinc-400">
                                Sisa Pembayaran
                            </span>

                            <span class="text-xs font-bold">
                                Rp {{ number_format($remainingAmount, 0, ',', '.') }}
                            </span>

                        </div>


                        @if($isMitra)

                            <div class="price-row">

                                <span class="text-xs text-zinc-400">
                                    Pendapatan Mitra
                                </span>

                                <span class="text-xs font-bold text-green-400">
                                    Rp {{ number_format($booking->mitra_payout ?? 0, 0, ',', '.') }}
                                </span>

                            </div>

                        @endif


                        @if($isAdmin)

                            <div class="price-row">

                                <span class="text-xs text-zinc-400">
                                    Commission
                                </span>

                                <span class="text-xs font-bold text-[#d8b77a]">
                                    Rp {{ number_format($booking->commission ?? 0, 0, ',', '.') }}
                                </span>

                            </div>

                        @endif


                        <div class="price-total">

                            <div class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">
                                Total Amount
                            </div>

                            <div class="price-total-value">
                                Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PAYMENT
                ================================================== --}}

                @if($isCustomer)

                    <div class="payment-card">

                        <div class="luxury-eyebrow">
                            Payment
                        </div>

                        <div class="mt-2 luxury-section-title">
                            Pembayaran Booking
                        </div>

                        <div class="luxury-section-subtitle">
                            Booking menggunakan sistem pembayaran 50% DP dan 50% pelunasan.
                        </div>

                        {{-- RINGKASAN DP --}}
                        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">

                            <div class="rounded-2xl border border-zinc-200 bg-[#faf9f6] p-4">

                                <div class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">
                                    DP 50%
                                </div>

                                <div class="mt-1 text-lg font-extrabold text-zinc-900">
                                    Rp {{ number_format($dpAmount, 0, ',', '.') }}
                                </div>

                                @if($booking->dp_paid_at)
                                    <div class="mt-1 text-xs text-green-600">
                                        Dibayar {{ $booking->dp_paid_at->format('d M Y, H:i') }}
                                    </div>
                                @endif

                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-[#faf9f6] p-4">

                                <div class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">
                                    Sisa Pembayaran
                                </div>

                                <div class="mt-1 text-lg font-extrabold text-zinc-900">
                                    Rp {{ number_format($remainingAmount, 0, ',', '.') }}
                                </div>

                                @if($isFullyPaid)
                                    <div class="mt-1 text-xs text-green-600">
                                        Sudah lunas
                                    </div>
                                @elseif($isDpPaid)
                                    <div class="mt-1 text-xs text-amber-600">
                                        Menunggu pelunasan
                                    </div>
                                @endif

                            </div>

                        </div>


                        {{-- BELUM BAYAR DP --}}
                        @if($booking->payment_status === 'pending')

                            <div class="qris-container">

                                <div class="mb-2 text-xs font-bold uppercase tracking-widest text-zinc-500">
                                    Bayar DP 50% via QRIS
                                </div>

                                <p class="mb-4 text-xs leading-5 text-zinc-500">
                                    Scan QRIS di bawah untuk pembayaran uang muka booking.
                                </p>

                                @if(file_exists(public_path('images/qris.png')))

                                    <img
                                        src="{{ asset('images/qris.png') }}"
                                        alt="QRIS"
                                        class="qris-image"
                                    >

                                @else

                                    <div class="mx-auto flex h-44 w-44 items-center justify-center rounded-xl border border-zinc-200 bg-white text-4xl text-zinc-300">
                                        ▦
                                    </div>

                                @endif

                            </div>

                            <form
                                action="{{ route('customer.bookings.simulate-pay', $booking) }}"
                                method="POST"
                                class="mt-5"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="luxury-button gold-button"
                                >
                                    Simulasikan Pembayaran DP 50%
                                </button>

                            </form>

                            <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs leading-5 text-amber-800">
                                Setelah DP dibayar, booking akan masuk tahap konfirmasi. Untuk booking yang dikelola mitra, konfirmasi dilakukan oleh Mitra.
                            </div>


                        {{-- DP SUDAH DIBAYAR --}}
                        @elseif($isDpPaid)

                            <div class="mt-5 rounded-2xl border border-blue-200 bg-blue-50 p-5">

                                <div class="flex items-start gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                        ✓
                                    </div>

                                    <div>

                                        <div class="font-extrabold text-blue-900">
                                            DP 50% Berhasil Dibayar
                                        </div>

                                        <div class="mt-1 text-xs leading-5 text-blue-700">
                                            Booking sudah menerima pembayaran DP sebesar
                                            <strong>Rp {{ number_format($dpAmount, 0, ',', '.') }}</strong>.
                                        </div>

                                    </div>

                                </div>

                            </div>

                            @if($booking->status === 'pending')

                                <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-5">

                                    <div class="font-extrabold text-amber-900">
                                        Menunggu Konfirmasi Mitra
                                    </div>

                                    <p class="mt-1 text-xs leading-5 text-amber-800">
                                        DP sudah diterima. Pelunasan dapat dilakukan setelah booking dikonfirmasi.
                                    </p>

                                </div>

                            @elseif($booking->status === 'confirmed')

                                @if($settlementIsOpen)

                                    <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                                        <div class="font-extrabold text-emerald-900">
                                            Pelunasan Sudah Dibuka
                                        </div>

                                        <p class="mt-1 text-xs leading-5 text-emerald-800">
                                            Silakan selesaikan sisa pembayaran sebesar
                                            <strong>Rp {{ number_format($remainingAmount, 0, ',', '.') }}</strong>
                                            sebelum check-in.
                                        </p>

                                        @if($settlementDeadline)
                                            <div class="mt-3 text-xs font-bold text-emerald-900">
                                                Batas pelunasan:
                                                {{ $settlementDeadline->format('d M Y, H:i') }}
                                            </div>
                                        @endif

                                    </div>

                                    <div class="qris-container mt-4">

                                        <div class="mb-2 text-xs font-bold uppercase tracking-widest text-zinc-500">
                                            Bayar Pelunasan via QRIS
                                        </div>

                                        <p class="mb-4 text-xs leading-5 text-zinc-500">
                                            Scan QRIS untuk membayar sisa 50% pembayaran booking.
                                        </p>

                                        @if(file_exists(public_path('images/qris.png')))

                                            <img
                                                src="{{ asset('images/qris.png') }}"
                                                alt="QRIS Pelunasan"
                                                class="qris-image"
                                            >

                                        @else

                                            <div class="mx-auto flex h-44 w-44 items-center justify-center rounded-xl border border-zinc-200 bg-white text-4xl text-zinc-300">
                                                ▦
                                            </div>

                                        @endif

                                    </div>

                                    <form
                                        action="{{ route('customer.bookings.simulate-settlement', $booking) }}"
                                        method="POST"
                                        class="mt-5"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="luxury-button gold-button"
                                        >
                                            Simulasikan Pelunasan 50%
                                        </button>

                                    </form>

                                @elseif($settlementBeforeH1)

                                    <div class="mt-5 rounded-2xl border border-blue-200 bg-blue-50 p-5">

                                        <div class="font-extrabold text-blue-900">
                                            Booking Sudah Dikonfirmasi
                                        </div>

                                        <p class="mt-1 text-xs leading-5 text-blue-800">
                                            Pelunasan dapat dilakukan mulai H-1 sebelum check-in dan wajib diselesaikan sebelum check-in.
                                        </p>

                                        @if($settlementStart)
                                            <div class="mt-3 text-xs font-bold text-blue-900">
                                                Pelunasan dibuka:
                                                {{ $settlementStart->format('d M Y, H:i') }}
                                            </div>
                                        @endif

                                    </div>

                                @elseif($settlementExpired)

                                    <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 p-5">

                                        <div class="font-extrabold text-red-900">
                                            Batas Pelunasan Terlewati
                                        </div>

                                        <p class="mt-1 text-xs leading-5 text-red-800">
                                            Pembayaran pelunasan belum diterima sampai batas waktu yang ditentukan. Booking dapat diproses sesuai aturan pembatalan dan refund.
                                        </p>

                                        @if($settlementDeadline)
                                            <div class="mt-3 text-xs font-bold text-red-900">
                                                Batas pelunasan:
                                                {{ $settlementDeadline->format('d M Y, H:i') }}
                                            </div>
                                        @endif

                                    </div>

                                @endif

                            @endif


                        {{-- SUDAH LUNAS --}}
                        @elseif($isFullyPaid)

                            <div class="mt-5 rounded-2xl bg-green-50 p-5">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-700">
                                        ✓
                                    </div>

                                    <div>

                                        <div class="font-extrabold text-green-800">
                                            Payment Complete
                                        </div>

                                        <div class="mt-1 text-xs text-green-700">
                                            Pembayaran booking telah lunas. Sisa pembayaran Rp 0.
                                        </div>

                                        @if($booking->settlement_paid_at)
                                            <div class="mt-1 text-xs text-green-700">
                                                Dilunasi {{ $booking->settlement_paid_at->format('d M Y, H:i') }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                @endif


                {{-- =================================================
                     PAYMENT INFO
                ================================================== --}}

                <div class="luxury-card luxury-card-padding">

                    <div class="luxury-eyebrow">
                        Transaction
                    </div>

                    <div class="mt-2 luxury-section-title">
                        Informasi Pembayaran
                    </div>


                    <div class="mt-5">

                        <div class="luxury-info-row">

                            <span class="luxury-info-label">
                                Metode
                            </span>

                            <span class="luxury-info-value">
                                {{ strtoupper($booking->payment_method ?? 'QRIS') }}
                            </span>

                        </div>


                        <div class="luxury-info-row">

                            <span class="luxury-info-label">
                                Payment Status
                            </span>

                            <span class="luxury-info-value">
                                {{ ucfirst($booking->payment_status ?? '-') }}
                            </span>

                        </div>


                        @if($transactionId)

                            <div class="luxury-info-row">

                                <span class="luxury-info-label">
                                    Transaction ID
                                </span>

                                <span class="luxury-info-value break-all">
                                    {{ $transactionId }}
                                </span>

                            </div>

                        @endif


                        @if($booking->cancellation_fee_amount !== null)

                            <div class="luxury-info-row">

                                <span class="luxury-info-label">
                                    Biaya Pembatalan
                                </span>

                                <span class="luxury-info-value">
                                    Rp {{ number_format($booking->cancellation_fee_amount ?? 0, 0, ',', '.') }}
                                </span>

                            </div>

                        @endif


                        @if($booking->refund_amount !== null)

                            <div class="luxury-info-row">

                                <span class="luxury-info-label">
                                    Jumlah Refund
                                </span>

                                <span class="luxury-info-value text-green-700">
                                    Rp {{ number_format($booking->refund_amount ?? 0, 0, ',', '.') }}
                                </span>

                            </div>

                        @endif


                        @if($booking->terms_accepted_at)

                            <div class="luxury-info-row">

                                <span class="luxury-info-label">
                                    Syarat Booking
                                </span>

                                <span class="luxury-info-value text-green-700">
                                    Disetujui
                                </span>

                            </div>

                        @endif


                        @if($booking->refund_transaction_id)

                            <div class="luxury-info-row">

                                <span class="luxury-info-label">
                                    Refund ID
                                </span>

                                <span class="luxury-info-value break-all">
                                    {{ $booking->refund_transaction_id }}
                                </span>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                     REFUND
                ================================================== --}}

                @if($isAdmin && $booking->refund_status === 'pending')

                    <div class="luxury-card luxury-card-padding">

                        <div class="luxury-eyebrow">
                            Administration
                        </div>

                        <div class="mt-2 luxury-section-title">
                            Proses Refund
                        </div>

                        <div class="mt-2 text-sm leading-6 text-zinc-500">
                            Refund booking ini sedang menunggu proses admin.
                        </div>


                        <form
                            action="{{ route('admin.bookings.refund', $booking) }}"
                            method="POST"
                            class="mt-5"
                            onsubmit="return confirm('Yakin ingin memproses refund booking ini?')"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="luxury-button"
                            >
                                Proses Refund
                            </button>

                        </form>

                    </div>

                @endif

            </div>

        </div>


        {{-- =========================================================
             FOOTER
        ========================================================== --}}

        <div class="mt-10 text-center">

            <div class="luxury-eyebrow">
                Thank You For Staying With Us
            </div>

            <p class="mt-2 text-xs text-zinc-400">

                Simpan kode booking

                <span class="font-bold text-zinc-600">
                    {{ $booking->booking_code }}
                </span>

                untuk kebutuhan reservasi kamu.

            </p>

        </div>

    </div>

</div>


{{-- =========================================================
     STAR RATING JAVASCRIPT
========================================================= --}}

@if($isCustomer && $canReview && !$booking->review)

<script>
document.addEventListener('DOMContentLoaded', function () {

    const bookingId = @json($booking->id);

    const ratingContainer = document.getElementById(
        'star-rating-' + bookingId
    );

    const scoreInput = document.getElementById(
        'rating-score-' + bookingId
    );

    const scoreText = document.getElementById(
        'rating-score-text-' + bookingId
    );

    const description = document.getElementById(
        'rating-description-' + bookingId
    );

    const form = document.getElementById(
        'review-form-' + bookingId
    );

    if (!ratingContainer || !scoreInput) {
        return;
    }

    const stars = ratingContainer.querySelectorAll('.star-button');

    const descriptions = {
        1: 'Sangat buruk',
        2: 'Kurang',
        3: 'Cukup',
        4: 'Bagus',
        5: 'Sangat bagus'
    };

    let selectedStar = parseInt(
        ratingContainer.dataset.selected || '0',
        10
    );

    function updateStars(value, preview = false) {

        stars.forEach(function (star) {

            const starNumber = parseInt(
                star.dataset.star,
                10
            );

            star.classList.remove('active');
            star.classList.remove('preview');

            if (starNumber <= value) {

                if (preview) {
                    star.classList.add('preview');
                } else {
                    star.classList.add('active');
                }

            }

            star.setAttribute(
                'aria-pressed',
                starNumber === selectedStar
                    ? 'true'
                    : 'false'
            );

        });

    }


    function updateText(value) {

        if (!value) {

            scoreText.textContent = 'Pilih rating';

            description.textContent = '';

            return;
        }

        scoreText.textContent = value + '/5';

        description.textContent =
            descriptions[value] || '';

    }


    /*
     * Klik bintang
     *
     * Tampilan = 1-5 bintang
     * Backend = score 2,4,6,8,10
     */
    stars.forEach(function (star) {

        star.addEventListener('click', function () {

            selectedStar = parseInt(
                this.dataset.star,
                10
            );

            const backendScore = selectedStar * 2;

            scoreInput.value = backendScore;

            ratingContainer.dataset.selected = selectedStar;

            updateStars(selectedStar);

            updateText(selectedStar);

        });


        star.addEventListener('mouseenter', function () {

            const previewValue = parseInt(
                this.dataset.star,
                10
            );

            updateStars(previewValue, true);

        });

    });


    ratingContainer.addEventListener(
        'mouseleave',
        function () {

            updateStars(selectedStar);

        }
    );


    /*
     * Pastikan user memilih bintang
     * sebelum form dikirim.
     */
    if (form) {

        form.addEventListener('submit', function (event) {

            if (!scoreInput.value) {

                event.preventDefault();

                alert('Silakan pilih rating bintang terlebih dahulu.');

                return false;
            }

        });

    }


    /*
     * Initial state
     */
    if (selectedStar > 0) {

        scoreInput.value = selectedStar * 2;

        updateStars(selectedStar);

        updateText(selectedStar);

    } else {

        updateStars(0);

        updateText(0);

    }

});
</script>

@endif

{{-- =========================================================
     CANCEL BOOKING MODAL
========================================================== --}}

@if($isCustomer && $booking->status !== 'cancelled' && $booking->status !== 'completed' && $booking->payment_status !== 'paid')

    @php
        $cancelDpAmount = (float) (
            $booking->dp_amount
            ?? ($booking->total_price * 0.50)
        );

        $cancelCheckIn = $booking->check_in
            ? \Carbon\Carbon::parse($booking->check_in)->startOfDay()
            : null;

        $cancelToday = now()->startOfDay();

        $cancelDaysBeforeCheckIn = $cancelCheckIn
            ? $cancelToday->diffInDays($cancelCheckIn, false)
            : null;

        if ($booking->payment_status === 'dp_paid' && $cancelDaysBeforeCheckIn !== null) {
            if ($cancelDaysBeforeCheckIn >= 7) {
                $cancelPercentage = 0;
            } elseif ($cancelDaysBeforeCheckIn >= 3) {
                $cancelPercentage = 5;
            } elseif ($cancelDaysBeforeCheckIn === 2) {
                $cancelPercentage = 10;
            } elseif ($cancelDaysBeforeCheckIn === 1) {
                $cancelPercentage = 15;
            } else {
                $cancelPercentage = 20;
            }

            $cancelFee = round(
                $cancelDpAmount * ($cancelPercentage / 100),
                2
            );

            $cancelRefund = round(
                $cancelDpAmount - $cancelFee,
                2
            );
        } else {
            $cancelPercentage = 0;
            $cancelFee = 0;
            $cancelRefund = 0;
        }
    @endphp

    <div
        id="cancelBookingModal"
        class="cancel-booking-overlay"
        aria-hidden="true"
    >

        <div
            class="cancel-booking-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="cancelBookingTitle"
        >

            <button
                type="button"
                class="cancel-booking-close"
                onclick="closeCancelBookingModal()"
                aria-label="Tutup"
            >
                &times;
            </button>

            <div class="cancel-booking-icon">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>
                    <path d="M10.3 3.7 2.9 16.5A2 2 0 0 0 4.6 19.5h14.8a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"/>
                </svg>
            </div>

            <div class="cancel-booking-eyebrow">
                Cancellation
            </div>

            <h3
                id="cancelBookingTitle"
                class="cancel-booking-title"
            >
                Batalkan Booking?
            </h3>

            <p class="cancel-booking-description">
                Anda yakin ingin membatalkan booking ini?
                Periksa rincian potongan dan refund sebelum melanjutkan.
            </p>

            @if($booking->payment_status === 'dp_paid')

                <div class="cancel-booking-summary">

                    <div class="cancel-booking-row">
                        <span>DP yang dibayarkan</span>
                        <strong>
                            Rp {{ number_format($cancelDpAmount, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="cancel-booking-row">
                        <span>Potongan pembatalan</span>
                        <strong class="cancel-booking-danger">
                            {{ $cancelPercentage }}%
                        </strong>
                    </div>

                    <div class="cancel-booking-row">
                        <span>Jumlah potongan</span>
                        <strong class="cancel-booking-danger">
                            Rp {{ number_format($cancelFee, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="cancel-booking-divider"></div>

                    <div class="cancel-booking-row cancel-booking-refund-row">
                        <span>Estimasi refund</span>
                        <strong>
                            Rp {{ number_format($cancelRefund, 0, ',', '.') }}
                        </strong>
                    </div>

                </div>

                <div class="cancel-booking-note">
                    <span>ⓘ</span>
                    <div>
                        Besaran potongan mengikuti kebijakan pembatalan
                        berdasarkan jarak waktu dengan tanggal check-in.
                    </div>
                </div>

            @else

                <div class="cancel-booking-summary cancel-booking-no-payment">
                    <div class="cancel-booking-row">
                        <span>Status pembayaran</span>
                        <strong>Belum dibayar</strong>
                    </div>

                    <div class="cancel-booking-divider"></div>

                    <div class="cancel-booking-no-payment-text">
                        Booking dapat dibatalkan tanpa potongan karena DP
                        belum dibayarkan.
                    </div>
                </div>

            @endif

            <div class="cancel-booking-actions">

                <button
                    type="button"
                    class="cancel-booking-button cancel-booking-button-secondary"
                    onclick="closeCancelBookingModal()"
                >
                    Batal
                </button>

                <button
                    type="button"
                    class="cancel-booking-button cancel-booking-button-danger"
                    onclick="confirmCancelBooking()"
                >
                    OK, Batalkan
                </button>

            </div>

        </div>

    </div>

    <style>
        .cancel-booking-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, .58);
            backdrop-filter: blur(7px);
            -webkit-backdrop-filter: blur(7px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .22s ease, visibility .22s ease;
        }

        .cancel-booking-overlay.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .cancel-booking-modal {
            position: relative;
            width: min(100%, 460px);
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            padding: 30px;
            border: 1px solid rgba(24, 24, 27, .08);
            border-radius: 28px;
            background: rgba(255, 255, 255, .98);
            box-shadow: 0 30px 80px rgba(15, 23, 42, .25);
            transform: translateY(18px) scale(.96);
            transition: transform .25s cubic-bezier(.22, 1, .36, 1);
        }

        .cancel-booking-overlay.is-open .cancel-booking-modal {
            transform: translateY(0) scale(1);
        }

        .cancel-booking-close {
            position: absolute;
            top: 14px;
            right: 16px;
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 50%;
            background: #f4f4f5;
            color: #71717a;
            font-size: 25px;
            line-height: 1;
            cursor: pointer;
            transition: .2s ease;
        }

        .cancel-booking-close:hover {
            background: #e4e4e7;
            color: #18181b;
            transform: rotate(5deg);
        }

        .cancel-booking-icon {
            display: flex;
            width: 58px;
            height: 58px;
            margin: 0 auto 15px;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: #fff1f2;
            color: #dc2626;
            box-shadow: inset 0 0 0 1px rgba(220, 38, 38, .08);
        }

        .cancel-booking-icon svg {
            width: 29px;
            height: 29px;
        }

        .cancel-booking-eyebrow {
            text-align: center;
            color: #a17d45;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .cancel-booking-title {
            margin-top: 7px;
            text-align: center;
            color: #18181b;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .cancel-booking-description {
            margin: 8px auto 0;
            max-width: 370px;
            text-align: center;
            color: #71717a;
            font-size: 13px;
            line-height: 1.7;
        }

        .cancel-booking-summary {
            margin-top: 22px;
            padding: 17px;
            border: 1px solid #e4e4e7;
            border-radius: 18px;
            background: #fafafa;
        }

        .cancel-booking-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            color: #71717a;
            font-size: 13px;
            line-height: 1.6;
        }

        .cancel-booking-row + .cancel-booking-row {
            margin-top: 10px;
        }

        .cancel-booking-row strong {
            color: #18181b;
            font-weight: 800;
            text-align: right;
        }

        .cancel-booking-danger {
            color: #dc2626 !important;
        }

        .cancel-booking-divider {
            height: 1px;
            margin: 15px 0;
            background: #e4e4e7;
        }

        .cancel-booking-refund-row span {
            color: #3f3f46;
            font-weight: 700;
        }

        .cancel-booking-refund-row strong {
            color: #059669;
            font-size: 15px;
        }

        .cancel-booking-note {
            display: flex;
            gap: 9px;
            margin-top: 12px;
            padding: 11px 13px;
            border-radius: 13px;
            background: #fffbeb;
            color: #92400e;
            font-size: 11px;
            line-height: 1.6;
        }

        .cancel-booking-note > span {
            flex: 0 0 auto;
            font-weight: 800;
        }

        .cancel-booking-no-payment {
            background: #f8fafc;
        }

        .cancel-booking-no-payment-text {
            color: #52525b;
            font-size: 12px;
            line-height: 1.7;
        }

        .cancel-booking-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }

        .cancel-booking-button {
            min-height: 46px;
            border: 0;
            border-radius: 14px;
            padding: 11px 15px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .cancel-booking-button:hover {
            transform: translateY(-1px);
        }

        .cancel-booking-button-secondary {
            border: 1px solid #e4e4e7;
            background: #fff;
            color: #3f3f46;
        }

        .cancel-booking-button-secondary:hover {
            background: #f4f4f5;
        }

        .cancel-booking-button-danger {
            background: #dc2626;
            color: #fff;
            box-shadow: 0 10px 22px rgba(220, 38, 38, .18);
        }

        .cancel-booking-button-danger:hover {
            background: #b91c1c;
            box-shadow: 0 13px 28px rgba(220, 38, 38, .24);
        }

        body.cancel-booking-lock {
            overflow: hidden;
        }

        @media (max-width: 480px) {
            .cancel-booking-modal {
                padding: 25px 20px 20px;
                border-radius: 24px;
            }

            .cancel-booking-title {
                font-size: 21px;
            }

            .cancel-booking-actions {
                grid-template-columns: 1fr;
            }

            .cancel-booking-button-danger {
                order: -1;
            }
        }
    </style>

    <script>
        function openCancelBookingModal() {
            const modal = document.getElementById('cancelBookingModal');

            if (!modal) return;

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('cancel-booking-lock');
        }

        function closeCancelBookingModal() {
            const modal = document.getElementById('cancelBookingModal');

            if (!modal) return;

            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('cancel-booking-lock');
        }

        function confirmCancelBooking() {
            const form = document.getElementById('cancelBookingForm');

            if (!form) return;

            const button = form.querySelector('.danger-button');

            if (button) {
                button.disabled = true;
                button.style.opacity = '0.6';
                button.style.pointerEvents = 'none';
            }

            form.submit();
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeCancelBookingModal();
            }
        });

        document.getElementById('cancelBookingModal')?.addEventListener('click', function (event) {
            if (event.target === this) {
                closeCancelBookingModal();
            }
        });
    </script>

@endif

@endsection