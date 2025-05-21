<section class="w-full bg-white py-24 px-6">
    <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up" data-aos-duration="800">
        <h2 class="text-3xl md:text-4xl font-semibold text-gray-900 mb-3 leading-snug">
            Ternak Kurban Untuk Semua
        </h2>
        <p class="text-gray-500 text-lg md:text-xl">
            Raih ikhlas ibadah kurban dengan aplikasi <span class="text-green-600 font-medium">Ternakku</span>.
        </p>
    </div>

    <div class="flex justify-center relative mb-20" data-aos="zoom-in" data-aos-duration="1000">
        <!-- Background Gradient -->
        <div class="absolute w-[540px] h-[540px] md:w-[660px] md:h-[660px] bg-green-100 opacity-50 blur-[140px] rounded-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0"></div>

        <!-- Mockup Image -->
        <img
            src="{{ asset('home/assets/img/desain.png') }}"
            alt="Mockup Ternak Kurban"
            class="relative z-10 w-64 md:w-[360px] lg:w-[420px] h-auto drop-shadow-xl transition-all duration-700 hover:scale-[1.03]"
            loading="lazy"
        />
    </div>

    <!-- 3 Fitur Utama -->
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center mb-20">
        @php
            $features = [
                ['icon' => 'M5 13l4 4L19 7', 'title' => 'Pantau Progres Kurban', 'desc' => 'Ikuti proses mulai dari pemesanan hingga distribusi.'],
                ['icon' => 'M4 6h16M4 12h16M4 18h7', 'title' => 'Transparansi Biaya', 'desc' => 'Semua biaya & laporan tersedia secara terbuka.'],
                ['icon' => 'M12 4v16m8-8H4', 'title' => 'Mudah & Otomatis', 'desc' => 'Pendaftaran kurban cukup dari genggaman.']
            ];
        @endphp

        @foreach ($features as $i => $feature)
        <div class="flex flex-col items-center p-4 transition-all duration-500 hover:scale-105" data-aos="fade-up" data-aos-delay="{{ ($i + 1) * 100 }}">
            <div class="w-14 h-14 flex items-center justify-center rounded-full bg-green-100 mb-4 shadow-md">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="{{ $feature['icon'] }}" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2">{{ $feature['title'] }}</h4>
            <p class="text-gray-500 text-sm">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Tambahan Konten CTA -->
    <div class="max-w-4xl mx-auto text-center" data-aos="fade-up" data-aos-delay="400">
        <div class="bg-green-50 border border-green-200 rounded-2xl px-6 py-8 shadow-sm">
            <h3 class="text-2xl font-bold text-green-700 mb-4">Yuk, mulai kurban digital sekarang!</h3>
            <p class="text-gray-600 mb-6 text-base md:text-lg">
                Dengan <span class="text-green-600 font-semibold">Ternakku</span>, kamu bisa kurban dengan lebih tenang, transparan, dan terpercaya.
            </p>
            <a href="#"
               class="inline-block px-6 py-3 rounded-full bg-green-600 text-white font-semibold text-base hover:bg-green-700 transition duration-300 shadow-md">
               Mulai Kurban Sekarang
            </a>
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

