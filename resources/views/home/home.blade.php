@extends('home.layouts.main')

@section('title', 'Landing Page')

@section('content')
    <!-- Banner -->
    <section class="relative w-full bg-gradient-to-b from-white to-[#B0DB9C] pt-40 pb-[590px] mt-20 overflow-hidden">

        <div class="max-w-7xl mx-auto px-6 text-center relative z-10 -mt-16">
            <!-- Headline -->
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-black leading-tight mb-10 text-gray-900">
                Aplikasi Peternak Unggul<br class="hidden md:inline" /> untuk Era Digital
            </h1>




            <!-- CTA -->
            <a href="#"
                class="inline-flex items-center gap-3 bg-black text-white px-6 py-3 rounded-full shadow-md hover:bg-gray-900 transition font-semibold text-base">
                Download Gratis di Playstore
                <span class="bg-white text-black rounded-full p-1">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                        <path d="M8 12h8m0 0l-4-4m4 4l-4 4" stroke="#000" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
        </div>

        <!-- Bubbles & Mockup HP, POSISI ABSOLUTE DI DALAM SECTION -->
        <div class="absolute left-1/2 bottom-0 -translate-x-1/2 z-20 justify-center items-center min-w-0 hidden sm:flex"
            style="height:410px; width:100%; max-width:680px;">

            <!-- Bubble kiri atas -->
            <div id="bubble-kiri-atas"
                class="absolute -left-20 -top-3 w-56 h-16 bg-cyan-200 rounded-2xl opacity-90 flex items-center gap-3 px-4 py-2
            transition-all duration-700 ease-out opacity-0 translate-y-8">
                <svg class="w-6 h-6 text-cyan-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" />
                    <path d="M12 8v4l3 3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-cyan-800 font-semibold text-base leading-tight">
                    Membantu Peternak untuk unggul
                </span>
            </div>

            <!-- Bubble kanan atas -->
            <div id="bubble-kanan-atas"
                class="absolute -right-20 -top-2 w-48 h-16 bg-red-300 rounded-2xl opacity-90 flex items-center gap-2 px-4 py-2
            transition-all duration-700 ease-out opacity-0 translate-y-8">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="4" y="7" width="16" height="13" rx="2" />
                    <path d="M16 3v4M8 3v4" />
                </svg>
                <span class="text-red-800 font-semibold text-base">Gerai</span>
            </div>

            <!-- Bubble kiri bawah -->
            <div id="bubble-kiri-bawah"
                class="absolute -left-24 bottom-4 w-44 h-14 bg-green-200 rounded-2xl opacity-90 flex items-center gap-2 px-4 py-2
            transition-all duration-700 ease-out opacity-0 translate-y-8">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4l-3-3" />
                </svg>
                <span class="text-green-800 font-semibold text-base">Kesehatan</span>
            </div>

            <!-- Bubble kanan bawah -->
            <div id="bubble-kanan-bawah"
                class="absolute -right-16 bottom-6 w-40 h-14 bg-green-400 rounded-2xl opacity-90 flex items-center gap-2 px-4 py-2
            transition-all duration-700 ease-out opacity-0 translate-y-8">
                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <rect x="4" y="7" width="16" height="13" rx="2" />
                    <path d="M8 3v4" />
                </svg>
                <span class="text-green-900 font-semibold text-base">Pakan</span>
            </div>

            <!-- Gambar mockup HP MEDIUM-BESAR -->
            <img src="{{ asset('home/assets/img/desain.png') }}" alt="Mockup App"
                class="relative z-10 w-[240px] sm:w-[320px] md:w-[400px] lg:w-[460px] xl:w-[500px] max-w-full select-none pointer-events-none drop-shadow-xl" />
        </div>
    </section>

    <section class="w-full bg-white py-24">
        <div class="max-w-6xl mx-auto px-6">
            @include('home.partials.about')
        </div>
    </section>

    @include('home.partials.ternak-kurban-section')
    @include('home.partials.our-features')
    @include('home.partials.accordion')
    @include('home.partials.footer-section')


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Semua bubble id
            const bubbleIds = [
                'bubble-kiri-atas',
                'bubble-kanan-atas',
                'bubble-kiri-bawah',
                'bubble-kanan-bawah'
            ];

            bubbleIds.forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const observer = new IntersectionObserver(
                    (entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                el.classList.add('opacity-100', 'translate-y-0');
                                el.classList.remove('opacity-0', 'translate-y-8');
                                observer.unobserve(el); // Cukup sekali muncul
                            }
                        });
                    }, {
                        threshold: 0.2
                    }
                );
                observer.observe(el);
            });
        });
    </script>
@endsection
