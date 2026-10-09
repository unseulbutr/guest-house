@extends('layouts.app')

@section('title', 'Verifikasi Mitra')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a
                    href="{{ route('dashboard') }}"
                    class="hover:text-gray-900 transition"
                >
                    Dashboard
                </a>

                <span>/</span>

                <span class="text-gray-700">
                    Verifikasi Mitra
                </span>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Verifikasi Mitra
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola pengajuan pengguna yang ingin menjadi Mitra.
            </p>
        </div>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            <div class="flex items-start gap-3">
                <span class="text-lg">✓</span>

                <div>
                    <div class="font-semibold">
                        Berhasil
                    </div>

                    <div class="mt-0.5">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <div class="flex items-start gap-3">
                <span class="text-lg">!</span>

                <div>
                    <div class="font-semibold">
                        Terjadi kesalahan
                    </div>

                    <div class="mt-0.5">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- VALIDATION ERROR --}}
    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">

            <div class="font-semibold mb-2">
                Periksa kembali data berikut:
            </div>

            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- STATISTICS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- SEMUA --}}
        <a
            href="{{ route('admin.mitra-verifications.index') }}"
            class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition
            {{ !request('status') ? 'ring-2 ring-gray-900' : '' }}"
        >

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Semua
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $statistics['all'] ?? 0 }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center text-xl">
                    👥
                </div>

            </div>

        </a>


        {{-- PENDING --}}
        <a
            href="{{ route('admin.mitra-verifications.index', ['status' => 'pending']) }}"
            class="bg-white border border-yellow-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition
            {{ request('status') === 'pending' ? 'ring-2 ring-yellow-400' : '' }}"
        >

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-yellow-600">
                        Menunggu
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $statistics['pending'] ?? 0 }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center text-xl">
                    ⏳
                </div>

            </div>

        </a>


        {{-- APPROVED --}}
        <a
            href="{{ route('admin.mitra-verifications.index', ['status' => 'approved']) }}"
            class="bg-white border border-green-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition
            {{ request('status') === 'approved' ? 'ring-2 ring-green-400' : '' }}"
        >

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-600">
                        Disetujui
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $statistics['approved'] ?? 0 }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center text-xl">
                    ✓
                </div>

            </div>

        </a>


        {{-- REJECTED --}}
        <a
            href="{{ route('admin.mitra-verifications.index', ['status' => 'rejected']) }}"
            class="bg-white border border-red-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition
            {{ request('status') === 'rejected' ? 'ring-2 ring-red-400' : '' }}"
        >

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-red-600">
                        Ditolak
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $statistics['rejected'] ?? 0 }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center text-xl">
                    ✕
                </div>

            </div>

        </a>

    </div>


    {{-- SEARCH & FILTER --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-6">

        <form
            method="GET"
            action="{{ route('admin.mitra-verifications.index') }}"
            class="grid grid-cols-1 md:grid-cols-12 gap-4"
        >

            {{-- SEARCH --}}
            <div class="md:col-span-7">

                <label
                    for="search"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Cari Pengajuan
                </label>

                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Nama, email, atau nomor identitas..."
                    class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                >

            </div>


            {{-- STATUS --}}
            <div class="md:col-span-3">

                <label
                    for="status"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Status
                </label>

                <select
                    name="status"
                    id="status"
                    class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="pending"
                        @selected(request('status') === 'pending')
                    >
                        Menunggu
                    </option>

                    <option
                        value="approved"
                        @selected(request('status') === 'approved')
                    >
                        Disetujui
                    </option>

                    <option
                        value="rejected"
                        @selected(request('status') === 'rejected')
                    >
                        Ditolak
                    </option>

                </select>

            </div>


            {{-- BUTTON --}}
            <div class="md:col-span-2 flex items-end gap-2">

                <button
                    type="submit"
                    class="flex-1 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 transition"
                >
                    Cari
                </button>

                @if(request('search') || request('status'))
                    <a
                        href="{{ route('admin.mitra-verifications.index') }}"
                        class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition"
                        title="Reset filter"
                    >
                        ↻
                    </a>
                @endif

            </div>

        </form>

    </div>


    {{-- TABLE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-200">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <h2 class="font-bold text-gray-900">
                        Daftar Pengajuan
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Menampilkan
                        {{ $verifications->firstItem() ?? 0 }}
                        -
                        {{ $verifications->lastItem() ?? 0 }}
                        dari
                        {{ $verifications->total() }}
                        pengajuan
                    </p>
                </div>

            </div>

        </div>


        @if($verifications->count())

            {{-- DESKTOP TABLE --}}
            <div class="hidden md:block overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                Pengguna
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                Identitas
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                Pengajuan
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                Status
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-gray-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($verifications as $verification)

                            @php
                                $status = $verification->status;

                                $statusLabel = match ($status) {
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => ucfirst($status),
                                };

                                $statusClass = match ($status) {
                                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'approved' => 'bg-green-50 text-green-700 border-green-200',
                                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                                };

                                $identityLabel = match ($verification->identity_type) {
                                    'ktp' => 'KTP',
                                    'sim' => 'SIM',
                                    'paspor' => 'Paspor',
                                    default => strtoupper($verification->identity_type ?? '-'),
                                };
                            @endphp

                            <tr class="hover:bg-gray-50 transition">

                                {{-- USER --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-600 shrink-0">
                                            {{ strtoupper(substr($verification->user->name ?? '?', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">

                                            <div class="font-semibold text-gray-900 truncate">
                                                {{ $verification->user->name ?? 'User tidak ditemukan' }}
                                            </div>

                                            <div class="text-xs text-gray-500 truncate">
                                                {{ $verification->user->email ?? '-' }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- IDENTITY --}}
                                <td class="px-5 py-4">

                                    <div class="font-semibold text-gray-800">
                                        {{ $identityLabel }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        {{ $verification->identity_number ?: '-' }}
                                    </div>

                                </td>


                                {{-- SUBMITTED --}}
                                <td class="px-5 py-4">

                                    @if($verification->submitted_at)

                                        <div class="font-medium text-gray-800">
                                            {{ $verification->submitted_at->format('d M Y') }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ $verification->submitted_at->format('H:i') }} WIB
                                        </div>

                                    @else

                                        <span class="text-gray-400">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td class="px-5 py-4">

                                    <span
                                        class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold {{ $statusClass }}"
                                    >
                                        {{ $statusLabel }}
                                    </span>

                                    @if($verification->status === 'approved' && $verification->verified_at)

                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $verification->verified_at->format('d M Y H:i') }}
                                        </div>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="px-5 py-4 text-right">

                                    <a
                                        href="{{ route('admin.mitra-verifications.show', $verification) }}"
                                        class="inline-flex items-center rounded-xl bg-gray-900 px-4 py-2 text-xs font-semibold text-white hover:bg-gray-800 transition"
                                    >
                                        Lihat Detail
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- MOBILE CARDS --}}
            <div class="md:hidden divide-y divide-gray-100">

                @foreach($verifications as $verification)

                    @php
                        $status = $verification->status;

                        $statusLabel = match ($status) {
                            'pending' => 'Menunggu',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            default => ucfirst($status),
                        };

                        $statusClass = match ($status) {
                            'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'approved' => 'bg-green-50 text-green-700 border-green-200',
                            'rejected' => 'bg-red-50 text-red-700 border-red-200',
                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                        };

                        $identityLabel = match ($verification->identity_type) {
                            'ktp' => 'KTP',
                            'sim' => 'SIM',
                            'paspor' => 'Paspor',
                            default => strtoupper($verification->identity_type ?? '-'),
                        };
                    @endphp

                    <div class="p-5">

                        <div class="flex items-start justify-between gap-4">

                            <div class="flex items-center gap-3 min-w-0">

                                <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-600 shrink-0">
                                    {{ strtoupper(substr($verification->user->name ?? '?', 0, 1)) }}
                                </div>

                                <div class="min-w-0">

                                    <div class="font-bold text-gray-900 truncate">
                                        {{ $verification->user->name ?? 'User tidak ditemukan' }}
                                    </div>

                                    <div class="text-xs text-gray-500 truncate">
                                        {{ $verification->user->email ?? '-' }}
                                    </div>

                                </div>

                            </div>


                            <span
                                class="shrink-0 inline-flex items-center rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $statusClass }}"
                            >
                                {{ $statusLabel }}
                            </span>

                        </div>


                        <div class="mt-4 grid grid-cols-2 gap-3">

                            <div class="rounded-xl bg-gray-50 p-3">

                                <div class="text-[11px] uppercase tracking-wide text-gray-500 font-semibold">
                                    Identitas
                                </div>

                                <div class="mt-1 text-sm font-semibold text-gray-800">
                                    {{ $identityLabel }}
                                </div>

                                <div class="text-xs text-gray-500 break-all">
                                    {{ $verification->identity_number ?: '-' }}
                                </div>

                            </div>


                            <div class="rounded-xl bg-gray-50 p-3">

                                <div class="text-[11px] uppercase tracking-wide text-gray-500 font-semibold">
                                    Diajukan
                                </div>

                                <div class="mt-1 text-sm font-semibold text-gray-800">

                                    @if($verification->submitted_at)
                                        {{ $verification->submitted_at->format('d M Y') }}
                                    @else
                                        -
                                    @endif

                                </div>

                                @if($verification->submitted_at)

                                    <div class="text-xs text-gray-500">
                                        {{ $verification->submitted_at->format('H:i') }} WIB
                                    </div>

                                @endif

                            </div>

                        </div>


                        <a
                            href="{{ route('admin.mitra-verifications.show', $verification) }}"
                            class="mt-4 w-full inline-flex items-center justify-center rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-800 transition"
                        >
                            Lihat Detail
                        </a>

                    </div>

                @endforeach

            </div>

        @else

            {{-- EMPTY STATE --}}
            <div class="px-6 py-16 text-center">

                <div class="mx-auto w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-3xl mb-4">
                    📋
                </div>

                <h3 class="text-lg font-bold text-gray-900">
                    Tidak ada pengajuan
                </h3>

                <p class="mt-1 text-sm text-gray-500 max-w-md mx-auto">
                    @if(request('search') || request('status'))
                        Tidak ditemukan pengajuan yang sesuai dengan filter atau pencarian.
                    @else
                        Belum ada pengguna yang mengajukan diri untuk menjadi Mitra.
                    @endif
                </p>

                @if(request('search') || request('status'))

                    <a
                        href="{{ route('admin.mitra-verifications.index') }}"
                        class="inline-flex items-center mt-5 rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition"
                    >
                        Reset Filter
                    </a>

                @endif

            </div>

        @endif


        {{-- PAGINATION --}}
        @if($verifications->hasPages())

            <div class="px-5 py-4 border-t border-gray-200">
                {{ $verifications->links() }}
            </div>

        @endif

    </div>

</div>

@endsection