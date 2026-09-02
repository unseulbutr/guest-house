@extends('layouts.app')

@section('title', 'Artikel & Tips')
@section('meta_description', 'Kumpulan artikel dan tips seputar liburan, homestay, dan kost harian.')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-extrabold text-navy-900 mb-1">Artikel & Tips</h1>
    <p class="text-sm text-gray-500 mb-8">Rekomendasi wisata, tips menginap, dan info seputar homestay.</p>

    @if ($articles->isEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center text-gray-500">
            Belum ada artikel yang dipublikasikan.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($articles as $article)
                <a href="{{ route('articles.show', $article->slug) }}"
                   class="block bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-lg transition group">
                    <div class="h-40 bg-gray-200 overflow-hidden">
                        @if ($article->cover_image)
                            <img src="{{ asset('storage/' . $article->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Tidak ada foto</div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="text-xs text-gray-400 mb-1">{{ $article->published_at?->translatedFormat('d M Y') }}</div>
                        <h3 class="font-bold text-navy-900 mb-1">{{ $article->title }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-2">{{ $article->excerpt }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    @endif

</div>
@endsection