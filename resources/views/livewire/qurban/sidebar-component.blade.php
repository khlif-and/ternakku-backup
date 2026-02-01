<aside class="sidebar text-white relative z-30 {{ $sidebarCollapsed ? 'closed' : '' }}" wire:key="qurban-sidebar">
    <div class="px-4 pt-6 pb-2 text-center">
        <a href="{{ route('qurban.dashboard') }}" class="flex justify-center w-full">
            <span
                class="font-bold text-white text-base font-[Oleo_Script,cursive] tracking-wide sidebar-label text-center block"
                style="font-family:'Oleo Script',cursive;">
                {{ $farm->name ?? 'Qurban Farm' }}
            </span>
        </a>
    </div>

    <div class="border-t border-white/20 mx-4 my-3"></div>

    <nav class="px-2 text-sm">
        <ul class="space-y-5">

            <li>
                <button wire:click="toggleDataAwal" type="button"
                    class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
                    <span class="sidebar-label">Data Awal</span>
                    <svg class="w-4 h-4 transition-transform {{ $dataAwalOpen ? 'rotate-180' : '' }}" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                @if($dataAwalOpen)
                    <div class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-1">
                        <a href="{{ route('qurban.farm.user-list') }}"
                            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                            Data Pengguna
                        </a>
                        <a href="{{ route('qurban.customer.index') }}"
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
                @endif
            </li>

            <li>
                <button wire:click="toggleAktivitas" type="button"
                    class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
                    <span class="sidebar-label">Aktivitas</span>
                    <svg class="w-4 h-4 transition-transform {{ $aktivitasOpen ? 'rotate-180' : '' }}" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                @if($aktivitasOpen)
                    <div class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-2">
                        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2">
                            Penjualan & Pembayaran
                        </p>
                        <a href="{{ route('qurban.reweight.index') }}"
                            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                            ReWeight / Timbang Ulang
                        </a>
                        <a href="{{ route('qurban.sales-order.index') }}"
                            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                            Sales Order Kurban
                        </a>
                        <a href="{{ url('qurban/sales-livestock') }}"
                            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                            Penjualan Ternak Kurban
                        </a>
                        <a href="{{ route('qurban.payment.index') }}"
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
                        <a href="{{ route('admin.qurban.qurban_delivery.index') }}"
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
                            Cancelation Qurban
                        </a>
                    </div>
                @endif
            </li>

            <li>
                <button wire:click="toggleLaporan" type="button"
                    class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
                    <span class="sidebar-label">Laporan</span>
                    <svg class="w-4 h-4 transition-transform {{ $laporanOpen ? 'rotate-180' : '' }}" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                @if($laporanOpen)
                    <div class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-1">
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
                @endif
            </li>

        </ul>
    </nav>

    <div class="absolute bottom-4 w-full text-center">
        <button wire:click="toggleSidebar"
            class="w-9 h-9 mx-auto rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center">
            <svg class="w-5 h-5 text-white arrow-icon transition-transform {{ $sidebarCollapsed ? 'rotate-180' : '' }}"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>

</aside>