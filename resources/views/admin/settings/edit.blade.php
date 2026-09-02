@extends('layouts.app')

@section('title', 'Pengaturan Platform')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <a href="{{ route('dashboard') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali ke Dashboard</a>

    <div class="flex items-center gap-2 mt-3 mb-1">
        <h1 class="text-2xl font-extrabold text-navy-900">Pengaturan Platform</h1>
        <span class="text-[10px] font-bold uppercase tracking-wide bg-navy-900 text-brand-yellow px-2 py-1 rounded-full">Super Admin</span>
    </div>
    <p class="text-sm text-gray-500 mb-6">Kebijakan komisi & konfigurasi global platform.</p>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white border border-gray-100 rounded-2xl p-6 space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <h2 class="font-bold text-navy-900 mb-1">Skema Komisi Default</h2>
            <p class="text-xs text-gray-500 mb-4">
                Dipakai sebagai persentase komisi otomatis saat Mitra mendaftarkan properti baru
                (properti yang sudah ada tidak ikut berubah, cuma properti baru yang pakai nilai ini).
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Komisi Skema Mandiri (%)
                    </label>
                    <input type="number" step="0.01" min="0" max="100" name="commission_mandiri_percentage"
                           value="{{ old('commission_mandiri_percentage', $settings['commission_mandiri_percentage']) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
                    <p class="text-[11px] text-gray-400 mt-1">Mitra kelola properti sendiri.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Komisi Skema Dikelola (%)
                    </label>
                    <input type="number" step="0.01" min="0" max="100" name="commission_dikelola_percentage"
                           value="{{ old('commission_dikelola_percentage', $settings['commission_dikelola_percentage']) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
                    <p class="text-[11px] text-gray-400 mt-1">Sudah termasuk pajak (final).</p>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg">
            Simpan Pengaturan
        </button>
    </form>
</div>
@endsection