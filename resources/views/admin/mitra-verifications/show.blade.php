@extends('layouts.app')

@section('title', 'Detail Verifikasi Mitra')

@section('content')

<div class="min-h-screen bg-slate-50 py-8">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-6">

            <a
                href="{{ route('admin.mitra-verifications.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition"
            >
                ← Kembali ke Pengajuan Mitra
            </a>

            <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>

                    <p class="text-sm font-semibold text-indigo-600">
                        Verifikasi Mitra
                    </p>

                    <h1 class="mt-1 text-2xl sm:text-3xl font-bold text-slate-900">
                        Detail Pengajuan Mitra
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Periksa data identitas dan dokumen calon mitra sebelum memberikan keputusan.
                    </p>

                </div>


                {{-- STATUS --}}
                @php

                    $status = $verification->status ?? 'pending';

                    $statusLabel = match ($status) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu Verifikasi',
                    };

                    $statusClass = match ($status) {
                        'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'rejected' => 'bg-red-100 text-red-700 border-red-200',
                        default => 'bg-amber-100 text-amber-700 border-amber-200',
                    };

                @endphp


                <span
                    class="inline-flex items-center justify-center rounded-full border px-4 py-2 text-sm font-bold {{ $statusClass }}"
                >
                    {{ $statusLabel }}
                </span>

            </div>

        </div>


        {{-- SUCCESS --}}
        @if(session('success'))

            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">

                <div class="flex items-start gap-3">

                    <span class="text-lg">
                        ✓
                    </span>

                    <div>
                        {{ session('success') }}
                    </div>

                </div>

            </div>

        @endif


        {{-- ERROR --}}
        @if(session('error'))

            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">

                <div class="flex items-start gap-3">

                    <span class="text-lg">
                        !
                    </span>

                    <div>
                        {{ session('error') }}
                    </div>

                </div>

            </div>

        @endif


        {{-- VALIDATION ERROR --}}
        @if($errors->any())

            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

                <p class="font-bold mb-2">
                    Terjadi kesalahan:
                </p>

                <ul class="list-disc pl-5 space-y-1">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


            {{-- =====================================================
                 KOLOM KIRI
            ====================================================== --}}
            <div class="lg:col-span-2 space-y-6">


                {{-- DATA PEMOHON --}}
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-slate-100">

                        <h2 class="text-lg font-bold text-slate-900">
                            Data Pemohon
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Informasi akun yang mengajukan menjadi Mitra.
                        </p>

                    </div>


                    <div class="p-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                            {{-- NAMA --}}
                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                    Nama
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $verification->user->name ?? '-' }}
                                </p>

                            </div>


                            {{-- EMAIL --}}
                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                    Email
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900 break-all">
                                    {{ $verification->user->email ?? '-' }}
                                </p>

                            </div>


                            {{-- TELEPON --}}
                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                    Nomor Telepon
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $verification->user->phone ?? '-' }}
                                </p>

                            </div>


                            {{-- TANGGAL PENGAJUAN --}}
                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                    Tanggal Pengajuan
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">

                                    @if($verification->submitted_at)

                                        {{ $verification->submitted_at->format('d F Y, H:i') }}

                                    @elseif($verification->created_at)

                                        {{ $verification->created_at->format('d F Y, H:i') }}

                                    @else

                                        -

                                    @endif

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- DATA IDENTITAS --}}
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-slate-100">

                        <h2 class="text-lg font-bold text-slate-900">
                            Data Identitas
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Informasi identitas yang dikirim oleh calon Mitra.
                        </p>

                    </div>


                    <div class="p-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                            {{-- JENIS IDENTITAS --}}
                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                    Jenis Identitas
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">

                                    @switch($verification->identity_type)

                                        @case('ktp')
                                            KTP
                                            @break

                                        @case('sim')
                                            SIM
                                            @break

                                        @case('paspor')
                                            Paspor
                                            @break

                                        @default
                                            {{ strtoupper($verification->identity_type ?? '-') }}

                                    @endswitch

                                </p>

                            </div>


                            {{-- NOMOR IDENTITAS --}}
                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                    Nomor Identitas
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900 break-all">
                                    {{ $verification->identity_number ?? '-' }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- DOKUMEN IDENTITAS --}}
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-slate-100">

                        <h2 class="text-lg font-bold text-slate-900">
                            Dokumen Identitas
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Periksa kesesuaian dokumen dengan data pemohon.
                        </p>

                    </div>


                    <div class="p-6">

                        @php

                            $identityDocumentUrl = null;

                            if (!empty($verification->identity_photo)) {

                                $identityDocumentUrl = route(
                                    'admin.mitra-verifications.document',
                                    [
                                        'verification' => $verification->id,
                                        'type' => 'identity',
                                    ]
                                );

                            }

                        @endphp


                        @if($identityDocumentUrl)

                            <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50">

                                <div class="min-h-[300px] flex items-center justify-center p-4">

                                    <img
                                        src="{{ $identityDocumentUrl }}"
                                        alt="Dokumen identitas"
                                        class="max-w-full max-h-[600px] object-contain rounded-xl"
                                    >

                                </div>


                                <div class="border-t border-slate-200 bg-white px-4 py-3 flex justify-end">

                                    <a
                                        href="{{ $identityDocumentUrl }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition"
                                    >
                                        Buka Dokumen →
                                    </a>

                                </div>

                            </div>

                        @else

                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">

                                <div class="text-4xl mb-3">
                                    📄
                                </div>

                                <p class="font-semibold text-slate-700">
                                    Dokumen identitas tidak tersedia.
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    Tidak ada file identitas yang dapat ditampilkan.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- SELFIE --}}
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-slate-100">

                        <h2 class="text-lg font-bold text-slate-900">
                            Foto Selfie
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Cocokkan wajah pada selfie dengan identitas yang dikirim.
                        </p>

                    </div>


                    <div class="p-6">

                        @php

                            $selfieUrl = null;

                            if (!empty($verification->selfie_photo)) {

                                $selfieUrl = route(
                                    'admin.mitra-verifications.document',
                                    [
                                        'verification' => $verification->id,
                                        'type' => 'selfie',
                                    ]
                                );

                            }

                        @endphp


                        @if($selfieUrl)

                            <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50">

                                <div class="flex justify-center p-4">

                                    <img
                                        src="{{ $selfieUrl }}"
                                        alt="Foto selfie calon Mitra"
                                        class="max-h-[600px] max-w-full rounded-xl object-contain"
                                    >

                                </div>


                                <div class="border-t border-slate-200 bg-white px-4 py-3 flex justify-end">

                                    <a
                                        href="{{ $selfieUrl }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition"
                                    >
                                        Buka Foto →
                                    </a>

                                </div>

                            </div>

                        @else

                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">

                                <div class="text-4xl mb-3">
                                    🤳
                                </div>

                                <p class="font-semibold text-slate-700">
                                    Foto selfie tidak tersedia.
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    Tidak ada selfie yang dapat ditampilkan.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


            </div>


            {{-- =====================================================
                 KOLOM KANAN
            ====================================================== --}}
            <div class="space-y-6">


                {{-- RINGKASAN --}}
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-slate-100">

                        <h2 class="text-lg font-bold text-slate-900">
                            Ringkasan Verifikasi
                        </h2>

                    </div>


                    <div class="p-6 space-y-5">


                        {{-- STATUS --}}
                        <div>

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Status
                            </p>

                            <p class="mt-1 text-sm font-bold text-slate-900">
                                {{ $statusLabel }}
                            </p>

                        </div>


                        {{-- IDENTITAS --}}
                        <div>

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Identitas
                            </p>

                            <p class="mt-1 text-sm font-bold text-slate-900">

                                @switch($verification->identity_type)

                                    @case('ktp')
                                        KTP
                                        @break

                                    @case('sim')
                                        SIM
                                        @break

                                    @case('paspor')
                                        Paspor
                                        @break

                                    @default
                                        {{ strtoupper($verification->identity_type ?? '-') }}

                                @endswitch

                            </p>

                        </div>


                        {{-- NOMOR IDENTITAS --}}
                        <div>

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Nomor Identitas
                            </p>

                            <p class="mt-1 text-sm font-semibold text-slate-900 break-all">
                                {{ $verification->identity_number ?? '-' }}
                            </p>

                        </div>


                        {{-- SUBMITTED --}}
                        @if($verification->submitted_at)

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                    Diajukan Pada
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $verification->submitted_at->format('d F Y, H:i') }}
                                </p>

                            </div>

                        @endif


                        {{-- VERIFIED --}}
                        @if($verification->verified_at)

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                    Diverifikasi Pada
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $verification->verified_at->format('d F Y, H:i') }}
                                </p>

                            </div>

                        @endif


                        {{-- VERIFIER --}}
                        @if($verification->verifier)

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                    Diverifikasi Oleh
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $verification->verifier->name ?? '-' }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- KEPUTUSAN --}}
                @if($status === 'pending')

                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                        <div class="px-6 py-5 border-b border-slate-100">

                            <h2 class="text-lg font-bold text-slate-900">
                                Keputusan Verifikasi
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Pastikan semua data sudah diperiksa sebelum mengambil keputusan.
                            </p>

                        </div>


                        <div class="p-6 space-y-5">


                            {{-- APPROVE --}}
                            <form
                                method="POST"
                                action="{{ route('admin.mitra-verifications.approve', $verification) }}"
                                onsubmit="return confirm('Yakin ingin menyetujui pengajuan Mitra ini?');"
                            >

                                @csrf

                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3.5 text-sm font-bold text-white hover:bg-emerald-700 transition shadow-sm"
                                >
                                    ✓ Setujui Pengajuan
                                </button>

                            </form>


                            {{-- REJECT --}}
                            <form
                                method="POST"
                                action="{{ route('admin.mitra-verifications.reject', $verification) }}"
                            >

                                @csrf

                                @method('PATCH')


                                <div class="mb-3">

                                    <label
                                        for="rejection_reason"
                                        class="block text-sm font-bold text-slate-700 mb-2"
                                    >
                                        Alasan Penolakan
                                    </label>

                                    <textarea
                                        id="rejection_reason"
                                        name="rejection_reason"
                                        rows="4"
                                        required
                                        class="w-full rounded-2xl border-slate-300 focus:border-red-500 focus:ring-red-500 text-sm"
                                        placeholder="Tuliskan alasan jika pengajuan ditolak..."
                                    >{{ old('rejection_reason') }}</textarea>

                                </div>


                                <button
                                    type="submit"
                                    onclick="return confirm('Yakin ingin menolak pengajuan Mitra ini?');"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-red-600 px-5 py-3.5 text-sm font-bold text-white hover:bg-red-700 transition shadow-sm"
                                >
                                    ✕ Tolak Pengajuan
                                </button>

                            </form>

                        </div>

                    </div>

                @endif


                {{-- HASIL PENOLAKAN --}}
                @if($status === 'rejected' && $verification->rejection_reason)

                    <div class="rounded-3xl border border-red-200 bg-red-50 p-6">

                        <div class="flex gap-3">

                            <div class="text-xl">
                                ⚠️
                            </div>

                            <div>

                                <h3 class="text-sm font-bold text-red-900">
                                    Alasan Penolakan
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-red-800 whitespace-pre-line">
                                    {{ $verification->rejection_reason }}
                                </p>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- CATATAN --}}
                <div class="rounded-3xl border border-indigo-100 bg-indigo-50 p-6">

                    <div class="flex gap-3">

                        <div class="text-xl">
                            ℹ️
                        </div>

                        <div>

                            <h3 class="text-sm font-bold text-indigo-900">
                                Catatan Verifikasi
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-indigo-800">
                                Pastikan nama, nomor identitas, foto dokumen, dan foto selfie
                                sesuai sebelum menyetujui pengajuan.
                            </p>

                            <p class="mt-3 text-sm leading-6 text-indigo-800">
                                Setelah disetujui, akun pengguna akan mendapatkan role
                                <strong>Mitra</strong> dan dapat mengakses dashboard Mitra.
                                Penambahan properti dapat dilakukan kemudian.
                            </p>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>

</div>

@endsection