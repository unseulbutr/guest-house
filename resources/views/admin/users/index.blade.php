@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-navy-900">Manajemen User</h1>
            <p class="text-sm text-gray-500">Kelola akun Admin, Mitra, dan Customer. Khusus Super Admin.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-5 py-2.5 rounded-full">
            + Tambah User
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">{{ session('success') }}</div>
    @endif

    {{-- Filter & search --}}
    <form method="GET" class="flex flex-wrap items-center gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
               class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">

        <select name="role" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
            <option value="">Semua Role</option>
            @foreach ($roles as $r)
                <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
            @endforeach
        </select>

        <button type="submit" class="text-sm font-semibold text-brand-blue px-4 py-2 rounded-lg hover:bg-blue-50 transition">Cari</button>
        @if (request('search') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
        @endif
    </form>

    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold">Nama</th>
                        <th class="text-left px-5 py-3 font-semibold">Email</th>
                        <th class="text-left px-5 py-3 font-semibold">Role</th>
                        <th class="text-left px-5 py-3 font-semibold">Status</th>
                        <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-5 py-4 font-medium text-navy-900">
                                {{ $user->name }}
                                @if ($user->id === auth()->id())
                                    <span class="text-[10px] text-gray-400 font-normal">(Anda)</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                @foreach ($user->roles as $role)
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-brand-blue">
                                        {{ ucfirst(str_replace('_',' ',$role->name)) }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-5 py-4">
                                @if ($user->is_active)
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-50 text-green-700">Aktif</span>
                                @else
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-red-50 text-red-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-brand-blue hover:underline font-semibold text-xs">Edit</a>

                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}"
                                              onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?');">
                                            @csrf @method('PATCH')
                                            <button class="text-xs font-semibold hover:underline {{ $user->is_active ? 'text-red-500' : 'text-green-600' }}">
                                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-500">Tidak ada user ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">{{ $users->links() }}</div>
</div>
@endsection