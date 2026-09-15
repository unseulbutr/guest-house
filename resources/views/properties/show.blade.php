@extends('layouts.app')

@section('title', $property->name)

@section('meta_description', \Illuminate\Support\Str::limit(
    strip_tags($property->description),
    155
))

@section('content')

{{-- =========================================================
    STRUCTURED DATA SEO
========================================================= --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "LodgingBusiness",
    "name": {!! json_encode($property->name) !!},
    "description": {!! json_encode($property->description ?: $property->name) !!},
    "address": {
        "@@type": "PostalAddress",
        "streetAddress": {!! json_encode($property->address) !!},
        "addressLocality": {!! json_encode($property->city) !!},
        "addressCountry": "ID"
    }
    @if ($property->latitude && $property->longitude)
    ,
    "geo": {
        "@@type": "GeoCoordinates",
        "latitude": {{ $property->latitude }},
        "longitude": {{ $property->longitude }}
    }
    @endif
    @if ($property->cover_image)
    ,
    "image": {!! json_encode(asset('storage/' . $property->cover_image)) !!}
    @endif
    ,
    "priceRange": "Rp {{ number_format($property->price_per_night, 0, ',', '.') }}",
    "url": {!! json_encode(route('properties.show', $property)) !!}
}
</script>


@php

    /*
    |--------------------------------------------------------------------------
    | DATA RATING
    |--------------------------------------------------------------------------
    */

    $starRating = max(
        1,
        min(
            5,
            (int) ($property->star_rating ?? 1)
        )
    );

    $starLabels = [
        1 => 'Sederhana',
        2 => 'Standar',
        3 => 'Nyaman',
        4 => 'Premium',
        5 => 'Mewah',
    ];

    $starLabel = $starLabels[$starRating];


    /*
    |--------------------------------------------------------------------------
    | GALLERY
    |--------------------------------------------------------------------------
    */

    $allPhotos = collect();

    if ($property->cover_image) {
        $allPhotos->push($property->cover_image);
    }

    if ($property->relationLoaded('images')) {
        $allPhotos = $allPhotos->merge(
            $property->images->pluck('path')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FASILITAS TERSEDIA
    |--------------------------------------------------------------------------
    */

    $availableFacilities = $property->facilities
        ->filter(function ($facility) {
            return $facility->pivot->is_available;
        });

@endphp


<div class="bg-gray-50/60 min-h-screen">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">


        {{-- =====================================================
            BREADCRUMB
        ====================================================== --}}
        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-5">

            <a
                href="{{ route('home') }}"
                class="text-brand-blue hover:underline"
            >
                Beranda
            </a>

            <span>/</span>

            <span>{{ $property->city }}</span>

            <span>/</span>

            <span class="text-gray-700 font-medium">
                {{ $property->name }}
            </span>

        </div>


        {{-- =====================================================
            HERO SECTION
        ====================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-6">


            {{-- =================================================
                FOTO
            ================================================= --}}
            <div class="lg:col-span-5">

                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">

                    @if ($allPhotos->isNotEmpty())

                        <div class="relative h-72 md:h-[390px]">

                            <button
                                type="button"
                                onclick="openLightbox(0)"
                                class="w-full h-full block relative group"
                            >

                                <img
                                    src="{{ asset('storage/' . $allPhotos[0]) }}"
                                    alt="{{ $property->name }}"
                                    class="w-full h-full object-cover group-hover:scale-[1.02] transition duration-300"
                                >

                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>


                                {{-- TIPE --}}
                                <span class="absolute top-4 left-4 bg-white/95 text-brand-blue text-xs font-bold uppercase px-3 py-1.5 rounded-full shadow-sm">
                                    {{ $property->type === 'kost_harian'
                                        ? 'Kost Harian'
                                        : ($property->type === 'villa'
                                            ? 'Villa'
                                            : 'Guest House')
                                    }}
                                </span>


                                {{-- JUMLAH FOTO --}}
                                <span class="absolute bottom-4 right-4 bg-black/60 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                                    📷 {{ $allPhotos->count() }} Foto
                                </span>

                            </button>

                        </div>


                        {{-- THUMBNAIL --}}
                        @if ($allPhotos->count() > 1)

                            <div class="grid grid-cols-4 gap-2 p-2">

                                @foreach ($allPhotos->take(4) as $index => $photo)

                                    <button
                                        type="button"
                                        onclick="openLightbox({{ $index }})"
                                        class="h-16 rounded-lg overflow-hidden border border-gray-100 hover:border-brand-blue transition relative"
                                    >

                                        <img
                                            src="{{ asset('storage/' . $photo) }}"
                                            alt="{{ $property->name }}"
                                            class="w-full h-full object-cover"
                                        >

                                        @if ($index === 3 && $allPhotos->count() > 4)

                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white text-xs font-bold">
                                                +{{ $allPhotos->count() - 4 }}
                                            </div>

                                        @endif

                                    </button>

                                @endforeach

                            </div>

                        @endif


                        <button
                            type="button"
                            onclick="openLightbox(0)"
                            class="w-full py-3 text-sm font-semibold text-brand-blue hover:bg-blue-50 transition border-t border-gray-100"
                        >
                            📷 Lihat Semua Foto
                        </button>

                    @else

                        <div class="h-72 md:h-[390px] flex items-center justify-center bg-gray-100 text-gray-400">

                            <div class="text-center">

                                <div class="text-4xl mb-2">
                                    🏡
                                </div>

                                <p class="text-sm">
                                    Belum ada foto property
                                </p>

                            </div>

                        </div>

                    @endif

                </div>

            </div>



            {{-- =================================================
                INFORMASI PROPERTY
            ================================================= --}}
            <div class="lg:col-span-4">

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm h-full">

                    {{-- TIPE --}}
                    <div class="text-xs font-bold text-brand-blue uppercase tracking-wide mb-2">

                        {{ $property->type === 'kost_harian'
                            ? 'Kost Harian'
                            : ($property->type === 'villa'
                                ? 'Villa'
                                : 'Guest House')
                        }}

                    </div>


                    {{-- NAMA --}}
                    <h1 class="text-2xl md:text-3xl font-extrabold text-navy-900 leading-tight">

                        {{ $property->name }}

                    </h1>


                    {{-- ALAMAT --}}
                    <p class="text-sm text-gray-500 mt-3 leading-relaxed">

                        📍 {{ $property->address }},
                        {{ $property->city }}

                    </p>


                    {{-- RATING --}}
                    <div class="flex flex-wrap items-center gap-2 mt-5">

                        <div class="text-yellow-400 text-lg tracking-tight">

                            {{ str_repeat('★', $starRating) }}

                        </div>

                        <span class="font-bold text-navy-900">

                            {{ $starRating }} Bintang

                        </span>

                        <span class="text-xs bg-blue-50 text-brand-blue px-3 py-1 rounded-full font-semibold">

                            {{ $starLabel }}

                        </span>

                    </div>


                    {{-- KAMAR + TAMU --}}
                    <div class="grid grid-cols-2 gap-3 mt-6">

                        <div class="bg-gray-50 rounded-xl p-4">

                            <div class="text-xl mb-1">
                                🛏️
                            </div>

                            <div class="text-xs text-gray-500">
                                Kamar Tidur
                            </div>

                            <div class="font-bold text-navy-900">
                                {{ $property->bedroom_count }} Kamar
                            </div>

                        </div>


                        <div class="bg-gray-50 rounded-xl p-4">

                            <div class="text-xl mb-1">
                                👥
                            </div>

                            <div class="text-xs text-gray-500">
                                Kapasitas
                            </div>

                            <div class="font-bold text-navy-900">
                                {{ $property->guest_capacity }} Tamu
                            </div>

                        </div>

                    </div>


                    {{-- FASILITAS SINGKAT --}}
                    @if ($availableFacilities->isNotEmpty())

                        <div class="mt-6">

                            <p class="text-xs font-semibold text-gray-500 mb-2">
                                Fasilitas utama
                            </p>

                            <div class="flex flex-wrap gap-2">

                                @foreach ($availableFacilities->take(5) as $facility)

                                    <span class="text-xs bg-blue-50 text-brand-blue border border-blue-100 px-3 py-1.5 rounded-full">

                                        ✓ {{ $facility->name }}

                                    </span>

                                @endforeach


                                @if ($availableFacilities->count() > 5)

                                    <span class="text-xs bg-gray-50 text-gray-500 border border-gray-100 px-3 py-1.5 rounded-full">

                                        +{{ $availableFacilities->count() - 5 }} lainnya

                                    </span>

                                @endif

                            </div>

                        </div>

                    @endif

                </div>

            </div>



            {{-- =================================================
                CARD BOOKING
            ================================================= --}}
            <div class="lg:col-span-3">

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sticky top-28">

                    <div class="text-xs text-gray-400 mb-1">
                        Mulai dari
                    </div>


                    <div class="text-2xl md:text-3xl font-extrabold text-navy-900">

                        Rp {{ number_format(
                            $property->price_per_night,
                            0,
                            ',',
                            '.'
                        ) }}

                        <span class="text-sm font-normal text-gray-500">
                            / malam
                        </span>

                    </div>


                    {{-- BOOKING CUSTOMER --}}
                    @if (
                        auth()->check()
                        && auth()->user()->hasRole('customer')
                    )

                        <a
                            href="{{ route(
                                'customer.bookings.create',
                                $property
                            ) }}"
                            class="block text-center mt-6 bg-brand-blue hover:bg-blue-700 transition text-white font-bold py-3.5 rounded-xl"
                        >
                            📅 Booking Sekarang
                        </a>


                    {{-- MITRA PEMILIK --}}
                    @elseif (
                        auth()->check()
                        && auth()->user()->hasRole('mitra')
                        && $property->mitra_id === auth()->id()
                    )

                        <a
                            href="{{ route(
                                'mitra.properties.edit',
                                $property
                            ) }}"
                            class="block text-center mt-6 bg-brand-blue hover:bg-blue-700 transition text-white font-bold py-3.5 rounded-xl"
                        >
                            ✏️ Edit Properti
                        </a>


                        <div class="mt-3 text-center text-xs text-gray-500">

                            Status:

                            <span
                                class="font-bold
                                {{ $property->status === 'active'
                                    ? 'text-green-600'
                                    : ($property->status === 'pending'
                                        ? 'text-yellow-600'
                                        : 'text-red-500')
                                }}"
                            >

                                {{ ucfirst($property->status) }}

                            </span>

                        </div>


                    {{-- USER LOGIN BUKAN CUSTOMER --}}
                    @elseif (auth()->check())

                        <div class="mt-6 text-center text-sm text-gray-500 bg-gray-50 rounded-xl p-3">

                            Hanya customer yang dapat melakukan booking.

                        </div>


                    {{-- BELUM LOGIN --}}
                    @else

                        <a
                            href="{{ route('login') }}"
                            class="block text-center mt-6 bg-brand-blue hover:bg-blue-700 transition text-white font-bold py-3.5 rounded-xl"
                        >
                            🔐 Masuk untuk Booking
                        </a>

                    @endif


                    {{-- BAGIKAN --}}
                    <button
                        type="button"
                        onclick="shareProperty()"
                        class="w-full mt-3 border border-gray-300 hover:border-brand-blue hover:text-brand-blue transition text-sm font-semibold py-3 rounded-xl"
                    >
                        🔗 Bagikan
                    </button>


                    <div class="mt-5 pt-5 border-t border-gray-100">

                        <div class="flex items-start gap-2 text-xs text-gray-500">

                            <span>✓</span>

                            <span>
                                Informasi harga ditampilkan per malam.
                            </span>

                        </div>


                        <div class="flex items-start gap-2 text-xs text-gray-500 mt-2">

                            <span>✓</span>

                            <span>
                                Periksa tanggal tersedia sebelum booking.
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
            DESKRIPSI + FASILITAS
        ====================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-6">


            {{-- DESKRIPSI --}}
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-lg">
                        📄
                    </div>

                    <h2 class="font-bold text-navy-900 text-lg">
                        Deskripsi Property
                    </h2>

                </div>


                <p class="text-sm text-gray-600 leading-7 whitespace-pre-line">

                    {{ $property->description ?: 'Belum ada deskripsi property.' }}

                </p>

            </div>



            {{-- FASILITAS --}}
            <div class="lg:col-span-3 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

                <div class="flex items-center gap-3 mb-5">

                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-lg">
                        🏠
                    </div>

                    <h2 class="font-bold text-navy-900 text-lg">
                        Fasilitas
                    </h2>

                </div>


                @if ($availableFacilities->isNotEmpty())

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6">

                        @foreach ($availableFacilities as $facility)

                            <div class="flex items-center gap-2.5 text-sm text-navy-900">

                                <svg
                                    class="w-5 h-5 text-brand-blue shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />

                                </svg>

                                <span>
                                    {{ $facility->name }}
                                </span>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-sm text-gray-400">

                        Belum ada fasilitas yang tersedia.

                    </div>

                @endif

            </div>

        </div>



        {{-- =====================================================
            LOKASI + INFORMASI + ULASAN
        ====================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-6">


            {{-- =================================================
                LOKASI
            ================================================= --}}
            <div class="lg:col-span-4 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-lg">
                        📍
                    </div>

                    <h2 class="font-bold text-navy-900 text-lg">
                        Lokasi
                    </h2>

                </div>


                <p class="text-sm text-gray-600 leading-6">

                    {{ $property->address }}

                </p>

                <p class="text-sm text-gray-600 mt-1">

                    {{ $property->city }}

                </p>


                @if ($property->latitude && $property->longitude)

                    <a
                        href="https://www.google.com/maps/search/?api=1&query={{ $property->latitude }},{{ $property->longitude }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 mt-5 text-sm font-bold text-brand-blue hover:underline"
                    >
                        🗺️ Lihat di Google Maps →
                    </a>

                @else

                    <div class="mt-5 text-xs text-gray-400">

                        Lokasi peta belum tersedia.

                    </div>

                @endif

            </div>



            {{-- =================================================
                INFORMASI PROPERTY
            ================================================= --}}
            <div class="lg:col-span-3 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

                <div class="flex items-center gap-3 mb-5">

                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-lg">
                        📋
                    </div>

                    <h2 class="font-bold text-navy-900 text-lg">
                        Informasi Property
                    </h2>

                </div>


                <div class="space-y-4 text-sm">

                    <div class="flex justify-between gap-3">

                        <span class="text-gray-500">
                            Tipe
                        </span>

                        <span class="font-semibold text-navy-900 text-right">

                            {{ $property->type === 'kost_harian'
                                ? 'Kost Harian'
                                : ($property->type === 'villa'
                                    ? 'Villa'
                                    : 'Guest House')
                            }}

                        </span>

                    </div>


                    <div class="flex justify-between gap-3">

                        <span class="text-gray-500">
                            Rating
                        </span>

                        <span class="font-semibold text-navy-900">

                            {{ $starRating }} / 5

                        </span>

                    </div>


                    <div class="flex justify-between gap-3">

                        <span class="text-gray-500">
                            Kamar
                        </span>

                        <span class="font-semibold text-navy-900">

                            {{ $property->bedroom_count }}

                        </span>

                    </div>


                    <div class="flex justify-between gap-3">

                        <span class="text-gray-500">
                            Kapasitas
                        </span>

                        <span class="font-semibold text-navy-900">

                            {{ $property->guest_capacity }} Tamu

                        </span>

                    </div>


                    @if ($property->management_type)

                        <div class="flex justify-between gap-3">

                            <span class="text-gray-500">
                                Skema
                            </span>

                            <span class="font-semibold text-navy-900 capitalize">

                                {{ $property->management_type }}

                            </span>

                        </div>

                    @endif

                </div>

            </div>



            {{-- =================================================
                ULASAN
            ================================================= --}}
            <div class="lg:col-span-5 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-lg">
                        ★
                    </div>

                    <h2 class="font-bold text-navy-900 text-lg">
                        Ulasan Tamu
                    </h2>

                </div>


                {{-- =================================================
                    RATING TAMU
                ================================================= --}}
                @php

                    $guestAverage = null;

                    if (
                        isset($property->reviews_avg_score)
                        && $property->reviews_avg_score !== null
                    ) {
                        $guestAverage = (float) $property->reviews_avg_score;
                    }

                    $guestReviewCount = (int) (
                        $property->reviews_count ?? 0
                    );

                @endphp


                @if ($guestAverage !== null)

                    @php

                        $guestCategory =
                            $guestAverage >= 9
                                ? 'Luar Biasa'
                                : (
                                    $guestAverage >= 8
                                        ? 'Mengesankan'
                                        : (
                                            $guestAverage >= 7
                                                ? 'Nyaman'
                                                : 'Belum masuk kategori'
                                        )
                                );

                        $visualStars = max(
                            1,
                            min(
                                5,
                                (int) round($guestAverage / 2)
                            )
                        );

                    @endphp


                    <div class="flex items-center gap-5 mb-5">

                        <div>

                            <div class="text-3xl font-extrabold text-navy-900">

                                {{ number_format(
                                    $guestAverage,
                                    1,
                                    ',',
                                    '.'
                                ) }}/10

                            </div>

                            <div class="text-yellow-400 tracking-tight text-lg">

                                {{ str_repeat('★', $visualStars) }}

                            </div>

                        </div>


                        <div class="text-sm text-gray-500">

                            <p class="font-semibold text-navy-900">

                                Rating dari Tamu

                            </p>

                            <p class="mt-1">

                                {{ $guestCategory }}

                            </p>

                            <p class="text-xs text-gray-400 mt-1">

                                {{ $guestReviewCount }}
                                {{ $guestReviewCount === 1 ? 'ulasan' : 'ulasan' }}

                            </p>

                        </div>

                    </div>


                    {{-- DAFTAR ULASAN --}}
                    @if (
                        $property->relationLoaded('reviews')
                        && $property->reviews->isNotEmpty()
                    )

                        <div class="space-y-4">

                            @foreach (
                                $property->reviews
                                    ->sortByDesc('created_at')
                                    ->take(5)
                                as $review
                            )

                                <div class="border-t border-gray-100 pt-4">

                                    <div class="flex items-start justify-between gap-3">

                                        <div>

                                            <p class="text-sm font-bold text-navy-900">

                                                {{ $review->customer->name
                                                    ?? 'Tamu'
                                                }}

                                            </p>

                                            <p class="text-xs text-gray-400 mt-1">

                                                {{ $review->created_at
                                                    ->translatedFormat('d M Y')
                                                }}

                                            </p>

                                        </div>


                                        <div class="text-right">

                                            <div class="text-yellow-400 text-sm">

                                                ★ {{ $review->score }}/10

                                            </div>

                                        </div>

                                    </div>


                                    @if ($review->comment)

                                        <p class="text-sm text-gray-600 leading-6 mt-3">

                                            {{ $review->comment }}

                                        </p>

                                    @endif

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="border border-dashed border-gray-200 rounded-xl p-4 text-center">

                            <div class="text-2xl mb-2">
                                💬
                            </div>

                            <p class="text-sm font-semibold text-gray-700">

                                Belum ada ulasan tertulis

                            </p>

                            <p class="text-xs text-gray-400 mt-1">

                                Rating sudah tersedia dari tamu.

                            </p>

                        </div>

                    @endif

                @else

                    <div class="border border-dashed border-gray-200 rounded-xl p-5 text-center">

                        <div class="text-3xl mb-2">
                            ⭐
                        </div>

                        <p class="text-sm font-semibold text-gray-700">

                            Belum ada rating dari tamu

                        </p>

                        <p class="text-xs text-gray-400 mt-1">

                            Jadilah tamu pertama yang memberikan rating
                            untuk property ini.

                        </p>

                    </div>

                @endif

            </div>

        </div>



        {{-- =====================================================
            REKOMENDASI PROPERTY
            1 PROPERTY = 1 CARD HORIZONTAL
        ====================================================== --}}
        @if (
            isset($recommendedProperties)
            && $recommendedProperties->isNotEmpty()
        )

            <div class="bg-white border border-gray-200 rounded-2xl p-5 sm:p-6 shadow-sm mb-10">

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-6">

                    <div>

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-lg">

                                🏡

                            </div>

                            <h2 class="font-bold text-navy-900 text-xl">

                                Rekomendasi Properti Lain

                            </h2>

                        </div>


                        <p class="text-xs text-gray-400 mt-1 ml-13">

                            Properti lain yang mungkin kamu sukai

                        </p>

                    </div>


                    <a
                        href="{{ route('home') }}"
                        class="hidden sm:block text-sm font-bold text-brand-blue hover:underline"
                    >
                        Lihat Semua →
                    </a>

                </div>



                {{-- =================================================
                    DAFTAR PROPERTY
                    SETIAP PROPERTY SATU CARD HORIZONTAL
                ================================================= --}}
                <div class="space-y-5">

                    @foreach ($recommendedProperties as $recommended)

                        @php

                            $recommendedRating = max(
                                1,
                                min(
                                    5,
                                    (int) ($recommended->star_rating ?? 1)
                                )
                            );

                            $recommendedType =
                                $recommended->type === 'kost_harian'
                                    ? 'Kost Harian'
                                    : (
                                        $recommended->type === 'villa'
                                            ? 'Villa'
                                            : 'Guest House'
                                    );

                        @endphp


                        <a
                            href="{{ route(
                                'properties.show',
                                $recommended
                            ) }}"
                            class="
                                group
                                block
                                border
                                border-gray-200
                                rounded-2xl
                                overflow-hidden
                                bg-white
                                hover:border-blue-200
                                hover:shadow-lg
                                transition
                                duration-200
                            "
                        >

                            {{-- =================================================
                                CARD HORIZONTAL
                            ================================================= --}}
                            <div class="flex flex-col md:flex-row">


                                {{-- =================================================
                                    FOTO PROPERTY
                                ================================================= --}}
                                <div
                                    class="
                                        relative
                                        w-full
                                        md:w-[300px]
                                        lg:w-[340px]
                                        h-56
                                        md:h-[220px]
                                        bg-gray-100
                                        shrink-0
                                        overflow-hidden
                                    "
                                >

                                    @if ($recommended->cover_image)

                                        <img
                                            src="{{ asset(
                                                'storage/' .
                                                $recommended->cover_image
                                            ) }}"
                                            alt="{{ $recommended->name }}"
                                            class="
                                                w-full
                                                h-full
                                                object-cover
                                                group-hover:scale-105
                                                transition
                                                duration-500
                                            "
                                        >

                                    @else

                                        <div
                                            class="
                                                w-full
                                                h-full
                                                flex
                                                items-center
                                                justify-center
                                                text-gray-400
                                                text-4xl
                                            "
                                        >
                                            🏡
                                        </div>

                                    @endif


                                    {{-- OVERLAY --}}
                                    <div
                                        class="
                                            absolute
                                            inset-0
                                            bg-gradient-to-t
                                            from-black/40
                                            via-transparent
                                            to-transparent
                                            pointer-events-none
                                        "
                                    ></div>


                                    {{-- TIPE --}}
                                    <span
                                        class="
                                            absolute
                                            top-4
                                            left-4
                                            bg-white/95
                                            text-brand-blue
                                            text-xs
                                            font-bold
                                            uppercase
                                            px-3
                                            py-1.5
                                            rounded-full
                                            shadow-sm
                                        "
                                    >

                                        {{ $recommendedType }}

                                    </span>


                                    {{-- RATING --}}
                                    <span
                                        class="
                                            absolute
                                            top-4
                                            right-4
                                            bg-white/95
                                            text-xs
                                            font-bold
                                            px-3
                                            py-1.5
                                            rounded-full
                                            shadow-sm
                                        "
                                    >

                                        <span class="text-yellow-400">
                                            ★
                                        </span>

                                        {{ $recommendedRating }}

                                    </span>

                                </div>



                                {{-- =================================================
                                    DETAIL PROPERTY
                                ================================================= --}}
                                <div class="flex-1 p-5 md:p-6">

                                    <div
                                        class="
                                            h-full
                                            flex
                                            flex-col
                                        "
                                    >


                                        {{-- NAMA --}}
                                        <div>

                                            <h3
                                                class="
                                                    text-xl
                                                    md:text-2xl
                                                    font-extrabold
                                                    text-navy-900
                                                    group-hover:text-brand-blue
                                                    transition
                                                "
                                            >

                                                {{ $recommended->name }}

                                            </h3>


                                            {{-- LOKASI --}}
                                            <div
                                                class="
                                                    flex
                                                    items-center
                                                    gap-2
                                                    text-sm
                                                    text-gray-400
                                                    mt-2
                                                "
                                            >

                                                <span>
                                                    📍
                                                </span>

                                                <span>
                                                    {{ $recommended->city }}
                                                </span>

                                            </div>

                                        </div>



                                        {{-- INFO KAMAR --}}
                                        <div
                                            class="
                                                flex
                                                flex-wrap
                                                items-center
                                                gap-3
                                                mt-5
                                            "
                                        >

                                            <div
                                                class="
                                                    flex
                                                    items-center
                                                    gap-2
                                                    bg-gray-50
                                                    border
                                                    border-gray-100
                                                    rounded-xl
                                                    px-3
                                                    py-2
                                                "
                                            >

                                                <span>
                                                    🛏️
                                                </span>

                                                <span
                                                    class="
                                                        text-sm
                                                        font-semibold
                                                        text-gray-600
                                                    "
                                                >

                                                    {{ $recommended->bedroom_count }}
                                                    Kamar

                                                </span>

                                            </div>


                                            <div
                                                class="
                                                    flex
                                                    items-center
                                                    gap-2
                                                    bg-gray-50
                                                    border
                                                    border-gray-100
                                                    rounded-xl
                                                    px-3
                                                    py-2
                                                "
                                            >

                                                <span>
                                                    👥
                                                </span>

                                                <span
                                                    class="
                                                        text-sm
                                                        font-semibold
                                                        text-gray-600
                                                    "
                                                >

                                                    {{ $recommended->guest_capacity }}
                                                    Tamu

                                                </span>

                                            </div>


                                            <div
                                                class="
                                                    flex
                                                    items-center
                                                    gap-2
                                                    bg-yellow-50
                                                    border
                                                    border-yellow-100
                                                    rounded-xl
                                                    px-3
                                                    py-2
                                                "
                                            >

                                                <span class="text-yellow-400">
                                                    ★
                                                </span>

                                                <span
                                                    class="
                                                        text-sm
                                                        font-semibold
                                                        text-gray-600
                                                    "
                                                >

                                                    {{ $recommendedRating }}/5

                                                </span>

                                            </div>

                                        </div>



                                        {{-- DESKRIPSI --}}
                                        @if ($recommended->description)

                                            <p
                                                class="
                                                    text-sm
                                                    text-gray-500
                                                    leading-6
                                                    mt-5
                                                    line-clamp-2
                                                "
                                            >

                                                {{ $recommended->description }}

                                            </p>

                                        @endif



                                        {{-- BOTTOM --}}
                                        <div
                                            class="
                                                mt-auto
                                                pt-5
                                                flex
                                                flex-col
                                                sm:flex-row
                                                sm:items-end
                                                sm:justify-between
                                                gap-4
                                            "
                                        >


                                            {{-- HARGA --}}
                                            <div>

                                                <div
                                                    class="
                                                        text-xs
                                                        text-gray-400
                                                        mb-1
                                                    "
                                                >

                                                    Mulai dari

                                                </div>


                                                <div
                                                    class="
                                                        text-xl
                                                        font-extrabold
                                                        text-navy-900
                                                    "
                                                >

                                                    Rp {{ number_format(
                                                        $recommended->price_per_night,
                                                        0,
                                                        ',',
                                                        '.'
                                                    ) }}

                                                </div>


                                                <div
                                                    class="
                                                        text-xs
                                                        text-gray-400
                                                        mt-0.5
                                                    "
                                                >

                                                    / malam

                                                </div>

                                            </div>



                                            {{-- BUTTON --}}
                                            <span
                                                class="
                                                    inline-flex
                                                    items-center
                                                    justify-center
                                                    gap-2
                                                    bg-brand-yellow
                                                    hover:bg-yellow-400
                                                    text-navy-900
                                                    text-sm
                                                    font-bold
                                                    px-5
                                                    py-3
                                                    rounded-xl
                                                    whitespace-nowrap
                                                    transition
                                                "
                                            >

                                                Lihat Detail

                                                <span>
                                                    →
                                                </span>

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>



                {{-- MOBILE --}}
                <a
                    href="{{ route('home') }}"
                    class="
                        sm:hidden
                        block
                        text-center
                        mt-6
                        text-sm
                        font-bold
                        text-brand-blue
                    "
                >

                    Lihat Semua Properti →

                </a>

            </div>

        @endif

    </div>

</div>



{{-- =========================================================
    LIGHTBOX GALERI
========================================================= --}}
@if ($allPhotos->isNotEmpty())

    <div
        id="lightbox"
        class="
            hidden
            fixed
            inset-0
            z-[300]
            bg-black/95
            flex
            items-center
            justify-center
            p-4
        "
    >

        {{-- CLOSE --}}
        <button
            type="button"
            onclick="closeLightbox()"
            class="
                absolute
                top-5
                right-5
                w-11
                h-11
                rounded-full
                bg-white/10
                hover:bg-white/20
                text-white
                flex
                items-center
                justify-center
                transition
                text-lg
            "
        >
            ✕
        </button>


        {{-- PREVIOUS --}}
        <button
            type="button"
            onclick="changePhoto(-1)"
            class="
                absolute
                left-3
                sm:left-8
                w-11
                h-11
                rounded-full
                bg-white/10
                hover:bg-white/20
                text-white
                flex
                items-center
                justify-center
                transition
                text-2xl
            "
        >
            ‹
        </button>


        {{-- IMAGE --}}
        <img
            id="lightbox-image"
            src=""
            alt="{{ $property->name }}"
            class="
                max-w-full
                max-h-[85vh]
                object-contain
                rounded-xl
            "
        >


        {{-- NEXT --}}
        <button
            type="button"
            onclick="changePhoto(1)"
            class="
                absolute
                right-3
                sm:right-8
                w-11
                h-11
                rounded-full
                bg-white/10
                hover:bg-white/20
                text-white
                flex
                items-center
                justify-center
                transition
                text-2xl
            "
        >
            ›
        </button>


        {{-- COUNTER --}}
        <div
            id="lightbox-counter"
            class="
                absolute
                bottom-5
                left-1/2
                -translate-x-1/2
                text-white
                text-sm
                bg-black/60
                px-4
                py-1.5
                rounded-full
            "
        ></div>

    </div>


    <script>

        const galleryPhotos = @json(
            $allPhotos
                ->map(fn ($p) => asset('storage/' . $p))
                ->values()
        );

        let currentPhotoIndex = 0;


        function openLightbox(index)
        {
            currentPhotoIndex = index;

            renderLightbox();

            document
                .getElementById('lightbox')
                .classList
                .remove('hidden');

            document.body.classList.add('overflow-hidden');
        }


        function closeLightbox()
        {
            document
                .getElementById('lightbox')
                .classList
                .add('hidden');

            document.body.classList.remove('overflow-hidden');
        }


        function changePhoto(direction)
        {
            currentPhotoIndex =
                (
                    currentPhotoIndex +
                    direction +
                    galleryPhotos.length
                ) %
                galleryPhotos.length;

            renderLightbox();
        }


        function renderLightbox()
        {
            document.getElementById('lightbox-image').src =
                galleryPhotos[currentPhotoIndex];

            document.getElementById('lightbox-counter').textContent =
                (currentPhotoIndex + 1)
                + ' / '
                + galleryPhotos.length;
        }


        document.addEventListener('keydown', function (e)
        {
            const lightbox =
                document.getElementById('lightbox');

            if (lightbox.classList.contains('hidden')) {
                return;
            }

            if (e.key === 'Escape') {
                closeLightbox();
            }

            if (e.key === 'ArrowLeft') {
                changePhoto(-1);
            }

            if (e.key === 'ArrowRight') {
                changePhoto(1);
            }
        });

    </script>

@endif



{{-- =========================================================
    SHARE PROPERTY
========================================================= --}}
<script>

    function shareProperty()
    {
        const title = @json($property->name);
        const url = window.location.href;

        if (navigator.share) {

            navigator.share({
                title: title,
                url: url
            }).catch(() => {});

        } else {

            navigator.clipboard
                .writeText(url)
                .then(() => {
                    alert('Link property berhasil disalin.');
                })
                .catch(() => {
                    alert('Salin link: ' + url);
                });

        }
    }

</script>

@endsection