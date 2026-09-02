@extends('layouts.app')

@section('title', 'Kelola Artikel')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <a href="{{ route('dashboard') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali ke Dashboard</a>

    <div class="flex items-center justify-between mt-3 mb-6 flex-wrap gap-3">
        <h1 class="text-2xl font-extrabold text-navy-900">Kelola Artikel</h1>
        <a href="{{ route('admin.articles.create') }}"
           class="bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-4 py-2 rounded-full">
            + Tulis Artikel
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
        @if ($articles->isEmpty())
            <p class="text-sm text-gray-500 p-6 text-center">Belum ada artikel. Yuk mulai menulis untuk kebutuhan SEO & konten.</p>
        @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr class="text-left">
                        <th class="py-3 px-4">Judul</th>
                        <th class="py-3 px-4">Penulis</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($articles as $article)
                        <tr>
                            <td class="py-3 px-4 font-semibold text-navy-900">{{ $article->title }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $article->author->name ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="text-xs px-2 py-1 rounded-full {{ $article->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $article->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-500">{{ $article->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="text-brand-blue hover:underline text-xs font-semibold">Edit</a>
                                    <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline text-xs font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-6">
        {{ $articles->links() }}
    </div>

</div>
@endsection