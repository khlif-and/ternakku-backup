<section class="w-full bg-gradient-to-br from-white to-gray-100 py-24 px-6">
    <div class="max-w-6xl mx-auto">
        <!-- Text Intro -->
        <div class="text-center mb-14">
            <p class="text-sm text-gray-400 uppercase tracking-wide mb-2">Layanan Kami</p>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug mb-4">
                Solusi Digital Terpadu<br class="hidden sm:inline" /> untuk Peternak Modern
            </h2>
            <p class="text-gray-600 text-base md:text-lg leading-relaxed">
                Dari pemantauan kesehatan hingga pencatatan harian, semua bisa dilakukan dengan lebih efisien dan
                transparan melalui aplikasi <span class="font-semibold text-green-600">Ternakku</span>.
            </p>
        </div>


        <!-- Custom Slider -->
        <div class="flex items-center justify-center gap-6 relative">
            <!-- Left Arrow -->
            <button onclick="slideCards(-1)"
                class="absolute left-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-white border border-gray-300 rounded-full shadow flex items-center justify-center z-10">
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div id="cardWrapper" class="flex items-center gap-6 min-h-[360px] transition-all duration-500">

                <!-- Card 1 -->
                <div
                    class="slider-card relative w-[120px] sm:w-[180px] md:w-[240px] lg:w-[300px] h-[320px] rounded-2xl overflow-hidden shadow-md transition-all duration-500 bg-white transform scale-90">
                    <img src="/images/interior1.jpg" class="w-full h-2/3 object-cover" alt="Interior 1">
                    <div class="p-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-1">Kavian</h4>
                        <p class="text-sm text-gray-500 leading-snug">Elegant minimalist,<br>Maximum Warmth</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div
                    class="slider-card relative w-[120px] sm:w-[180px] md:w-[240px] lg:w-[300px] h-[360px] rounded-2xl overflow-hidden shadow-xl transition-all duration-500 bg-white transform scale-100 -translate-y-2">
                    <img src="/images/interior2.jpg" class="w-full h-2/3 object-cover" alt="Interior 2">
                    <div class="p-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-1">Savana</h4>
                        <p class="text-sm text-gray-500 leading-snug">Modern calm,<br>Scandinavian Touch</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div
                    class="slider-card relative w-[120px] sm:w-[180px] md:w-[240px] lg:w-[300px] h-[320px] rounded-2xl overflow-hidden shadow-md transition-all duration-500 bg-white transform scale-90">
                    <img src="/images/interior3.jpg" class="w-full h-2/3 object-cover" alt="Interior 3">
                    <div class="p-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-1">Atara</h4>
                        <p class="text-sm text-gray-500 leading-snug">Timeless harmony,<br>Clean functionality</p>
                    </div>
                </div>
            </div>

            <!-- Right Arrow -->
            <button onclick="slideCards(1)"
                class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-white border border-gray-300 rounded-full shadow flex items-center justify-center z-10">
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</section>

<script>
    let activeIndex = 1;

    function updateSliderCards() {
        const cards = document.querySelectorAll('.slider-card');
        cards.forEach((card, index) => {
            card.classList.remove('scale-100', 'h-[360px]', 'shadow-xl', 'translate-y-[-0.5rem]');
            card.classList.add('scale-90', 'h-[320px]', 'shadow-md', 'translate-y-0');

            if (index === activeIndex) {
                card.classList.remove('scale-90', 'h-[320px]', 'shadow-md', 'translate-y-0');
                card.classList.add('scale-100', 'h-[360px]', 'shadow-xl', '-translate-y-2');
            }
        });
    }

    function slideCards(direction) {
        const totalCards = document.querySelectorAll('.slider-card').length;
        activeIndex = (activeIndex + direction + totalCards) % totalCards;
        updateSliderCards();
    }

    document.addEventListener('DOMContentLoaded', updateSliderCards);
</script>
