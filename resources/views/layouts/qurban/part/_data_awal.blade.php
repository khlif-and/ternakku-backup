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

        <a href="{{ route('qurban.farm.user-list') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Data Pengguna
        </a>

        <a href="{{ route('admin.care-livestock.customer.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Data Pelanggan & Alamat Kirim
        </a>

        <a href="{{ route('qurban.fleet.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Data Armada
        </a>

        <a href="{{ route('qurban.driver.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Data Pengemudi
        </a>

    </div>
</li>
