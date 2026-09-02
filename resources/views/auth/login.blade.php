@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-blue-50/40 px-4 py-16">
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl shadow-navy-900/10 overflow-hidden grid grid-cols-1 md:grid-cols-2 border border-gray-100">

        {{-- Panel brand — premium, gelap, aksen emas --}}
        <div class="hidden md:flex flex-col justify-between bg-navy-900 text-white p-10 relative overflow-hidden">
            {{-- Aksen dekoratif --}}
            <div class="absolute inset-0 opacity-[0.04]"
                 style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;"></div>
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-brand-yellow/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-10 -bottom-16 w-56 h-56 bg-brand-blue/20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2.5 font-display font-extrabold text-xl mb-10">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-brand-yellow text-navy-900 text-lg">🏠</span>
                    <span class="lowercase tracking-tight">Guest House</span>
                </div>

                <div class="w-10 h-[2px] bg-brand-yellow mb-5"></div>
                <h2 class="font-display text-3xl font-bold mb-4 leading-snug">
                    Selamat datang<br>kembali.
                </h2>
                <p class="text-gray-300 text-sm leading-relaxed max-w-xs">
                    Masuk untuk melanjutkan booking, mengelola properti, atau memantau transaksi Anda.
                </p>
            </div>

            {{-- Trust indicators, kesan premium --}}
            <div class="relative z-10 grid grid-cols-3 gap-4 pt-8 border-t border-white/10">
                <div>
                    <div class="font-display font-extrabold text-xl text-brand-yellow">500+</div>
                    <div class="text-[11px] text-gray-400 mt-0.5">Properti</div>
                </div>
                <div>
                    <div class="font-display font-extrabold text-xl text-brand-yellow">4.8★</div>
                    <div class="text-[11px] text-gray-400 mt-0.5">Rating Rata-rata</div>
                </div>
                <div>
                    <div class="font-display font-extrabold text-xl text-brand-yellow">24/7</div>
                    <div class="text-[11px] text-gray-400 mt-0.5">Dukungan</div>
                </div>
            </div>
        </div>

        {{-- Panel form --}}
        <div class="p-8 sm:p-12 flex flex-col justify-center">
            <h1 class="font-display text-2xl font-extrabold text-navy-900 mb-1">Masuk ke Akun</h1>
            <p class="text-sm text-gray-500 mb-6">Belum punya akun?
                <a href="{{ route('register') }}" class="text-brand-blue font-semibold hover:underline">Daftar di sini</a>
            </p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl p-3.5 mb-5">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- ====== Tampilan awal: tombol login sosial (gaya Traveloka) ====== --}}
            {{-- CATATAN: Google/Apple/Facebook belum terhubung ke provider asli (perlu
                 Laravel Socialite + API key masing-masing). Tombolnya jujur: klik akan
                 otomatis buka form email/password di bawah, bukan pura-pura login. --}}
            <div id="social-login-panel" class="space-y-3 mb-2">
                <button type="button" onclick="showEmailForm()"
                        class="w-full flex items-center justify-center gap-3 border border-gray-200 rounded-xl py-3 text-sm font-semibold text-navy-900 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3c-1.6 4.7-6.1 8-11.3 8-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.5 29.6 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.7-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 16 19 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.5 29.6 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.5 0 10.4-2.1 14.1-5.6l-6.5-5.5C29.6 34.7 26.9 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.6 5.1C9.6 39.6 16.3 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.2 4.2-4.1 5.6l6.5 5.5C39.8 37 44 31 44 24c0-1.3-.1-2.7-.4-3.5z"/></svg>
                    Lanjutkan dengan Google
                </button>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="showEmailForm()"
                            class="flex items-center justify-center gap-2 border border-gray-200 rounded-xl py-3 text-sm font-semibold text-navy-900 hover:bg-gray-50 transition">
                        <span></span> Apple
                    </button>
                    <button type="button" onclick="showEmailForm()"
                            class="flex items-center justify-center gap-2 border border-gray-200 rounded-xl py-3 text-sm font-semibold text-white bg-[#1877F2] hover:bg-[#166fe5] transition">
                        <span>f</span> Facebook
                    </button>
                </div>

                <button type="button" onclick="showEmailForm()"
                        class="w-full text-center text-sm font-semibold text-brand-blue hover:underline pt-1">
                    Metode lain
                </button>

                <p class="text-[11px] text-gray-400 leading-relaxed pt-2">
                    Dengan melanjutkan, kamu menyetujui
                    <a href="#" class="text-brand-blue hover:underline">Syarat &amp; Ketentuan</a> ini dan kamu sudah
                    diberi tahu mengenai <a href="#" class="text-brand-blue hover:underline">Pemberitahuan Privasi</a> kami.
                </p>
            </div>

            {{-- ====== Form email/password asli — tersembunyi di awal, muncul lewat "Metode lain" ====== --}}
            <form id="email-login-form" method="POST" action="{{ route('login') }}" class="space-y-5 {{ $errors->any() ? '' : 'hidden' }}">
                @csrf

                <button type="button" onclick="hideEmailForm()" class="flex items-center gap-1 text-xs text-gray-400 hover:text-navy-900 transition mb-1">
                    ‹ Kembali ke opsi login lain
                </button>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="nama@email.com"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Password</label>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue/30">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full bg-navy-900 hover:bg-navy-800 transition text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-navy-900/20">
                    Masuk
                </button>
            </form>

            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-xs text-gray-400">atau</span>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <a href="{{ route('home') }}" class="block text-center text-sm font-semibold text-navy-900 border border-gray-200 rounded-xl py-3 hover:bg-gray-50 transition">
                Lanjutkan sebagai Tamu
            </a>
        </div>
    </div>
</div>

<script>
    function showEmailForm() {
        document.getElementById('social-login-panel').classList.add('hidden');
        document.getElementById('email-login-form').classList.remove('hidden');
    }
    function hideEmailForm() {
        document.getElementById('email-login-form').classList.add('hidden');
        document.getElementById('social-login-panel').classList.remove('hidden');
    }
</script>
@endsection