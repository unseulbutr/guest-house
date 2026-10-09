@extends('layouts.app')

@section('title', 'Status Pengajuan Mitra')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/40 px-4 py-12 sm:py-20">

    <div class="mx-auto max-w-2xl">


        {{-- ======================================================
             STATUS PENDING
        ======================================================= --}}

        @if ($verification->status === 'pending')

            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl shadow-[#0c1830]/5">

                <div class="bg-[#0c1830] px-6 py-8 text-center text-white sm:px-10">

                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-amber-400/15 text-4xl">
                        ⏳
                    </div>

                    <h1 class="mt-5 font-display text-2xl font-extrabold">
                        Pengajuan Sedang Diverifikasi
                    </h1>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-white/60">
                        Data Anda sudah berhasil dikirim dan sedang diperiksa oleh Admin atau Super Admin.
                    </p>

                </div>


                <div class="p-6 sm:p-8">

                    {{-- STATUS --}}
                    <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">

                        <div class="flex items-start gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                !
                            </div>

                            <div>

                                <div class="text-sm font-bold text-amber-800">
                                    Menunggu Verifikasi
                                </div>

                                <p class="mt-1 text-xs leading-relaxed text-amber-700/80">
                                    Anda belum perlu menambahkan properti.
                                    Setelah pengajuan disetujui, Anda dapat masuk ke Dashboard Mitra
                                    dan menambahkan properti kapan saja.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- DATA --}}
                    <div class="mt-6 rounded-2xl border border-gray-100 bg-gray-50 p-5">

                        <div class="mb-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                            Data Pengajuan
                        </div>

                        <div class="space-y-3">

                            <div class="flex items-center justify-between gap-4">

                                <span class="text-xs text-gray-500">
                                    Nama
                                </span>

                                <span class="text-right text-xs font-bold text-[#0c1830]">
                                    {{ $verification->user->name }}
                                </span>

                            </div>


                            <div class="flex items-center justify-between gap-4">

                                <span class="text-xs text-gray-500">
                                    Email
                                </span>

                                <span class="text-right text-xs font-bold text-[#0c1830]">
                                    {{ $verification->user->email }}
                                </span>

                            </div>


                            <div class="flex items-center justify-between gap-4">

                                <span class="text-xs text-gray-500">
                                    Jenis Identitas
                                </span>

                                <span class="text-right text-xs font-bold uppercase text-[#0c1830]">
                                    {{ $verification->identity_type }}
                                </span>

                            </div>


                            <div class="flex items-center justify-between gap-4">

                                <span class="text-xs text-gray-500">
                                    Nomor Identitas
                                </span>

                                <span class="text-right text-xs font-bold text-[#0c1830]">
                                    {{ $verification->identity_number }}
                                </span>

                            </div>


                            @if ($verification->submitted_at)

                                <div class="flex items-center justify-between gap-4 border-t border-gray-200 pt-3">

                                    <span class="text-xs text-gray-500">
                                        Diajukan
                                    </span>

                                    <span class="text-right text-xs font-bold text-[#0c1830]">
                                        {{ $verification->submitted_at->format('d M Y, H:i') }}
                                    </span>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- BUTTON --}}
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">

                        <a
                            href="{{ route('dashboard') }}"
                            class="flex-1 rounded-xl border border-gray-200 px-5 py-3 text-center text-xs font-bold text-gray-600 transition hover:bg-gray-50"
                        >
                            Kembali ke Dashboard
                        </a>

                        <a
                            href="{{ route('home') }}"
                            class="flex-1 rounded-xl bg-[#0c1830] px-5 py-3 text-center text-xs font-bold text-white transition hover:bg-[#152442]"
                        >
                            Lihat Website
                        </a>

                    </div>

                </div>

            </div>


        {{-- ======================================================
             STATUS REJECTED
        ======================================================= --}}

        @elseif ($verification->status === 'rejected')

            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl shadow-[#0c1830]/5">

                <div class="bg-rose-600 px-6 py-8 text-center text-white sm:px-10">

                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/15 text-4xl">
                        ✕
                    </div>

                    <h1 class="mt-5 font-display text-2xl font-extrabold">
                        Pengajuan Belum Disetujui
                    </h1>

                    <p class="mt-2 text-sm text-white/75">
                        Pengajuan Anda ditolak dan dapat diajukan kembali.
                    </p>

                </div>


                <div class="p-6 sm:p-8">

                    @if ($verification->rejection_reason)

                        <div class="rounded-2xl border border-rose-100 bg-rose-50 p-5">

                            <div class="text-xs font-bold uppercase tracking-wide text-rose-500">
                                Alasan Penolakan
                            </div>

                            <p class="mt-2 text-sm leading-relaxed text-rose-700">
                                {{ $verification->rejection_reason }}
                            </p>

                        </div>

                    @endif


                    <div class="mt-6 rounded-2xl bg-gray-50 p-5">

                        <p class="text-sm font-bold text-[#0c1830]">
                            Anda dapat mengajukan kembali.
                        </p>

                        <p class="mt-1 text-xs leading-relaxed text-gray-500">
                            Periksa kembali data identitas dan foto sebelum mengirim pengajuan ulang.
                        </p>

                    </div>


                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">

                        <a
                            href="{{ route('mitra.apply') }}"
                            class="flex-1 rounded-xl bg-[#0c1830] px-5 py-3 text-center text-xs font-bold text-white transition hover:bg-[#152442]"
                        >
                            Ajukan Kembali
                        </a>

                        <a
                            href="{{ route('dashboard') }}"
                            class="flex-1 rounded-xl border border-gray-200 px-5 py-3 text-center text-xs font-bold text-gray-600 transition hover:bg-gray-50"
                        >
                            Kembali
                        </a>

                    </div>

                </div>

            </div>


        {{-- ======================================================
             STATUS APPROVED
        ======================================================= --}}

        @elseif ($verification->status === 'approved')

            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl shadow-[#0c1830]/5">

                <div class="bg-emerald-600 px-6 py-10 text-center text-white sm:px-10">

                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/15 text-4xl">
                        ✓
                    </div>

                    <h1 class="mt-5 font-display text-2xl font-extrabold">
                        Selamat, Anda Telah Disetujui!
                    </h1>

                    <p class="mt-2 text-sm text-white/75">
                        Akun Anda sekarang telah menjadi Mitra GuestHouse.
                    </p>

                </div>


                <div class="p-6 text-center sm:p-8">

                    <p class="mx-auto max-w-md text-sm leading-relaxed text-gray-500">
                        Anda sekarang dapat masuk ke Dashboard Mitra.
                        Anda tidak wajib langsung menambahkan properti dan dapat melakukannya kapan saja.
                    </p>


                    <a
                        href="{{ route('dashboard') }}"
                        class="mt-7 inline-flex rounded-xl bg-[#0c1830] px-7 py-3 text-xs font-bold text-white transition hover:bg-[#152442]"
                    >
                        Masuk Dashboard Mitra
                    </a>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection