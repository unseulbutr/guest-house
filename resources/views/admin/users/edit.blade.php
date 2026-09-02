@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <a href="{{ route('admin.users.index') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali ke Manajemen User</a>

    <h1 class="text-2xl font-extrabold text-navy-900 mt-3 mb-6">Edit User: {{ $user->name }}</h1>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white border border-gray-100 rounded-2xl p-6 space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
            @if ($user->id === auth()->id())
                <input type="hidden" name="role" value="{{ $user->roles->first()->name ?? '' }}">
                <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2.5 text-sm text-gray-500">
                    {{ ucfirst(str_replace('_',' ', $user->roles->first()->name ?? '-')) }}
                    <span class="text-xs">(role akun sendiri tidak bisa diubah)</span>
                </div>
            @else
                <select name="role" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
                    @foreach ($roles as $r)
                        <option value="{{ $r }}" {{ old('role', $user->roles->first()->name ?? '') === $r ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ',$r)) }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
                <p class="text-[11px] text-gray-400 mt-1">Kosongkan kalau tidak ingin ganti password.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue">
            </div>
        </div>

        <button type="submit" class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection