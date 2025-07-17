<aside class="sidebar min-h-screen flex flex-col transition-all duration-300 overflow-x-hidden hidden xl:flex">


    <div class="flex items-center justify-center px-4 pt-6 pb-2 bg-[#255F38]">
        <a href="{{ url('qurban/dashboard') }}" class="flex justify-center w-full">
            <span
                class="font-bold text-white text-lg font-[Oleo_Script,cursive] navbar-brand tracking-wide sidebar-label text-center block"
                style="font-family:'Oleo Script',cursive;">
                {{ $farm->name ?? 'halo' }}
            </span>
        </a>
    </div>
    <div class="sidebar-divider h-px bg-white/10 my-3 mx-4"></div>

    <nav class="flex-1 overflow-y-auto">
        <ul class="space-y-1 text-white">

            <li>
                <button type="button"
                    class="flex w-full items-center justify-between rounded-lg px-4 py-2 text-left font-semibold hover:bg-white/10 transition submenu-btn {{ Request::is('qurban/farm*') || Request::is('qurban/customer*') || Request::is('qurban/fleet*') || Request::is('qurban/driver*') ? 'bg-white/10' : '' }}"
                    data-target="submenu1">
                    <span class="sidebar-label">Data Awal</span>
                    <svg class="w-4 h-4 ml-2 transition-transform duration-200 arrow {{ Request::is('qurban/farm*') || Request::is('qurban/customer*') || Request::is('qurban/fleet*') || Request::is('qurban/driver*') ? 'rotate-180' : '' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="submenu-container transition-all duration-300 overflow-hidden {{ Request::is('qurban/farm*') || Request::is('qurban/customer*') || Request::is('qurban/fleet*') || Request::is('qurban/driver*') ? '' : 'max-h-0' }}"
                    id="submenu1">
                    <ul class="bg-white rounded-lg mt-2 p-2 space-y-1 shadow mx-3">
                        <li>
                            <a href="{{ url('qurban/farm/user-list') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">
                                Data Pengguna
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/customer') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">
                                Data Pelanggan & Alamat Kirim
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/fleet') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">
                                Data Armada
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/driver') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">
                                Data Pengemudi
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- AKTIVITAS -->
            <li>
                <button type="button"
                    class="flex w-full items-center justify-between rounded-lg px-4 py-2 text-left font-semibold hover:bg-white/10 transition submenu-btn {{ Request::is('qurban/sales-order*') || Request::is('qurban/sales-livestock*') || Request::is('qurban/reweight*') || Request::is('qurban/payment*') || Request::is('qurban/delivery*') || Request::is('qurban/fleet-tracking*') || Request::is('qurban/livestock-delivery-note*') || Request::is('qurban/qurban-delivery-order-data*') ? 'bg-white/10' : '' }}"
                    data-target="submenu2">
                    <span class="sidebar-label">Aktivitas</span>
                    <svg class="w-4 h-4 ml-2 transition-transform duration-200 arrow {{ Request::is('qurban/sales-order*') || Request::is('qurban/sales-livestock*') || Request::is('qurban/reweight*') || Request::is('qurban/payment*') || Request::is('qurban/delivery*') || Request::is('qurban/fleet-tracking*') || Request::is('qurban/livestock-delivery-note*') || Request::is('qurban/qurban-delivery-order-data*') ? 'rotate-180' : '' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="submenu-container transition-all duration-300 overflow-hidden {{ Request::is('qurban/sales-order*') || Request::is('qurban/sales-livestock*') || Request::is('qurban/reweight*') || Request::is('qurban/payment*') || Request::is('qurban/delivery*') || Request::is('qurban/fleet-tracking*') || Request::is('qurban/livestock-delivery-note*') || Request::is('qurban/qurban-delivery-order-data*') ? '' : 'max-h-0' }}"
                    id="submenu2">
                    <ul class="bg-white rounded-lg mt-2 p-2 space-y-1 shadow mx-3">
                        <li>
                            <a href="{{ url('qurban/reweight') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">ReWeight
                                / Timbang Ulang</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/sales-order') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Sales
                                Order Kurban</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/sales-livestock') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Penjualan
                                Ternak Kurban</a>
                        </li>
                        <li>
                            <a href="#"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Penerimaan
                                Pembayaran</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/livestock-delivery-note') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Surat
                                Jalan Ternak Kurban</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/delivery') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Pengiriman
                                Ternak Kurban</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/fleet-tracking') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Pelacakan
                                Armada</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/payment') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Pembayaran</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/qurban-delivery-order-data') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Surat
                                Jalan Qurban</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/cancelation-qurban') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Cancelation
                                Qurban</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- LAPORAN -->
            <li>
                <button type="button"
                    class="flex w-full items-center justify-between rounded-lg px-4 py-2 text-left font-semibold hover:bg-white/10 transition submenu-btn {{ Request::is('qurban/report*') ? 'bg-white/10' : '' }}"
                    data-target="submenu3">
                    <span class="sidebar-label">Laporan</span>
                    <svg class="w-4 h-4 ml-2 transition-transform duration-200 arrow {{ Request::is('qurban/report*') ? 'rotate-180' : '' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="submenu-container transition-all duration-300 overflow-hidden {{ Request::is('qurban/report*') ? '' : 'max-h-0' }}"
                    id="submenu3">
                    <ul class="bg-white rounded-lg mt-2 p-2 space-y-1 shadow mx-3">
                        <li>
                            <a href="{{ url('qurban/population-report') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Laporan
                                Populasi</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/report/sales-order') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Daftar
                                Sales Order</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/report/sales-livestock') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Daftar
                                Penjualan Hewan Kurban</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/report/payment') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Daftar
                                Penerimaan Pembayaran</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/report/cancelation') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Daftar
                                Pembatalan Penjualan</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/report/surat-jalan') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Daftar
                                Surat Jalan</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/report/delivery') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Daftar
                                Pengiriman Hewan Kurban</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/report/antar') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Daftar
                                Pengantaran Hewan Kurban</a>
                        </li>
                        <li>
                            <a href="{{ url('qurban/report/penerimaan') }}"
                                class="block px-3 py-2 text-sm text-gray-900 rounded-md hover:bg-orange-50 font-medium transition">Daftar
                                Penerimaan Hewan Kurban</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <div class="sidebar-divider h-px bg-white/10 my-3 mx-4"></div>
        <div class="flex justify-center mt-8 mb-4">
            <button id="closeSidebarBtn2"
                class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition group"
                aria-label="Collapse Sidebar">
                <svg class="w-5 h-5 text-white group-hover:text-orange-500 transition-transform duration-300 sidebar-toggle-arrow"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>
    </nav>
</aside>
