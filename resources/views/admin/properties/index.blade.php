@extends('layouts.app')

@section('title', 'Kelola Properti')

@section('dashboard_layout', 'true')

@section('content')

<div class="min-h-screen bg-slate-50 py-8">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <p class="text-sm font-semibold text-blue-600">
                    ADMINISTRASI PROPERTI
                </p>

                <h1 class="mt-1 text-2xl font-bold text-slate-900">
                    Kelola Properti
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola seluruh properti yang terdaftar di platform.
                </p>

            </div>

            <div class="flex flex-wrap gap-3">

                <a
                    href="{{ route('dashboard') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('admin.properties.create') }}"
                    class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                >
                    + Tambah Properti
                </a>

            </div>

        </div>


        {{-- FLASH --}}
        @if (session('success'))

            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>

        @endif


        {{-- STATISTICS --}}
        <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <p class="text-sm text-slate-500">
                    Total Properti
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $totalProperties }}
                </p>

            </div>


            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">

                <p class="text-sm text-emerald-700">
                    Properti Aktif
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800">
                    {{ $activeProperties }}
                </p>

            </div>


            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">

                <p class="text-sm text-amber-700">
                    Menunggu Verifikasi
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-800">
                    {{ $pendingProperties }}
                </p>

            </div>


            <div class="rounded-2xl border border-red-100 bg-red-50 p-5">

                <p class="text-sm text-red-700">
                    Ditolak
                </p>

                <p class="mt-2 text-3xl font-bold text-red-800">
                    {{ $rejectedProperties }}
                </p>

            </div>

        </div>


        {{-- FILTER --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <form
                method="GET"
                action="{{ route('admin.properties.index') }}"
                class="grid gap-4 md:grid-cols-[1fr_220px_auto]"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama, kota, atau alamat..."
                    class="rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                >

                <select
                    name="status"
                    class="rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Aktif
                    </option>

                    <option
                        value="pending"
                        @selected(request('status') === 'pending')
                    >
                        Pending
                    </option>

                    <option
                        value="rejected"
                        @selected(request('status') === 'rejected')
                    >
                        Ditolak
                    </option>

                </select>

                <button
                    type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    Filter
                </button>

            </form>

        </div>


        {{-- TABLE --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="border-b border-slate-100 bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Properti
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Mitra
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Harga
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Booking
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wide text-slate-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse ($properties as $property)

                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-4">

                                        <div class="h-14 w-20 shrink-0 overflow-hidden rounded-xl bg-slate-100">

                                            @if ($property->cover_image)

                                                <img
                                                    src="{{ asset('storage/' . $property->cover_image) }}"
                                                    alt="{{ $property->name }}"
                                                    class="h-full w-full object-cover"
                                                >

                                            @else

                                                <div class="flex h-full items-center justify-center text-xs text-slate-400">
                                                    No Image
                                                </div>

                                            @endif

                                        </div>

                                        <div>

                                            <p class="font-semibold text-slate-900">
                                                {{ $property->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $property->city }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-5">

                                    <p class="text-sm font-medium text-slate-800">
                                        {{ $property->mitra?->name ?? '-' }}
                                    </p>

                                </td>


                                <td class="px-6 py-5">

                                    <p class="text-sm font-semibold text-slate-900">
                                        Rp {{ number_format($property->price_per_night, 0, ',', '.') }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        / malam
                                    </p>

                                </td>


                                <td class="px-6 py-5">

                                    @if ($property->status === 'active')

                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Aktif
                                        </span>

                                    @elseif ($property->status === 'pending')

                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Pending
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Ditolak
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-5">

                                    <span class="text-sm font-semibold text-slate-800">
                                        {{ $property->bookings_count }}
                                    </span>

                                </td>


                                <td class="px-6 py-5">

                                    <div class="flex justify-end gap-2">

                                        <a
                                            href="{{ route('properties.show', $property) }}"
                                            target="_blank"
                                            class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50"
                                        >
                                            Lihat
                                        </a>


                                        @if ($property->status === 'pending')

                                            <form
                                                action="{{ route('admin.properties.approve', $property) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700"
                                                >
                                                    Approve
                                                </button>

                                            </form>


                                            <form
                                                action="{{ route('admin.properties.reject', $property) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700"
                                                >
                                                    Reject
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-16 text-center"
                                >

                                    <p class="font-semibold text-slate-700">
                                        Belum ada properti
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Tambahkan properti baru untuk mulai mengelola data.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            @if ($properties->hasPages())

                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $properties->links() }}
                </div>

            @endif

        </div>

    </div>

</div>

@endsection