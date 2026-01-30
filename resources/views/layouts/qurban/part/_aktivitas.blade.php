<li>
    <button @click="aktivitasOpen = !aktivitasOpen" type="button"
        class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
        <span class="sidebar-label">Aktivitas</span>
        <svg :class="{ 'rotate-180': aktivitasOpen }" class="w-4 h-4" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="aktivitasOpen" x-transition
        class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-2">

        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2">
            Penjualan & Pembayaran
        </p>

        <a href="{{ route('admin.care-livestock.reweight.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            ReWeight / Timbang Ulang
        </a>

        <a href="{{ route('admin.care-livestock.sales-order.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Sales Order Kurban
        </a>

        <a href="{{ route('admin.care-livestock.sales-livestock.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Penjualan Ternak Kurban
        </a>

        <a href="{{ route('admin.qurban.payment.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Pembayaran
        </a>

        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2 pt-2">
            Pengiriman
        </p>

        <a href="{{ route('qurban.livestock-delivery-note.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Surat Jalan Ternak Kurban
        </a>

        <a href="{{ route('qurban.delivery.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Pengiriman Ternak Kurban
        </a>

        <a href="{{ route('qurban.fleet-tracking.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Pelacakan Armada
        </a>

        <a href="{{ route('qurban.delivery-order-data.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Surat Jalan Qurban
        </a>

        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2 pt-2">
            Pembatalan
        </p>

        <a href="{{ route('qurban.cancelation.index') }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Pembatalan Kurban
        </a>

    </div>
</li>
