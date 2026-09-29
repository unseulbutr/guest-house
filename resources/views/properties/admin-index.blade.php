@extends('layouts.app')

@section('title', 'Kelola Properti')

@section('dashboard_layout', 'true')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div>
                <p class="text-sm text-blue-600 font-semibold mb-1">
                    Admin Management
                </p>

                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">
                    Kelola Properti
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Kelola seluruh properti yang terdaftar di platform.
                </p>
            </div>

            <a
                href="{{ route('admin.properties.create') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition"
            >
                <span class="text-lg">＋</span>
                Tambah Properti
            </a>

        </div>


        {{-- FLASH --}}
        @if(session('success'))

            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>

        @endif


        {{-- FILTER --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">

            <form
                method="GET"
                action="{{ route('admin.properties.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4"
            >

                <div class="md:col-span-2">

                    <label class="block text-xs font-semibold text-slate-500 mb-1">
                        Cari Properti / Kota / Mitra
                    </label>

                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="Contoh: Villa Sejuk, Bandung, Budi..."
                        class="w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>

                <div>

                    <label class="block text-xs font-semibold text-slate-500 mb-1">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="active"
                            {{ request('status') === 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="pending"
                            {{ request('status') === 'pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="rejected"
                            {{ request('status') === 'rejected' ? 'selected' : '' }}
                        >
                            Rejected
                        </option>

                    </select>

                </div>

                <div class="flex items-end">

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 text-sm font-semibold transition"
                    >
                        Terapkan Filter
                    </button>

                </div>

            </form>

        </div>


        {{-- TABLE --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-slate-50 border-b border-slate-200">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">
                                Properti
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">
                                Mitra
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">
                                Tipe
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">
                                Harga
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($properties as $property)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 shrink-0">

                                            @if($property->cover_image)

                                                <img
                                                    src="{{ asset('storage/' . $property->cover_image) }}"
                                                    class="w-full h-full object-cover"
                                                >

                                            @else

                                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                    🏠
                                                </div>

                                            @endif

                                        </div>

                                        <div>

                                            <div class="font-bold text-slate-900">
                                                {{ $property->name }}
                                            </div>

                                            <div class="text-xs text-slate-500">
                                                {{ $property->city }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-4">

                                    <div class="text-sm font-semibold text-slate-800">
                                        {{ $property->mitra?->name ?? 'Tidak ada' }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        ID #{{ $property->mitra_id }}
                                    </div>

                                </td>


                                <td class="px-6 py-4">

                                    <span class="text-sm text-slate-700">
                                        {{ ucfirst(str_replace('_', ' ', $property->type)) }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <div class="text-sm font-bold text-slate-900">
                                        Rp {{ number_format($property->price_per_night, 0, ',', '.') }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        / malam
                                    </div>

                                </td>


                                <td class="px-6 py-4">

                                    @if($property->status === 'active')

                                        <span class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                            Active
                                        </span>

                                    @elseif($property->status === 'pending')

                                        <span class="inline-flex px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">
                                            Pending
                                        </span>

                                    @else

                                        <span class="inline-flex px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                                            Rejected
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4">

                                    <div class="flex justify-end gap-2">

                                        <a
                                            href="{{ route('properties.show', $property) }}"
                                            class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold"
                                        >
                                            Lihat
                                        </a>

                                        @if($property->status === 'pending')

                                            <form
                                                method="POST"
                                                action="{{ route('admin.properties.approve', $property) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    class="px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold"
                                                >
                                                    Setujui
                                                </button>

                                            </form>

                                            <form
                                                method="POST"
                                                action="{{ route('admin.properties.reject', $property) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    class="px-3 py-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold"
                                                >
                                                    Tolak
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

                                    <div class="text-4xl mb-3">
                                        🏠
                                    </div>

                                    <div class="font-bold text-slate-800">
                                        Belum ada properti
                                    </div>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Tambahkan properti baru menggunakan tombol di atas.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            @if($properties->hasPages())

                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $properties->links() }}
                </div>

            @endif

        </div>

    </div>

</div>

@endsection