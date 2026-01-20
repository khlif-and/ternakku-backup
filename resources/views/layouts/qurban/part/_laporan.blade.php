<li>
    <button @click="laporanOpen = !laporanOpen" type="button"
        class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
        <span class="sidebar-label">Laporan</span>
        <svg :class="{ 'rotate-180': laporanOpen }" class="w-4 h-4" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="laporanOpen" x-transition
        class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-1">

        <a href="{{ route('qurban.population-report.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Laporan Populasi
        </a>

        <a href="{{ url('qurban/report/sales-order') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Daftar Sales Order
        </a>

        <a href="{{ url('qurban/report/sales-livestock') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Daftar Penjualan Hewan Kurban
        </a>

        <a href="{{ url('qurban/report/payment') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Daftar Penerimaan Pembayaran
        </a>

        <a href="{{ url('qurban/report/cancelation') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Daftar Pembatalan Penjualan
        </a>

        <a href="{{ url('qurban/report/surat-jalan') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Daftar Surat Jalan
        </a>

        <a href="{{ url('qurban/report/delivery') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Daftar Pengiriman Hewan Kurban
        </a>

        <a href="{{ url('qurban/report/antar') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Daftar Pengantaran Hewan Kurban
        </a>

        <a href="{{ url('qurban/report/penerimaan') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Daftar Penerimaan Hewan Kurban
        </a>

    </div>
</li>
