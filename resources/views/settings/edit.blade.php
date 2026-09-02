@extends('layouts.app')

@section('title', 'Pengaturan Platform')

@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <h1 class="font-display text-2xl font-extrabold text-navy-900 mb-1">Pengaturan Platform</h1>
    <p class="text-sm text-gray-500 mb-8">Khusus Super Admin — pengaturan global yang berlaku di seluruh sistem.</p>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-700 text-sm px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        {{-- ===== Info Situs ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-card p-6 space-y-4">
            <h2 class="font-display font-bold text-navy-900">Info Situs</h2>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nama Situs</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-navy-900 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                @error('site_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- ===== Kontak ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-card p-6 space-y-4">
            <h2 class="font-display font-bold text-navy-900">Kontak</h2>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Email Kontak</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-navy-900 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                @error('contact_email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">No. Telepon</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-navy-900 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                    @error('contact_phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">No. WhatsApp</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" placeholder="62812xxxxxxx"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-navy-900 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                    @error('whatsapp_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ===== Komisi Default ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-card p-6 space-y-4">
            <h2 class="font-display font-bold text-navy-900">Komisi Default</h2>
            <p class="text-xs text-gray-500 -mt-2">Dipakai sebagai persentase komisi awal saat mitra memilih skema pengelolaan properti baru.</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Kelola Mandiri (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="default_commission_mandiri"
                           value="{{ old('default_commission_mandiri', $settings->default_commission_mandiri) }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-navy-900 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                    @error('default_commission_mandiri') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Dikelola Platform (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="default_commission_dikelola"
                           value="{{ old('default_commission_dikelola', $settings->default_commission_dikelola) }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-navy-900 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                    @error('default_commission_dikelola') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    <p class="text-[11px] text-gray-400 mt-1">*sudah termasuk pajak</p>
                </div>
            </div>
        </div>

        {{-- ===== Mode Perawatan ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-card p-6">
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <h2 class="font-display font-bold text-navy-900">Mode Perawatan (Maintenance)</h2>
                    <p class="text-xs text-gray-500 mt-1">Kalau aktif, situs publik akan menampilkan halaman "sedang perawatan" untuk pengunjung biasa.</p>
                </div>
                <input type="hidden" name="maintenance_mode" value="0">
                <input type="checkbox" name="maintenance_mode" value="1"
                       {{ old('maintenance_mode', $settings->maintenance_mode) ? 'checked' : '' }}
                       class="w-5 h-5 rounded accent-brand-blue shrink-0 ml-4">
            </label>
        </div>

        <button type="submit"
                class="bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-6 py-2.5 rounded-full">
            Simpan Pengaturan
        </button>
    </form>
</section>
@endsection