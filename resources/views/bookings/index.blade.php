@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-extrabold text-navy-900 mb-1">
        @role('customer') Booking Saya @endrole
        @role('mitra') Booking Masuk @endrole
        @role('admin') Semua Booking @endrole
        @role('super_admin') Semua Booking @endrole
    </h1>
    <p class="text-sm text-gray-500 mb-6">{{ $bookings->total() }} booking ditemukan</p>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">{{ session('success') }}</div>
    @endif

    @if ($bookings->isEmpty())
        <div class="text-center py-16 border border-gray-100 rounded-2xl">
            <div class="text-4xl mb-3">🗓️</div>
            <p class="text-navy-900 font-semibold mb-1">Belum ada booking</p>
            @role('customer')
                <p class="text-gray-500 text-sm">Yuk cari properti dan mulai booking pertamamu.</p>
                <a href="{{ route('home') }}" class="inline-block mt-4 bg-brand-blue text-white text-sm font-semibold px-5 py-2.5 rounded-full">
                    Cari Properti
                </a>
            @endrole
        </div>
    @else
        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold">Kode</th>
                            <th class="text-left px-5 py-3 font-semibold">Properti</th>
                            @role('mitra') <th class="text-left px-5 py-3 font-semibold">Customer</th> @endrole
                            @role('admin') <th class="text-left px-5 py-3 font-semibold">Customer</th> @endrole
                            @role('super_admin') <th class="text-left px-5 py-3 font-semibold">Customer</th> @endrole
                            <th class="text-left px-5 py-3 font-semibold">Tanggal</th>
                            <th class="text-left px-5 py-3 font-semibold">Total</th>
                            <th class="text-left px-5 py-3 font-semibold">Status</th>
                            <th class="text-left px-5 py-3 font-semibold">Pembayaran</th>
                            <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($bookings as $booking)
                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-4 font-mono text-xs text-gray-500">{{ $booking->booking_code }}</td>
                                <td class="px-5 py-4 font-medium text-navy-900">{{ $booking->property->name }}</td>

                                @role('mitra') <td class="px-5 py-4 text-gray-600">{{ $booking->customer->name }}</td> @endrole
                                @role('admin') <td class="px-5 py-4 text-gray-600">{{ $booking->customer->name }}</td> @endrole
                                @role('super_admin') <td class="px-5 py-4 text-gray-600">{{ $booking->customer->name }}</td> @endrole

                                <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                    {{ $booking->check_in->format('d M Y') }} – {{ $booking->check_out->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-navy-900 whitespace-nowrap">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    @include('bookings.partials.status-badge', ['status' => $booking->status])
                                </td>
                                <td class="px-5 py-4">
                                    @include('bookings.partials.payment-badge', ['status' => $booking->payment_status])
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route(
                                                (auth()->user()->hasRole('mitra') ? 'mitra' : (auth()->user()->hasRole('customer') ? 'customer' : 'admin')) . '.bookings.show',
                                                $booking
                                            ) }}"
                                           class="text-brand-blue hover:underline font-semibold text-xs">
                                            Lihat
                                        </a>

                                        @role('mitra')
                                            @if ($booking->status === 'pending')
                                                <form method="POST" action="{{ route('mitra.bookings.confirm', $booking) }}">
                                                    @csrf @method('PATCH')
                                                    <button class="text-green-600 hover:underline font-semibold text-xs">Konfirmasi</button>
                                                </form>
                                            @endif
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection