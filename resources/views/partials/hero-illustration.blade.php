{{-- =========================================================
     HERO CAROUSEL
     ========================================================= --}}

<div
    id="jogjaHeroCarousel"
    class="absolute inset-0 w-full h-full"
    style="
        z-index: 20;
        pointer-events: none;
    "
>

    {{-- =====================================================
         SLIDE 1
         ===================================================== --}}

    <img
        src="{{ asset('images/Jogja6.png') }}"
        alt="Guest House & Kost Harian di Yogyakarta"
        class="hero-jogja-slide"
        style="
            opacity: 1;
            z-index: 2;
        "
    >


    {{-- =====================================================
         SLIDE 2
         ===================================================== --}}

    <img
        src="{{ asset('images/Jogja7.jpg') }}"
        alt="Guest House & Kost Harian di Yogyakarta"
        class="hero-jogja-slide"
        style="
            opacity: 0;
            z-index: 1;
        "
    >


    {{-- =====================================================
         SLIDE 3
         ===================================================== --}}

    <img
        src="{{ asset('images/Jogja8.jpg') }}"
        alt="Guest House & Kost Harian di Yogyakarta"
        class="hero-jogja-slide"
        style="
            opacity: 0;
            z-index: 1;
        "
    >


    {{-- =====================================================
         SLIDE 4
         ===================================================== --}}

    <img
        src="{{ asset('images/Jogja9.jpg') }}"
        alt="Guest House & Kost Harian di Yogyakarta"
        class="hero-jogja-slide"
        style="
            opacity: 0;
            z-index: 1;
        "
    >


    {{-- =====================================================
         SLIDE 5
         ===================================================== --}}

    <img
        src="{{ asset('images/Jogja10.jpg') }}"
        alt="Guest House & Kost Harian di Yogyakarta"
        class="hero-jogja-slide"
        style="
            opacity: 0;
            z-index: 1;
        "
    >


    {{-- =====================================================
         DARK OVERLAY
         ===================================================== --}}

  


    {{-- =====================================================
         TOMBOL KIRI
         ===================================================== --}}

    <button
        type="button"
        id="jogjaPrev"
        aria-label="Slide sebelumnya"
        class="jogja-carousel-control"

        style="
            position: absolute;
            left: 24px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 100;
        "
    >

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15 19l-7-7 7-7"
            />
        </svg>

    </button>


    {{-- =====================================================
         TOMBOL KANAN
         ===================================================== --}}

    <button
        type="button"
        id="jogjaNext"
        aria-label="Slide berikutnya"
        class="jogja-carousel-control"

        style="
            position: absolute;
            right: 24px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 100;
        "
    >

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M9 5l7 7-7 7"
            />
        </svg>

    </button>


    {{-- =====================================================
         DOTS
         ===================================================== --}}

    <div
        id="jogjaDots"

        style="
            position: absolute;
            left: 50%;
            bottom: 58px;
            transform: translateX(-50%);
            z-index: 100;
            pointer-events: auto;
        "

        class="
            flex
            items-center
            gap-2
            px-3
            py-1.5
            rounded-full
            bg-black/25
            backdrop-blur-sm
        "
    >

        <button
            type="button"
            data-slide="0"
            class="jogja-dot text-white text-sm"
            aria-label="Slide 1"
        >●</button>

        <button
            type="button"
            data-slide="1"
            class="jogja-dot text-white/50 text-sm"
            aria-label="Slide 2"
        >○</button>

        <button
            type="button"
            data-slide="2"
            class="jogja-dot text-white/50 text-sm"
            aria-label="Slide 3"
        >○</button>

        <button
            type="button"
            data-slide="3"
            class="jogja-dot text-white/50 text-sm"
            aria-label="Slide 4"
        >○</button>

        <button
            type="button"
            data-slide="4"
            class="jogja-dot text-white/50 text-sm"
            aria-label="Slide 5"
        >○</button>

    </div>

</div>


{{-- =========================================================
     CSS
     ========================================================= --}}

<style>

    .hero-jogja-slide {
        position: absolute;
        inset: 0;

        width: 100%;
        height: 100%;

        object-fit: cover;

        transition:
            opacity 700ms ease-in-out;

        pointer-events: none;
    }


    /*
    |--------------------------------------------------------------------------
    | TOMBOL CAROUSEL
    |--------------------------------------------------------------------------
    */

    .jogja-carousel-control {

        width: 44px;
        height: 44px;

        border-radius: 9999px;

        background: rgba(0, 0, 0, 0.40);

        border: 1px solid rgba(255, 255, 255, 0.30);

        color: white;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;

        pointer-events: auto;

        transition:
            background 200ms ease,
            transform 200ms ease;

    }


    .jogja-carousel-control:hover {

        background: rgba(0, 0, 0, 0.70);

        transform:
            translateY(-50%)
            scale(1.10);

    }


    .jogja-carousel-control:active {

        transform:
            translateY(-50%)
            scale(0.95);

    }


    /*
    |--------------------------------------------------------------------------
    | DOT
    |--------------------------------------------------------------------------
    */

    .jogja-dot {

        border: none;

        background: transparent;

        padding: 0;

        width: 16px;
        height: 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;

        pointer-events: auto;

        transition:
            transform 300ms ease,
            opacity 300ms ease;

    }


    .jogja-dot:hover {

        transform: scale(1.2);

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 640px) {

        #jogjaPrev {

            left: 12px !important;

        }


        #jogjaNext {

            right: 12px !important;

        }


        .jogja-carousel-control {

            width: 38px !important;
            height: 38px !important;

        }


        #jogjaDots {

            bottom: 52px !important;

        }

    }

</style>


{{-- =========================================================
     JAVASCRIPT
     ========================================================= --}}

<script>

(function () {

    function initJogjaHero() {

        const carousel =
            document.getElementById(
                'jogjaHeroCarousel'
            );


        if (!carousel) {

            return;

        }


        const slides =
            carousel.querySelectorAll(
                '.hero-jogja-slide'
            );


        const dots =
            carousel.querySelectorAll(
                '.jogja-dot'
            );


        const prev =
            document.getElementById(
                'jogjaPrev'
            );


        const next =
            document.getElementById(
                'jogjaNext'
            );


        if (!slides.length) {

            return;

        }


        if (!prev || !next) {

            return;

        }


        let current = 0;

        let timer = null;


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN SLIDE
        |--------------------------------------------------------------------------
        */

        function showSlide(index) {

            if (index >= slides.length) {

                index = 0;

            }


            if (index < 0) {

                index = slides.length - 1;

            }


            current = index;


            slides.forEach(
                function (slide, i) {

                    if (i === current) {

                        slide.style.opacity = '1';

                        slide.style.zIndex = '2';

                    } else {

                        slide.style.opacity = '0';

                        slide.style.zIndex = '1';

                    }

                }
            );


            dots.forEach(
                function (dot, i) {

                    if (i === current) {

                        dot.textContent = '●';

                        dot.classList.remove(
                            'text-white/50'
                        );

                        dot.classList.add(
                            'text-white'
                        );

                        dot.style.transform =
                            'scale(1.15)';

                    } else {

                        dot.textContent = '○';

                        dot.classList.remove(
                            'text-white'
                        );

                        dot.classList.add(
                            'text-white/50'
                        );

                        dot.style.transform =
                            'scale(1)';

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NEXT
        |--------------------------------------------------------------------------
        */

        function nextSlide() {

            showSlide(
                current + 1
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PREVIOUS
        |--------------------------------------------------------------------------
        */

        function previousSlide() {

            showSlide(
                current - 1
            );

        }


        /*
        |--------------------------------------------------------------------------
        | AUTO SLIDE
        |--------------------------------------------------------------------------
        */

        function startAutoPlay() {

            clearInterval(timer);


            timer = setInterval(
                function () {

                    nextSlide();

                },
                5000
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TOMBOL KIRI
        |--------------------------------------------------------------------------
        */

        prev.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();

                previousSlide();

                startAutoPlay();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TOMBOL KANAN
        |--------------------------------------------------------------------------
        */

        next.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();

                nextSlide();

                startAutoPlay();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | DOTS
        |--------------------------------------------------------------------------
        */

        dots.forEach(
            function (dot) {

                dot.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();

                        event.stopPropagation();


                        const index =
                            Number(
                                dot.dataset.slide
                            );


                        showSlide(index);

                        startAutoPlay();

                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | SWIPE HP
        |--------------------------------------------------------------------------
        */

        let touchStartX = 0;


        carousel.addEventListener(
            'touchstart',
            function (event) {

                touchStartX =
                    event.touches[0].clientX;

            },
            {
                passive: true
            }
        );


        carousel.addEventListener(
            'touchend',
            function (event) {

                const touchEndX =
                    event.changedTouches[0].clientX;


                const distance =
                    touchEndX - touchStartX;


                if (
                    Math.abs(distance) > 50
                ) {

                    if (distance < 0) {

                        nextSlide();

                    } else {

                        previousSlide();

                    }


                    startAutoPlay();

                }

            },
            {
                passive: true
            }
        );


        /*
        |--------------------------------------------------------------------------
        | MULAI
        |--------------------------------------------------------------------------
        */

        showSlide(0);

        startAutoPlay();

    }


    /*
    |--------------------------------------------------------------------------
    | JALANKAN SETELAH HTML SELESAI
    |--------------------------------------------------------------------------
    */

    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initJogjaHero
        );

    } else {

        initJogjaHero();

    }

})();

</script>