@extends('home.layouts.main')

@section('title', 'Ternakku : Platform Digital Ternak')

@section('content')
    <section id="hero-banner"
        class="relative w-full bg-gradient-to-b from-[#B0DB9C]/20 to-white text-gray-900 pt-32 pb-20 overflow-hidden"
        style="perspective: 1000px;">

        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-bold leading-tight mt-6 text-gray-900">
                Ternakku membantu Peternak<br class="hidden md:inline" /> untuk Go digital
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mt-6">
                Deliver fast, personalized, and AI-powered support that keeps your customers happy—without overwhelming your
                team.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-10">
                <a href="#"
                    class="inline-flex items-center justify-center bg-black text-white hover:bg-gray-800 px-8 py-4 rounded-lg font-semibold text-lg transition duration-300 shadow-lg shadow-black/20">
                    <svg class="w-6 h-6 mr-3" aria-hidden="true" focusable="false" role="img"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M325.3 234.3L104.6 13.6c-5.4-3.1-12.1-3.1-17.5 0C81.6 19.9 77 26.6 77 34v444c0 7.4 4.6 14.1 10.1 17.1c2.8 1.5 5.8 2.3 8.8 2.3c6.1 0 12-2.3 16.5-6.4l220.7-127.1c3.2-1.8 5.7-4.9 7.2-8.3c1.5-3.4 2-7.2 2-11c0-3.9-.5-7.6-2-11c-1.5-3.4-4-6.5-7.2-8.3z">
                        </path>
                    </svg>
                    Download Aplikasi Ternakku.id
                </a>
            </div>
        </div>

        <div id="mockup-wrapper" class="relative z-10 max-w-6xl mx-auto mt-20 px-6">
            <div id="mockup-content"
                class="bg-gray-900/80 backdrop-blur-md border border-gray-700 rounded-t-xl shadow-xl shadow-black/20 overflow-hidden transform-gpu
                        origin-bottom">
                <div class="w-full h-[300px] md:h-[500px] lg:h-[600px] flex items-center justify-center bg-dots-pattern">
                    <span class="text-gray-500"></span>
                </div>
            </div>
        </div>
    </section>

    @include('home.partials.ternak-kurban-section')
    @include('home.partials.about')
    @include('home.partials.our-features')
    @include('home.partials.accordion')
    @include('home.partials.carousel-ternak-kurban')
    @include('home.partials.footer-section')

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        gsap.registerPlugin(ScrollTrigger);
        gsap.fromTo("#mockup-content", {
            opacity: 0.3,
            y: 50,
            rotateX: 60
        }, {
            opacity: 1,
            y: 0,
            rotateX: 0,
            ease: "none",
            scrollTrigger: {
                trigger: "#mockup-wrapper",
                start: "top bottom",
                end: "center center",
                scrub: true,
            }
        });
    });
</script>
@endpush
