@extends('home.layouts.main')

@section('title', 'Landing Page')

@section('content')
<!-- SECTION -->
<section class="relative w-full bg-gradient-to-b from-white to-[#B0DB9C] pt-40 pb-[10px] mt-20 overflow-hidden">

    <div class="max-w-7xl mx-auto px-6 text-center relative z-10 -mt-16">
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

    <!-- WRAPPER HP & BUBBLES -->
    <div class="relative z-20 w-fit mx-auto mt-20">

        <!-- Bubble kiri atas -->
        <div id="bubble-kiri-atas"
            class="bubble absolute -left-48 top-10 w-56 h-16 bg-gradient-to-r from-cyan-100 to-cyan-50 rounded-2xl shadow-md flex items-center px-4 py-2 opacity-0 translate-y-8 transition-all duration-700 ease-out">
            <div class="absolute right-[-8px] top-1/2 -translate-y-1/2 w-0 h-0 border-y-8 border-y-transparent border-l-[8px] border-l-cyan-100"></div>
            <span class="text-black font-medium text-sm leading-tight">Memberdayakan peternak, memajukan negeri</span>
        </div>

        <!-- Bubble kanan atas -->
        <div id="bubble-kanan-atas"
            class="bubble absolute -right-48 top-[100px] w-48 h-16 bg-gradient-to-r from-orange-100 to-orange-50 rounded-2xl shadow-md flex items-center px-4 py-2 opacity-0 translate-y-8 transition-all duration-700 ease-out delay-150">
            <div class="absolute left-[-8px] top-1/2 -translate-y-1/2 w-0 h-0 border-y-8 border-y-transparent border-r-[8px] border-r-orange-100"></div>
            <span class="text-black font-medium text-sm">Gerai untuk Kemajuan Ternak</span>
        </div>

        <!-- Bubble kiri bawah -->
        <div id="bubble-kiri-bawah"
            class="bubble absolute -left-44 bottom-[220px] w-44 h-14 bg-gradient-to-r from-green-100 to-green-50 rounded-2xl shadow-md flex items-center px-4 py-2 opacity-0 translate-y-8 transition-all duration-700 ease-out delay-300">
            <div class="absolute right-[-8px] top-1/2 -translate-y-1/2 w-0 h-0 border-y-8 border-y-transparent border-l-[8px] border-l-green-100"></div>
            <span class="text-black font-medium text-sm">Layanan Kesehatan Ternak</span>
        </div>

        <!-- Bubble kanan bawah -->
        <div id="bubble-kanan-bawah"
            class="bubble absolute -right-40 bottom-[60px] w-40 h-14 bg-gradient-to-r from-emerald-100 to-green-50 rounded-2xl shadow-md flex items-center px-4 py-2 opacity-0 translate-y-8 transition-all duration-700 ease-out delay-500">
            <div class="absolute left-[-8px] top-1/2 -translate-y-1/2 w-0 h-0 border-y-8 border-y-transparent border-r-[8px] border-r-emerald-100"></div>
            <span class="text-black font-medium text-sm">Pakan Optimal, Hasil Maksimal</span>
        </div>

        <!-- Gambar mockup HP -->
        <img src="{{ asset('home/assets/img/desain.png') }}" alt="Mockup App"
            class="relative z-10 w-[240px] sm:w-[320px] md:w-[400px] lg:w-[460px] xl:w-[500px] max-w-full select-none pointer-events-none drop-shadow-xl" />
    </div>
</section>

@include('home.partials.ternak-kurban-section')
@include('home.partials.about')
@include('home.partials.our-features')
@include('home.partials.carousel-ternak-kurban')
@include('home.partials.accordion')
@include('home.partials.footer-section')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const bubbleIds = [
            'bubble-kiri-atas',
            'bubble-kanan-atas',
            'bubble-kiri-bawah',
            'bubble-kanan-bawah'
        ];

        bubbleIds.forEach((id, index) => {
            const el = document.getElementById(id);
            if (!el) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        el.classList.add('opacity-100', 'translate-y-0');
                        el.classList.remove('opacity-0', 'translate-y-8');
                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.2 });

            observer.observe(el);
        });
    });
</script>
@endsection
