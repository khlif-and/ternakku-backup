<li>
    <button
        @click="laporanAktivitasOpen = !laporanAktivitasOpen"
        class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">

        <span class="sidebar-label">Laporan Aktivitas</span>

        <svg :class="{ 'rotate-180': laporanAktivitasOpen }"
            class="w-4 h-4" fill="none"
            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="laporanAktivitasOpen"
        x-transition
        class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-2">

        <a href="{{ route('admin.care-livestock.pen-report.index', ['farm_id' => $farm->id]) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Laporan Kandang
        </a>

        <a href="{{ route('admin.care-livestock.mutation-individu-report.index', ['farm_id' => $farm->id]) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Laporan Mutasi Individu
        </a>

        <a href="{{ route('admin.care-livestock.artificial-inseminasi-report.index', ['farm_id' => $farm->id]) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Laporan Artificial Inseminasi
        </a>

        <a href="{{ route('admin.care-livestock.natural-inseminasi-report.index', ['farm_id' => $farm->id]) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Laporan Natural Inseminasi
        </a>

    </div>
</li>
