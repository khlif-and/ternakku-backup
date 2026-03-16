<li>
    <button @click="aktivitasOpen = !aktivitasOpen" type="button"
        class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
        <span class="sidebar-label">Aktivitas</span>
        <svg :class="{ 'rotate-180': aktivitasOpen }" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="aktivitasOpen" x-transition class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-2">

        @php
            extract(App\Helpers\web\FarmRoleResolver::resolve($farm->id));
        @endphp

        @if($isOwner || $isAdmin || $isMarketing)
            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2">
                Penjualan & Pembayaran
            </p>

            <a href="{{ route('shared.reweight.index', $farm->id) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                ReWeight / Timbang Ulang
            </a>

            <a href="{{ route('qurban.sales-order.index') }}" class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                Sales Order Kurban
            </a>

            <a href="{{ route('qurban.sales.index') }}" class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                Penjualan Ternak Kurban
            </a>

            @if($isOwner || $isAdmin)
                <a href="{{ route('admin.qurban.payment.index') }}" class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                    Pembayaran
                </a>
            @endif
        @endif

        @if($isOwner || $isAdmin || $isDriver)
            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2 pt-2">
                Pengiriman
            </p>

            @if($isOwner || $isAdmin)
                <a href="{{ route('qurban.livestock-delivery-note.index') }}"
                    class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                    Surat Jalan Ternak Kurban
                </a>

                <a href="{{ route('admin.qurban.qurban_delivery.index') }}"
                    class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                    Pengiriman Ternak Kurban
                </a>

                <a href="{{ route('admin.qurban.delivery_order_qurban.index') }}"
                    class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                    Data DO
                </a>
            @endif

            <a href="{{ route('qurban.fleet-tracking.index') }}" class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                Pelacakan Armada
            </a>

            <a href="{{ route('driver.delivery.index') }}" class="block hover:bg-gray-100 px-3 py-1 rounded text-sm {{ request()->is('driver/delivery*') ? 'bg-gray-100 font-semibold' : '' }}">
                Instruksi Pengiriman
            </a>
        @endif

        @if($isOwner || $isAdmin)
            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2 pt-2">
                Penerimaan
            </p>

            <a href="{{ route('qurban.livestock-reception.index') }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm {{ request()->routeIs('qurban.livestock-reception.*') ? 'bg-gray-100 font-semibold' : '' }}">
                Penerimaan Ternak Qurban
            </a>
        @endif

    </div>
</li>