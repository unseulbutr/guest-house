@extends('layouts.app')

@section('title', 'Kelola Fasilitas')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

        {{-- ============================================================
             HEADER
        ============================================================ --}}
        <div class="mb-8">

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2
                      text-sm font-semibold text-slate-500
                      hover:text-blue-600 transition">

                <span class="w-8 h-8 rounded-lg
                             bg-white border border-slate-200
                             flex items-center justify-center
                             shadow-sm">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 19l-7-7 7-7"/>

                    </svg>

                </span>

                Kembali ke Dashboard

            </a>


            <div class="flex flex-col lg:flex-row
                        lg:items-end lg:justify-between
                        gap-5 mt-6">

                <div>

                    <div class="flex items-center gap-2 mb-3">

                        <span class="inline-flex items-center gap-2
                                     px-3 py-1.5
                                     rounded-full
                                     bg-blue-50
                                     text-blue-700
                                     text-xs font-bold">

                            <span class="w-1.5 h-1.5
                                         rounded-full bg-blue-600">
                            </span>

                            MASTER DATA

                        </span>

                    </div>


                    <h1 class="text-3xl sm:text-4xl
                               font-black tracking-tight
                               text-slate-900">

                        Kelola Fasilitas

                    </h1>


                    <p class="mt-2 text-sm sm:text-base
                              text-slate-500 max-w-2xl">

                        Kelola daftar fasilitas yang dapat dipilih
                        oleh mitra saat menambahkan properti.

                    </p>

                </div>


                {{-- TAMBAH FASILITAS --}}
                <div>

                    <a href="{{ route('admin.facilities.create') }}"
                       class="group inline-flex items-center
                              justify-center gap-2
                              px-5 py-3
                              rounded-xl
                              bg-slate-900
                              hover:bg-blue-700
                              text-white
                              text-sm font-bold
                              shadow-lg shadow-slate-900/10
                              hover:shadow-blue-700/20
                              transition-all duration-200">

                        <svg class="w-5 h-5
                                    transition-transform
                                    group-hover:rotate-90"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 4v16m8-8H4"/>

                        </svg>

                        Tambah Fasilitas

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
                            rounded-2xl
                            border border-emerald-200
                            bg-emerald-50
                            px-5 py-4">

                    <div class="flex-shrink-0">

                        <div class="w-9 h-9
                                    rounded-xl
                                    bg-emerald-100
                                    flex items-center justify-center">

                            <svg class="w-5 h-5 text-emerald-600"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linecap="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7"/>

                            </svg>

                        </div>

                    </div>


                    <div>

                        <p class="text-sm font-bold
                                  text-emerald-800">

                            Berhasil

                        </p>

                        <p class="mt-0.5 text-sm
                                  text-emerald-700">

                            {{ session('success') }}

                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- ============================================================
             INFORMATION CARD
        ============================================================ --}}
        <div class="mb-6
                    rounded-2xl
                    border border-blue-100
                    bg-gradient-to-r
                    from-blue-50
                    to-indigo-50
                    p-5 sm:p-6">

            <div class="flex items-start gap-4">

                <div class="w-11 h-11
                            rounded-xl
                            bg-white
                            border border-blue-100
                            flex items-center justify-center
                            shadow-sm
                            flex-shrink-0">

                    <svg class="w-5 h-5 text-blue-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"/>

                    </svg>

                </div>


                <div>

                    <h2 class="text-sm font-bold text-slate-900">
                        Fasilitas Master
                    </h2>

                    <p class="text-sm text-slate-600 mt-1 leading-relaxed">

                        Fasilitas yang ditambahkan di sini akan tersedia
                        sebagai pilihan ketika mitra membuat atau mengedit
                        properti mereka.

                    </p>

                </div>

            </div>

        </div>


        {{-- ============================================================
             FACILITY TABLE
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
                        sm:items-center
                        sm:justify-between gap-3">

                <div>

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10
                                    rounded-xl
                                    bg-slate-100
                                    flex items-center justify-center">

                            <svg class="w-5 h-5 text-slate-600"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M4 6h16M4 10h16M4 14h16M4 18h16"/>

                            </svg>

                        </div>


                        <div>

                            <h2 class="text-base font-bold
                                       text-slate-900">

                                Daftar Fasilitas

                            </h2>

                            <p class="text-xs text-slate-500 mt-0.5">

                                Kelola fasilitas yang tersedia di platform.

                            </p>

                        </div>

                    </div>

                </div>


                @if (!$facilities->isEmpty())

                    <div class="inline-flex items-center gap-2
                                px-3 py-1.5
                                rounded-full
                                bg-slate-100
                                text-slate-600
                                text-xs font-bold">

                        <span class="w-2 h-2 rounded-full
                                     bg-blue-500">
                        </span>

                        {{ $facilities->total() }} Fasilitas

                    </div>

                @endif

            </div>


            {{-- ========================================================
                 EMPTY STATE
            ========================================================= --}}
            @if ($facilities->isEmpty())

                <div class="px-6 py-20">

                    <div class="flex flex-col
                                items-center
                                justify-center
                                text-center">

                        <div class="w-20 h-20
                                    rounded-3xl
                                    bg-slate-100
                                    flex items-center justify-center
                                    mb-5">

                            <svg class="w-10 h-10 text-slate-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.5"
                                      d="M9 3h6m-7 4h8M6 21h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z"/>

                            </svg>

                        </div>


                        <h3 class="text-base font-bold
                                   text-slate-900">

                            Belum Ada Fasilitas

                        </h3>


                        <p class="max-w-md mt-2
                                  text-sm text-slate-500
                                  leading-relaxed">

                            Tambahkan fasilitas terlebih dahulu agar
                            mitra dapat memilih fasilitas tersebut
                            saat menambahkan properti.

                        </p>


                        <a href="{{ route('admin.facilities.create') }}"
                           class="mt-6 inline-flex items-center
                                  gap-2 px-5 py-2.5
                                  rounded-xl
                                  bg-blue-600
                                  hover:bg-blue-700
                                  text-white
                                  text-sm font-bold
                                  transition">

                            <svg class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 4v16m8-8H4"/>

                            </svg>

                            Tambah Fasilitas

                        </a>

                    </div>

                </div>


            {{-- ========================================================
                 FACILITY LIST
            ========================================================= --}}
            @else

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[700px]">

                        {{-- TABLE HEAD --}}
                        <thead>

                            <tr class="bg-slate-50/80
                                       border-b border-slate-100">

                                <th class="text-left
                                           px-6 py-4
                                           text-[11px]
                                           font-extrabold
                                           uppercase
                                           tracking-wider
                                           text-slate-500">

                                    Nama Fasilitas

                                </th>


                                <th class="text-left
                                           px-6 py-4
                                           text-[11px]
                                           font-extrabold
                                           uppercase
                                           tracking-wider
                                           text-slate-500">

                                    Icon

                                </th>


                                <th class="text-right
                                           px-6 py-4
                                           text-[11px]
                                           font-extrabold
                                           uppercase
                                           tracking-wider
                                           text-slate-500">

                                    Aksi

                                </th>

                            </tr>

                        </thead>


                        {{-- TABLE BODY --}}
                        <tbody class="divide-y divide-slate-100">

                            @foreach ($facilities as $facility)

                                <tr class="group
                                           hover:bg-slate-50/70
                                           transition-colors">


                                    {{-- NAME --}}
                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-4">

                                            <div class="w-11 h-11
                                                        rounded-xl
                                                        bg-blue-50
                                                        border
                                                        border-blue-100
                                                        flex items-center
                                                        justify-center
                                                        flex-shrink-0">

                                                <svg class="w-5 h-5
                                                            text-blue-600"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     viewBox="0 0 24 24">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="1.8"
                                                          d="M4 6h16M4 10h16M4 14h16M4 18h16"/>

                                                </svg>

                                            </div>


                                            <div>

                                                <p class="text-sm
                                                          font-bold
                                                          text-slate-900">

                                                    {{ $facility->name }}

                                                </p>

                                                <p class="text-xs
                                                          text-slate-400
                                                          mt-0.5">

                                                    Fasilitas properti

                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- ICON --}}
                                    <td class="px-6 py-5">

                                        @if ($facility->icon)

                                            <span class="inline-flex
                                                         items-center
                                                         px-3 py-1.5
                                                         rounded-lg
                                                         bg-slate-100
                                                         border
                                                         border-slate-200
                                                         text-xs
                                                         font-semibold
                                                         text-slate-600">

                                                {{ $facility->icon }}

                                            </span>

                                        @else

                                            <span class="text-xs
                                                         text-slate-400
                                                         italic">

                                                Tidak ada icon

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}
                                    <td class="px-6 py-5 text-right">

                                        <div class="inline-flex
                                                    items-center
                                                    gap-2">


                                            {{-- EDIT --}}
                                            <a href="{{ route('admin.facilities.edit', $facility) }}"
                                               class="inline-flex
                                                      items-center
                                                      gap-1.5
                                                      px-3 py-2
                                                      rounded-lg
                                                      bg-blue-50
                                                      text-blue-700
                                                      hover:bg-blue-100
                                                      text-xs
                                                      font-bold
                                                      transition">

                                                <svg class="w-4 h-4"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     viewBox="0 0 24 24">

                                                    <path stroke-linecap="round"
                                                          stroke-linecap="round"
                                                          stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>

                                                </svg>

                                                Edit

                                            </a>


                                            {{-- DELETE --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.facilities.destroy', $facility) }}"
                                                onsubmit="return confirm('Hapus fasilitas ini? Properti yang sudah pakai fasilitas ini juga akan kehilangan centangnya.')">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex
                                                           items-center
                                                           gap-1.5
                                                           px-3 py-2
                                                           rounded-lg
                                                           bg-red-50
                                                           text-red-600
                                                           hover:bg-red-100
                                                           text-xs
                                                           font-bold
                                                           transition">

                                                    <svg class="w-4 h-4"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         viewBox="0 0 24 24">

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h14"/>

                                                    </svg>

                                                    Hapus

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif


            {{-- ========================================================
                 PAGINATION
            ========================================================= --}}
            @if (!$facilities->isEmpty())

                <div class="px-5 sm:px-6 py-5
                            border-t border-slate-100
                            flex flex-col sm:flex-row
                            sm:items-center
                            sm:justify-between gap-4">

                    <div class="text-xs text-slate-500">

                        Menampilkan

                        <span class="font-bold text-slate-700">
                            {{ $facilities->firstItem() ?? 0 }}
                        </span>

                        –

                        <span class="font-bold text-slate-700">
                            {{ $facilities->lastItem() ?? 0 }}
                        </span>

                        dari

                        <span class="font-bold text-slate-700">
                            {{ $facilities->total() }}
                        </span>

                        fasilitas

                    </div>


                    <div>
                        {{ $facilities->links() }}
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection