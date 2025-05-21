<section class="w-full bg-white py-20 px-6">
    <div class="max-w-3xl mx-auto text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Pertanyaan Umum Seputar Kurban
        </h2>
        <p class="text-gray-600 text-base md:text-lg">
            Informasi lengkap dan terpercaya untuk bantu kamu memahami ibadah kurban lebih baik.
        </p>
    </div>

    <div class="max-w-3xl mx-auto rounded-xl overflow-hidden shadow-sm bg-gray-50">
        @php
            $faqs = [
                ['q' => 'Apa itu kurban digital?', 'a' => 'Kurban digital adalah sistem kurban yang dilakukan secara online melalui aplikasi. Proses penyembelihan tetap dilakukan sesuai syariat.'],
                ['q' => 'Apakah hewan kurban dicek kesehatannya?', 'a' => 'Ya, semua hewan kurban telah melalui proses pemeriksaan kesehatan oleh tim medis berlisensi.'],
                ['q' => 'Bagaimana saya tahu hewan saya disembelih?', 'a' => 'Kamu akan menerima dokumentasi dan laporan penyembelihan langsung dari sistem kami.'],
                ['q' => 'Kapan kurban dilaksanakan?', 'a' => 'Penyembelihan dilakukan pada hari-hari tasyrik sesuai ketentuan syariat Islam.']
            ];
        @endphp

        @foreach ($faqs as $index => $item)
            <div class="border-b last:border-b-0 bg-white hover:bg-gray-50 transition">
                <button type="button"
                        class="w-full flex justify-between items-center px-6 py-5 text-left accordion-header"
                        data-index="{{ $index }}">
                    <div class="text-base font-semibold text-gray-800">
                        {{ $item['q'] }}
                    </div>
                    <div class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-200 text-gray-700 accordion-toggle-icon transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path class="plus-icon" stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            <path class="minus-icon hidden" stroke-linecap="round" stroke-linejoin="round" d="M6 12h12" />
                        </svg>
                    </div>
                </button>
                <div class="accordion-content hidden px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                    {{ $item['a'] }}
                </div>
            </div>
        @endforeach
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const headers = document.querySelectorAll('.accordion-header');

        headers.forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                const icon = header.querySelector('.accordion-toggle-icon');
                const plus = icon.querySelector('.plus-icon');
                const minus = icon.querySelector('.minus-icon');

                const isOpen = !content.classList.contains('hidden');

                // Close all
                document.querySelectorAll('.accordion-content').forEach(c => c.classList.add('hidden'));
                document.querySelectorAll('.plus-icon').forEach(i => i.classList.remove('hidden'));
                document.querySelectorAll('.minus-icon').forEach(i => i.classList.add('hidden'));

                // Open current if not already
                if (!isOpen) {
                    content.classList.remove('hidden');
                    plus.classList.add('hidden');
                    minus.classList.remove('hidden');
                }
            });
        });
    });
</script>
