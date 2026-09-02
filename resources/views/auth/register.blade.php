@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-blue-50/40 px-4 py-16">
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl shadow-navy-900/10 overflow-hidden grid grid-cols-1 md:grid-cols-2 border border-gray-100">

        {{-- Panel brand — premium, gelap, aksen emas --}}
        <div class="hidden md:flex flex-col justify-between bg-navy-900 text-white p-10 relative overflow-hidden">
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
                    Gabung sekarang,<br>mulai perjalananmu.
                </h2>
                <p class="text-gray-300 text-sm leading-relaxed max-w-xs">
                    Booking properti impianmu, atau daftarkan properti dan mulai hasilkan pendapatan sebagai mitra.
                </p>
            </div>

            <div class="relative z-10 space-y-3 pt-8 border-t border-white/10">
                <div class="flex items-center gap-3 text-sm text-gray-300">
                    <span class="w-6 h-6 rounded-full bg-brand-yellow/20 text-brand-yellow flex items-center justify-center text-xs">✓</span>
                    Booking cepat, bayar via QRIS
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-300">
                    <span class="w-6 h-6 rounded-full bg-brand-yellow/20 text-brand-yellow flex items-center justify-center text-xs">✓</span>
                    Ratusan properti terverifikasi
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-300">
                    <span class="w-6 h-6 rounded-full bg-brand-yellow/20 text-brand-yellow flex items-center justify-center text-xs">✓</span>
                    Jadi mitra, komisi mulai 15%
                </div>
            </div>
        </div>

        {{-- Panel form --}}
        <div class="p-8 sm:p-12 flex flex-col justify-center">
            <h1 class="font-display text-2xl font-extrabold text-navy-900 mb-1">Buat Akun Baru</h1>
            <p class="text-sm text-gray-500 mb-6">Sudah punya akun?
                <a href="{{ route('login') }}" class="text-brand-blue font-semibold hover:underline">Masuk di sini</a>
            </p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl p-3.5 mb-5">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Pilihan role --}}
                <div class="grid grid-cols-2 gap-3">
                    <label class="border-2 rounded-xl p-3.5 text-sm text-center cursor-pointer transition has-[:checked]:border-navy-900 has-[:checked]:bg-navy-900 has-[:checked]:text-white border-gray-200">
                        <input type="radio" name="role" value="customer" class="hidden" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                        <div class="font-semibold">🧳 Customer</div>
                        <div class="text-xs opacity-70 mt-0.5">Booking properti</div>
                    </label>
                    <label class="border-2 rounded-xl p-3.5 text-sm text-center cursor-pointer transition has-[:checked]:border-navy-900 has-[:checked]:bg-navy-900 has-[:checked]:text-white border-gray-200">
                        <input type="radio" name="role" value="mitra" class="hidden" {{ old('role') === 'mitra' ? 'checked' : '' }}>
                        <div class="font-semibold">🏠 Mitra</div>
                        <div class="text-xs opacity-70 mt-0.5">Sewakan properti</div>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Nama Anda"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="nama@email.com"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="08xx-xxxx-xxxx"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Konfirmasi</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-navy-900 hover:bg-navy-800 transition text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-navy-900/20">
                    Daftar Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection