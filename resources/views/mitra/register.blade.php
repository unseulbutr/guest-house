@extends('layouts.app')

@section('title', 'Daftar Menjadi Mitra')

@section('content')

@php
    $isGuest = !auth()->check();
    $currentUser = $user ?? auth()->user();

    $identityType = old(
        'identity_type',
        $verification->identity_type ?? 'ktp'
    );

    $identityNumber = old(
        'identity_number',
        $verification->identity_number ?? ''
    );
@endphp

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/40 px-4 py-10 sm:py-16">

    <div class="mx-auto max-w-5xl">

        {{-- ==========================================================
             HEADER
        =========================================================== --}}
        <div class="mb-8 text-center">

            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0c1830] text-2xl text-white shadow-lg">
                🏠
            </div>

            <h1 class="mt-5 font-display text-2xl font-extrabold text-[#0c1830] sm:text-3xl">
                Daftar Menjadi Mitra
            </h1>

            <p class="mx-auto mt-2 max-w-xl text-sm leading-relaxed text-gray-500">
                Lengkapi data Anda untuk mengajukan diri sebagai Mitra GuestHouse.
                Anda belum perlu memiliki atau menambahkan properti pada tahap ini.
            </p>

        </div>


        {{-- ==========================================================
             INFO GUEST
        =========================================================== --}}
        @if($isGuest)

            <div class="mx-auto mb-6 max-w-3xl rounded-2xl border border-blue-100 bg-blue-50 p-4">

                <div class="flex items-start gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-lg shadow-sm">
                        ℹ️
                    </div>

                    <div>
                        <div class="text-sm font-bold text-blue-900">
                            Belum punya akun?
                        </div>

                        <p class="mt-1 text-xs leading-5 text-blue-700">
                            Tidak masalah. Isi data akun di bawah ini.
                            Setelah pengajuan dikirim, akun Anda akan dibuat sebagai
                            <strong>Customer sementara</strong> dan menunggu verifikasi.
                            Setelah disetujui Admin atau Super Admin, role akun akan berubah menjadi
                            <strong>Mitra</strong>.
                        </p>
                    </div>

                </div>

            </div>

        @else

            <div class="mx-auto mb-6 max-w-3xl rounded-2xl border border-emerald-100 bg-emerald-50 p-4">

                <div class="flex items-start gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-lg shadow-sm">
                        ✓
                    </div>

                    <div>
                        <div class="text-sm font-bold text-emerald-900">
                            Anda sudah memiliki akun
                        </div>

                        <p class="mt-1 text-xs leading-5 text-emerald-700">
                            Pengajuan ini akan menggunakan data akun
                            <strong>{{ $currentUser->email ?? '-' }}</strong>.
                            Anda hanya perlu melengkapi data identitas dan selfie.
                        </p>
                    </div>

                </div>

            </div>

        @endif


        {{-- ==========================================================
             ERROR
        =========================================================== --}}
        @if ($errors->any())

            <div class="mx-auto mb-6 max-w-3xl rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-600">

                <div class="mb-2 font-bold">
                    Pengajuan belum dapat dikirim:
                </div>

                <ul class="list-disc space-y-1 pl-5">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ==========================================================
             SUCCESS
        =========================================================== --}}
        @if (session('success'))

            <div class="mx-auto mb-6 max-w-3xl rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>

        @endif


        {{-- ==========================================================
             FORM
        =========================================================== --}}
        <form
            id="mitraRegistrationForm"
            method="POST"
            action="{{ $isGuest
                ? route('mitra.register.store')
                : route('mitra.apply.store')
            }}"
            enctype="multipart/form-data"
            class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl shadow-[#0c1830]/5"
        >

            @csrf


            {{-- ======================================================
                 ACCOUNT DATA FOR GUEST
            ======================================================= --}}
            @if($isGuest)

                <div class="border-b border-gray-100 bg-white px-6 py-6 sm:px-8">

                    <div class="mb-5">

                        <div class="flex items-start gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xl">
                                👤
                            </div>

                            <div>

                                <h2 class="font-display text-lg font-bold text-[#0c1830]">
                                    Data Akun
                                </h2>

                                <p class="mt-1 text-xs leading-relaxed text-gray-500">
                                    Karena Anda belum memiliki akun, isi data berikut
                                    untuk membuat akun sementara sebelum proses verifikasi Mitra.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="grid gap-5 md:grid-cols-2">

                        {{-- NAMA --}}
                        <div class="md:col-span-2">

                            <label
                                for="name"
                                class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500"
                            >
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                placeholder="Masukkan nama lengkap"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm transition focus:border-[#0c1830] focus:outline-none focus:ring-2 focus:ring-[#0c1830]/10"
                            >

                        </div>


                        {{-- EMAIL --}}
                        <div>

                            <label
                                for="email"
                                class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500"
                            >
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="contoh@email.com"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm transition focus:border-[#0c1830] focus:outline-none focus:ring-2 focus:ring-[#0c1830]/10"
                            >

                        </div>


                        {{-- PHONE --}}
                        <div>

                            <label
                                for="phone"
                                class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500"
                            >
                                Nomor WhatsApp
                            </label>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                autocomplete="tel"
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm transition focus:border-[#0c1830] focus:outline-none focus:ring-2 focus:ring-[#0c1830]/10"
                            >

                        </div>


                        {{-- PASSWORD --}}
                        <div>

                            <label
                                for="password"
                                class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500"
                            >
                                Password
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Masukkan password"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm transition focus:border-[#0c1830] focus:outline-none focus:ring-2 focus:ring-[#0c1830]/10"
                            >

                            <p class="mt-1.5 text-[11px] text-gray-400">
                                Gunakan password yang kuat dan mudah Anda ingat.
                            </p>

                        </div>


                        {{-- PASSWORD CONFIRMATION --}}
                        <div>

                            <label
                                for="password_confirmation"
                                class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500"
                            >
                                Konfirmasi Password
                            </label>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm transition focus:border-[#0c1830] focus:outline-none focus:ring-2 focus:ring-[#0c1830]/10"
                            >

                        </div>

                    </div>

                </div>

            @endif


            {{-- ======================================================
                 TOP INFO
            ======================================================= --}}
            <div class="border-b border-gray-100 bg-[#0c1830] px-6 py-6 text-white sm:px-8">

                <div class="flex items-start gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-xl">
                        🛡️
                    </div>

                    <div>

                        <h2 class="font-display text-lg font-bold">
                            Verifikasi Identitas
                        </h2>

                        <p class="mt-1 text-xs leading-relaxed text-white/65">
                            Data identitas digunakan untuk proses verifikasi calon Mitra.
                            Pastikan foto terlihat jelas dan data yang diberikan benar.
                        </p>

                    </div>

                </div>

            </div>


            <div class="grid gap-8 p-6 sm:p-8 lg:grid-cols-2">


                {{-- ======================================================
                     DATA IDENTITAS
                ======================================================= --}}
                <div>

                    <div class="mb-5">

                        <h2 class="font-display text-base font-extrabold text-[#0c1830]">
                            1. Data Identitas
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Pilih jenis identitas dan masukkan nomor identitas Anda.
                        </p>

                    </div>


                    {{-- JENIS IDENTITAS --}}
                    <div>

                        <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500">
                            Jenis Identitas
                        </label>

                        <div class="grid grid-cols-3 gap-2">

                            {{-- KTP --}}
                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="identity_type"
                                    value="ktp"
                                    class="peer sr-only"
                                    {{ $identityType === 'ktp' ? 'checked' : '' }}
                                >

                                <div class="rounded-xl border-2 border-gray-200 px-3 py-3 text-center transition peer-checked:border-[#0c1830] peer-checked:bg-[#0c1830] peer-checked:text-white">

                                    <div class="text-lg">
                                        🪪
                                    </div>

                                    <div class="mt-1 text-xs font-bold">
                                        KTP
                                    </div>

                                </div>

                            </label>


                            {{-- SIM --}}
                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="identity_type"
                                    value="sim"
                                    class="peer sr-only"
                                    {{ $identityType === 'sim' ? 'checked' : '' }}
                                >

                                <div class="rounded-xl border-2 border-gray-200 px-3 py-3 text-center transition peer-checked:border-[#0c1830] peer-checked:bg-[#0c1830] peer-checked:text-white">

                                    <div class="text-lg">
                                        🚗
                                    </div>

                                    <div class="mt-1 text-xs font-bold">
                                        SIM
                                    </div>

                                </div>

                            </label>


                            {{-- PASPOR --}}
                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="identity_type"
                                    value="paspor"
                                    class="peer sr-only"
                                    {{ $identityType === 'paspor' ? 'checked' : '' }}
                                >

                                <div class="rounded-xl border-2 border-gray-200 px-3 py-3 text-center transition peer-checked:border-[#0c1830] peer-checked:bg-[#0c1830] peer-checked:text-white">

                                    <div class="text-lg">
                                        🌎
                                    </div>

                                    <div class="mt-1 text-xs font-bold">
                                        Paspor
                                    </div>

                                </div>

                            </label>

                        </div>

                    </div>


                    {{-- NOMOR IDENTITAS --}}
                    <div class="mt-5">

                        <label
                            for="identity_number"
                            class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500"
                        >
                            Nomor Identitas
                        </label>

                        <input
                            type="text"
                            id="identity_number"
                            name="identity_number"
                            value="{{ $identityNumber }}"
                            required
                            autocomplete="off"
                            placeholder="Masukkan nomor identitas"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm transition focus:border-[#0c1830] focus:outline-none focus:ring-2 focus:ring-[#0c1830]/10"
                        >

                        <p class="mt-1.5 text-[11px] text-gray-400">
                            Pastikan nomor sesuai dengan identitas yang Anda upload.
                        </p>

                    </div>


                    {{-- FOTO IDENTITAS --}}
                    <div class="mt-5">

                        <label
                            for="identity_photo"
                            class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500"
                        >
                            Foto Identitas
                        </label>

                        <label
                            for="identity_photo"
                            class="flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 p-5 transition hover:border-[#0c1830]/30 hover:bg-gray-100"
                        >

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white text-xl shadow-sm">
                                🪪
                            </div>

                            <div class="min-w-0">

                                <div class="text-sm font-bold text-[#0c1830]">
                                    Pilih Foto Identitas
                                </div>

                                <div class="mt-1 text-[11px] text-gray-400">
                                    JPG, PNG, WEBP — maksimal 5 MB
                                </div>

                                <div
                                    id="identityFileName"
                                    class="mt-1 truncate text-xs font-semibold text-emerald-600"
                                >
                                    Belum ada file dipilih
                                </div>

                            </div>

                        </label>

                        <input
                            type="file"
                            id="identity_photo"
                            name="identity_photo"
                            accept="image/jpeg,image/png,image/webp"
                            class="hidden"
                            required
                        >

                        <div
                            id="identityPreviewWrapper"
                            class="mt-4 hidden overflow-hidden rounded-2xl border border-gray-200 bg-gray-50"
                        >

                            <img
                                id="identityPreview"
                                src=""
                                alt="Preview identitas"
                                class="max-h-64 w-full object-contain"
                            >

                        </div>

                    </div>

                </div>


                {{-- ======================================================
                     SELFIE
                ======================================================= --}}
                <div>

                    <div class="mb-5">

                        <h2 class="font-display text-base font-extrabold text-[#0c1830]">
                            2. Verifikasi Selfie
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Ambil foto wajah secara langsung untuk membantu proses verifikasi.
                        </p>

                    </div>


                    {{-- CAMERA --}}
                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-950">

                        <div
                            id="cameraContainer"
                            class="relative aspect-[4/3] w-full overflow-hidden bg-gray-950"
                        >

                            <video
                                id="cameraVideo"
                                autoplay
                                playsinline
                                muted
                                class="hidden h-full w-full object-cover"
                            ></video>

                            <canvas
                                id="cameraCanvas"
                                class="hidden"
                            ></canvas>


                            {{-- CAMERA PLACEHOLDER --}}
                            <div
                                id="cameraPlaceholder"
                                class="flex h-full min-h-[260px] flex-col items-center justify-center px-6 text-center text-white"
                            >

                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 text-3xl">
                                    🤳
                                </div>

                                <div class="mt-4 text-sm font-bold">
                                    Kamera Selfie
                                </div>

                                <p class="mt-1 max-w-xs text-[11px] leading-relaxed text-white/50">
                                    Izinkan akses kamera untuk mengambil foto selfie secara langsung.
                                </p>

                            </div>


                            {{-- SELFIE PREVIEW --}}
                            <img
                                id="selfiePreview"
                                src=""
                                alt="Preview selfie"
                                class="hidden h-full w-full object-cover"
                            >

                        </div>


                        {{-- CAMERA BUTTONS --}}
                        <div class="flex flex-wrap gap-2 border-t border-white/10 bg-gray-950 p-3">

                            <button
                                type="button"
                                id="startCameraButton"
                                class="flex-1 rounded-xl bg-white px-4 py-3 text-xs font-bold text-[#0c1830] transition hover:bg-gray-100"
                            >
                                📷 Buka Kamera
                            </button>

                            <button
                                type="button"
                                id="captureButton"
                                class="hidden flex-1 rounded-xl bg-emerald-500 px-4 py-3 text-xs font-bold text-white transition hover:bg-emerald-400"
                            >
                                ✓ Ambil Selfie
                            </button>

                            <button
                                type="button"
                                id="retakeButton"
                                class="hidden flex-1 rounded-xl bg-white/10 px-4 py-3 text-xs font-bold text-white transition hover:bg-white/20"
                            >
                                ↻ Foto Ulang
                            </button>

                        </div>

                    </div>


                    {{-- HIDDEN SELFIE FILE --}}
                    <input
                        type="file"
                        id="selfie_photo"
                        name="selfie_photo"
                        accept="image/jpeg,image/png,image/webp"
                        capture="user"
                        class="hidden"
                    >


                    {{-- CAMERA ERROR --}}
                    <div
                        id="cameraError"
                        class="mt-3 hidden rounded-xl border border-amber-100 bg-amber-50 p-3 text-xs leading-relaxed text-amber-700"
                    ></div>


                    {{-- FALLBACK --}}
                    <div class="mt-4">

                        <label
                            for="selfieFallback"
                            class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 transition hover:bg-gray-50"
                        >

                            <span class="text-lg">
                                📱
                            </span>

                            <span>

                                <span class="block text-xs font-bold text-[#0c1830]">
                                    Gunakan foto dari perangkat
                                </span>

                                <span class="block text-[10px] text-gray-400">
                                    Gunakan jika kamera langsung tidak tersedia.
                                </span>

                            </span>

                        </label>

                        <input
                            type="file"
                            id="selfieFallback"
                            accept="image/jpeg,image/png,image/webp"
                            capture="user"
                            class="hidden"
                        >

                    </div>


                    {{-- SELFIE STATUS --}}
                    <div
                        id="selfieStatus"
                        class="mt-3 rounded-xl bg-gray-50 px-4 py-3 text-xs text-gray-500"
                    >
                        Selfie belum diambil.
                    </div>

                </div>

            </div>


            {{-- ======================================================
                 PERSETUJUAN
            ======================================================= --}}
            <div class="border-t border-gray-100 bg-gray-50/70 px-6 py-6 sm:px-8">

                <label class="flex cursor-pointer items-start gap-3">

                    <input
                        type="checkbox"
                        name="terms"
                        value="1"
                        required
                        class="mt-0.5 h-4 w-4 rounded border-gray-300 text-[#0c1830] focus:ring-[#0c1830]"
                        {{ old('terms') ? 'checked' : '' }}
                    >

                    <span class="text-xs leading-relaxed text-gray-600">

                        Saya menyatakan bahwa data identitas yang saya berikan adalah benar
                        dan sesuai dengan identitas saya. Saya bersedia mengikuti proses
                        verifikasi dan menyetujui syarat menjadi Mitra GuestHouse.

                    </span>

                </label>

            </div>


            {{-- ======================================================
                 ACTION
            ======================================================= --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 px-6 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-8">

                @if($isGuest)

                    <a
                        href="{{ route('home') }}"
                        class="rounded-xl border border-gray-200 px-5 py-3 text-center text-xs font-bold text-gray-600 transition hover:bg-gray-50"
                    >
                        Kembali
                    </a>

                @else

                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-xl border border-gray-200 px-5 py-3 text-center text-xs font-bold text-gray-600 transition hover:bg-gray-50"
                    >
                        Kembali
                    </a>

                @endif


                <button
                    type="submit"
                    id="submitButton"
                    class="rounded-xl bg-[#0c1830] px-7 py-3 text-xs font-bold text-white shadow-lg shadow-[#0c1830]/15 transition hover:bg-[#152442] disabled:cursor-not-allowed disabled:opacity-60"
                >
                    Kirim Pengajuan Mitra
                </button>

            </div>

        </form>


        {{-- ==========================================================
             LOGIN INFO UNTUK GUEST
        =========================================================== --}}
        @if($isGuest)

            <div class="mx-auto mt-6 max-w-3xl text-center">

                <p class="text-xs text-gray-400">
                    Sudah memiliki akun?
                    <a
                        href="{{ route('login') }}"
                        class="font-bold text-[#0c1830] hover:text-blue-600"
                    >
                        Login di sini
                    </a>
                </p>

            </div>

        @endif

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById('mitraRegistrationForm');

    const cameraVideo = document.getElementById('cameraVideo');
    const cameraCanvas = document.getElementById('cameraCanvas');
    const cameraPlaceholder = document.getElementById('cameraPlaceholder');

    const selfiePreview = document.getElementById('selfiePreview');

    const startCameraButton = document.getElementById('startCameraButton');
    const captureButton = document.getElementById('captureButton');
    const retakeButton = document.getElementById('retakeButton');

    const selfiePhotoInput = document.getElementById('selfie_photo');
    const selfieFallback = document.getElementById('selfieFallback');

    const selfieStatus = document.getElementById('selfieStatus');
    const cameraError = document.getElementById('cameraError');

    const identityPhotoInput = document.getElementById('identity_photo');
    const identityFileName = document.getElementById('identityFileName');

    const identityPreview = document.getElementById('identityPreview');
    const identityPreviewWrapper = document.getElementById('identityPreviewWrapper');

    const submitButton = document.getElementById('submitButton');

    let cameraStream = null;


    /*
    |--------------------------------------------------------------------------
    | IDENTITY PHOTO PREVIEW
    |--------------------------------------------------------------------------
    */

    identityPhotoInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {

            identityFileName.textContent =
                'Belum ada file dipilih';

            identityPreviewWrapper.classList.add('hidden');

            identityPreview.src = '';

            return;
        }


        /*
        | Cek ukuran file di browser terlebih dahulu.
        */

        if (file.size > 5 * 1024 * 1024) {

            identityFileName.textContent =
                'Ukuran file terlalu besar';

            identityPreviewWrapper.classList.add('hidden');

            this.value = '';

            alert('Ukuran foto identitas maksimal 5 MB.');

            return;
        }


        identityFileName.textContent = file.name;


        const reader = new FileReader();

        reader.onload = function (event) {

            identityPreview.src =
                event.target.result;

            identityPreviewWrapper.classList.remove('hidden');

        };

        reader.readAsDataURL(file);

    });


    /*
    |--------------------------------------------------------------------------
    | START CAMERA
    |--------------------------------------------------------------------------
    */

    startCameraButton.addEventListener('click', async function () {

        cameraError.classList.add('hidden');

        if (
            !navigator.mediaDevices ||
            !navigator.mediaDevices.getUserMedia
        ) {

            cameraError.textContent =
                'Browser tidak mendukung akses kamera langsung. Gunakan pilihan foto dari perangkat di bawah kamera.';

            cameraError.classList.remove('hidden');

            return;
        }


        try {

            cameraStream =
                await navigator.mediaDevices.getUserMedia({

                    video: {
                        facingMode: 'user',
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 960
                        }
                    },

                    audio: false

                });


            cameraVideo.srcObject =
                cameraStream;


            cameraPlaceholder.classList.add('hidden');

            selfiePreview.classList.add('hidden');

            cameraVideo.classList.remove('hidden');

            startCameraButton.classList.add('hidden');

            captureButton.classList.remove('hidden');

            retakeButton.classList.add('hidden');


            selfieStatus.textContent =
                'Kamera aktif. Posisikan wajah di tengah lalu tekan "Ambil Selfie".';

        } catch (error) {

            console.error(error);

            cameraError.textContent =
                'Kamera tidak dapat digunakan. Pastikan izin kamera sudah diberikan pada browser. Anda juga dapat menggunakan foto dari perangkat.';

            cameraError.classList.remove('hidden');

        }

    });


    /*
    |--------------------------------------------------------------------------
    | CAPTURE SELFIE
    |--------------------------------------------------------------------------
    */

    captureButton.addEventListener('click', function () {

        if (!cameraStream) {
            return;
        }


        const width =
            cameraVideo.videoWidth;

        const height =
            cameraVideo.videoHeight;


        if (!width || !height) {

            cameraError.textContent =
                'Kamera belum siap. Tunggu beberapa saat lalu coba lagi.';

            cameraError.classList.remove('hidden');

            return;
        }


        cameraCanvas.width =
            width;

        cameraCanvas.height =
            height;


        const context =
            cameraCanvas.getContext('2d');


        context.drawImage(
            cameraVideo,
            0,
            0,
            width,
            height
        );


        cameraCanvas.toBlob(
            function (blob) {

                if (!blob) {
                    return;
                }


                const file =
                    new File(
                        [blob],
                        'selfie-mitra.jpg',
                        {
                            type: 'image/jpeg'
                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | Masukkan hasil kamera ke input file
                |--------------------------------------------------------------------------
                */

                const dataTransfer =
                    new DataTransfer();

                dataTransfer.items.add(file);

                selfiePhotoInput.files =
                    dataTransfer.files;


                /*
                |--------------------------------------------------------------------------
                | Preview
                |--------------------------------------------------------------------------
                */

                if (selfiePreview.dataset.objectUrl) {

                    URL.revokeObjectURL(
                        selfiePreview.dataset.objectUrl
                    );

                }


                const objectUrl =
                    URL.createObjectURL(file);

                selfiePreview.src =
                    objectUrl;

                selfiePreview.dataset.objectUrl =
                    objectUrl;


                cameraVideo.classList.add('hidden');

                selfiePreview.classList.remove('hidden');

                captureButton.classList.add('hidden');

                retakeButton.classList.remove('hidden');


                selfieStatus.textContent =
                    '✓ Selfie berhasil diambil dan siap dikirim.';


                stopCamera();

            },
            'image/jpeg',
            0.90
        );

    });


    /*
    |--------------------------------------------------------------------------
    | RETAKE
    |--------------------------------------------------------------------------
    */

    retakeButton.addEventListener('click', async function () {

        selfiePhotoInput.value = '';

        selfiePreview.src = '';

        if (selfiePreview.dataset.objectUrl) {

            URL.revokeObjectURL(
                selfiePreview.dataset.objectUrl
            );

            delete selfiePreview.dataset.objectUrl;

        }


        selfiePreview.classList.add('hidden');

        retakeButton.classList.add('hidden');

        startCameraButton.classList.remove('hidden');

        cameraPlaceholder.classList.remove('hidden');

        selfieStatus.textContent =
            'Selfie belum diambil.';


        cameraError.classList.add('hidden');


        startCameraButton.click();

    });


    /*
    |--------------------------------------------------------------------------
    | FALLBACK PHOTO
    |--------------------------------------------------------------------------
    */

    selfieFallback.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi ukuran
        |--------------------------------------------------------------------------
        */

        if (file.size > 5 * 1024 * 1024) {

            this.value = '';

            alert(
                'Ukuran foto selfie maksimal 5 MB.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Salin file fallback ke input utama
        |--------------------------------------------------------------------------
        */

        const dataTransfer =
            new DataTransfer();

        dataTransfer.items.add(file);

        selfiePhotoInput.files =
            dataTransfer.files;


        /*
        |--------------------------------------------------------------------------
        | Preview
        |--------------------------------------------------------------------------
        */

        if (selfiePreview.dataset.objectUrl) {

            URL.revokeObjectURL(
                selfiePreview.dataset.objectUrl
            );

        }


        const objectUrl =
            URL.createObjectURL(file);

        selfiePreview.src =
            objectUrl;

        selfiePreview.dataset.objectUrl =
            objectUrl;


        cameraVideo.classList.add('hidden');

        cameraPlaceholder.classList.add('hidden');

        selfiePreview.classList.remove('hidden');

        startCameraButton.classList.add('hidden');

        captureButton.classList.add('hidden');

        retakeButton.classList.remove('hidden');


        selfieStatus.textContent =
            '✓ Foto selfie dari perangkat siap dikirim.';

    });


    /*
    |--------------------------------------------------------------------------
    | STOP CAMERA
    |--------------------------------------------------------------------------
    */

    function stopCamera() {

        if (!cameraStream) {
            return;
        }


        cameraStream
            .getTracks()
            .forEach(function (track) {
                track.stop();
            });


        cameraStream = null;

    }


    /*
    |--------------------------------------------------------------------------
    | FORM VALIDATION
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', function (event) {

        /*
        |--------------------------------------------------------------------------
        | Identity photo
        |--------------------------------------------------------------------------
        */

        if (!identityPhotoInput.files.length) {

            event.preventDefault();

            identityFileName.textContent =
                'Foto identitas wajib dipilih.';

            identityFileName.classList.remove(
                'text-emerald-600'
            );

            identityFileName.classList.add(
                'text-red-600'
            );

            identityPhotoInput.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Selfie
        |--------------------------------------------------------------------------
        */

        if (!selfiePhotoInput.files.length) {

            event.preventDefault();

            cameraError.textContent =
                'Silakan ambil selfie terlebih dahulu sebelum mengirim pengajuan.';

            cameraError.classList.remove('hidden');

            cameraError.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Disable submit
        |--------------------------------------------------------------------------
        */

        submitButton.disabled = true;

        submitButton.textContent =
            'Mengirim Pengajuan...';


        stopCamera();

    });


    /*
    |--------------------------------------------------------------------------
    | CLEANUP
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        'beforeunload',
        function () {

            stopCamera();


            if (selfiePreview.dataset.objectUrl) {

                URL.revokeObjectURL(
                    selfiePreview.dataset.objectUrl
                );

            }

        }
    );

});
</script>

@endsection