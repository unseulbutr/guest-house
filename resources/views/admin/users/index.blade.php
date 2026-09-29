@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

        {{-- ============================================================
             HEADER
        ============================================================ --}}
        <div class="mb-8">

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">

                <div>
                    <div class="flex items-center gap-2 mb-3">

                        <span class="inline-flex items-center gap-2 px-3 py-1.5
                                     rounded-full bg-blue-50 text-blue-700
                                     text-xs font-bold tracking-wide">

                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>

                            SUPER ADMIN

                        </span>

                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900">
                        Manajemen User
                    </h1>

                    <p class="mt-2 text-sm sm:text-base text-slate-500 max-w-2xl">
                        Kelola akun pengguna, role, dan status akses platform
                        dari satu tempat.
                    </p>
                </div>


                {{-- TAMBAH USER --}}
                <div>

                    <a href="{{ route('admin.users.create') }}"
                       class="group inline-flex items-center justify-center gap-2
                              px-5 py-3 rounded-xl
                              bg-slate-900 hover:bg-blue-700
                              text-white text-sm font-bold
                              shadow-lg shadow-slate-900/10
                              hover:shadow-blue-700/20
                              transition-all duration-200">

                        <svg class="w-5 h-5 transition-transform group-hover:rotate-90"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 4v16m8-8H4"/>

                        </svg>

                        Tambah User

                    </a>

                </div>

            </div>

        </div>


        {{-- ============================================================
             SUCCESS MESSAGE
        ============================================================ --}}
        @if (session('success'))

            <div class="mb-6">

                <div class="flex items-start gap-3
                            rounded-2xl border border-emerald-200
                            bg-emerald-50 px-5 py-4">

                    <div class="flex-shrink-0">

                        <div class="w-9 h-9 rounded-xl bg-emerald-100
                                    flex items-center justify-center">

                            <svg class="w-5 h-5 text-emerald-600"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7"/>

                            </svg>

                        </div>

                    </div>

                    <div>
                        <p class="text-sm font-bold text-emerald-800">
                            Berhasil
                        </p>

                        <p class="mt-0.5 text-sm text-emerald-700">
                            {{ session('success') }}
                        </p>
                    </div>

                </div>

            </div>

        @endif


        {{-- ============================================================
             SEARCH & FILTER PANEL
        ============================================================ --}}
        <div class="bg-white border border-slate-200
                    rounded-3xl shadow-sm p-5 sm:p-6 mb-6">

            <div class="flex items-center gap-3 mb-5">

                <div class="w-10 h-10 rounded-xl bg-blue-50
                            flex items-center justify-center">

                    <svg class="w-5 h-5 text-blue-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>

                    </svg>

                </div>

                <div>

                    <h2 class="text-sm font-bold text-slate-900">
                        Cari & Filter User
                    </h2>

                    <p class="text-xs text-slate-500 mt-0.5">
                        Gunakan pencarian atau role untuk menemukan akun dengan cepat.
                    </p>

                </div>

            </div>


            <form method="GET">

                <div class="grid grid-cols-1 md:grid-cols-[1fr_220px_auto] gap-3">

                    {{-- SEARCH --}}
                    <div class="relative">

                        <div class="absolute inset-y-0 left-0 pl-4
                                    flex items-center pointer-events-none">

                            <svg class="w-5 h-5 text-slate-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>

                            </svg>

                        </div>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="w-full h-12
                                   pl-11 pr-4
                                   rounded-xl
                                   border border-slate-200
                                   bg-slate-50
                                   text-sm text-slate-900
                                   placeholder:text-slate-400
                                   focus:bg-white
                                   focus:outline-none
                                   focus:ring-4
                                   focus:ring-blue-500/10
                                   focus:border-blue-500
                                   transition"
                        >

                    </div>


                    {{-- ROLE --}}
                    <div>

                        <select
                            name="role"
                            onchange="this.form.submit()"
                            class="w-full h-12
                                   px-4
                                   rounded-xl
                                   border border-slate-200
                                   bg-slate-50
                                   text-sm font-medium text-slate-700
                                   focus:bg-white
                                   focus:outline-none
                                   focus:ring-4
                                   focus:ring-blue-500/10
                                   focus:border-blue-500
                                   transition">

                            <option value="">
                                Semua Role
                            </option>

                            @foreach ($roles as $r)

                                <option
                                    value="{{ $r }}"
                                    {{ request('role') === $r ? 'selected' : '' }}>

                                    {{ ucfirst(str_replace('_', ' ', $r)) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BUTTON --}}
                    <div class="flex gap-2">

                        <button
                            type="submit"
                            class="h-12 px-6 rounded-xl
                                   bg-blue-600 hover:bg-blue-700
                                   text-white text-sm font-bold
                                   shadow-lg shadow-blue-600/15
                                   transition">

                            Cari

                        </button>


                        @if (request('search') || request('role'))

                            <a
                                href="{{ route('admin.users.index') }}"
                                class="h-12 px-5 rounded-xl
                                       border border-slate-200
                                       bg-white hover:bg-slate-50
                                       text-slate-600
                                       text-sm font-semibold
                                       inline-flex items-center justify-center
                                       transition">

                                Reset

                            </a>

                        @endif

                    </div>

                </div>

            </form>

        </div>


        {{-- ============================================================
             USER TABLE
        ============================================================ --}}
        <div class="bg-white
                    border border-slate-200
                    rounded-3xl
                    shadow-sm
                    overflow-hidden">

            {{-- TABLE HEADER --}}
            <div class="px-5 sm:px-6 py-5
                        border-b border-slate-100
                        flex flex-col sm:flex-row
                        sm:items-center sm:justify-between gap-3">

                <div>

                    <h2 class="text-base font-bold text-slate-900">
                        Daftar Pengguna
                    </h2>

                    <p class="text-xs text-slate-500 mt-1">
                        Kelola akun dan hak akses pengguna.
                    </p>

                </div>


                @if (request('search') || request('role'))

                    <div class="inline-flex items-center gap-2
                                px-3 py-1.5 rounded-full
                                bg-blue-50 text-blue-700
                                text-xs font-semibold">

                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>

                        Filter aktif

                    </div>

                @endif

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[850px]">

                    {{-- =================================================
                         TABLE HEAD
                    ================================================= --}}
                    <thead>

                        <tr class="bg-slate-50/80
                                   border-b border-slate-100">

                            <th class="text-left px-6 py-4
                                       text-[11px] font-extrabold
                                       uppercase tracking-wider
                                       text-slate-500">

                                Pengguna

                            </th>


                            <th class="text-left px-6 py-4
                                       text-[11px] font-extrabold
                                       uppercase tracking-wider
                                       text-slate-500">

                                Email

                            </th>


                            <th class="text-left px-6 py-4
                                       text-[11px] font-extrabold
                                       uppercase tracking-wider
                                       text-slate-500">

                                Role

                            </th>


                            <th class="text-left px-6 py-4
                                       text-[11px] font-extrabold
                                       uppercase tracking-wider
                                       text-slate-500">

                                Status

                            </th>


                            <th class="text-right px-6 py-4
                                       text-[11px] font-extrabold
                                       uppercase tracking-wider
                                       text-slate-500">

                                Aksi

                            </th>

                        </tr>

                    </thead>


                    {{-- =================================================
                         TABLE BODY
                    ================================================= --}}
                    <tbody class="divide-y divide-slate-100">

                        @forelse ($users as $user)

                            <tr class="group hover:bg-slate-50/70 transition-colors">


                                {{-- USER --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        {{-- AVATAR --}}
                                        <div class="w-11 h-11
                                                    rounded-xl
                                                    bg-gradient-to-br
                                                    from-blue-600
                                                    to-indigo-600
                                                    flex items-center justify-center
                                                    text-white
                                                    font-black
                                                    text-sm
                                                    shadow-sm
                                                    flex-shrink-0">

                                            {{ strtoupper(substr($user->name, 0, 1)) }}

                                        </div>


                                        <div class="min-w-0">

                                            <div class="flex items-center gap-2">

                                                <p class="font-bold text-slate-900 truncate">

                                                    {{ $user->name }}

                                                </p>


                                                @if ($user->id === auth()->id())

                                                    <span class="inline-flex
                                                                 items-center
                                                                 px-2 py-0.5
                                                                 rounded-full
                                                                 bg-slate-100
                                                                 text-slate-500
                                                                 text-[10px]
                                                                 font-bold
                                                                 whitespace-nowrap">

                                                        Anda

                                                    </span>

                                                @endif

                                            </div>

                                            <p class="text-xs text-slate-400 mt-0.5">
                                                ID #{{ $user->id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- EMAIL --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-2">

                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>

                                        </svg>

                                        <span class="text-sm text-slate-600">
                                            {{ $user->email }}
                                        </span>

                                    </div>

                                </td>


                                {{-- ROLE --}}
                                <td class="px-6 py-5">

                                    <div class="flex flex-wrap gap-1.5">

                                        @foreach ($user->roles as $role)

                                            @php

                                                $roleName = $role->name;

                                                $roleClass = match ($roleName) {

                                                    'super_admin' =>
                                                        'bg-purple-50 text-purple-700 border-purple-100',

                                                    'admin' =>
                                                        'bg-blue-50 text-blue-700 border-blue-100',

                                                    'mitra' =>
                                                        'bg-amber-50 text-amber-700 border-amber-100',

                                                    'customer' =>
                                                        'bg-emerald-50 text-emerald-700 border-emerald-100',

                                                    default =>
                                                        'bg-slate-50 text-slate-600 border-slate-200',

                                                };

                                            @endphp


                                            <span
                                                class="inline-flex items-center
                                                       px-2.5 py-1
                                                       rounded-lg
                                                       border
                                                       {{ $roleClass }}
                                                       text-[11px]
                                                       font-bold">

                                                {{ ucfirst(str_replace('_', ' ', $roleName)) }}

                                            </span>

                                        @endforeach

                                    </div>

                                </td>


                                {{-- STATUS --}}
                                <td class="px-6 py-5">

                                    @if ($user->is_active)

                                        <span class="inline-flex items-center gap-2
                                                     px-3 py-1.5
                                                     rounded-full
                                                     bg-emerald-50
                                                     text-emerald-700
                                                     border border-emerald-100
                                                     text-xs font-bold">

                                            <span class="w-2 h-2
                                                         rounded-full
                                                         bg-emerald-500">
                                            </span>

                                            Aktif

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-2
                                                     px-3 py-1.5
                                                     rounded-full
                                                     bg-red-50
                                                     text-red-600
                                                     border border-red-100
                                                     text-xs font-bold">

                                            <span class="w-2 h-2
                                                         rounded-full
                                                         bg-red-500">
                                            </span>

                                            Nonaktif

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="px-6 py-5 text-right">

                                    <div class="inline-flex items-center gap-2">


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('admin.users.edit', $user) }}"
                                            class="inline-flex items-center gap-1.5
                                                   px-3 py-2
                                                   rounded-lg
                                                   bg-blue-50
                                                   text-blue-700
                                                   hover:bg-blue-100
                                                   text-xs font-bold
                                                   transition">

                                            <svg class="w-4 h-4"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>

                                            </svg>

                                            Edit

                                        </a>


                                        {{-- TOGGLE ACTIVE --}}
                                        @if ($user->id !== auth()->id())

                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.toggle-active', $user) }}"
                                                onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?');">

                                                @csrf
                                                @method('PATCH')

                                                @if ($user->is_active)

                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-1.5
                                                               px-3 py-2
                                                               rounded-lg
                                                               bg-red-50
                                                               text-red-600
                                                               hover:bg-red-100
                                                               text-xs font-bold
                                                               transition">

                                                        <svg class="w-4 h-4"
                                                             fill="none"
                                                             stroke="currentColor"
                                                             viewBox="0 0 24 24">

                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>

                                                        </svg>

                                                        Nonaktifkan

                                                    </button>

                                                @else

                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-1.5
                                                               px-3 py-2
                                                               rounded-lg
                                                               bg-emerald-50
                                                               text-emerald-700
                                                               hover:bg-emerald-100
                                                               text-xs font-bold
                                                               transition">

                                                        <svg class="w-4 h-4"
                                                             fill="none"
                                                             stroke="currentColor"
                                                             viewBox="0 0 24 24">

                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M5 13l4 4L19 7"/>

                                                        </svg>

                                                        Aktifkan

                                                    </button>

                                                @endif

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>


                        @empty

                            {{-- EMPTY STATE --}}
                            <tr>

                                <td colspan="5" class="px-6 py-16">

                                    <div class="flex flex-col items-center justify-center">

                                        <div class="w-16 h-16
                                                    rounded-2xl
                                                    bg-slate-100
                                                    flex items-center justify-center
                                                    mb-4">

                                            <svg class="w-8 h-8 text-slate-400"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="1.7"
                                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>

                                            </svg>

                                        </div>


                                        <h3 class="text-sm font-bold text-slate-900">
                                            Tidak ada user ditemukan
                                        </h3>

                                        <p class="mt-1 text-sm text-slate-500 text-center">
                                            Coba gunakan kata kunci atau filter yang berbeda.
                                        </p>


                                        @if (request('search') || request('role'))

                                            <a
                                                href="{{ route('admin.users.index') }}"
                                                class="mt-4 text-sm font-bold
                                                       text-blue-600 hover:text-blue-700">

                                                Hapus filter

                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =========================================================
                 TABLE FOOTER
            ========================================================== --}}
            @if ($users->hasPages() || $users->total() > 0)

                <div class="px-5 sm:px-6 py-5
                            border-t border-slate-100
                            flex flex-col sm:flex-row
                            sm:items-center
                            sm:justify-between gap-4">

                    <div class="text-xs text-slate-500">

                        Menampilkan

                        <span class="font-bold text-slate-700">
                            {{ $users->firstItem() ?? 0 }}
                        </span>

                        –

                        <span class="font-bold text-slate-700">
                            {{ $users->lastItem() ?? 0 }}
                        </span>

                        dari

                        <span class="font-bold text-slate-700">
                            {{ $users->total() }}
                        </span>

                        user

                    </div>


                    <div>
                        {{ $users->links() }}
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection