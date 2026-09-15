@extends('layouts.app')

@section('title', 'Tambah Properti')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <a href="{{ route('mitra.properties.index') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali ke Properti Saya</a>

    <h1 class="text-2xl font-extrabold text-navy-900 mt-3 mb-6">Tambah Properti Baru</h1>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('mitra.properties.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- ===== Info Dasar ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">
            <h2 class="font-bold text-navy-900">Info Dasar</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Properti</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Villa Sejuk Kaliurang"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Properti</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                    <option value="guesthouse" {{ old('type') === 'guesthouse' ? 'selected' : '' }}>Guest House</option>
                    <option value="kost_harian" {{ old('type') === 'kost_harian' ? 'selected' : '' }}>Kost Harian</option>
                    <option value="villa" {{ old('type') === 'villa' ? 'selected' : '' }}>Villa</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="4" placeholder="Ceritakan keunggulan properti Anda..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <input type="text" name="address" value="{{ old('address') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                    <input type="text" name="city" value="{{ old('city') }}" required placeholder="Contoh: Yogyakarta"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude (opsional)</label>
                    <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="-7.797068"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude (opsional)</label>
                    <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="110.370529"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
                </div>
            </div>
        </div>

        {{-- ===== Kapasitas & Harga ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">
            <h2 class="font-bold text-navy-900">Kapasitas &amp; Harga</h2>

           <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Jumlah Kamar Tidur
        </label>

        <input
            type="number"
            name="bedroom_count"
            min="1"
            value="{{ old('bedroom_count') }}"
            required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
        >
    </div>


    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Kapasitas Tamu
        </label>

        <input
            type="number"
            name="guest_capacity"
            min="1"
            value="{{ old('guest_capacity') }}"
            required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
        >
    </div>

</div>


{{-- ===== KELAS BINTANG & HARGA ===== --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    {{-- BINTANG --}}
    <div>

        <label class="block text-sm font-medium text-gray-700 mb-1">
            Kelas Bintang Properti
        </label>

        <select
            name="star_rating"
            required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
        >

            <option value="1" {{ old('star_rating', 1) == 1 ? 'selected' : '' }}>
                ⭐ 1 Bintang — Sederhana
            </option>

            <option value="2" {{ old('star_rating') == 2 ? 'selected' : '' }}>
                ⭐⭐ 2 Bintang — Standar
            </option>

            <option value="3" {{ old('star_rating') == 3 ? 'selected' : '' }}>
                ⭐⭐⭐ 3 Bintang — Nyaman
            </option>

            <option value="4" {{ old('star_rating') == 4 ? 'selected' : '' }}>
                ⭐⭐⭐⭐ 4 Bintang — Premium
            </option>

            <option value="5" {{ old('star_rating') == 5 ? 'selected' : '' }}>
                ⭐⭐⭐⭐⭐ 5 Bintang — Mewah
            </option>

        </select>

        <p class="text-xs text-gray-400 mt-1">
            Tentukan kelas fasilitas dan kenyamanan properti.
        </p>

    </div>


    {{-- HARGA --}}
    <div>

        <label class="block text-sm font-medium text-gray-700 mb-1">
            Harga / Malam (Rp)
        </label>

        <input
            type="number"
            name="price_per_night"
            min="0"
            value="{{ old('price_per_night') }}"
            required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
        >

    </div>

</div>
        </div>

        {{-- ===== Skema Pengelolaan ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">
            <h2 class="font-bold text-navy-900">Skema Pengelolaan</h2>
            <p class="text-sm text-gray-500">Pilih siapa yang mengelola operasional properti ini. Komisi otomatis mengikuti skema yang dipilih.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="border rounded-xl p-4 cursor-pointer has-[:checked]:border-brand-blue has-[:checked]:bg-blue-50 transition">
                    <input type="radio" name="management_type" value="mandiri" class="hidden"
                           {{ old('management_type', 'mandiri') === 'mandiri' ? 'checked' : '' }}>
                    <div class="font-semibold text-navy-900">Kelola Mandiri</div>
                    <div class="text-xs text-gray-500 mt-1">Anda yang atur ketersediaan, harga, dan respon booking.</div>
                    <div class="text-brand-blue font-bold text-lg mt-2">Komisi 15%</div>
                </label>
                <label class="border rounded-xl p-4 cursor-pointer has-[:checked]:border-brand-blue has-[:checked]:bg-blue-50 transition">
                    <input type="radio" name="management_type" value="dikelola" class="hidden"
                           {{ old('management_type') === 'dikelola' ? 'checked' : '' }}>
                    <div class="font-semibold text-navy-900">Dikelola Platform</div>
                    <div class="text-xs text-gray-500 mt-1">Tim kami yang urus operasional harian properti Anda.</div>
                    <div class="text-brand-blue font-bold text-lg mt-2">Komisi 45%</div>
                    <div class="text-[11px] text-gray-400">*sudah termasuk pajak</div>
                </label>
            </div>
        </div>

        {{-- ===== Checklist Fasilitas ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">
            <h2 class="font-bold text-navy-900">Checklist Fasilitas</h2>
            <p class="text-sm text-gray-500">Centang fasilitas yang tersedia di properti Anda. Yang tidak dicentang otomatis dianggap tidak tersedia.</p>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @forelse ($facilities as $facility)
                    <label class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2.5 cursor-pointer has-[:checked]:border-green-500 has-[:checked]:bg-green-50 transition">
                        <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                               {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}
                               class="accent-green-600">
                        <span class="text-sm text-navy-900">{{ $facility->icon }} {{ $facility->name }}</span>
                    </label>
                @empty
                    <p class="text-sm text-gray-500 col-span-full">
                        Belum ada data fasilitas master.
                        @auth
                            @role('admin|super_admin')
                                <a href="{{ route('admin.facilities.create') }}" class="text-brand-blue hover:underline">Tambah fasilitas dulu.</a>
                            @endrole
                        @endauth
                    </p>
                @endforelse
            </div>
        </div>

        {{-- ===== Foto Sampul ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-2">
            <h2 class="font-bold text-navy-900">Foto Sampul</h2>
            <p class="text-xs text-gray-500 mb-2">Foto utama yang tampil di kartu listing homepage.</p>
            <input type="file" name="cover_image" accept="image/*" class="text-sm">
        </div>

        {{-- ===== Galeri Foto Tambahan ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-2">
            <h2 class="font-bold text-navy-900">Galeri Foto</h2>
            <p class="text-xs text-gray-500 mb-2">
                Foto-foto lain buat halaman detail properti (kamar, kolam renang, dapur, dll) — kayak di Traveloka.
                Bisa pilih beberapa foto sekaligus.
            </p>
            <input type="file" name="photos[]" accept="image/*" multiple class="text-sm">
        </div>

        <button type="submit"
                class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg">
            Simpan Properti (Menunggu Verifikasi Admin)
        </button>
    </form>
</div>
@endsection