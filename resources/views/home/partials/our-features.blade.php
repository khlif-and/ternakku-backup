<section class="w-full bg-white py-24 px-6">
    <div class="max-w-4xl mx-auto text-center mb-16" data-aos="fade-up">
        <!-- Badge / Label kecil -->
        <div class="inline-block bg-gray-100 text-gray-600 text-xs font-medium px-3 py-1 rounded-full uppercase tracking-wide mb-4">
            Fitur Unggulan
        </div>

        <!-- Heading besar -->
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">
            Kurban Jadi Mudah, Cepat, dan Transparan
        </h2>

        <!-- Subheading -->
        <p class="text-gray-600 text-base md:text-lg leading-relaxed">
            Membantu kamu meraih ibadah kurban yang berkah dengan proses yang
            <span class="font-semibold">fleksibel</span>,
            <span class="font-semibold">transparan</span>, dan
            <span class="font-semibold">bebas repot</span> langsung dari genggaman.
        </p>
    </div>

    <!-- 3 Fitur Utama -->
    <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-20">
        @php
            $features = [
                ['icon' => 'location-marker', 'title' => 'Lacak Ternak', 'desc' => 'untuk melacak setiap pengiriman ternak, jadi kamu tidak perlu khawatir'],
                ['icon' => 'clipboard-check', 'title' => 'Laporan Digital', 'desc' => 'semua proses terekam dan terdokumentasi otomatis'],
                ['icon' => 'shield-check', 'title' => 'Transparansi Proses', 'desc' => 'pantau mulai dari pemilihan, penyembelihan hingga distribusi']
            ];
        @endphp

        @foreach ($features as $index => $item)
        <div class="border border-gray-200 rounded-xl px-6 py-8 text-center shadow-sm transition hover:shadow-md bg-white"
             data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
            <div class="w-14 h-14 flex items-center justify-center mx-auto mb-5 rounded-lg border border-gray-100 bg-gray-50">
                @if ($item['icon'] === 'location-marker')
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6c0 4.5 6 10 6 10s6-5.5 6-10a6 6 0 00-6-6zm0 8a2 2 0 110-4 2 2 0 010 4z" clip-rule="evenodd" />
                    </svg>
                @elseif ($item['icon'] === 'clipboard-check')
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4 -4M12 4H8a2 2 0 00-2 2v14a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2h-4z" />
                    </svg>
                @elseif ($item['icon'] === 'shield-check')
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                @endif
            </div>
            <h4 class="text-green-700 font-semibold text-base mb-2">{{ $item['title'] }}</h4>
            <p class="text-gray-600 text-sm">{{ $item['desc'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Horizontal 2 Cards Besar -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        @for ($i = 0; $i < 2; $i++)
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 border border-gray-200 rounded-2xl px-8 py-8 shadow-md hover:shadow-lg transition bg-white"
             data-aos="fade-up" data-aos-delay="{{ 200 + ($i * 100) }}">
            <!-- Icon area -->
            <div class="w-24 h-24 flex items-center justify-center bg-gray-50 rounded-xl border border-gray-100 shrink-0">
                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6c0 4.5 6 10 6 10s6-5.5 6-10a6 6 0 00-6-6zm0 8a2 2 0 110-4 2 2 0 010 4z" clip-rule="evenodd" />
                </svg>
            </div>
            <!-- Text content -->
            <div class="text-center sm:text-left">
                <h4 class="text-green-700 text-lg font-semibold mb-2">Lacak Ternak</h4>
                <p class="text-gray-700 text-base font-medium leading-relaxed">
                    untuk melacak setiap pengiriman ternak, jadi kamu tidak perlu khawatir
                </p>
            </div>
        </div>
        @endfor
    </div>
</section>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        duration: 700,
        offset: 60,
    });
</script>
