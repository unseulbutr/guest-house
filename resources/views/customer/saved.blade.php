@extends('layouts.app')

@section('title', 'Properti Tersimpan')

@section('content')

{{-- ============ HEADER — navy gelap + aksen emas, senada dengan dashboard ============ --}}
<div class="relative bg-navy-900 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;"></div>
    <div class="absolute -right-16 -top-16 w-64 h-64 bg-brand-yellow/10 rounded-full blur-3xl"></div>
    <div class="absolute -left-10 -bottom-16 w-56 h-56 bg-brand-blue/20 rounded-full blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-xl shrink-0">
                    ❤️
                </div>
                <div>
                    <div class="w-8 h-[2px] bg-brand-yellow mb-2"></div>
                    <h1 class="font-display text-2xl sm:text-3xl font-bold leading-snug">Properti Tersimpan</h1>
                    <p class="text-gray-300 text-sm mt-0.5">
                        {{ $properties->total() }} guest house &amp; kost harian yang kamu simpan.
                    </p>
                </div>
            </div>

            <a href="{{ route('home') }}"
               class="bg-brand-yellow hover:brightness-95 transition text-navy-900 text-sm font-bold px-5 py-2.5 rounded-full">
                + Cari Properti Lain
            </a>
        </div>
    </div>
</div>

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if ($properties->isEmpty())
        <div class="text-center py-20 border border-gray-100 rounded-3xl bg-white shadow-card">
            <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center text-3xl mx-auto mb-4">🤍</div>
            <p class="font-display text-lg font-bold text-navy-900 mb-1">Belum ada properti tersimpan</p>
            <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                Tekan ikon hati di listing properti mana pun untuk menyimpannya ke sini, biar gampang dicek lagi nanti.
            </p>
            <a href="{{ route('home') }}"
               class="inline-block bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-6 py-2.5 rounded-full">
                Cari Properti
            </a>
        </div>
    @else
        <div class="flex flex-col gap-4">
            @foreach ($properties as $property)
                @include('properties.partials.card', ['property' => $property])
            @endforeach
        </div>

        <div class="mt-10">
            {{ $properties->links() }}
        </div>
    @endif
</section>
@endsection