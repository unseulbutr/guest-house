@php
    $map = [
        'pending' => ['bg-yellow-50 text-yellow-700', 'Belum Dibayar'],
        'paid' => ['bg-green-50 text-green-700', 'Sudah Dibayar'],
        'failed' => ['bg-red-50 text-red-500', 'Gagal'],
        'refunded' => ['bg-gray-100 text-gray-500', 'Dikembalikan'],
    ];
    [$classes, $label] = $map[$status] ?? ['bg-gray-50 text-gray-500', ucfirst($status)];
@endphp
<span class="{{ $classes }} text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">{{ $label }}</span>