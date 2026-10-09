<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daftar Akun - {{ config('app.name', 'Guest House') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }

        body {
            background:
                radial-gradient(
                    circle at top left,
                    rgba(245, 158, 11, 0.12),
                    transparent 32%
                ),
                radial-gradient(
                    circle at bottom right,
                    rgba(59, 130, 246, 0.10),
                    transparent 30%
                ),
                #f8fafc;
        }

        .brand-font {
            font-family: 'Fredoka', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .input-field {
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                transform 0.2s ease;
        }

        .input-field:focus {
            transform: translateY(-1px);
        }

        .role-card {
            transition:
                border-color 0.2s ease,
                background-color 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .role-card:hover {
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="min-h-screen">

<div class="min-h-screen flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8">

    <div class="w-full max-w-6xl">

        <div class="glass-card overflow-hidden rounded-[2rem] border border-white shadow-2xl shadow-slate-200/70">

            <div class="grid grid-cols-1 lg:grid-cols-2">


                {{-- =====================================================
                     LEFT SIDE
                ====================================================== --}}

                <div class="hidden lg:flex relative overflow-hidden bg-slate-950 p-10 xl:p-14 text-white">

                    <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-amber-400/20 blur-3xl"></div>

                    <div class="absolute -bottom-32 -left-20 h-80 w-80 rounded-full bg-blue-500/20 blur-3xl"></div>

                    <div class="relative z-10 flex flex-col justify-between w-full">

                        <div>

                            <a
                                href="{{ route('home') }}"
                                class="inline-flex items-center gap-3"
                            >

                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-slate-950 shadow-lg">
                                    🏠
                                </div>

                                <div>

                                    <div class="brand-font text-xl font-semibold">
                                        {{ config('app.name', 'Guest House') }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        Booking Guest House & Kost Harian
                                    </div>

                                </div>

                            </a>


                            <div class="mt-20">

                                <p class="text-sm font-bold uppercase tracking-[0.25em] text-amber-400">
                                    Selamat Datang
                                </p>

                                <h1 class="mt-4 text-4xl xl:text-5xl font-extrabold leading-tight">
                                    Mulai perjalanan
                                    <span class="text-amber-400">
                                        Anda
                                    </span>
                                </h1>

                                <p class="mt-6 max-w-md text-base leading-8 text-slate-300">
                                    Buat akun untuk mencari penginapan,
                                    melakukan booking, dan mengelola perjalanan
                                    Anda dengan lebih mudah.
                                </p>

                            </div>

                        </div>


                        <div class="mt-12">

                            <div class="grid grid-cols-3 gap-3">

                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">

                                    <div class="text-2xl">
                                        🏡
                                    </div>

                                    <p class="mt-3 text-sm font-bold">
                                        Properti
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Pilihan penginapan
                                    </p>

                                </div>


                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">

                                    <div class="text-2xl">
                                        🔒
                                    </div>

                                    <p class="mt-3 text-sm font-bold">
                                        Aman
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Data terlindungi
                                    </p>

                                </div>


                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">

                                    <div class="text-2xl">
                                        ⚡
                                    </div>

                                    <p class="mt-3 text-sm font-bold">
                                        Mudah
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Booking cepat
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     RIGHT SIDE - FORM
                ====================================================== --}}

                <div class="p-6 sm:p-8 lg:p-10 xl:p-12">


                    {{-- MOBILE BRAND --}}

                    <div class="lg:hidden mb-8">

                        <a
                            href="{{ route('home') }}"
                            class="inline-flex items-center gap-3"
                        >

                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-white">
                                🏠
                            </div>

                            <div>

                                <div class="brand-font text-xl font-semibold text-slate-900">
                                    {{ config('app.name', 'Guest House') }}
                                </div>

                                <div class="text-xs text-slate-500">
                                    Booking Guest House & Kost Harian
                                </div>

                            </div>

                        </a>

                    </div>


                    {{-- HEADER --}}

                    <div class="mb-8">

                        <div class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700">
                            ✨ Buat Akun Baru
                        </div>

                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">
                            Daftar sekarang
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Buat akun untuk mulai menggunakan layanan kami.
                        </p>

                    </div>


                    {{-- VALIDATION ERRORS --}}

                    @if ($errors->any())

                        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">

                            <div class="flex gap-3">

                                <div class="text-lg">
                                    ⚠️
                                </div>

                                <div>

                                    <p class="text-sm font-bold text-red-800">
                                        Periksa kembali data Anda.
                                    </p>

                                    <ul class="mt-2 space-y-1 text-sm text-red-700">

                                        @foreach ($errors->all() as $error)

                                            <li>
                                                • {{ $error }}
                                            </li>

                                        @endforeach

                                    </ul>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- SUCCESS --}}

                    @if (session('success'))

                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                            {{ session('success') }}
                        </div>

                    @endif


                    {{-- =================================================
                         REGISTER FORM
                    ================================================== --}}

                    <form
                        method="POST"
                        action="{{ route('register') }}"
                        class="space-y-5"
                    >

                        @csrf


                        {{-- =================================================
                             ACCOUNT TYPE
                        ================================================== --}}

                        <div>

                            <label class="mb-3 block text-sm font-bold text-slate-800">
                                Daftar sebagai
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">


                                {{-- CUSTOMER --}}

                                <div class="block">

                                    <div class="role-card h-full rounded-2xl border-2 border-slate-900 bg-slate-50 p-4 shadow-lg">

                                        <div class="flex items-start gap-3">

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xl">
                                                🧳
                                            </div>

                                            <div>

                                                <div class="font-bold text-slate-900">
                                                    Customer
                                                </div>

                                                <div class="mt-1 text-xs leading-5 text-slate-500">
                                                    Booking properti untuk kebutuhan menginap.
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- MITRA --}}

                                <a
                                    href="{{ route('mitra.landing') }}"
                                    class="block"
                                >

                                    <div class="role-card h-full rounded-2xl border-2 border-slate-200 bg-white p-4 hover:border-amber-400 hover:bg-amber-50/40 hover:shadow-lg">

                                        <div class="flex items-start gap-3">

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-xl">
                                                🏠
                                            </div>

                                            <div>

                                                <div class="font-bold text-slate-900">
                                                    Daftar sebagai Mitra
                                                </div>

                                                <div class="mt-1 text-xs leading-5 text-slate-500">
                                                    Lengkapi data Anda dan lakukan
                                                    verifikasi identitas untuk menjadi Mitra.
                                                </div>

                                            </div>

                                        </div>

                                        <div class="mt-3 text-xs font-bold text-amber-600">
                                            Lihat alur pendaftaran Mitra →
                                        </div>

                                    </div>

                                </a>

                            </div>


                            <p class="mt-2 text-xs text-slate-400">
                                Pendaftaran Mitra membutuhkan verifikasi identitas terlebih dahulu.
                            </p>

                        </div>


                        {{-- =================================================
                             NAME
                        ================================================== --}}

                        <div>

                            <label
                                for="name"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                Nama Lengkap
                            </label>

                            <div class="relative">

                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    👤
                                </div>

                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Masukkan nama lengkap"
                                    class="input-field w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm text-slate-900 outline-none focus:border-slate-900 focus:bg-white focus:ring-4 focus:ring-slate-900/5"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             EMAIL
                        ================================================== --}}

                        <div>

                            <label
                                for="email"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                Email
                            </label>

                            <div class="relative">

                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    ✉️
                                </div>

                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    placeholder="contoh@email.com"
                                    class="input-field w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm text-slate-900 outline-none focus:border-slate-900 focus:bg-white focus:ring-4 focus:ring-slate-900/5"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             PHONE
                        ================================================== --}}

                        <div>

                            <label
                                for="phone"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                Nomor WhatsApp

                                <span class="font-normal text-slate-400">
                                    (opsional)
                                </span>

                            </label>

                            <div class="relative">

                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    📱
                                </div>

                                <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    value="{{ old('phone') }}"
                                    autocomplete="tel"
                                    placeholder="08xxxxxxxxxx"
                                    class="input-field w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm text-slate-900 outline-none focus:border-slate-900 focus:bg-white focus:ring-4 focus:ring-slate-900/5"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             PASSWORD
                        ================================================== --}}

                        <div>

                            <label
                                for="password"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                Password
                            </label>

                            <div class="relative">

                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    🔒
                                </div>

                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Minimal 8 karakter"
                                    class="input-field w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm text-slate-900 outline-none focus:border-slate-900 focus:bg-white focus:ring-4 focus:ring-slate-900/5"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             CONFIRM PASSWORD
                        ================================================== --}}

                        <div>

                            <label
                                for="password_confirmation"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                Konfirmasi Password
                            </label>

                            <div class="relative">

                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    🔐
                                </div>

                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Ulangi password"
                                    class="input-field w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm text-slate-900 outline-none focus:border-slate-900 focus:bg-white focus:ring-4 focus:ring-slate-900/5"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             INFO CUSTOMER
                        ================================================== --}}

                        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">

                            <div class="flex gap-3">

                                <div class="text-lg">
                                    ℹ️
                                </div>

                                <div>

                                    <p class="text-sm font-bold text-blue-900">
                                        Akun Customer
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-blue-700">
                                        Dengan mendaftar melalui halaman ini,
                                        akun Anda akan dibuat sebagai Customer.
                                        Jika ingin menjadi Mitra, pilih
                                        <strong>Daftar sebagai Mitra</strong>
                                        untuk mengikuti proses verifikasi identitas.
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             TERMS
                        ================================================== --}}

                        <div class="flex items-start gap-3">

                            <input
                                id="terms"
                                name="terms"
                                type="checkbox"
                                value="1"
                                required
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                            >

                            <label
                                for="terms"
                                class="text-xs leading-5 text-slate-500"
                            >
                                Saya menyetujui syarat dan ketentuan penggunaan
                                layanan serta kebijakan yang berlaku.
                            </label>

                        </div>


                        {{-- =================================================
                             SUBMIT
                        ================================================== --}}

                        <button
                            type="submit"
                            class="group w-full rounded-2xl bg-slate-950 px-5 py-4 text-sm font-bold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-slate-800 active:translate-y-0"
                        >

                            <span class="inline-flex items-center justify-center gap-2">

                                Buat Akun Customer

                                <span class="transition-transform group-hover:translate-x-1">
                                    →
                                </span>

                            </span>

                        </button>

                    </form>


                    {{-- =================================================
                         LOGIN
                    ================================================== --}}

                    <div class="mt-8 text-center">

                        <p class="text-sm text-slate-500">

                            Sudah punya akun?

                            <a
                                href="{{ route('login') }}"
                                class="font-bold text-slate-900 hover:text-amber-600 transition"
                            >
                                Masuk sekarang
                            </a>

                        </p>

                    </div>


                    {{-- =================================================
                         MITRA CTA
                    ================================================== --}}

                    <div class="mt-6 rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 p-5">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                            <div>

                                <p class="text-sm font-extrabold text-slate-900">
                                    Ingin menyewakan properti?
                                </p>

                                <p class="mt-1 text-xs leading-5 text-slate-600">
                                    Daftar sebagai Mitra dan lakukan verifikasi
                                    identitas terlebih dahulu.
                                </p>

                            </div>


                            <a
                                href="{{ route('mitra.landing') }}"
                                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-amber-500 px-4 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:bg-amber-600"
                            >
                                Daftar Mitra →
                            </a>

                        </div>

                    </div>


                    {{-- BACK --}}

                    <div class="mt-6 text-center">

                        <a
                            href="{{ route('home') }}"
                            class="text-xs font-semibold text-slate-400 hover:text-slate-700 transition"
                        >
                            ← Kembali ke halaman utama
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <p class="mt-6 text-center text-xs text-slate-400">
            © {{ date('Y') }}
            {{ config('app.name', 'Guest House') }}.
            All rights reserved.
        </p>

    </div>

</div>

</body>
</html>