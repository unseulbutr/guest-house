@extends('layouts.app')

@section('title', 'Tambah Properti')

@section('dashboard_layout', 'true')

@section('content')

<div class="min-h-screen bg-slate-50 py-8">

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">
                    <a
                        href="{{ route('dashboard') }}"
                        class="hover:text-blue-600"
                    >
                        Dashboard
                    </a>

                    <span>/</span>

                    <a
                        href="{{ route('admin.properties.index') }}"
                        class="hover:text-blue-600"
                    >
                        Properti
                    </a>

                    <span>/</span>

                    <span class="text-slate-700">
                        Tambah
                    </span>
                </div>

                <h1 class="text-2xl font-bold text-slate-900">
                    Tambah Properti
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Admin dapat menambahkan properti untuk mitra secara langsung.
                </p>
            </div>

            <a
                href="{{ route('admin.properties.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                ← Kembali
            </a>

        </div>


        {{-- INFO --}}
        <div class="mb-6 rounded-2xl border border-blue-100 bg-blue-50 p-5">

            <div class="flex gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white">
                    ✓
                </div>

                <div>
                    <h3 class="font-semibold text-blue-900">
                        Properti dibuat oleh Admin
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-blue-700">
                        Properti yang ditambahkan melalui halaman ini akan langsung
                        berstatus aktif setelah berhasil disimpan.
                    </p>
                </div>

            </div>

        </div>


        {{-- ERROR --}}
        @if ($errors->any())

            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">

                <div class="font-semibold text-red-800">
                    Terdapat beberapa kesalahan:
                </div>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('admin.properties.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >

            @csrf


            {{-- DATA PROPERTI --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Informasi Properti
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Masukkan informasi utama properti.
                    </p>
                </div>

                <div class="grid gap-6 p-6 md:grid-cols-2">

                    {{-- MITRA --}}
                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Pemilik / Mitra
                        </label>

                        <select
                            name="mitra_id"
                            required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                            <option value="">
                                -- Pilih Mitra --
                            </option>

                            @foreach ($mitras as $mitra)

                                <option
                                    value="{{ $mitra->id }}"
                                    @selected(old('mitra_id') == $mitra->id)
                                >
                                    {{ $mitra->name }}
                                    @if ($mitra->email)
                                        - {{ $mitra->email }}
                                    @endif
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- NAMA --}}
                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama Properti
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Contoh: Guest House Amaliah"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                    </div>


                    {{-- TYPE --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Tipe Properti
                        </label>

                        <select
                            name="type"
                            required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                            <option value="">
                                -- Pilih Tipe --
                            </option>

                            <option
                                value="guesthouse"
                                @selected(old('type') === 'guesthouse')
                            >
                                Guest House
                            </option>

                            <option
                                value="kost_harian"
                                @selected(old('type') === 'kost_harian')
                            >
                                Kost Harian
                            </option>

                            <option
                                value="villa"
                                @selected(old('type') === 'villa')
                            >
                                Villa
                            </option>

                        </select>

                    </div>


                    {{-- KOTA --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Kota
                        </label>

                        <input
                            type="text"
                            name="city"
                            value="{{ old('city') }}"
                            required
                            placeholder="Contoh: Bogor"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                    </div>


                    {{-- ALAMAT --}}
                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Alamat Lengkap
                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            required
                            placeholder="Masukkan alamat lengkap..."
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >{{ old('address') }}</textarea>

                    </div>


                    {{-- DESCRIPTION --}}
                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            required
                            placeholder="Jelaskan fasilitas, suasana, lokasi, dan keunggulan properti..."
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- KAPASITAS --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-6 py-5">

                    <h2 class="text-lg font-bold text-slate-900">
                        Kapasitas & Harga
                    </h2>

                </div>

                <div class="grid gap-6 p-6 md:grid-cols-3">

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Jumlah Kamar
                        </label>

                        <input
                            type="number"
                            name="bedroom_count"
                            min="1"
                            value="{{ old('bedroom_count', 1) }}"
                            required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Kapasitas Tamu
                        </label>

                        <input
                            type="number"
                            name="guest_capacity"
                            min="1"
                            value="{{ old('guest_capacity', 1) }}"
                            required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Harga / Malam
                        </label>

                        <input
                            type="number"
                            name="price_per_night"
                            min="0"
                            value="{{ old('price_per_night') }}"
                            required
                            placeholder="150000"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Rating Bintang
                        </label>

                        <select
                            name="star_rating"
                            required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                            @for ($i = 1; $i <= 5; $i++)

                                <option
                                    value="{{ $i }}"
                                    @selected(old('star_rating', 3) == $i)
                                >
                                    {{ $i }} Bintang
                                </option>

                            @endfor

                        </select>

                    </div>


                    <div class="md:col-span-2">

                        <label class="mb-3 block text-sm font-semibold text-slate-700">
                            Sistem Pengelolaan
                        </label>

                        <div class="grid gap-4 sm:grid-cols-2">

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="management_type"
                                    value="mandiri"
                                    class="peer sr-only"
                                    @checked(old('management_type', 'mandiri') === 'mandiri')
                                >

                                <div class="rounded-xl border border-slate-200 p-4 transition peer-checked:border-blue-500 peer-checked:bg-blue-50">

                                    <div class="font-semibold text-slate-900">
                                        Mandiri
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        Komisi platform 15%
                                    </div>

                                </div>

                            </label>


                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="management_type"
                                    value="dikelola"
                                    class="peer sr-only"
                                    @checked(old('management_type') === 'dikelola')
                                >

                                <div class="rounded-xl border border-slate-200 p-4 transition peer-checked:border-blue-500 peer-checked:bg-blue-50">

                                    <div class="font-semibold text-slate-900">
                                        Dikelola
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        Komisi platform 45%
                                    </div>

                                </div>

                            </label>

                        </div>

                    </div>

                </div>

            </div>


            {{-- KOORDINAT --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-6 py-5">

                    <h2 class="text-lg font-bold text-slate-900">
                        Lokasi Peta
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Opsional. Isi koordinat jika tersedia.
                    </p>

                </div>

                <div class="grid gap-6 p-6 md:grid-cols-2">

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Latitude
                        </label>

                        <input
                            type="text"
                            name="latitude"
                            value="{{ old('latitude') }}"
                            placeholder="-6.595038"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Longitude
                        </label>

                        <input
                            type="text"
                            name="longitude"
                            value="{{ old('longitude') }}"
                            placeholder="106.816635"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        >

                    </div>

                </div>

            </div>


            {{-- FACILITIES --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-6 py-5">

                    <h2 class="text-lg font-bold text-slate-900">
                        Fasilitas
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Pilih fasilitas yang tersedia.
                    </p>

                </div>

                <div class="grid gap-3 p-6 sm:grid-cols-2 lg:grid-cols-3">

                    @forelse ($facilities as $facility)

                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-4 hover:bg-slate-50">

                            <input
                                type="checkbox"
                                name="facilities[]"
                                value="{{ $facility->id }}"
                                @checked(
                                    in_array(
                                        $facility->id,
                                        old('facilities', [])
                                    )
                                )
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >

                            <span class="text-sm font-medium text-slate-700">
                                {{ $facility->name }}
                            </span>

                        </label>

                    @empty

                        <div class="sm:col-span-2 lg:col-span-3 rounded-xl bg-slate-50 p-5 text-sm text-slate-500">
                            Belum ada fasilitas.
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- FOTO --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-6 py-5">

                    <h2 class="text-lg font-bold text-slate-900">
                        Foto Properti
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Gunakan foto yang jelas dan sesuai kondisi properti.
                    </p>

                </div>

                <div class="grid gap-6 p-6 md:grid-cols-2">

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Foto Utama
                        </label>

                        <input
                            type="file"
                            name="cover_image"
                            accept="image/*"
                            class="block w-full rounded-xl border border-slate-200 bg-white text-sm file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-blue-700"
                        >

                        <p class="mt-2 text-xs text-slate-400">
                            JPG, PNG, WEBP. Maksimal 5 MB.
                        </p>

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Foto Galeri
                        </label>

                        <input
                            type="file"
                            name="photos[]"
                            accept="image/*"
                            multiple
                            class="block w-full rounded-xl border border-slate-200 bg-white text-sm file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-blue-700"
                        >

                        <p class="mt-2 text-xs text-slate-400">
                            Bisa memilih beberapa foto sekaligus.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ACTION --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('admin.properties.index') }}"
                    class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    Simpan & Aktifkan Properti
                </button>

            </div>

        </form>

    </div>

</div>

@endsection