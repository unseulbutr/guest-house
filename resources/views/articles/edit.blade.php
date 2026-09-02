@extends('layouts.app')

@section('title', 'Tulis Artikel')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <a href="{{ route('admin.articles.index') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali</a>

    <h1 class="text-2xl font-extrabold text-navy-900 mt-3 mb-6">Tulis Artikel Baru</h1>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan (Excerpt)</label>
            <textarea name="excerpt" rows="2"
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">{{ old('excerpt') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
            <textarea name="content" rows="10" required
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">{{ old('content') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Sampul</label>
            <input type="file" name="cover_image" class="text-sm">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title (SEO)</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description (SEO)</label>
                <input type="text" name="meta_description" value="{{ old('meta_description') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
            </select>
        </div>

        <button type="submit" class="bg-brand-blue hover:bg-blue-700 transition text-white font-semibold px-6 py-2.5 rounded-lg">
            Simpan Artikel
        </button>
    </form>
</div>
@endsection