<section class="w-full py-24 px-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-12">

        <!-- Kiri: Text Content -->
        <div data-aos="fade-right" data-aos-delay="100">
<h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug mb-4">
    Kelola Peternakan Lebih Cerdas<br />Bersama Ternakku
</h2>
<p class="text-gray-500 text-base md:text-lg mb-6">
    Ternakku membantu peternak memantau kesehatan hewan, mencatat aktivitas harian, serta meningkatkan efisiensi dan hasil ternak lewat sistem digital yang mudah digunakan.
</p>


            <a href="{{ url('auth/login') }}"
                class="inline-flex items-center gap-3 bg-black text-white px-6 py-3 rounded-full shadow-md hover:bg-gray-800 transition font-semibold text-base">
                Mulai Sekarang
                <span class="bg-white text-black rounded-full p-1">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                        <path d="M8 12h8m0 0l-4-4m4 4l-4 4" stroke="#000" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
        </div>

        <!-- Kanan: Image -->
        <div class="relative w-full flex justify-center" data-aos="fade-left" data-aos-delay="200">
            <img src="{{ asset('home/assets/img/desain.png') }}" alt="Mockup Ilustrasi"
                class="w-[320px] sm:w-[380px] md:w-[440px] lg:w-[460px] drop-shadow-xl transition-all duration-700 hover:scale-105"
                loading="lazy" />
        </div>
    </div>
</section>

<!-- Head -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<!-- Before </body> -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        duration: 700,
        offset: 60,
    });
</script>
