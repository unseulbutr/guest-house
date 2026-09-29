@extends('layouts.app')

@section('title', 'Artikel & Tips')
@section('meta_description', 'Kumpulan artikel dan tips seputar liburan, homestay, dan kost harian.')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

        {{-- ============================================================
             HERO HEADER
        ============================================================ --}}
        <div class="relative overflow-hidden rounded-[2rem]
                    bg-slate-950 mb-10
                    shadow-xl">

            {{-- Decorative background --}}
            <div class="absolute inset-0 overflow-hidden">

                <div class="absolute -top-32 -right-32
                            w-80 h-80
                            rounded-full
                            bg-blue-600/20
                            blur-3xl">
                </div>

                <div class="absolute -bottom-40 -left-20
                            w-80 h-80
                            rounded-full
                            bg-indigo-500/10
                            blur-3xl">
                </div>

                <div class="absolute inset-0
                            bg-gradient-to-br
                            from-slate-950
                            via-slate-900
                            to-blue-950/80">
                </div>

            </div>


            <div class="relative px-6 sm:px-10
                        py-10 sm:py-14">

                <div class="max-w-3xl">

                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2
                                px-3.5 py-1.5
                                rounded-full
                                bg-white/10
                                border border-white/10
                                text-blue-200
                                text-xs font-bold
                                mb-5">

                        <span class="w-1.5 h-1.5
                                     rounded-full
                                     bg-blue-400">
                        </span>

                        INFORMASI & INSPIRASI

                    </div>


                    <h1 class="text-3xl sm:text-4xl lg:text-5xl
                               font-black tracking-tight
                               text-white leading-tight">

                        Artikel & Tips

                    </h1>


                    <p class="mt-4
                              text-sm sm:text-base
                              text-slate-300
                              leading-relaxed
                              max-w-2xl">

                        Temukan rekomendasi wisata, tips menginap,
                        panduan perjalanan, dan informasi menarik
                        seputar guest house serta kost harian.

                    </p>

                </div>

            </div>

        </div>


        {{-- ============================================================
             SECTION HEADER
        ============================================================ --}}
        @if (!$articles->isEmpty())

            <div class="flex flex-col sm:flex-row
                        sm:items-end
                        sm:justify-between
                        gap-3 mb-6">

                <div>

                    <p class="text-xs font-bold
                              uppercase tracking-[0.18em]
                              text-blue-600 mb-2">

                        Explore

                    </p>

                    <h2 class="text-2xl sm:text-3xl
                               font-black tracking-tight
                               text-slate-900">

                        Artikel Terbaru

                    </h2>

                </div>


                <div class="text-sm text-slate-500">

                    {{ $articles->total() }} artikel tersedia

                </div>

            </div>


            {{-- ========================================================
                 ARTICLE GRID
            ========================================================= --}}
            <div class="grid grid-cols-1
                        md:grid-cols-2
                        lg:grid-cols-3
                        gap-6">

                @foreach ($articles as $article)

                    <a href="{{ route('articles.show', $article->slug) }}"
                       class="group block
                              bg-white
                              border border-slate-200
                              rounded-3xl
                              overflow-hidden
                              shadow-sm
                              hover:shadow-xl
                              hover:-translate-y-1
                              transition-all duration-300">


                        {{-- =================================================
                             COVER IMAGE
                        ================================================= --}}
                        <div class="relative h-56
                                    bg-slate-100
                                    overflow-hidden">

                            @if ($article->cover_image)

                                <img
                                    src="{{ asset('storage/' . $article->cover_image) }}"
                                    alt="{{ $article->title }}"
                                    class="w-full h-full
                                           object-cover
                                           group-hover:scale-105
                                           transition-transform
                                           duration-700">

                                {{-- Image overlay --}}
                                <div class="absolute inset-0
                                            bg-gradient-to-t
                                            from-black/50
                                            via-black/5
                                            to-transparent">
                                </div>

                            @else

                                <div class="w-full h-full
                                            flex flex-col
                                            items-center
                                            justify-center
                                            bg-gradient-to-br
                                            from-slate-100
                                            to-slate-200">

                                    <div class="w-14 h-14
                                                rounded-2xl
                                                bg-white
                                                flex items-center
                                                justify-center
                                                shadow-sm
                                                mb-3">

                                        <svg class="w-7 h-7 text-slate-400"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="1.5"
                                                  d="M4 5a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm4 3h8m-8 4h8m-8 4h5"/>

                                        </svg>

                                    </div>

                                    <span class="text-xs
                                                 font-semibold
                                                 text-slate-400">

                                        Tidak ada foto

                                    </span>

                                </div>

                            @endif


                            {{-- Category badge --}}
                            <div class="absolute top-4 left-4">

                                <span class="inline-flex
                                             items-center
                                             px-3 py-1.5
                                             rounded-full
                                             bg-white/95
                                             backdrop-blur-sm
                                             text-blue-700
                                             text-[11px]
                                             font-extrabold
                                             shadow-sm">

                                    Artikel

                                </span>

                            </div>


                            {{-- Arrow --}}
                            <div class="absolute
                                        bottom-4 right-4
                                        w-10 h-10
                                        rounded-full
                                        bg-white/95
                                        backdrop-blur-sm
                                        flex items-center
                                        justify-center
                                        text-slate-700
                                        shadow-sm
                                        opacity-0
                                        translate-y-2
                                        group-hover:opacity-100
                                        group-hover:translate-y-0
                                        transition-all
                                        duration-300">

                                <svg class="w-4 h-4"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M5 12h14m-6-6l6 6-6 6"/>

                                </svg>

                            </div>

                        </div>


                        {{-- =================================================
                             ARTICLE CONTENT
                        ================================================= --}}
                        <div class="p-5 sm:p-6">

                            {{-- DATE --}}
                            <div class="flex items-center gap-2
                                        text-xs
                                        text-slate-400
                                        font-medium
                                        mb-3">

                                <svg class="w-4 h-4"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.7"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                                </svg>


                                <span>

                                    {{ $article->published_at?->translatedFormat('d M Y') }}

                                </span>

                            </div>


                            {{-- TITLE --}}
                            <h3 class="text-lg
                                       font-extrabold
                                       text-slate-900
                                       leading-snug
                                       line-clamp-2
                                       group-hover:text-blue-600
                                       transition-colors">

                                {{ $article->title }}

                            </h3>


                            {{-- EXCERPT --}}
                            <p class="mt-3
                                      text-sm
                                      text-slate-500
                                      leading-relaxed
                                      line-clamp-3">

                                {{ $article->excerpt }}

                            </p>


                            {{-- READ MORE --}}
                            <div class="mt-5
                                        pt-4
                                        border-t
                                        border-slate-100">

                                <div class="flex items-center
                                            justify-between">

                                    <span class="text-xs
                                                 font-bold
                                                 text-slate-700
                                                 group-hover:text-blue-600
                                                 transition">

                                        Baca selengkapnya

                                    </span>


                                    <svg class="w-4 h-4
                                                text-slate-400
                                                group-hover:text-blue-600
                                                group-hover:translate-x-1
                                                transition-all"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M5 12h14m-6-6l6 6-6 6"/>

                                    </svg>

                                </div>

                            </div>

                        </div>

                    </a>

                @endforeach

            </div>


            {{-- ========================================================
                 PAGINATION
            ========================================================= --}}
            <div class="mt-10
                        bg-white
                        border border-slate-200
                        rounded-2xl
                        px-5 py-4
                        shadow-sm">

                {{ $articles->links() }}

            </div>


        @else

            {{-- ========================================================
                 EMPTY STATE
            ========================================================= --}}
            <div class="bg-white
                        border border-slate-200
                        rounded-3xl
                        shadow-sm
                        px-6 py-20">

                <div class="flex flex-col
                            items-center
                            justify-center
                            text-center">

                    <div class="w-20 h-20
                                rounded-3xl
                                bg-blue-50
                                flex items-center
                                justify-center
                                mb-5">

                        <svg class="w-10 h-10 text-blue-500"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.5"
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h7a2 2 0 012 2v10a2 2 0 01-2 2z"/>

                        </svg>

                    </div>


                    <h2 class="text-lg
                               font-extrabold
                               text-slate-900">

                        Belum Ada Artikel

                    </h2>


                    <p class="mt-2
                              max-w-md
                              text-sm
                              text-slate-500
                              leading-relaxed">

                        Belum ada artikel yang dipublikasikan.
                        Silakan kembali lagi nanti untuk menemukan
                        tips dan informasi terbaru.

                    </p>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection