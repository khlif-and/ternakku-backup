<footer class="bg-gray-50 border-t border-gray-200 pt-20 pb-10 px-6">
    <!-- Footer Grid -->
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 text-sm text-gray-600">
        <!-- Tentang Ternakku -->
        <div data-aos="fade-up">
            <h4 class="text-gray-900 font-semibold mb-4">Tentang Ternakku</h4>
            <p class="text-gray-600 leading-relaxed text-sm md:text-base max-w-md">
                Ternakku adalah aplikasi cerdas yang membantu peternak mengelola dan memantau ternaknya secara efisien,
                mulai dari pemeliharaan hingga kurban. Kami menghadirkan transparansi, kemudahan, dan keberkahan dalam
                satu genggaman.
            </p>
        </div>

        <!-- Navigasi -->
        <div data-aos="fade-up" data-aos-delay="100">
            <h4 class="text-gray-900 font-semibold mb-4">Navigasi</h4>
            <ul class="space-y-3">
                <li><a href="#" class="hover:text-green-600 transition duration-300">Beranda</a></li>
                <li><a href="#" class="hover:text-green-600 transition duration-300">Fitur</a></li>
                <li><a href="#" class="hover:text-green-600 transition duration-300">Tentang Kami</a></li>
                <li><a href="#" class="hover:text-green-600 transition duration-300">Kontak</a></li>
                <li><a href="#" class="hover:text-green-600 transition duration-300">Masuk / Daftar</a></li>
            </ul>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="mt-16 border-t border-gray-200 pt-6 text-sm text-gray-500 text-center max-w-6xl mx-auto">
        <p>© {{ now()->year }} <span class="text-green-600 font-semibold">Ternakku</span>. All rights reserved.</p>
    </div>
</footer>

<!-- AOS Library -->
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        duration: 700,
        offset: 50,
    });
</script>
