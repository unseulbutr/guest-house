@extends('layouts.app')

@section('title', 'Edit Properti')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-10">

    <a
        href="{{ route('mitra.properties.index') }}"
        class="text-sm text-brand-blue hover:underline"
    >
        &larr; Kembali ke Properti Saya
    </a>

    <h1 class="text-2xl font-extrabold text-navy-900 mt-3 mb-6">
        Edit Properti
    </h1>


    {{-- SUCCESS --}}
    @if (session('success'))

        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">
            {{ session('success') }}
        </div>

    @endif


    {{-- ERROR --}}
    @if ($errors->any())

        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">

            @foreach ($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    @php

        $checkedFacilityIds = $property->facilities
            ->where('pivot.is_available', true)
            ->pluck('id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | RATING BINTANG
        |--------------------------------------------------------------------------
        */

        $currentRating = old(
            'star_rating',
            $property->star_rating ?? 1
        );

        $currentRating = (int) $currentRating;

        if ($currentRating < 1) {
            $currentRating = 1;
        }

        if ($currentRating > 5) {
            $currentRating = 5;
        }

    @endphp


    <form
        method="POST"
        action="{{ route('mitra.properties.update', $property) }}"
        enctype="multipart/form-data"
        class="space-y-6"
    >

        @csrf

        @method('PUT')


        {{-- =========================================================
             INFO DASAR
             ========================================================= --}}

        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">

            <h2 class="font-bold text-navy-900">
                Info Dasar
            </h2>


            {{-- NAMA --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Properti
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $property->name) }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
                >

            </div>


            {{-- TIPE --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tipe Properti
                </label>

                <select
                    name="type"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm"
                >

                    <option
                        value="guesthouse"
                        {{ old('type', $property->type) === 'guesthouse' ? 'selected' : '' }}
                    >
                        Guest House
                    </option>

                    <option
                        value="kost_harian"
                        {{ old('type', $property->type) === 'kost_harian' ? 'selected' : '' }}
                    >
                        Kost Harian
                    </option>

                    <option
                        value="villa"
                        {{ old('type', $property->type) === 'villa' ? 'selected' : '' }}
                    >
                        Villa
                    </option>

                </select>

            </div>


            {{-- DESKRIPSI --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
                >{{ old('description', $property->description) }}</textarea>

            </div>


            {{-- ALAMAT + KOTA --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat
                    </label>

                    <input
                        type="text"
                        name="address"
                        value="{{ old('address', $property->address) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
                    >

                </div>


                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Kota
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city', $property->city) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
                    >

                </div>

            </div>


            {{-- LATITUDE + LONGITUDE --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Latitude (opsional)
                    </label>

                    <input
                        type="text"
                        name="latitude"
                        value="{{ old('latitude', $property->latitude) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
                    >

                </div>


                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Longitude (opsional)
                    </label>

                    <input
                        type="text"
                        name="longitude"
                        value="{{ old('longitude', $property->longitude) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
                    >

                </div>

            </div>

        </div>



        {{-- =========================================================
             RATING BINTANG
             ========================================================= --}}

        <div class="bg-white border border-gray-100 rounded-2xl p-6">

            <h2 class="font-bold text-navy-900 mb-2">
                Rating Properti
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Tentukan tingkat properti berdasarkan kualitas dan kemewahannya.
            </p>


            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">

                {{-- BINTANG 1 --}}
                <label class="cursor-pointer">

                    <input
                        type="radio"
                        name="star_rating"
                        value="1"
                        class="peer sr-only"
                        {{ $currentRating === 1 ? 'checked' : '' }}
                    >

                    <div
                        class="border border-gray-200 rounded-xl p-4 text-center transition
                        peer-checked:border-yellow-400
                        peer-checked:bg-yellow-50
                        hover:border-yellow-300"
                    >

                        <div class="text-2xl text-yellow-400">
                            ★
                        </div>

                        <div class="font-bold text-sm text-gray-800 mt-1">
                            1 Bintang
                        </div>

                        <div class="text-[11px] text-gray-500 mt-1">
                            Sederhana
                        </div>

                    </div>

                </label>


                {{-- BINTANG 2 --}}
                <label class="cursor-pointer">

                    <input
                        type="radio"
                        name="star_rating"
                        value="2"
                        class="peer sr-only"
                        {{ $currentRating === 2 ? 'checked' : '' }}
                    >

                    <div
                        class="border border-gray-200 rounded-xl p-4 text-center transition
                        peer-checked:border-yellow-400
                        peer-checked:bg-yellow-50
                        hover:border-yellow-300"
                    >

                        <div class="text-2xl text-yellow-400">
                            ★★
                        </div>

                        <div class="font-bold text-sm text-gray-800 mt-1">
                            2 Bintang
                        </div>

                        <div class="text-[11px] text-gray-500 mt-1">
                            Standar
                        </div>

                    </div>

                </label>


                {{-- BINTANG 3 --}}
                <label class="cursor-pointer">

                    <input
                        type="radio"
                        name="star_rating"
                        value="3"
                        class="peer sr-only"
                        {{ $currentRating === 3 ? 'checked' : '' }}
                    >

                    <div
                        class="border border-gray-200 rounded-xl p-4 text-center transition
                        peer-checked:border-yellow-400
                        peer-checked:bg-yellow-50
                        hover:border-yellow-300"
                    >

                        <div class="text-2xl text-yellow-400">
                            ★★★
                        </div>

                        <div class="font-bold text-sm text-gray-800 mt-1">
                            3 Bintang
                        </div>

                        <div class="text-[11px] text-gray-500 mt-1">
                            Nyaman
                        </div>

                    </div>

                </label>


                {{-- BINTANG 4 --}}
                <label class="cursor-pointer">

                    <input
                        type="radio"
                        name="star_rating"
                        value="4"
                        class="peer sr-only"
                        {{ $currentRating === 4 ? 'checked' : '' }}
                    >

                    <div
                        class="border border-gray-200 rounded-xl p-4 text-center transition
                        peer-checked:border-yellow-400
                        peer-checked:bg-yellow-50
                        hover:border-yellow-300"
                    >

                        <div class="text-2xl text-yellow-400">
                            ★★★★
                        </div>

                        <div class="font-bold text-sm text-gray-800 mt-1">
                            4 Bintang
                        </div>

                        <div class="text-[11px] text-gray-500 mt-1">
                            Premium
                        </div>

                    </div>

                </label>


                {{-- BINTANG 5 --}}
                <label class="cursor-pointer">

                    <input
                        type="radio"
                        name="star_rating"
                        value="5"
                        class="peer sr-only"
                        {{ $currentRating === 5 ? 'checked' : '' }}
                    >

                    <div
                        class="border border-gray-200 rounded-xl p-4 text-center transition
                        peer-checked:border-yellow-400
                        peer-checked:bg-yellow-50
                        hover:border-yellow-300"
                    >

                        <div class="text-2xl text-yellow-400">
                            ★★★★★
                        </div>

                        <div class="font-bold text-sm text-gray-800 mt-1">
                            5 Bintang
                        </div>

                        <div class="text-[11px] text-gray-500 mt-1">
                            Mewah
                        </div>

                    </div>

                </label>

            </div>

        </div>



        {{-- =========================================================
             KAPASITAS & HARGA
             ========================================================= --}}

        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">

            <h2 class="font-bold text-navy-900">
                Kapasitas &amp; Harga
            </h2>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- KAMAR --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jumlah Kamar Tidur
                    </label>

                    <input
                        type="number"
                        name="bedroom_count"
                        min="1"
                        value="{{ old('bedroom_count', $property->bedroom_count) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
                    >

                </div>


                {{-- KAPASITAS --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Kapasitas Tamu
                    </label>

                    <input
                        type="number"
                        name="guest_capacity"
                        min="1"
                        value="{{ old('guest_capacity', $property->guest_capacity) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
                    >

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
                        value="{{ old('price_per_night', $property->price_per_night) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue"
                    >

                </div>

            </div>

        </div>



        {{-- =========================================================
             SKEMA PENGELOLAAN
             ========================================================= --}}

        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">

            <h2 class="font-bold text-navy-900">
                Skema Pengelolaan
            </h2>

            <p class="text-sm text-gray-500">
                Komisi otomatis mengikuti skema yang dipilih.
            </p>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                {{-- MANDIRI --}}
                <label
                    class="border rounded-xl p-4 cursor-pointer has-[:checked]:border-brand-blue has-[:checked]:bg-blue-50 transition"
                >

                    <input
                        type="radio"
                        name="management_type"
                        value="mandiri"
                        class="hidden"
                        {{ old('management_type', $property->management_type) === 'mandiri' ? 'checked' : '' }}
                    >

                    <div class="font-semibold text-navy-900">
                        Kelola Mandiri
                    </div>

                    <div class="text-xs text-gray-500 mt-1">
                        Anda yang atur ketersediaan, harga, dan respon booking.
                    </div>

                    <div class="text-brand-blue font-bold text-lg mt-2">
                        Komisi 15%
                    </div>

                </label>


                {{-- DIKELOLA --}}
                <label
                    class="border rounded-xl p-4 cursor-pointer has-[:checked]:border-brand-blue has-[:checked]:bg-blue-50 transition"
                >

                    <input
                        type="radio"
                        name="management_type"
                        value="dikelola"
                        class="hidden"
                        {{ old('management_type', $property->management_type) === 'dikelola' ? 'checked' : '' }}
                    >

                    <div class="font-semibold text-navy-900">
                        Dikelola Platform
                    </div>

                    <div class="text-xs text-gray-500 mt-1">
                        Tim kami yang urus operasional harian properti Anda.
                    </div>

                    <div class="text-brand-blue font-bold text-lg mt-2">
                        Komisi 45%
                    </div>

                    <div class="text-[11px] text-gray-400">
                        *sudah termasuk pajak
                    </div>

                </label>

            </div>

        </div>



        {{-- =========================================================
             CHECKLIST FASILITAS
             ========================================================= --}}

        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">

            <h2 class="font-bold text-navy-900">
                Checklist Fasilitas
            </h2>

            <p class="text-sm text-gray-500">
                Centang fasilitas yang tersedia. Yang tidak dicentang otomatis dianggap tidak tersedia.
            </p>


            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

                @forelse ($facilities as $facility)

                    <label
                        class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2.5 cursor-pointer has-[:checked]:border-green-500 has-[:checked]:bg-green-50 transition"
                    >

                        <input
                            type="checkbox"
                            name="facilities[]"
                            value="{{ $facility->id }}"
                            {{ in_array($facility->id, old('facilities', $checkedFacilityIds)) ? 'checked' : '' }}
                            class="accent-green-600"
                        >

                        <span class="text-sm text-navy-900">
                            {{ $facility->icon }} {{ $facility->name }}
                        </span>

                    </label>

                @empty

                    <p class="text-sm text-gray-500 col-span-full">
                        Belum ada data fasilitas master.
                    </p>

                @endforelse

            </div>

        </div>



        {{-- =========================================================
             FOTO SAMPUL
             ========================================================= --}}

        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-2">

            <h2 class="font-bold text-navy-900">
                Foto Sampul
            </h2>


            <img
                id="coverPreview"
                src="{{ $property->cover_image
                    ? asset('storage/' . $property->cover_image)
                    : '' }}"
                class="w-40 h-28 object-cover rounded-lg mb-2 {{ $property->cover_image ? '' : 'hidden' }}"
            >


            <input
                type="file"
                name="cover_image"
                id="coverInput"
                accept="image/*"
                class="text-sm"
            >


            <p class="text-xs text-gray-400">
                Kosongkan kalau tidak ingin mengganti foto sampul.
            </p>

        </div>



        {{-- =========================================================
             GALERI FOTO
             ========================================================= --}}

        <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">

            <h2 class="font-bold text-navy-900">
                Galeri Foto
            </h2>


            {{-- FOTO LAMA --}}
            @if ($property->images->isNotEmpty())

                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">

                    @foreach ($property->images as $image)

                        <div class="relative group">

                            <img
                                src="{{ asset('storage/' . $image->path) }}"
                                class="w-full h-24 object-cover rounded-lg border border-gray-100"
                            >


                            <button
                                type="button"
                                onclick="document.getElementById('delete-image-{{ $image->id }}').submit()"
                                class="absolute top-1 right-1 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 text-white text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition"
                            >
                                ✕
                            </button>

                        </div>

                    @endforeach

                </div>

            @else

                <p class="text-sm text-gray-500">
                    Belum ada foto galeri.
                </p>

            @endif


            {{-- FOTO BARU --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tambah Foto Baru
                </label>


                <div
                    id="newPhotosContainer"
                    class="space-y-3"
                >

                    {{-- INPUT FOTO PERTAMA --}}
                    <div class="photo-input-item flex items-center gap-3">

                        {{-- PREVIEW --}}
                        <div class="w-24 h-20 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex-shrink-0">

                            <img
                                src=""
                                alt="Preview foto"
                                class="photo-preview w-full h-full object-cover hidden"
                            >

                            <div
                                class="photo-placeholder w-full h-full flex items-center justify-center text-xs text-gray-400"
                            >
                                Foto
                            </div>

                        </div>


                        {{-- INPUT --}}
                        <input
                            type="file"
                            name="photos[]"
                            accept="image/*"
                            class="photo-file-input text-sm"
                        >


                        {{-- HAPUS --}}
                        <button
                            type="button"
                            class="remove-photo hidden text-red-500 hover:text-red-700 text-sm"
                        >
                            Hapus
                        </button>

                    </div>

                </div>


                {{-- TAMBAH --}}
                <button
                    type="button"
                    id="addPhotoButton"
                    class="mt-3 px-4 py-2 border border-brand-blue text-brand-blue rounded-lg text-sm font-medium hover:bg-blue-50 transition"
                >
                    + Tambahkan Foto
                </button>


                <p class="text-xs text-gray-400 mt-2">
                    Pilih foto untuk melihat preview. Klik "+ Tambahkan Foto"
                    untuk menambah foto berikutnya.
                    Semua foto akan disimpan saat menekan "Simpan Perubahan".
                </p>

            </div>

        </div>



        {{-- =========================================================
             SIMPAN
             ========================================================= --}}

        <button
            type="submit"
            class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg"
        >
            Simpan Perubahan
        </button>


    </form>


    {{-- =========================================================
         FORM HAPUS FOTO
         ========================================================= --}}

    @foreach ($property->images as $image)

        <form
            id="delete-image-{{ $image->id }}"
            action="{{ route('mitra.properties.images.destroy', $image) }}"
            method="POST"
            class="hidden"
        >

            @csrf

            @method('DELETE')

        </form>

    @endforeach


</div>



{{-- =============================================================
     JAVASCRIPT FOTO SAMPUL
     ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const coverInput = document.getElementById('coverInput');
    const coverPreview = document.getElementById('coverPreview');

    if (coverInput) {

        coverInput.addEventListener('change', function (event) {

            const file = event.target.files[0];

            if (!file) {
                return;
            }

            if (!file.type.startsWith('image/')) {

                alert('File yang dipilih harus berupa gambar.');

                this.value = '';

                return;
            }

            coverPreview.src = URL.createObjectURL(file);

            coverPreview.classList.remove('hidden');

        });

    }

});

</script>



{{-- =============================================================
     JAVASCRIPT GALERI
     ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('newPhotosContainer');

    const addButton = document.getElementById('addPhotoButton');


    if (!container || !addButton) {
        return;
    }


    function setupPhotoInput(item) {

        const input = item.querySelector('.photo-file-input');

        const preview = item.querySelector('.photo-preview');

        const placeholder = item.querySelector('.photo-placeholder');

        const removeButton = item.querySelector('.remove-photo');


        if (!input) {
            return;
        }


        input.addEventListener('change', function () {

            const file = this.files[0];


            if (!file) {
                return;
            }


            if (!file.type.startsWith('image/')) {

                alert('File yang dipilih harus berupa gambar.');

                this.value = '';

                return;
            }


            const imageUrl = URL.createObjectURL(file);


            preview.src = imageUrl;

            preview.classList.remove('hidden');

            placeholder.classList.add('hidden');


            if (removeButton) {

                removeButton.classList.remove('hidden');

            }

        });


        if (removeButton) {

            removeButton.addEventListener('click', function () {

                item.remove();

            });

        }

    }


    // Aktifkan input pertama

    const firstItem = container.querySelector('.photo-input-item');

    if (firstItem) {

        setupPhotoInput(firstItem);

    }


    // Tambahkan input foto

    addButton.addEventListener('click', function () {

        const wrapper = document.createElement('div');

        wrapper.className = 'photo-input-item flex items-center gap-3';


        wrapper.innerHTML = `

            <div class="w-24 h-20 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex-shrink-0">

                <img
                    src=""
                    alt="Preview foto"
                    class="photo-preview w-full h-full object-cover hidden"
                >

                <div
                    class="photo-placeholder w-full h-full flex items-center justify-center text-xs text-gray-400"
                >
                    Foto
                </div>

            </div>


            <input
                type="file"
                name="photos[]"
                accept="image/*"
                class="photo-file-input text-sm"
            >


            <button
                type="button"
                class="remove-photo hidden text-red-500 hover:text-red-700 text-sm"
            >
                Hapus
            </button>

        `;


        container.appendChild(wrapper);


        setupPhotoInput(wrapper);

    });

});

</script>

@endsection