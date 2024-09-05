<!-- resources/views/home/home.blade.php -->

<div class="container">
    <h2>Fitur Dari <span style="color: #4cb681">TernakKu</span></h2>
    <!-- Swiper -->
    <div class="swiper-container swiper-container-music">
        <div class="swiper-wrapper">
            @foreach ($fiturTernakKu as $fitur)
                <div class="swiper-slide">
                    <div class="card-img-top">
                        <img src="{{ asset($fitur['img']) }}" alt="Fitur TernakKu" class="img-fluid">
                    </div>
                    <p class="card-text">{{ $fitur['text'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Pagination dan Navigasi -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

<!-- Style -->
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
        overflow-x: hidden;
    }

    .container {
        width: 70%;
        margin: 40px auto;
        position: relative;
    }

    h2 {
        font-size: 44px;
        margin-bottom: 20px;
        text-align: left;
        font-weight: bold;
        display: inline-block;
    }

    .swiper-container {
        max-width: 100%;
        margin: auto;
        overflow: hidden;
        margin-bottom: 100px;
        /* Tambahkan lebih banyak jarak bawah */
    }

    .swiper-wrapper {
        display: flex;
    }

    .swiper-slide {
        border-radius: 10px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-sizing: border-box;
    }

    .swiper-slide .card-img-top {
        height: 150px;
        background-color: #eaeaea;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .swiper-slide .card-text {
        margin-top: 10px;
        font-size: 0.9rem;
        line-height: 1.5;
        color: #333;
        text-align: center;
    }

    .swiper-button-prev,
    .swiper-button-next {
        color: white;
        background-color: #58eca2;
        padding: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
        position: absolute;
        top: 42%;
        transform: translateY(-50%);
        z-index: 10;
    }

    .swiper-button-prev {
        left: -20px;
    }

    .swiper-button-next {
        right: -20px;
    }

    .swiper-button-prev:hover,
    .swiper-button-next:hover {
        background-color: #4cb681;
    }

    .swiper-button-prev::after,
    .swiper-button-next::after {
        font-size: 18px;
    }

    .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: #d9d9d9;
        border-radius: 50%;
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        /* Efek kenyal */
    }

    .swiper-pagination-bullet-active {
        width: 25px;
        background: #4cb681;
        border-radius: 12.5px;
    }

    .swiper-pagination {
        position: relative;
        bottom: 10px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 767px) {
        .swiper-button-prev {
            left: -15px;
        }

        .swiper-button-next {
            right: -15px;
        }

        .swiper-button-prev,
        .swiper-button-next {
            top: 42%;
        }
    }

    @media (min-width: 768px) and (max-width: 1023px) {
        .swiper-button-prev {
            left: -25px;
        }

        .swiper-button-next {
            right: -25px;
        }
    }

    @media (min-width: 1024px) {
        .swiper-button-prev {
            left: -30px;
        }

        .swiper-button-next {
            right: -30px;
        }

        .swiper-container {
            margin-bottom: 80px;
            /* Tambahkan lebih banyak jarak bawah */
        }

    }
</style>

<!-- Script to handle mouse scroll -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    var swiperMusic = new Swiper('.swiper-container-music', {
        spaceBetween: 15,
        loop: true,
        centeredSlides: false,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 1, // 1 card for mobile
            },
            768: {
                slidesPerView: 2, // 2 cards for tablet
            },
            1024: {
                slidesPerView: 3, // 3 cards for desktop
            }
        }
    });
</script>
