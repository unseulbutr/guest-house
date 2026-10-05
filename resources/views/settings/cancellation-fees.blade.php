@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        Biaya Pembatalan
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Atur persentase potongan dari DP berdasarkan waktu pembatalan booking.
                    </p>
                </div>

                <a
                    href="{{ route('admin.settings.edit') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                >
                    ← Pengaturan Platform
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                <div class="font-semibold text-red-700">
                    Ada data yang perlu diperbaiki:
                </div>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Card Header --}}
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-bold text-slate-900">
                    Persentase Potongan Pembatalan
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Persentase dihitung dari jumlah DP yang sudah dibayarkan customer.
                </p>
            </div>

            {{-- Form --}}
            <form
                action="{{ route('admin.settings.cancellation-fees.update') }}"
                method="POST"
                class="divide-y divide-slate-100"
            >
                @csrf
                @method('PUT')

                {{-- H-7 --}}
                <div class="px-6 py-5">
                    <div class="grid gap-4 md:grid-cols-[1fr_220px] md:items-center">
                        <div>
                            <label
                                for="cancellation_fee_h7"
                                class="block text-sm font-semibold text-slate-900"
                            >
                                ≥ H-7
                            </label>

                            <p class="mt-1 text-sm text-slate-500">
                                Pembatalan 7 hari atau lebih sebelum check-in.
                            </p>
                        </div>

                        <div>
                            <div class="relative">
                                <input
                                    type="number"
                                    id="cancellation_fee_h7"
                                    name="cancellation_fee_h7"
                                    value="{{ old('cancellation_fee_h7', $settings->cancellation_fee_h7 ?? 0) }}"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm font-medium text-slate-400">
                                    %
                                </span>
                            </div>

                            @error('cancellation_fee_h7')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- H-3 s/d H-6 --}}
                <div class="px-6 py-5">
                    <div class="grid gap-4 md:grid-cols-[1fr_220px] md:items-center">
                        <div>
                            <label
                                for="cancellation_fee_h3"
                                class="block text-sm font-semibold text-slate-900"
                            >
                                H-3 s/d H-6
                            </label>

                            <p class="mt-1 text-sm text-slate-500">
                                Pembatalan 3 sampai 6 hari sebelum check-in.
                            </p>
                        </div>

                        <div>
                            <div class="relative">
                                <input
                                    type="number"
                                    id="cancellation_fee_h3"
                                    name="cancellation_fee_h3"
                                    value="{{ old('cancellation_fee_h3', $settings->cancellation_fee_h3 ?? 5) }}"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm font-medium text-slate-400">
                                    %
                                </span>
                            </div>

                            @error('cancellation_fee_h3')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- H-2 --}}
                <div class="px-6 py-5">
                    <div class="grid gap-4 md:grid-cols-[1fr_220px] md:items-center">
                        <div>
                            <label
                                for="cancellation_fee_h2"
                                class="block text-sm font-semibold text-slate-900"
                            >
                                H-2
                            </label>

                            <p class="mt-1 text-sm text-slate-500">
                                Pembatalan 2 hari sebelum check-in.
                            </p>
                        </div>

                        <div>
                            <div class="relative">
                                <input
                                    type="number"
                                    id="cancellation_fee_h2"
                                    name="cancellation_fee_h2"
                                    value="{{ old('cancellation_fee_h2', $settings->cancellation_fee_h2 ?? 10) }}"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm font-medium text-slate-400">
                                    %
                                </span>
                            </div>

                            @error('cancellation_fee_h2')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- H-1 --}}
                <div class="px-6 py-5">
                    <div class="grid gap-4 md:grid-cols-[1fr_220px] md:items-center">
                        <div>
                            <label
                                for="cancellation_fee_h1"
                                class="block text-sm font-semibold text-slate-900"
                            >
                                H-1
                            </label>

                            <p class="mt-1 text-sm text-slate-500">
                                Pembatalan 1 hari sebelum check-in.
                            </p>
                        </div>

                        <div>
                            <div class="relative">
                                <input
                                    type="number"
                                    id="cancellation_fee_h1"
                                    name="cancellation_fee_h1"
                                    value="{{ old('cancellation_fee_h1', $settings->cancellation_fee_h1 ?? 15) }}"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm font-medium text-slate-400">
                                    %
                                </span>
                            </div>

                            @error('cancellation_fee_h1')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Same Day --}}
                <div class="px-6 py-5">
                    <div class="grid gap-4 md:grid-cols-[1fr_220px] md:items-center">
                        <div>
                            <label
                                for="cancellation_fee_same_day"
                                class="block text-sm font-semibold text-slate-900"
                            >
                                Hari H sebelum Check-in
                            </label>

                            <p class="mt-1 text-sm text-slate-500">
                                Berlaku jika pembatalan dilakukan pada hari check-in sebelum waktu check-in.
                            </p>
                        </div>

                        <div>
                            <div class="relative">
                                <input
                                    type="number"
                                    id="cancellation_fee_same_day"
                                    name="cancellation_fee_same_day"
                                    value="{{ old('cancellation_fee_same_day', $settings->cancellation_fee_same_day ?? 20) }}"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm font-medium text-slate-400">
                                    %
                                </span>
                            </div>

                            @error('cancellation_fee_same_day')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex flex-col gap-3 bg-slate-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-slate-500">
                        Nilai harus berada di antara 0% sampai 100%.
                    </p>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                        Simpan Biaya Pembatalan
                    </button>
                </div>
            </form>
        </div>

        {{-- Information --}}
        <div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4">
            <h3 class="text-sm font-semibold text-blue-900">
                Cara kerja
            </h3>

            <p class="mt-1 text-sm leading-6 text-blue-800">
                Persentase yang diatur di halaman ini akan digunakan ketika customer
                membatalkan booking yang sudah membayar DP. Potongan dihitung dari
                nominal DP, bukan dari total harga booking.
            </p>
        </div>

    </div>
</div>
@endsection