<li>
    <button @click="laporanOpen = !laporanOpen" type="button"
        class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
        <span class="sidebar-label">Laporan</span>
        <svg :class="{ 'rotate-180': laporanOpen }" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="laporanOpen" x-transition class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-1">

        <a href="{{ route('admin.care-livestock.qurban-population-report.index', ['farm_id' => session('selected_farm') ?? 1]) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Laporan Populasi
        </a>

        <x-sidebar.menu-link :href="url('qurban/report/sales-order')"
            :active="request()->is('qurban/report/sales-order*')" label="Laporan Penjualan Qurban" />
        <x-sidebar.menu-link :href="url('qurban/report/sales-livestock')"
            :active="request()->is('qurban/report/sales-livestock*')" label="Laporan Penjualan Ternak Qurban" />
        <x-sidebar.menu-link :href="url('qurban/report/payment')" :active="request()->is('qurban/report/payment*')"
            label="Laporan Penerimaan Pembayaran" />

        <x-sidebar.menu-link :href="route('qurban-cancelation-report.index')"
            :active="request()->routeIs('qurban-cancelation-report.*')" label="Laporan Pembatalan Qurban" />

        <x-sidebar.menu-link :href="route('qurban-delivery-order-report.index')"
            :active="request()->routeIs('qurban-delivery-order-report.*')" label="Laporan Surat Jalan" />

        <x-sidebar.menu-link :href="route('qurban-delivery-report.index')"
            :active="request()->routeIs('qurban-delivery-report.*')" label="Laporan Pengiriman Hewan" />

        <a href="{{ route('qurban-detailed-delivery-report.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm {{ request()->routeIs('qurban-detailed-delivery-report.*') ? 'bg-gray-100 font-semibold' : '' }}">
            Daftar Pengantaran Hewan Kurban
        </a>

        <a href="{{ route('qurban-reception-report.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm {{ request()->routeIs('qurban-reception-report.*') ? 'bg-gray-100 font-semibold' : '' }}">
            Daftar Penerimaan Hewan Kurban
        </a>

    </div>
</li>