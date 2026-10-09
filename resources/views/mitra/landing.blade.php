{{-- resources/views/mitra/landing.blade.php --}}

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Partnership | Guest House</title>

    <meta
        name="description"
        content="Bergabung menjadi Mitra Guest House dan kelola properti Anda melalui platform kami."
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        }
                    }
                }
            }
        }
    </script>

    <style>

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .hero-pattern {
            background-image:
                radial-gradient(
                    circle at 10% 20%,
                    rgba(59, 130, 246, 0.08) 0,
                    transparent 28%
                ),
                radial-gradient(
                    circle at 90% 80%,
                    rgba(37, 99, 235, 0.06) 0,
                    transparent 28%
                );
        }

        .blue-shadow {
            box-shadow:
                0 20px 45px rgba(30, 64, 175, 0.10);
        }

    </style>

</head>


<body class="bg-white text-slate-900">


@php

    /*
    |--------------------------------------------------------------------------
    | FORM ROUTE
    |--------------------------------------------------------------------------
    |
    | Guest:
    |   mitra.register
    |
    | Customer:
    |   mitra.apply.form
    |
    */

    $mitraFormRoute = auth()->check()
        ? route('mitra.apply.form')
        : route('mitra.register');

    $backRoute = auth()->check()
        ? route('dashboard')
        : route('home');

@endphp


{{-- ========================================================= --}}
{{-- NAVBAR --}}
{{-- ========================================================= --}}

<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">

        {{-- LOGO --}}

        <a
            href="{{ route('home') }}"
            class="flex items-center gap-3"
        >

            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-600 text-white"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 10.5 12 3l9 7.5M5.5 9.5V21h13V9.5M9 21v-6h6v6"
                    />

                </svg>

            </div>


            <div>

                <div class="text-sm font-bold text-slate-900">
                    Guest House
                </div>

                <div class="text-xs text-slate-500">
                    Partnership
                </div>

            </div>

        </a>


        {{-- BACK BUTTON --}}

        <a
            href="{{ $backRoute }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-primary-200 hover:bg-primary-50 hover:text-primary-700"
        >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 19l-7-7 7-7"
                />

            </svg>

            Kembali

        </a>

    </div>

</header>


{{-- ========================================================= --}}
{{-- HERO --}}
{{-- ========================================================= --}}

<section class="hero-pattern overflow-hidden">

    <div class="mx-auto max-w-7xl px-5 py-20 lg:px-8 lg:py-28">

        <div class="grid items-center gap-14 lg:grid-cols-2">


            {{-- LEFT --}}

            <div>

                <div class="mb-5 inline-flex items-center rounded-full bg-primary-50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-primary-700">

                    Partnership

                </div>


                <h1 class="max-w-2xl text-4xl font-bold leading-tight tracking-tight text-slate-900 sm:text-5xl">

                    Bergabung menjadi
                    <span class="text-primary-600">
                        Mitra Guest House
                    </span>

                </h1>


                <p class="mt-6 max-w-xl text-base leading-8 text-slate-600 sm:text-lg">

                    Kembangkan bisnis akomodasi Anda bersama platform kami.
                    Daftar, lakukan verifikasi, dan setelah disetujui Anda
                    dapat mengelola properti melalui Dashboard Mitra.

                </p>


                {{-- CTA --}}

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">

                    <a
                        href="{{ $mitraFormRoute }}"
                        class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-primary-700"
                    >

                        Daftar sebagai Mitra

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="ml-2 h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 5l7 7-7 7"
                            />

                        </svg>

                    </a>


                    <a
                        href="#alur"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 transition hover:border-primary-200 hover:bg-primary-50"
                    >

                        Lihat proses

                    </a>

                </div>


                {{-- TRUST --}}

                <div class="mt-8 flex flex-wrap gap-x-6 gap-y-3 text-sm text-slate-500">

                    <div class="flex items-center gap-2">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-primary-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />

                        </svg>

                        Verifikasi identitas

                    </div>


                    <div class="flex items-center gap-2">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-primary-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />

                        </svg>

                        Tanpa wajib punya properti

                    </div>

                </div>

            </div>


            {{-- RIGHT --}}

            <div>

                <div class="blue-shadow rounded-3xl border border-slate-200 bg-white p-7">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-primary-600">
                                Cara bergabung
                            </p>

                            <h2 class="mt-2 text-2xl font-bold text-slate-900">
                                Proses sederhana
                            </h2>

                        </div>


                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a3 3 0 00-6 0v1H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />

                            </svg>

                        </div>

                    </div>


                    <div class="mt-7 space-y-5">


                        {{-- STEP 1 --}}

                        <div class="flex gap-4">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-600 text-sm font-bold text-white">
                                1
                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-900">
                                    Isi data diri
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    Lengkapi informasi akun dan data pribadi.
                                </p>

                            </div>

                        </div>


                        {{-- STEP 2 --}}

                        <div class="flex gap-4">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-600 text-sm font-bold text-white">
                                2
                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-900">
                                    Verifikasi identitas
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    Upload identitas dan lakukan selfie.
                                </p>

                            </div>

                        </div>


                        {{-- STEP 3 --}}

                        <div class="flex gap-4">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-600 text-sm font-bold text-white">
                                3
                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-900">
                                    Tunggu pemeriksaan
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    Admin atau Super Admin memeriksa pengajuan.
                                </p>

                            </div>

                        </div>


                        {{-- STEP 4 --}}

                        <div class="flex gap-4">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-600 text-sm font-bold text-white">
                                4
                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-900">
                                    Mulai sebagai Mitra
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    Setelah disetujui, akses Dashboard Mitra.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- BENEFITS --}}
{{-- ========================================================= --}}

<section class="border-y border-slate-100 bg-slate-50 py-20">

    <div class="mx-auto max-w-7xl px-5 lg:px-8">

        <div class="max-w-2xl">

            <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">
                Keuntungan
            </p>

            <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                Dibuat untuk memudahkan Mitra
            </h2>

            <p class="mt-4 leading-7 text-slate-600">
                Setelah verifikasi selesai, Anda dapat mengelola
                kebutuhan properti melalui satu dashboard.
            </p>

        </div>


        <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-4">


            {{-- ITEM --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z"
                        />

                    </svg>

                </div>

                <h3 class="mt-5 font-semibold text-slate-900">
                    Verifikasi Aman
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Identitas diperiksa sebelum akses Mitra diberikan.
                </p>

            </div>


            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 19V5m0 14h16M8 16v-5m4 5V7m4 9v-8"
                        />

                    </svg>

                </div>

                <h3 class="mt-5 font-semibold text-slate-900">
                    Dashboard Mitra
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Kelola aktivitas properti dari satu tempat.
                </p>

            </div>


            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 10l9-7 9 7v10a1 1 0 01-1 1H4a1 1 0 01-1-1V10z"
                        />

                    </svg>

                </div>

                <h3 class="mt-5 font-semibold text-slate-900">
                    Tambah Properti
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Properti dapat ditambahkan setelah akun disetujui.
                </p>

            </div>


            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-10a4 4 0 110 8 4 4 0 010-8z"
                        />

                    </svg>

                </div>

                <h3 class="mt-5 font-semibold text-slate-900">
                    Partnership
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Menjadi bagian dari jaringan partner platform.
                </p>

            </div>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- ALUR PENDAFTARAN --}}
{{-- ========================================================= --}}

<section
    id="alur"
    class="bg-white py-20"
>

    <div class="mx-auto max-w-5xl px-5 lg:px-8">

        <div class="text-center">

            <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">
                Proses Pendaftaran
            </p>

            <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                Alur menjadi Mitra
            </h2>

            <p class="mx-auto mt-4 max-w-2xl leading-7 text-slate-600">
                Ikuti beberapa langkah berikut sampai pengajuan Anda
                selesai diverifikasi.
            </p>

        </div>


        <div class="mt-12 grid gap-5 sm:grid-cols-2">


            {{-- 01 --}}

            <div class="flex gap-4 rounded-2xl border border-slate-200 p-5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white">
                    01
                </div>

                <div>

                    <h3 class="font-semibold text-slate-900">
                        Daftar sebagai Mitra
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Masuk melalui menu Partnership dan mulai pengajuan.
                    </p>

                </div>

            </div>


            {{-- 02 --}}

            <div class="flex gap-4 rounded-2xl border border-slate-200 p-5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white">
                    02
                </div>

                <div>

                    <h3 class="font-semibold text-slate-900">
                        Isi data diri
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Lengkapi informasi akun dan data pribadi.
                    </p>

                </div>

            </div>


            {{-- 03 --}}

            <div class="flex gap-4 rounded-2xl border border-slate-200 p-5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white">
                    03
                </div>

                <div>

                    <h3 class="font-semibold text-slate-900">
                        Pilih identitas
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Pilih KTP, SIM, atau Paspor.
                    </p>

                </div>

            </div>


            {{-- 04 --}}

            <div class="flex gap-4 rounded-2xl border border-slate-200 p-5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white">
                    04
                </div>

                <div>

                    <h3 class="font-semibold text-slate-900">
                        Upload identitas
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Upload foto identitas yang jelas.
                    </p>

                </div>

            </div>


            {{-- 05 --}}

            <div class="flex gap-4 rounded-2xl border border-slate-200 p-5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white">
                    05
                </div>

                <div>

                    <h3 class="font-semibold text-slate-900">
                        Selfie
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Ambil selfie untuk proses verifikasi.
                    </p>

                </div>

            </div>


            {{-- 06 --}}

            <div class="flex gap-4 rounded-2xl border border-slate-200 p-5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white">
                    06
                </div>

                <div>

                    <h3 class="font-semibold text-slate-900">
                        Setujui syarat
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Setujui ketentuan pengajuan Mitra.
                    </p>

                </div>

            </div>


            {{-- 07 --}}

            <div class="flex gap-4 rounded-2xl border border-slate-200 p-5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white">
                    07
                </div>

                <div>

                    <h3 class="font-semibold text-slate-900">
                        Menunggu verifikasi
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Admin atau Super Admin memeriksa pengajuan.
                    </p>

                </div>

            </div>


            {{-- 08 --}}

            <div class="flex gap-4 rounded-2xl border border-primary-100 bg-primary-50 p-5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-sm font-bold text-white">
                    08
                </div>

                <div>

                    <h3 class="font-semibold text-slate-900">
                        Disetujui menjadi Mitra
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        Akses Dashboard Mitra dan mulai kelola properti.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- INFO PROPERTY --}}
{{-- ========================================================= --}}

<section class="bg-slate-50 py-20">

    <div class="mx-auto max-w-5xl px-5 lg:px-8">

        <div class="rounded-3xl border border-primary-100 bg-primary-50 p-8 sm:p-10">

            <div class="flex flex-col gap-6 md:flex-row md:items-center">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white text-primary-600 shadow-sm">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M5 21h14a2 2 0 002-2V7.5L16.5 3h-9L3 7.5V19a2 2 0 002 2z"
                        />

                    </svg>

                </div>


                <div>

                    <h2 class="text-2xl font-bold text-slate-900">
                        Belum punya properti?
                    </h2>

                    <p class="mt-2 max-w-3xl leading-7 text-slate-600">
                        Tidak masalah. Properti tidak wajib ditambahkan
                        saat proses pendaftaran. Setelah pengajuan Anda
                        disetujui, Anda dapat menambahkan properti melalui
                        Dashboard Mitra kapan saja.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- FAQ --}}
{{-- ========================================================= --}}

<section class="bg-white py-20">

    <div class="mx-auto max-w-4xl px-5 lg:px-8">

        <div class="text-center">

            <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">
                FAQ
            </p>

            <h2 class="mt-2 text-3xl font-bold text-slate-900">
                Pertanyaan umum
            </h2>

        </div>


        <div class="mt-10 space-y-3">


            <details class="group rounded-xl border border-slate-200">

                <summary class="cursor-pointer list-none px-5 py-4 font-semibold text-slate-900">

                    Apakah harus punya properti?

                    <span class="float-right text-primary-600">
                        +
                    </span>

                </summary>

                <div class="border-t border-slate-100 px-5 py-4 text-sm leading-7 text-slate-600">

                    Tidak. Properti tidak wajib saat mendaftar.
                    Setelah menjadi Mitra, properti dapat ditambahkan
                    melalui Dashboard Mitra.

                </div>

            </details>


            <details class="group rounded-xl border border-slate-200">

                <summary class="cursor-pointer list-none px-5 py-4 font-semibold text-slate-900">

                    Identitas apa yang bisa digunakan?

                    <span class="float-right text-primary-600">
                        +
                    </span>

                </summary>

                <div class="border-t border-slate-100 px-5 py-4 text-sm leading-7 text-slate-600">

                    Identitas yang tersedia adalah KTP, SIM, dan Paspor.

                </div>

            </details>


            <details class="group rounded-xl border border-slate-200">

                <summary class="cursor-pointer list-none px-5 py-4 font-semibold text-slate-900">

                    Apakah langsung menjadi Mitra?

                    <span class="float-right text-primary-600">
                        +
                    </span>

                </summary>

                <div class="border-t border-slate-100 px-5 py-4 text-sm leading-7 text-slate-600">

                    Tidak. Pengajuan harus melalui proses verifikasi
                    Admin atau Super Admin terlebih dahulu.

                </div>

            </details>


            <details class="group rounded-xl border border-slate-200">

                <summary class="cursor-pointer list-none px-5 py-4 font-semibold text-slate-900">

                    Bagaimana jika sudah memiliki akun Customer?

                    <span class="float-right text-primary-600">
                        +
                    </span>

                </summary>

                <div class="border-t border-slate-100 px-5 py-4 text-sm leading-7 text-slate-600">

                    Customer yang sudah memiliki akun dapat langsung
                    mengajukan diri menjadi Mitra melalui Partnership.

                </div>

            </details>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- CTA --}}
{{-- ========================================================= --}}

<section class="bg-slate-50 py-20">

    <div class="mx-auto max-w-4xl px-5 lg:px-8">

        <div class="rounded-3xl bg-primary-700 px-6 py-12 text-center sm:px-12">

            <p class="text-sm font-semibold uppercase tracking-wider text-primary-200">
                Partnership
            </p>

            <h2 class="mt-3 text-3xl font-bold text-white sm:text-4xl">
                Siap menjadi Mitra?
            </h2>

            <p class="mx-auto mt-4 max-w-xl leading-7 text-primary-100">
                Mulai proses pendaftaran dan lakukan verifikasi
                identitas Anda.
            </p>


            <div class="mt-8">

                <a
                    href="{{ $mitraFormRoute }}"
                    class="inline-flex items-center justify-center rounded-xl bg-white px-7 py-3.5 text-sm font-bold text-primary-700 transition hover:bg-primary-50"
                >

                    Daftar sebagai Mitra

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="ml-2 h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5l7 7-7 7"
                        />

                    </svg>

                </a>

            </div>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- FOOTER --}}
{{-- ========================================================= --}}

<footer class="border-t border-slate-200 bg-white">

    <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-7 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left lg:px-8">

        <div>

            <p class="text-sm font-semibold text-slate-900">
                Guest House
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Program Partnership
            </p>

        </div>


        <a
            href="{{ route('home') }}"
            class="text-sm text-slate-500 transition hover:text-primary-600"
        >
            Kembali ke Beranda
        </a>

    </div>

</footer>


</body>

</html>