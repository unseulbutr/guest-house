@extends('layouts.app')

@section('title', 'Hubungi Admin')

@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="font-display text-2xl font-extrabold text-navy-900 mb-1">Hubungi Admin</h1>
    <p class="text-sm text-gray-500 mb-8">
        Kirim pesan ke tim kami — biasanya dibalas dalam 1x24 jam lewat email.
        Untuk respons lebih cepat, coba <a href="#" class="text-brand-blue font-semibold hover:underline">chat via WhatsApp</a>.
    </p>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-700 text-sm px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- ====== Histori pesan (kalau user login & pernah kirim sebelumnya) ====== --}}
    @auth
        @if ($messages->isNotEmpty())
            <div class="mb-8 space-y-3">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wide">Riwayat Pesanmu</h2>
                @foreach ($messages as $msg)
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-card">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs text-gray-400">{{ $msg->created_at->translatedFormat('d M Y, H:i') }}</span>
                            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full
                                {{ $msg->status === 'answered' ? 'bg-green-50 text-green-600' : ($msg->status === 'closed' ? 'bg-gray-100 text-gray-500' : 'bg-yellow-50 text-yellow-600') }}">
                                {{ $msg->status === 'answered' ? 'Sudah Dibalas' : ($msg->status === 'closed' ? 'Selesai' : 'Menunggu Balasan') }}
                            </span>
                        </div>
                        <p class="text-sm text-navy-900">{{ $msg->message }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    @endauth

    {{-- ====== Form kirim pesan baru — bergaya bubble chat ====== --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-card overflow-hidden">
        <div class="bg-navy-900 text-white px-5 py-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-base">🎧</div>
            <div>
                <p class="font-display font-bold text-sm leading-tight">Tim Guest House</p>
                <p class="text-[11px] text-gray-300">Biasanya balas dalam 1x24 jam</p>
            </div>
        </div>

        <div class="p-5 bg-gray-50/60">
            <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-[85%] shadow-sm">
                <p class="text-sm text-navy-900">
                    Halo! 👋 Ada yang bisa kami bantu? Tulis pertanyaan/kendala kamu di bawah, ya.
                </p>
            </div>
        </div>

        <form action="{{ route('support.send') }}" method="POST" class="p-5 border-t border-gray-100 space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-navy-900 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-navy-900 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Pesan</label>
                <textarea name="message" rows="4" required placeholder="Ceritain kendala atau pertanyaan kamu di sini..."
                          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-navy-900 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">{{ old('message') }}</textarea>
                @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-xl text-sm">
                Kirim Pesan
            </button>
        </form>
    </div>
</section>
@endsection