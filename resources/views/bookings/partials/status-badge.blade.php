@php
    $map = [
        'pending' => ['bg-yellow-50 text-yellow-700', 'Menunggu Konfirmasi'],
        'confirmed' => ['bg-blue-50 text-blue-700', 'Terkonfirmasi'],
        'completed' => ['bg-green-50 text-green-700', 'Selesai'],
        'cancelled' => ['bg-red-50 text-red-500', 'Dibatalkan'],
    ];
    [$classes, $label] = $map[$status] ?? ['bg-gray-50 text-gray-500', ucfirst($status)];
@endphp
<span class="{{ $classes }} text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">{{ $label }}</span>