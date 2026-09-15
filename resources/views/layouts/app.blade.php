<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Booking Guest House & Kost Harian')</title>
    <meta name="description" content="@yield('meta_description', 'Booking guest house dan kost harian terpercaya, ratusan properti siap dipesan.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&family=Fredoka:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind via CDN untuk prototyping cepat.
         Nanti bisa diganti ke Tailwind lewat Vite (npm run build) kalau sudah siap production. -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                        sans: ['"Inter"', 'sans-serif'],
                        logo: ['"Fredoka"', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            900: '#0c1830',
                            800: '#123b6d',
                            700: '#1e5a9c',
                            600: '#2b64a8',
                        },
                        brand: {
                            blue: '#0868e0',
                            'blue-light': '#3b9dfa',
                            yellow: '#facc15',
                            orange: '#f97316',
                        }
                    },
                    boxShadow: {
                        card: '0 8px 24px -8px rgba(15, 31, 61, 0.15)',
                        floating: '0 20px 50px -12px rgba(12, 24, 48, 0.35)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        .batik-texture {
            background-image: repeating-linear-gradient(135deg, rgba(250,204,21,0.06) 0px, rgba(250,204,21,0.06) 2px, transparent 2px, transparent 14px);
        }
    </style>
</head>
<body class="bg-gradient-to-b from-blue-50/60 via-white to-white text-gray-800 antialiased">

  @php
    $dashboardLayout = trim(
        $__env->yieldContent('dashboard_layout', 'false')
    ) === 'true';

    $navTransparent = trim(
        $__env->yieldContent('nav_variant', 'solid')
    ) === 'transparent';
@endphp

@if (!$dashboardLayout)
    @include('partials.navbar')
@endif
    {{-- Nav sekarang selalu 'fixed', jadi halaman non-hero butuh jarak atas manual
         supaya kontennya tidak ketutup navbar. Halaman hero (transparent) sengaja
         dibiarkan tanpa padding karena nav memang didesain menyatu di atas hero. --}}
    <main class="{{ $dashboardLayout ? '' : ($navTransparent ? '' : 'pt-[80px] lg:pt-[128px]') }}">
    @yield('content')
</main>

@if (!$dashboardLayout)
    @include('partials.footer')
@endif
    @include('partials.login-modal')
    @include('partials.register-modal')

    <script>
        // ============ ANTI DOUBLE-SUBMIT (berlaku untuk SEMUA form di seluruh web) ============
        // Begitu form di-submit, tombol submit-nya langsung dinonaktifkan supaya
        // klik kedua (sengaja atau tidak sengaja/dobel klik) tidak mengirim data lagi.
        document.addEventListener('submit', function (e) {
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (!submitBtn || submitBtn.disabled) return;

            // Simpan teks asli, ganti jadi "Menyimpan..." biar ada indikator visual juga
            submitBtn.dataset.originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
            submitBtn.innerHTML = 'Menyimpan...';

            // Safety net: kalau ternyata form gagal submit (validasi browser, dsb),
            // aktifkan lagi tombolnya setelah 5 detik supaya user tidak stuck.
            setTimeout(function () {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                submitBtn.innerHTML = submitBtn.dataset.originalText;
            }, 5000);
        });
    </script>

</body>
</html>