@extends('layouts.app')

@section('title', $article->meta_title ?: $article->title)
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')
{{-- ============ STRUCTURED DATA (JSON-LD) untuk SEO ============
     Tempel blok ini persis di bawah @section('content') di articles/show.blade.php. --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": {!! json_encode($article->title) !!},
    "description": {!! json_encode($article->excerpt ?: $article->title) !!},
    @if ($article->cover_image)
    "image": {!! json_encode(asset('storage/' . $article->cover_image)) !!},
    @endif
    "datePublished": {!! json_encode(optional($article->published_at)->toAtomString()) !!},
    "dateModified": {!! json_encode($article->updated_at->toAtomString()) !!},
    "author": {
        "@type": "Organization",
        "name": "Guest House"
    },
    "url": {!! json_encode(route('articles.show', $article->slug)) !!}
}
</script>
<div class="max-w-3xl mx-auto px-4 py-10">

    <a href="{{ route('articles.index') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali ke Artikel</a>

    <div class="text-xs text-gray-400 mt-4">{{ $article->published_at?->translatedFormat('d M Y') }} · {{ $article->author->name ?? '' }}</div>
    <h1 class="text-3xl font-extrabold text-navy-900 mt-1 mb-6">{{ $article->title }}</h1>

    @if ($article->cover_image)
        <img src="{{ asset('storage/' . $article->cover_image) }}" class="w-full rounded-2xl mb-6">
    @endif

    <div class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
        {{ $article->content }}
    </div>

</div>
@endsection