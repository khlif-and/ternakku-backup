<li>
    <button @click="dataAwalOpen = !dataAwalOpen" type="button"
        class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
        <span class="sidebar-label">Data Awal</span>
        <svg :class="{ 'rotate-180': dataAwalOpen }" class="w-4 h-4" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="dataAwalOpen" x-transition
        class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-1">

        {{-- 👇 LIST MENU langsung tampil, TANPA Data Pelengkap dan TANPA sub-dropdown --}}

        <a href="{{ route('admin.care-livestock.pens.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Tambah Kandang
        </a>

        <a href="{{ route('admin.care-livestock.livestock-reception.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Registrasi Ternak
        </a>

        <a href="{{ route('admin.care-livestock.livestock-sale-weight.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Penjualan Ternak
        </a>

        <a href="{{ route('admin.care-livestock.livestock-death.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Kematian Ternak
        </a>

    </div>
</li>
