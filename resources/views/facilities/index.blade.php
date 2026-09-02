@extends('layouts.app')

@section('title', 'Kelola Fasilitas')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <a href="{{ route('dashboard') }}" class="text-sm text-brand-blue hover:underline">&larr; Kembali ke Dashboard</a>

    <div class="flex items-center justify-between mt-3 mb-6 flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-navy-900">Kelola Fasilitas</h1>
            <p class="text-sm text-gray-500">Daftar fasilitas master yang bisa dicentang mitra saat menambahkan properti.</p>
        </div>
        <a href="{{ route('admin.facilities.create') }}"
           class="bg-brand-blue hover:bg-blue-700 transition text-white text-sm font-semibold px-4 py-2 rounded-full">
            + Tambah Fasilitas
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
        @if ($facilities->isEmpty())
            <p class="text-sm text-gray-500 p-6 text-center">Belum ada fasilitas. Tambahkan dulu supaya mitra bisa mencentangnya saat menambah properti.</p>
        @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr class="text-left">
                        <th class="py-3 px-4">Nama Fasilitas</th>
                        <th class="py-3 px-4">Icon</th>
                        <th class="py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($facilities as $facility)
                        <tr>
                            <td class="py-3 px-4 font-medium text-navy-900">{{ $facility->name }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $facility->icon ?: '-' }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.facilities.edit', $facility) }}" class="text-brand-blue hover:underline text-xs font-semibold">Edit</a>
                                    <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}" onsubmit="return confirm('Hapus fasilitas ini? Properti yang sudah pakai fasilitas ini juga akan kehilangan centangnya.')">
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
        {{ $facilities->links() }}
    </div>

</div>
@endsection