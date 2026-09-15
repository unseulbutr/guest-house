@php

    $map = [

        'pending' => [
            'bg-yellow-50 text-yellow-700 border-yellow-100',
            'Menunggu Konfirmasi'
        ],

        'confirmed' => [
            'bg-blue-50 text-blue-700 border-blue-100',
            'Terkonfirmasi'
        ],

        'completed' => [
            'bg-green-50 text-green-700 border-green-100',
            'Selesai'
        ],

        'cancelled' => [
            'bg-red-50 text-red-600 border-red-100',
            'Dibatalkan'
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

    <span
        class="
            w-1.5
            h-1.5
            rounded-full

            @if ($status === 'pending')
                bg-yellow-500

            @elseif ($status === 'confirmed')
                bg-blue-500

            @elseif ($status === 'completed')
                bg-green-500

            @elseif ($status === 'cancelled')
                bg-red-500

            @else
                bg-gray-400
            @endif
        "
    ></span>

    {{ $label }}

</span>