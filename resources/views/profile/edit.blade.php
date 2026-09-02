@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10 space-y-6">

    <a href="{{ route('dashboard') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali ke Dashboard</a>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- ===== Data Diri ===== --}}
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
        <h2 class="font-bold text-navy-900 mb-4">Data Diri</h2>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center text-gray-400 text-xs">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                    @else
                        No Photo
                    @endif
                </div>
                <input type="file" name="avatar" class="text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
            </div>

            <button type="submit"
                    class="bg-brand-blue hover:bg-blue-700 transition text-white font-semibold px-6 py-2.5 rounded-lg">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- ===== Ganti Password ===== --}}
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
        <h2 class="font-bold text-navy-900 mb-4">Ganti Password</h2>

        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
            </div>

            <button type="submit"
                    class="bg-navy-900 hover:bg-navy-800 transition text-white font-semibold px-6 py-2.5 rounded-lg">
                Update Password
            </button>
        </form>
    </div>

    {{-- ===== Hapus Akun ===== --}}
    <div class="bg-white border border-red-100 shadow-sm rounded-2xl p-6">
        <h2 class="font-bold text-red-500 mb-1">Hapus Akun</h2>
        <p class="text-xs text-gray-500 mb-4">Tindakan ini permanen dan tidak bisa dibatalkan. Masukkan password Anda untuk konfirmasi.</p>

        <form method="POST" action="{{ route('profile.destroy') }}"
              onsubmit="return confirm('Yakin ingin menghapus akun Anda secara permanen?')" class="space-y-3">
            @csrf
            @method('DELETE')
            <input type="password" name="password" required placeholder="Password Anda"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
            <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 transition text-white font-semibold py-2.5 rounded-lg">
                Hapus Akun Saya
            </button>
        </form>
    </div>

    {{-- ===== Logout ===== --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-center text-red-500 font-semibold border border-red-200 hover:bg-red-50 transition py-2.5 rounded-lg">
            Logout
        </button>
    </form>

</div>
@endsection