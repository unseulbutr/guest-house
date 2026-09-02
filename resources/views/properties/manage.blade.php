@extends('layouts.app')

@section('title', 'Properti Saya')

@section('content')
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center justify-between mb-1 flex-wrap gap-3">
        <div>
            <h1 class="font-display text-2xl font-extrabold text-navy-900">Properti Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola listing guest house & kost harian milikmu.</p>
        </div>
        <a href="{{ route('mitra.properties.create') }}"
           class="bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-5 py-2.5 rounded-full">
            + Tambah Properti
        </a>
    </div>

    @if (session('success'))
        <div class="mt-6 bg-green-50 border border-green-100 text-green-700 text-sm px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-8">
        @if ($properties->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-card">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-3xl mx-auto mb-4">🏠</div>
                <p class="font-display font-bold text-navy-900 mb-1">Belum ada properti</p>
                <p class="text-sm text-gray-500 mb-6">Daftarkan properti pertamamu dan mulai terima booking dari customer.</p>
                <a href="{{ route('mitra.properties.create') }}"
                   class="inline-block bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-6 py-2.5 rounded-full">
                    + Tambah Properti
                </a>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($properties as $property)
                    @php
                        $statusMap = [
                            'active' => ['Aktif', 'bg-green-50 text-green-600'],
                            'pending' => ['Menunggu Verifikasi', 'bg-yellow-50 text-yellow-600'],
                            'rejected' => ['Ditolak', 'bg-red-50 text-red-500'],
                        ];
                        [$statusLabel, $statusClass] = $statusMap[$property->status] ?? [ucfirst($property->status), 'bg-gray-50 text-gray-500'];
                    @endphp
                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-card hover:shadow-floating transition">
                        <div class="h-32 bg-gradient-to-br from-navy-700 to-navy-900 relative overflow-hidden">
                            @if ($property->cover_image)
                                <img src="{{ asset('storage/' . $property->cover_image) }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white/60 text-2xl">🏡</div>
                            @endif
                            <span class="absolute top-3 left-3 text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="p-5">
                            <h3 class="font-display font-bold text-navy-900 leading-snug mb-1 truncate">{{ $property->name }}</h3>
                            <p class="text-xs text-gray-500 mb-1">📍 {{ $property->city }}</p>
                            <p class="text-xs text-gray-500 mb-4">
                                {{ $property->management_type === 'dikelola' ? 'Dikelola Platform' : 'Kelola Mandiri' }}
                                · Komisi {{ (float) $property->commission_percentage }}%
                            </p>

                            @if ($property->status === 'rejected' && $property->rejection_reason)
                                <div class="bg-red-50 border border-red-100 rounded-lg px-3 py-2 mb-4">
                                    <p class="text-[11px] font-semibold text-red-600 mb-0.5">Alasan ditolak:</p>
                                    <p class="text-xs text-red-700">{{ $property->rejection_reason }}</p>
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                <span class="font-display font-bold text-navy-900 text-sm">
                                    Rp {{ number_format($property->price_per_night, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-gray-400">/malam</span>
                                </span>
                                <a href="{{ route('mitra.properties.edit', $property) }}" class="text-xs font-semibold text-brand-blue hover:underline">
                                    Kelola →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">{{ $properties->links() }}</div>
        @endif
    </div>
</section>
@endsection