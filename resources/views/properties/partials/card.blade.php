@php
    $isOwnerView = auth()->check()
        && auth()->user()->hasRole('mitra')
        && $property->mitra_id === auth()->id();

    $starRating = (int) ($property->star_rating ?? 1);

    // Pastikan nilai bintang selalu 1-5
    $starRating = max(1, min(5, $starRating));

    $starLabel = match ($starRating) {
        1 => 'Sederhana',
        2 => 'Standar',
        3 => 'Nyaman',
        4 => 'Premium',
        5 => 'Mewah',
        default => 'Sederhana',
    };
@endphp

<div class="group relative border border-gray-100 rounded-2xl overflow-hidden hover:shadow-card transition bg-white flex flex-col sm:flex-row">

    {{-- =========================================================
         FOTO
         ========================================================= --}}
    <a
        href="{{ route('properties.show', $property) }}"
        class="block sm:w-64 md:w-72 shrink-0"
    >

        <div class="h-48 sm:h-full bg-gradient-to-br from-navy-700 to-navy-900 overflow-hidden relative">

            @if ($property->cover_image)

                <img
                    src="{{ asset('storage/' . $property->cover_image) }}"
                    alt="{{ $property->name }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                >

            @else

                <div class="w-full h-full flex flex-col items-center justify-center text-white/70 gap-1">

                    <span class="text-2xl">
                        🏡
                    </span>

                    <span class="text-xs">
                        Belum ada foto
                    </span>

                </div>

            @endif


            {{-- TIPE PROPERTI --}}
            <span
                class="absolute top-3 left-3 bg-white/95 backdrop-blur text-navy-900 text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full"
            >
                {{ $property->type === 'kost_harian' ? 'Kost Harian' : 'Guest House' }}
            </span>


            {{-- STATUS MITRA --}}
            @if ($isOwnerView)

                @php
                    $statusBadge = match ($property->status) {
                        'active' => ['bg-green-500', 'Aktif'],
                        'pending' => ['bg-yellow-500', 'Menunggu Verifikasi'],
                        'rejected' => ['bg-red-500', 'Ditolak'],
                        default => ['bg-gray-500', ucfirst($property->status)],
                    };
                @endphp

                <span
                    class="absolute top-3 right-3 {{ $statusBadge[0] }} text-white text-[11px] font-bold px-2.5 py-1 rounded-full"
                >
                    {{ $statusBadge[1] }}
                </span>

            @endif

        </div>

    </a>


    {{-- =========================================================
         SIMPAN PROPERTI
         ========================================================= --}}
    @if ($isOwnerView)

        {{-- Tampilan Mitra (pemilik properti ini) --}}

    @elseif (auth()->check())

        <form
            action="{{ route('customer.saved.toggle', $property) }}"
            method="POST"
            class="absolute top-3 right-3 sm:right-auto sm:left-[15rem] md:left-[17rem]"
        >

            @csrf

            @php
                $isSaved = auth()->user()->savedProperties->contains($property->id);
            @endphp

            <button
                type="submit"
                class="w-8 h-8 rounded-full bg-white/95 backdrop-blur flex items-center justify-center shadow-sm hover:scale-110 transition {{ $isSaved ? 'text-red-500' : 'text-gray-400' }}"
                aria-label="Simpan properti"
            >
                <span>
                    {{ $isSaved ? '❤️' : '🤍' }}
                </span>
            </button>

        </form>

    @else

        <a
            href="{{ route('login') }}"
            class="absolute top-3 right-3 sm:right-auto sm:left-[15rem] md:left-[17rem] w-8 h-8 rounded-full bg-white/95 backdrop-blur flex items-center justify-center shadow-sm hover:scale-110 transition text-gray-400"
            aria-label="Login untuk menyimpan properti"
        >
            <span>
                🤍
            </span>
        </a>

    @endif


    {{-- =========================================================
         INFO + HARGA
         ========================================================= --}}
    @if ($isOwnerView)

        <div class="flex-1 flex flex-col sm:flex-row">

    @else

        <a
            href="{{ route('properties.show', $property) }}"
            class="flex-1 flex flex-col sm:flex-row"
        >

    @endif


        {{-- =====================================================
             INFO TENGAH
             ===================================================== --}}
        <div class="flex-1 p-4 sm:p-5">

            {{-- NAMA PROPERTI --}}
            @if ($isOwnerView)

                <a
                    href="{{ route('properties.show', $property) }}"
                    class="hover:text-brand-blue transition"
                >

                    <h3 class="font-display font-bold text-navy-900 text-base sm:text-lg mb-1">
                        {{ $property->name }}
                    </h3>

                </a>

            @else

                <h3 class="font-display font-bold text-navy-900 text-base sm:text-lg mb-1 group-hover:text-brand-blue transition">
                    {{ $property->name }}
                </h3>

            @endif


            {{-- =================================================
                 BINTANG PROPERTI
                 ================================================= --}}
            <div class="flex items-center gap-2 mb-2">

                <span
                    class="text-yellow-400 text-sm tracking-tight"
                    aria-label="{{ $starRating }} Bintang"
                >
                    {{ str_repeat('★', $starRating) }}
                </span>

                <span class="text-xs font-semibold text-gray-600">
                    {{ $starRating }} Bintang
                </span>

                <span class="text-xs text-gray-400">
                    · {{ $starLabel }}
                </span>

            </div>


            {{-- LOKASI --}}
            <p class="text-sm text-gray-500 mb-3 flex items-center gap-1">

                <span>
                    📍
                </span>

                {{ $property->city }}

            </p>


            {{-- KAMAR & TAMU --}}
            <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">

                <span class="flex items-center gap-1">
                    🛏️ {{ $property->bedroom_count }} Kamar
                </span>

                <span class="flex items-center gap-1">
                    👥 {{ $property->guest_capacity }} Orang
                </span>

            </div>


            {{-- =================================================
                 FASILITAS
                 ================================================= --}}
            @if (
                !$isOwnerView
                && $property->relationLoaded('facilities')
                && $property->facilities->isNotEmpty()
            )

                <div class="flex flex-wrap gap-1.5">

                    @foreach ($property->facilities->take(4) as $facility)

                        <span
                            class="bg-gray-50 text-gray-600 text-[11px] px-2 py-1 rounded-md border border-gray-100"
                        >
                            {{ $facility->name }}
                        </span>

                    @endforeach


                    @if ($property->facilities->count() > 4)

                        <span class="text-[11px] text-gray-400 px-1 py-1">
                            +{{ $property->facilities->count() - 4 }} lainnya
                        </span>

                    @endif

                </div>

            @endif


            {{-- =================================================
                 INFO MITRA
                 ================================================= --}}
            @if ($isOwnerView)

                <div class="text-xs text-gray-400 mt-2">

                    Skema:

                    <span class="font-medium text-gray-600">
                        {{ ucfirst($property->management_type) }}
                    </span>

                    · Komisi

                    {{ rtrim(rtrim(number_format($property->commission_percentage, 2), '0'), '.') }}%

                </div>

            @endif

        </div>


        {{-- =====================================================
             KANAN: HARGA + AKSI
             ===================================================== --}}
        <div class="sm:w-48 md:w-56 shrink-0 flex flex-row sm:flex-col items-end sm:items-end justify-between sm:justify-center gap-2 p-4 sm:p-5 sm:border-l border-gray-100 bg-gray-50/40">


            {{-- HARGA --}}
            <div class="text-left sm:text-right">

                <span class="text-xs text-gray-400 block sm:hidden">
                    Mulai dari
                </span>

                <div class="font-display font-extrabold text-navy-900 text-lg sm:text-xl leading-none">
                    Rp {{ number_format($property->price_per_night, 0, ',', '.') }}
                </div>

                <span class="text-xs font-normal text-gray-400">
                    / malam
                </span>

            </div>


            {{-- AKSI --}}
            @if ($isOwnerView)

                <div class="flex items-center gap-2">

                    <a
                        href="{{ route('mitra.properties.edit', $property) }}"
                        class="bg-brand-blue hover:bg-blue-700 transition text-white text-xs font-bold px-4 py-2 rounded-full whitespace-nowrap"
                    >
                        Edit
                    </a>


                    <form
                        action="{{ route('mitra.properties.destroy', $property) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus properti &quot;{{ $property->name }}&quot;? Tindakan ini tidak bisa dibatalkan.');"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="border border-red-200 text-red-500 hover:bg-red-50 transition text-xs font-bold px-4 py-2 rounded-full whitespace-nowrap"
                        >
                            Hapus
                        </button>

                    </form>

                </div>

            @else

                <span
                    class="bg-brand-yellow text-navy-900 text-xs font-bold px-4 py-2 rounded-full group-hover:bg-brand-blue group-hover:text-white transition whitespace-nowrap"
                >
                    Lihat Detail
                </span>

            @endif

        </div>


    @if ($isOwnerView)

        </div>

    @else

        </a>

    @endif

</div>