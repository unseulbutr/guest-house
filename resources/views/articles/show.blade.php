@extends('layouts.app')

@section('title', $article->meta_title ?: $article->title)
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')

@php
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $article->title,
        'description' => $article->excerpt ?: $article->title,
        'datePublished' => optional($article->published_at)->toAtomString(),
        'dateModified' => $article->updated_at->toAtomString(),
        'author' => [
            '@type' => 'Organization',
            'name' => 'Guest House',
        ],
        'url' => route('articles.show', $article->slug),
    ];

    if ($article->cover_image) {
        $structuredData['image'] = asset('storage/' . $article->cover_image);
    }
@endphp

<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>


<div class="min-h-screen bg-slate-50">

    {{-- =====================================================
         HEADER
         ===================================================== --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        <a href="{{ route('articles.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>

            Kembali ke Artikel

        </a>

    </div>


    {{-- =====================================================
         ARTICLE
         ===================================================== --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <article class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- =================================================
                 COVER IMAGE
                 ================================================= --}}
            @if ($article->cover_image)

                <div class="relative w-full h-64 sm:h-80 lg:h-[420px] overflow-hidden">

                    <img
                        src="{{ asset('storage/' . $article->cover_image) }}"
                        alt="{{ $article->title }}"
                        class="w-full h-full object-cover"
                    >

                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>

                    <div class="absolute top-5 left-5">

                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/95 backdrop-blur-sm text-xs font-bold text-slate-800 shadow-sm">

                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                            Artikel & Tips

                        </span>

                    </div>

                </div>

            @else

                <div class="w-full h-48 sm:h-56 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">

                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-400">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-8 h-8"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.5">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-8h.01M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                        </svg>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 ARTICLE HEADER
                 ================================================= --}}
            <div class="px-5 sm:px-8 lg:px-12 pt-8 pb-6">

                {{-- Meta --}}
                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500 mb-5">

                    @if ($article->published_at)

                        <div class="inline-flex items-center gap-2">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-4 h-4 text-slate-400"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                            </svg>

                            {{ $article->published_at->translatedFormat('d M Y') }}

                        </div>

                    @endif


                    @if ($article->author)

                        <div class="inline-flex items-center gap-2">

                            <div class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold">

                                {{ strtoupper(substr($article->author->name, 0, 1)) }}

                            </div>

                            <span>
                                {{ $article->author->name }}
                            </span>

                        </div>

                    @endif

                </div>


                {{-- Title --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight">

                    {{ $article->title }}

                </h1>


                {{-- Excerpt --}}
                @if ($article->excerpt)

                    <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed max-w-3xl">

                        {{ $article->excerpt }}

                    </p>

                @endif

            </div>


            {{-- =================================================
                 DIVIDER
                 ================================================= --}}
            <div class="mx-5 sm:mx-8 lg:mx-12 border-t border-slate-100"></div>


            {{-- =================================================
                 ARTICLE CONTENT
                 ================================================= --}}
            <div class="px-5 sm:px-8 lg:px-12 py-8 sm:py-10">

                <div class="prose prose-slate prose-lg max-w-none leading-relaxed">

                    {!! nl2br(e($article->content)) !!}

                </div>

            </div>


            {{-- =================================================
                 FOOTER
                 ================================================= --}}
            <div class="mx-5 sm:mx-8 lg:mx-12 border-t border-slate-100"></div>

            <div class="px-5 sm:px-8 lg:px-12 py-7">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">

                    <div>

                        <p class="text-sm font-bold text-slate-900">
                            Guest House
                        </p>

                        <p class="text-xs text-slate-500 mt-1">
                            Informasi, tips, dan panduan seputar penginapan.
                        </p>

                    </div>


                    <a href="{{ route('articles.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition shadow-sm">

                        Lihat Artikel Lain

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M14 5l7 7m0 0l-7 7m7-7H3"/>

                        </svg>

                    </a>

                </div>

            </div>

        </article>

    </main>

</div>

@endsection