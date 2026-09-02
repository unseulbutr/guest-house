@extends('layouts.app')

@section('title', 'Tambah Fasilitas')

@section('content')
<div class="max-w-md mx-auto px-4 py-10">

    <a href="{{ route('admin.facilities.index') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali</a>

    <h1 class="text-2xl font-extrabold text-navy-900 mt-3 mb-6">Tambah Fasilitas</h1>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.facilities.store') }}" class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: AC, WiFi, Kolam Renang Pribadi"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Icon (opsional, emoji juga boleh)</label>
            <input type="text" name="icon" value="{{ old('icon') }}" placeholder="Contoh: ❄️ atau nama ikon"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
        </div>

        <button type="submit" class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-2.5 rounded-lg">
            Simpan Fasilitas
        </button>
    </form>
</div>
@endsection