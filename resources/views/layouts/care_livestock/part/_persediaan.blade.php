<li>
    <button @click="persediaanOpen = !persediaanOpen" type="button"
        class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
        <span class="sidebar-label">Persediaan</span>
        <svg :class="{ 'rotate-180': persediaanOpen }" class="w-4 h-4" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="persediaanOpen" x-transition
        class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-2">

        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2">
            Feeding & Treatment
        </p>

        <button @click="pakanInnerOpen = !pakanInnerOpen"
            class="w-full flex items-center justify-between px-3 py-1 text-sm hover:bg-gray-100 rounded">
            <span>Pemberian Pakan</span>
            <svg :class="{ 'rotate-180': pakanInnerOpen }" class="w-4 h-4" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="pakanInnerOpen" class="ml-4 space-y-1" x-transition>
            <a href="{{ route('admin.care-livestock.feeding-colony.index', $farm->id) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">Pemberian Pakan Koloni</a>

            <a href="{{ route('admin.care-livestock.feeding-individu.index', $farm->id) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">Pemberian Pakan Individu</a>
        </div>

        <button @click="perawatanInnerOpen = !perawatanInnerOpen"
            class="w-full flex items-center justify-between px-3 py-1 text-sm hover:bg-gray-100 rounded">
            <span>Perawatan Pengobatan</span>
            <svg :class="{ 'rotate-180': perawatanInnerOpen }" class="w-4 h-4" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="perawatanInnerOpen" class="ml-4 space-y-1" x-transition>
            <a href="{{ route('admin.care-livestock.treatment-colony.index', $farm->id) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">Perawatan Koloni</a>

            <a href="{{ route('admin.care-livestock.treatment-individu.index', $farm->id) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">Perawatan Individu</a>
        </div>

        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2">
            Produksi & Analisis Susu Ternak
        </p>

        <button @click="produksiInnerOpen = !produksiInnerOpen"
            class="w-full flex items-center justify-between px-3 py-1 text-sm hover:bg-gray-100 rounded">
            <span>Produksi Susu</span>
            <svg :class="{ 'rotate-180': produksiInnerOpen }" class="w-4 h-4" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="produksiInnerOpen" class="ml-4 space-y-1" x-transition>
            <a href="{{ route('admin.care-livestock.milk-production-global.index', $farm->id) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">Produksi Susu Global</a>

            <a href="{{ route('admin.care-livestock.milk-production-individu.index', $farm->id) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">Produksi Susu Individu</a>
        </div>

        <button @click="analisisInnerOpen = !analisisInnerOpen"
            class="w-full flex items-center justify-between px-3 py-1 text-sm hover:bg-gray-100 rounded">
            <span>Analisis Susu</span>
            <svg :class="{ 'rotate-180': analisisInnerOpen }" class="w-4 h-4" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="analisisInnerOpen" class="ml-4 space-y-1" x-transition>
            <a href="{{ route('admin.care-livestock.milk-analysis-global.index', $farm->id) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">Analisis Susu Global</a>

            <a href="{{ route('admin.care-livestock.milk-analysis-individu.index', $farm->id) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">Analisis Susu Individu</a>
        </div>

        <a href="{{ route('admin.care-livestock.sales-livestock.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Penjualan Ternak
        </a>

    </div>
</li>
