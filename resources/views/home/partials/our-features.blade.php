<section class="w-full bg-white py-24 px-6">
    <!-- JUDUL UTAMA -->
    <div class="max-w-2xl mx-auto text-center mb-20">
        <p class="text-gray-400 text-sm uppercase mb-2">Our Services</p>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Include Expert Interior Design Solutions.
        </h2>
        <p class="text-gray-600 text-base md:text-lg leading-relaxed">
            A very comfortable house is a house that is integrated with nature around it.
        </p>
    </div>

    <!-- GRID KONTEN BAWAH -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- KIRI ATAS -->
        <div
            class="relative group bg-white rounded-2xl shadow-xl p-10 flex flex-col items-center justify-center transition-transform duration-300 hover:scale-[1.03]">
            <!-- Gradient Background on Hover -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-blue-100 via-white to-transparent opacity-0 group-hover:opacity-100 transition duration-300 rounded-2xl -z-10">
            </div>
            <img src="{{ asset('home/assets/img/logo.png') }}" class="w-24 h-24 mb-6" alt="Ternakku Logo" />
            <h1 class="text-3xl font-bold text-blue-600">Ternakku</h1>
        </div>

        <!-- KIRI BAWAH -->
        <div
            class="relative group bg-gradient-to-br from-white to-[#ECFAE5] text-gray-900 rounded-2xl shadow-xl p-10 flex flex-col justify-between overflow-hidden transition-transform duration-300 hover:scale-[1.03]">
            <div>
                <h2 class="text-2xl font-bold leading-snug mb-4">Pendamping Digital Peternak Unggul</h2>
                <p class="text-gray-700 text-sm">
                    Ternakku adalah aplikasi pintar untuk memantau kesehatan ternak, mencatat aktivitas harian, dan
                    memastikan perawatan secara efisien. Satu platform terpadu untuk kemajuan peternakan modern.
                </p>
            </div>
            <div class="mt-6">
                <a href="#"
                    class="inline-block bg-blue-600 text-white font-semibold px-5 py-2 rounded-full shadow hover:bg-blue-700 transition">
                    Mulai Sekarang
                </a>
            </div>
            <!-- Diagonal Accent -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/20 rotate-45 rounded-xl"></div>
        </div>

        <!-- POSTER -->
        <div
            class="relative group rounded-2xl overflow-hidden shadow-2xl lg:row-span-2 min-h-[560px] transition-transform duration-300 hover:scale-[1.03]">
            <!-- Gradient Hover Layer -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-blue-100 to-white opacity-0 group-hover:opacity-100 transition duration-500 z-0">
            </div>
            <img src="{{ asset('home/assets/img/poster.jpg') }}" alt="Hero Poster"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 hover:scale-105 z-0" />
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/70 to-blue-700/80 z-0"></div>
            <div class="relative z-10 p-10 h-full flex flex-col justify-end">
                <h3 class="text-white text-2xl font-semibold mb-3">Kelola Ternak Lebih Mudah & Terpadu</h3>
                <p class="text-white text-lg leading-relaxed">
                    Dari monitoring kondisi ternak, pencatatan rutin, hingga perawatan harian — semua dalam satu
                    aplikasi yang membantu peternak makin unggul dan efisien.
                </p>
            </div>
        </div>

        <!-- KIRI BAWAH -->
        <div
            class="relative group bg-gradient-to-br from-white to-[#DDF6D2] text-gray-900 rounded-2xl p-10 flex flex-col justify-between overflow-hidden transition-transform duration-300 hover:scale-[1.03]">
            <div>
                <h2 class="text-2xl font-bold leading-snug mb-4">Kurban Digital yang Praktis & Terpercaya</h2>
                <p class="text-gray-700 text-sm">
                    Pantau seluruh proses kurban — mulai dari pemilihan hewan, perawatan, hingga distribusi — dalam satu
                    aplikasi yang transparan, aman, dan mudah digunakan.
                </p>
            </div>
            <div class="mt-6">
                <a href="#"
                    class="inline-block bg-white text-black font-semibold px-5 py-2 rounded-full hover:bg-blue-700 transition">
                    Mulai Sekarang
                </a>
            </div>
            <!-- Diagonal Accent -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/20 rotate-45 rounded-xl"></div>
        </div>

        <!-- POSTER -->
        <div
            class="relative group rounded-2xl overflow-hidden shadow-2xl lg:row-span-2 min-h-[560px] transition-transform duration-300 hover:scale-[1.03] bg-gradient-to-br from-white to-[#ECFAE5]">
            <!-- Gradient Hover Layer -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-white to-[#ECFAE5] opacity-0 group-hover:opacity-100 transition duration-500 z-0">
            </div>
            <img src="{{ asset('home/assets/img/poster.jpg') }}" alt="Hero Poster"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 hover:scale-105 z-0 mix-blend-overlay" />
            <!-- Remove blue overlay, use light layer to preserve visibility -->
            <div class="absolute inset-0 bg-white/20 z-0"></div>
            <!-- Text content -->
            <div class="relative z-10 p-10 h-full flex flex-col justify-end">
                <h3 class="text-gray-900 text-2xl font-semibold mb-3">Kurban Aman & Bersih</h3>
                <p class="text-gray-700 text-lg leading-relaxed">
                    Mendukung peternak lokal & transparansi distribusi kurban secara digital.
                </p>
            </div>
        </div>

    </div>
</section>
