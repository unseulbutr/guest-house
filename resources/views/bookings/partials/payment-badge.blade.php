@php

    $map = [

        'pending' => [
            'bg-yellow-50 text-yellow-700 border-yellow-100',
            'Belum Dibayar'
        ],

        'paid' => [
            'bg-green-50 text-green-700 border-green-100',
            'Sudah Dibayar'
        ],

        'failed' => [
            'bg-red-50 text-red-600 border-red-100',
            'Pembayaran Gagal'
        ],

        'refunded' => [
            'bg-purple-50 text-purple-700 border-purple-100',
            'Dana Dikembalikan'
        ],

    ];

    [$classes, $label] =
        $map[$status]
        ??
        [
            'bg-gray-50 text-gray-500 border-gray-100',
            ucfirst($status)
        ];

@endphp

<span
    class="
        inline-flex
        items-center
        gap-1.5
        {{ $classes }}
        border
        text-xs
        font-semibold
        px-3
        py-1.5
        rounded-full
        whitespace-nowrap
    "
>
    @if ($status === 'pending')

        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>

    @elseif ($status === 'paid')

        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>

    @elseif ($status === 'refunded')

        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>

    @elseif ($status === 'failed')

        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>

    @endif

    {{ $label }}

</span>