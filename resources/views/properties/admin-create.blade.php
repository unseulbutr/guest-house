@extends('layouts.app')

@section('title', 'Tambah Properti')

@section('dashboard_layout', 'true')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- HEADER --}}
        <div class="mb-8">

            <a
                href="{{ route('admin.properties.index') }}"
                class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-semibold"
            >
                ← Kembali ke Properti
            </a>

            <div class="mt-4">

                <p class="text-sm font-semibold text-blue-600">
                    Admin Management
                </p>

                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mt-1">
                    Tambah Properti
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Tambahkan properti baru dan tentukan Mitra pemiliknya.
                </p>

            </div>

        </div>


        {{-- ERROR --}}
        @if($errors->any())

            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">

                <div class="font-bold text-red-800 mb-2">
                    Periksa kembali data berikut:
                </div>

                <ul class="text-sm text-red-700 space-y-1">

                    @foreach($errors->all() as $error)

                        <li>
                            • {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('admin.properties.store') }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >

            @csrf


            {{-- MITRA --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <div class="mb-5">

                    <h2 class="font-bold text-lg text-slate-900">
                        Pemilik Properti
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Tentukan akun Mitra yang memiliki properti ini.
                    </p>

                </div>

                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Mitra
                </label>

                <select
                    name="mitra_id"
                    required
                    class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        -- Pilih Mitra --
                    </option>

                    @foreach($mitras as $mitra)

                        <option
                            value="{{ $mitra->id }}"
                            {{ old('mitra_id') == $mitra->id ? 'selected' : '' }}
                        >
                            {{ $mitra->name }}
                            @if($mitra->email)
                                — {{ $mitra->email }}
                            @endif
                        </option>

                    @endforeach

                </select>

                @if($mitras->isEmpty())

                    <p class="text-sm text-amber-600 mt-2">
                        Belum ada akun Mitra yang tersedia.
                    </p>

                @endif

            </div>


            {{-- INFO DASAR --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <h2 class="font-bold text-lg text-slate-900 mb-5">
                    Informasi Dasar
                </h2>

                <div class="space-y-5">

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Properti
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Contoh: Guest House Sejuk Ciawi"
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Tipe Properti
                            </label>

                            <select
                                name="type"
                                required
                                class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >

                                <option
                                    value="guesthouse"
                                    {{ old('type') === 'guesthouse' ? 'selected' : '' }}
                                >
                                    Guest House
                                </option>

                                <option
                                    value="kost_harian"
                                    {{ old('type') === 'kost_harian' ? 'selected' : '' }}
                                >
                                    Kost Harian
                                </option>

                                <option
                                    value="villa"
                                    {{ old('type') === 'villa' ? 'selected' : '' }}
                                >
                                    Villa
                                </option>

                            </select>

                        </div>


                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Kelas Bintang
                            </label>

                            <select
                                name="star_rating"
                                required
                                class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >

                                @for($i = 1; $i <= 5; $i++)

                                    <option
                                        value="{{ $i }}"
                                        {{ old('star_rating', 1) == $i ? 'selected' : '' }}
                                    >
                                        {{ str_repeat('⭐', $i) }}
                                        {{ $i }} Bintang
                                    </option>

                                @endfor

                            </select>

                        </div>

                    </div>


                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            placeholder="Jelaskan fasilitas, suasana, lokasi, dan keunggulan properti..."
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- LOKASI --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <h2 class="font-bold text-lg text-slate-900 mb-5">
                    Lokasi
                </h2>

                <div class="space-y-5">

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Alamat
                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            required
                            placeholder="Alamat lengkap properti..."
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('address') }}</textarea>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Kota
                            </label>

                            <input
                                type="text"
                                name="city"
                                value="{{ old('city') }}"
                                required
                                placeholder="Bogor"
                                class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Latitude
                            </label>

                            <input
                                type="text"
                                name="latitude"
                                value="{{ old('latitude') }}"
                                placeholder="-6.595038"
                                class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm"
                            >

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Longitude
                            </label>

                            <input
                                type="text"
                                name="longitude"
                                value="{{ old('longitude') }}"
                                placeholder="106.816635"
                                class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm"
                            >

                        </div>

                    </div>

                </div>

            </div>


            {{-- KAPASITAS --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <h2 class="font-bold text-lg text-slate-900 mb-5">
                    Kapasitas & Harga
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Jumlah Kamar
                        </label>

                        <input
                            type="number"
                            name="bedroom_count"
                            min="1"
                            value="{{ old('bedroom_count', 1) }}"
                            required
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm"
                        >

                    </div>


                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kapasitas Tamu
                        </label>

                        <input
                            type="number"
                            name="guest_capacity"
                            min="1"
                            value="{{ old('guest_capacity', 2) }}"
                            required
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm"
                        >

                    </div>


                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Harga / Malam
                        </label>

                        <input
                            type="number"
                            name="price_per_night"
                            min="0"
                            value="{{ old('price_per_night') }}"
                            required
                            placeholder="250000"
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm"
                        >

                    </div>

                </div>

            </div>


            {{-- MANAGEMENT --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <h2 class="font-bold text-lg text-slate-900 mb-2">
                    Skema Pengelolaan
                </h2>

                <p class="text-sm text-slate-500 mb-5">
                    Komisi akan otomatis mengikuti skema yang dipilih.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <label class="border-2 rounded-2xl p-5 cursor-pointer hover:border-blue-300 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">

                        <input
                            type="radio"
                            name="management_type"
                            value="mandiri"
                            class="sr-only"
                            {{ old('management_type', 'mandiri') === 'mandiri' ? 'checked' : '' }}
                        >

                        <div class="font-bold text-slate-900">
                            Kelola Mandiri
                        </div>

                        <p class="text-sm text-slate-500 mt-1">
                            Mitra mengatur operasional properti sendiri.
                        </p>

                        <div class="text-blue-600 font-bold text-xl mt-3">
                            15%
                        </div>

                        <div class="text-xs text-slate-400">
                            Komisi platform
                        </div>

                    </label>


                    <label class="border-2 rounded-2xl p-5 cursor-pointer hover:border-blue-300 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">

                        <input
                            type="radio"
                            name="management_type"
                            value="dikelola"
                            class="sr-only"
                            {{ old('management_type') === 'dikelola' ? 'checked' : '' }}
                        >

                        <div class="font-bold text-slate-900">
                            Dikelola Platform
                        </div>

                        <p class="text-sm text-slate-500 mt-1">
                            Operasional properti dibantu oleh platform.
                        </p>

                        <div class="text-blue-600 font-bold text-xl mt-3">
                            45%
                        </div>

                        <div class="text-xs text-slate-400">
                            Sudah termasuk pajak
                        </div>

                    </label>

                </div>

            </div>


            {{-- FACILITIES --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <h2 class="font-bold text-lg text-slate-900">
                    Fasilitas
                </h2>

                <p class="text-sm text-slate-500 mt-1 mb-5">
                    Pilih fasilitas yang tersedia.
                </p>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">

                    @forelse($facilities as $facility)

                        <label class="flex items-center gap-3 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">

                            <input
                                type="checkbox"
                                name="facilities[]"
                                value="{{ $facility->id }}"
                                {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >

                            <span class="text-sm font-medium text-slate-700">
                                {{ $facility->icon }}
                                {{ $facility->name }}
                            </span>

                        </label>

                    @empty

                        <div class="col-span-full rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-700">
                            Belum ada fasilitas master.
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- IMAGES --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                    <h2 class="font-bold text-lg text-slate-900">
                        Foto Sampul
                    </h2>

                    <p class="text-xs text-slate-500 mt-1 mb-4">
                        Foto utama yang akan tampil pada listing.
                    </p>

                    <input
                        type="file"
                        name="cover_image"
                        accept="image/*"
                        class="block w-full text-sm"
                    >

                </div>


                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                    <h2 class="font-bold text-lg text-slate-900">
                        Galeri Foto
                    </h2>

                    <p class="text-xs text-slate-500 mt-1 mb-4">
                        Tambahkan foto kamar, fasilitas, dapur, halaman, dan sebagainya.
                    </p>

                    <input
                        type="file"
                        name="photos[]"
                        accept="image/*"
                        multiple
                        class="block w-full text-sm"
                    >

                </div>

            </div>


            {{-- INFO STATUS --}}
            <div class="rounded-2xl bg-blue-50 border border-blue-200 p-5">

                <div class="flex gap-3">

                    <div class="text-xl">
                        ℹ️
                    </div>

                    <div>

                        <div class="font-bold text-blue-900">
                            Properti dari Admin
                        </div>

                        <p class="text-sm text-blue-700 mt-1">
                            Properti yang dibuat oleh Admin akan langsung berstatus
                            <strong>Active</strong> dan dapat tampil di website.
                        </p>

                    </div>

                </div>

            </div>


            {{-- SUBMIT --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">

                <a
                    href="{{ route('admin.properties.index') }}"
                    class="px-6 py-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold text-center"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-7 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-sm transition"
                >
                    Simpan & Aktifkan Properti
                </button>

            </div>

        </form>

    </div>

</div>

@endsection