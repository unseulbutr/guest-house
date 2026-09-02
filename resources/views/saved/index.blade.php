@extends('layouts.app')

@section('title', 'Properti Disimpan')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <a href="{{ route('dashboard') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali ke Dashboard</a>

    <h1 class="text-2xl font-extrabold text-navy-900 mt-3 mb-6">Properti Disimpan</h1>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-6">{{ session('success') }}</div>
    @endif

    @if ($properties->isEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center text-gray-500">
            Anda belum menyimpan properti apa pun.
            <a href="{{ route('home') }}" class="text-brand-blue hover:underline block mt-2">Cari properti sekarang</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($properties as $property)
                @include('properties.partials.card', ['property' => $property])
            @endforeach
        </div>

        <div class="mt-8">
            {{ $properties->links() }}
        </div>
    @endif

</div>
@endsection