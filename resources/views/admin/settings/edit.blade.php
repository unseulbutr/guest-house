@extends('layouts.app')

@section('title', 'Pengaturan Kontak')

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <a
        href="{{ route('dashboard') }}"
        class="text-sm text-brand-blue hover:underline"
    >
        &larr; Kembali ke Dashboard
    </a>

    <div class="flex items-center gap-2 mt-3 mb-1">

        <h1 class="text-2xl font-extrabold text-navy-900">
            Pengaturan Kontak
        </h1>

        <span
            class="text-[10px] font-bold uppercase tracking-wide bg-navy-900 text-brand-yellow px-2 py-1 rounded-full"
        >
            Super Admin
        </span>

    </div>

    <p class="text-sm text-gray-500 mb-6">
        Atur informasi kontak yang ditampilkan pada footer website.
    </p>


    {{-- SUCCESS --}}
    @if (session('success'))

        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">
            {{ session('success') }}
        </div>

    @endif


    {{-- ERROR --}}
    @if ($errors->any())

        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">

            @foreach ($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
        class="bg-white border border-gray-100 rounded-2xl p-6 space-y-5"
    >

        @csrf
        @method('PATCH')


        {{-- EMAIL --}}
        <div>

            <label
                class="block text-sm font-semibold text-gray-700 mb-1"
            >
                Email
            </label>

            <input
                type="email"
                name="contact_email"
                value="{{ old('contact_email', $settings->contact_email) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue"
                placeholder="support@contoh.com"
            >

            <p class="text-[11px] text-gray-400 mt-1">
                Email yang ditampilkan pada bagian Hubungi Kami di footer.
            </p>

        </div>


        {{-- NOMOR TELEPON --}}
        <div>

            <label
                class="block text-sm font-semibold text-gray-700 mb-1"
            >
                Nomor Telepon / WhatsApp
            </label>

            <input
                type="text"
                name="contact_phone"
                value="{{ old('contact_phone', $settings->contact_phone) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue"
                placeholder="08xxxxxxxxxx"
            >

            <p class="text-[11px] text-gray-400 mt-1">
                Nomor yang ditampilkan pada bagian WhatsApp di footer.
            </p>

        </div>


        {{-- SIMPAN --}}
        <button
            type="submit"
            class="w-full bg-brand-blue hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg"
        >
            Simpan Pengaturan
        </button>

    </form>

</div>

@endsection