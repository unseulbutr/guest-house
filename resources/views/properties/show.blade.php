@extends('layouts.app')

@section('title', $property->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($property->description), 155))

@section('content')
{{-- ============ STRUCTURED DATA (JSON-LD) untuk SEO ============
     Tempel blok ini persis di bawah @section('content') di properties/show.blade.php.
     Ini membantu Google menampilkan harga & info properti langsung di hasil pencarian. --}}
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
    },
    @if ($property->latitude && $property->longitude)
    "geo": {
        "@@type": "GeoCoordinates",
        "latitude": {{ $property->latitude }},
        "longitude": {{ $property->longitude }}
    },
    @endif
    @if ($property->cover_image)
    "image": {!! json_encode(asset('storage/' . $property->cover_image)) !!},
    @endif
    "priceRange": "Rp {{ number_format($property->price_per_night, 0, ',', '.') }}",
    "url": {!! json_encode(route('properties.show', $property)) !!}
}
</script>
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-500 mb-4">
        <a href="{{ route('home') }}" class="text-brand-blue hover:underline">Beranda</a>
        <span class="mx-1">/</span>
        <span>{{ $property->name }}</span>
    </div>

    {{-- Foto Sampul --}}
    <div class="h-72 md:h-96 bg-gray-200 rounded-2xl overflow-hidden mb-6">
        @if ($property->cover_image)
            <img src="{{ asset('storage/' . $property->cover_image) }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400">Tidak ada foto</div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ===== Kolom Kiri: Info Properti ===== --}}
        <div class="lg:col-span-2 space-y-6">

            <div>
                <div class="text-xs text-brand-blue font-semibold uppercase mb-1">
                    {{ $property->type === 'kost_harian' ? 'Kost Harian' : 'Guest House' }}
                </div>
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-navy-900">{{ $property->name }}</h1>

                    {{-- Tombol Simpan: pakai @if biasa (bukan @auth+@role bertingkat)
                         supaya tidak ambigu buat Blade compiler. --}}
                    @if (auth()->check() && auth()->user()->hasRole('customer'))
                        <form method="POST" action="{{ route('customer.saved.toggle', $property) }}">
                            @csrf
                            <button type="submit"
                                    class="border border-gray-300 hover:border-brand-blue hover:text-brand-blue transition text-sm font-semibold px-4 py-2 rounded-full">
                                ❤ Simpan
                            </button>
                        </form>
                    @endif
                </div>
                <p class="text-gray-500 mt-1">📍 {{ $property->address }}, {{ $property->city }}</p>
            </div>

            <div class="flex items-center gap-6 text-sm text-gray-600 border-y border-gray-100 py-4">
                <span>🛏️ {{ $property->bedroom_count }} Kamar Tidur</span>
                <span>👥 Maks {{ $property->guest_capacity }} Tamu</span>
            </div>

            <div>
                <h2 class="font-bold text-navy-900 mb-2">Deskripsi</h2>
                <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $property->description ?: 'Belum ada deskripsi.' }}</p>
            </div>

            {{-- ===== Checklist Fasilitas ===== --}}
            <div>
                <h2 class="font-bold text-navy-900 mb-3">Fasilitas</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach ($property->facilities as $facility)
                        <div class="flex items-center gap-2 text-sm">
                            @if ($facility->pivot->is_available)
                                <span class="text-green-500">✅</span>
                                <span class="text-navy-900">{{ $facility->name }}</span>
                            @else
                                <span class="text-red-400">❌</span>
                                <span class="text-gray-400 line-through">{{ $facility->name }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ===== Kolom Kanan: Card Booking ===== --}}
        <div>
            <div class="bg-white border border-gray-100 rounded-2xl shadow-lg p-6 sticky top-32">
                <div class="text-2xl font-extrabold text-navy-900">
                    Rp {{ number_format($property->price_per_night, 0, ',', '.') }}
                    <span class="text-sm font-normal text-gray-500">/ malam</span>
                </div>

                {{-- Diganti dari @auth + @role(...) + @elseif + @else bertingkat
                     jadi @if/@elseif/@else biasa — logic-nya identik, tapi native
                     Blade @if dijamin tidak ambigu untuk compiler (beda dengan
                     custom directive @role dari Spatie Permission yang kalau
                     ditumpuk dengan @elseif/@else bisa bikin parser Blade salah
                     pasangan endif, seperti yang bikin error kemarin). --}}
                @if (auth()->check() && auth()->user()->hasRole('customer'))
                    <a href="{{ route('customer.bookings.create', $property) }}"
                       class="block text-center mt-4 bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg">
                        Booking Sekarang
                    </a>
                @elseif (auth()->check() && auth()->user()->hasRole('mitra') && $property->mitra_id === auth()->id())
                    <a href="{{ route('mitra.properties.edit', $property) }}"
                       class="block text-center mt-4 bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg">
                        ✏️ Edit Properti Ini
                    </a>
                    <p class="text-xs text-gray-400 mt-2">Status:
                        <span class="font-semibold
                            {{ $property->status === 'active' ? 'text-green-600' : ($property->status === 'pending' ? 'text-yellow-600' : 'text-red-500') }}">
                            {{ ucfirst($property->status) }}
                        </span>
                    </p>
                @elseif (auth()->check())
                    <p class="text-xs text-gray-500 mt-4">Hanya customer yang bisa booking.</p>
                @else
                    <a href="{{ route('login') }}"
                       class="block text-center mt-4 bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg">
                        Masuk untuk Booking
                    </a>
                @endif

                <button type="button" onclick="navigator.share ? navigator.share({title: '{{ $property->name }}', url: window.location.href}) : alert('Salin link: ' + window.location.href)"
                        class="w-full mt-3 border border-gray-300 hover:border-brand-blue hover:text-brand-blue transition text-sm font-semibold py-2.5 rounded-lg">
                    🔗 Bagikan
                </button>
            </div>
        </div>
    </div>

</div>
@endsection