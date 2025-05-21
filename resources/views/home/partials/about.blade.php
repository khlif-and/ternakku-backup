<div
    class="w-full max-w-7xl mx-auto px-4 md:px-10 py-16 md:py-24 flex flex-col md:flex-row items-center md:items-start gap-12 relative overflow-visible">

    {{-- Kiri: Image HP Demo --}}
    <div class="flex-1 flex justify-center md:justify-end relative w-full md:w-auto">
        {{-- Gradient Circle di belakang gambar --}}
        <div
            class="absolute -top-10 left-1/2 -translate-x-1/2 md:translate-x-0 md:left-auto md:-right-10 w-80 h-80 md:w-[360px] md:h-[360px] rounded-full bg-green-300 opacity-30 blur-3xl z-0">
        </div>

        {{-- Gambar HP --}}
        <img src="{{ asset('home/assets/img/desain.png') }}" alt="Ternakku App Preview"
            class="floating-animate w-64 md:w-[370px] lg:w-[520px] h-auto block relative z-10"
            style="margin-bottom: -40px;" loading="lazy" />
    </div>

    {{-- Kanan: Konten --}}
    <div class="flex-1 max-w-2xl">
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-5 text-left leading-tight">
            Ternakku,
            <span class="text-green-600">Lebih dari Sekadar Aplikasi</span>
        </h2>
        <p class="text-gray-700 text-lg mb-7 font-medium leading-relaxed">
            Kami hadir untuk membantu peternak Indonesia bertumbuh di era digital.
            Dengan <span class="text-green-700 font-semibold">Ternakku</span>,
            urusan ternak, laporan usaha, hingga akses modal jadi <span class="font-semibold text-green-600">satu
                genggaman</span>.
        </p>
        <p class="text-base text-gray-500 font-normal mb-8">
            Bukan sekadar alat, tapi sahabat perjalanan usahamu.
        </p>
        <div class="space-y-6">
            <div class="flex items-start gap-4">
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100 mt-1">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <div>
                    <span class="font-bold text-gray-900">Kelola ternak otomatis</span>
                    <div class="text-gray-500 text-sm">Pantau kesehatan, riwayat, dan produktivitas ternak tanpa ribet.
                    </div>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 mt-1">
                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <div>
                    <span class="font-bold text-gray-900">Laporan usaha instan</span>
                    <div class="text-gray-500 text-sm">Seluruh pemasukan & pengeluaran langsung tercatat dan rapi.</div>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 mt-1">
                    <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <div>
                    <span class="font-bold text-gray-900">Akses kemitraan & modal</span>
                    <div class="text-gray-500 text-sm">Informasi dan pengajuan langsung dari aplikasi, tanpa birokrasi
                        rumit.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="w-full max-w-7xl mx-auto px-4 md:px-10 mt-20 md:mt-32 py-16 md:py-24 flex flex-col md:flex-row-reverse items-start gap-12">

    {{-- Kanan: Gambar HP --}}
    <div class="relative w-full md:w-1/2 flex justify-center md:justify-end">
        {{-- Circle Gradient (lebih besar dan soft) --}}
        <div
            class="absolute top-0 right-1/2 translate-x-1/2 md:right-0 md:translate-x-0 w-[400px] h-[400px] md:w-[480px] md:h-[480px] rounded-full bg-green-200 opacity-40 blur-[100px] z-0">
        </div>

        {{-- Gambar HP floating --}}
        <img src="{{ asset('home/assets/img/desain.png') }}" alt="Ternakku App Preview"
            class="w-64 md:w-[370px] lg:w-[520px] h-auto block relative z-10" style="margin-bottom: -40px;"
            loading="lazy" />
    </div>

    {{-- Kiri: Judul + Paragraf + Kotak Kemudahan --}}
    <div class="w-full md:w-1/2">
        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 leading-tight">
            Peternak Hebat, <span class="text-green-600">Peternak Tangguh</span>
        </h2>
        <p class="text-gray-700 text-base md:text-lg font-medium mb-8 leading-relaxed">
            Didesain untuk <span class="text-green-700 font-semibold">mendampingi peternak Indonesia</span> menjadi
            lebih mandiri, digital, dan unggul di pasar. Semua fitur kami hadir untuk membuat pekerjaan Anda lebih
            ringan dan usaha Anda lebih maju.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div
                class="flex items-start gap-4 p-5 bg-white border border-gray-200 rounded-xl shadow hover:shadow-md transition">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <div>
                    <div class="font-semibold text-gray-900">Manajemen Ternak</div>
                    <p class="text-sm text-gray-500">Data ternak tersimpan rapi & lengkap.</p>
                </div>
            </div>
            <div
                class="flex items-start gap-4 p-5 bg-white border border-gray-200 rounded-xl shadow hover:shadow-md transition">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <div>
                    <div class="font-semibold text-gray-900">Laporan Instan</div>
                    <p class="text-sm text-gray-500">Pemasukan dan pengeluaran langsung tercatat.</p>
                </div>
            </div>
            <div
                class="flex items-start gap-4 p-5 bg-white border border-gray-200 rounded-xl shadow hover:shadow-md transition">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <div>
                    <div class="font-semibold text-gray-900">Kemudahan Modal</div>
                    <p class="text-sm text-gray-500">Ajukan kemitraan langsung dari aplikasi.</p>
                </div>
            </div>
            <div
                class="flex items-start gap-4 p-5 bg-white border border-gray-200 rounded-xl shadow hover:shadow-md transition">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <div>
                    <div class="font-semibold text-gray-900">Pemantauan Kesehatan</div>
                    <p class="text-sm text-gray-500">Pantau performa & kondisi ternak kapan saja.</p>
                </div>
            </div>
        </div>
    </div>


</div>





<style>
    @keyframes floating {
        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-24px);
        }

        100% {
            transform: translateY(0);
        }
    }

    .floating-animate {
        animation: floating 7.5s ease-in-out infinite;
    }
</style>
