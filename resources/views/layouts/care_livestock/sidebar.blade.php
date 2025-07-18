<aside :class="{ 'closed': sidebarCollapsed }" class="sidebar text-white relative z-30">
    <div class="px-4 pt-6 pb-2 text-center">
        <a href="{{ route('admin.care-livestock.dashboard', ['farm_id' => $farm->id]) }}"
            class="flex justify-center w-full">
            <span
                class="font-bold text-white text-base font-[Oleo_Script,cursive] navbar-brand tracking-wide sidebar-label text-center block"
                style="font-family:'Oleo Script',cursive;">
                {{ $farm->name ?? 'Your Farm' }}
            </span>
        </a>
    </div>

    <div class="border-t border-white/20 mx-4 my-3"></div>

    <nav class="px-2 text-sm">
        <ul class="space-y-1">
            <li>
                <button @click="submenuOpen = !submenuOpen" type="button"
                    class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
                    <span class="sidebar-label">Pelihara Ternak</span>
                    <svg :class="{ 'rotate-180': submenuOpen }" class="w-4 h-4 arrow-icon" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="submenuOpen" x-transition
                    class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-1">
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

                    <a href="{{ route('admin.care-livestock.feed-medicine-purchase.index', $farm->id) }}"
                        class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                        Pembelian Pakan / Obat
                    </a>

                    <a href="{{ route('admin.care-livestock.milk-production-global.index', $farm->id) }}"
                        class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                        Produksi Susu Global
                    </a>

                    <a href="{{ route('admin.care-livestock.milk-analysis-global.index', $farm->id) }}"
                        class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                        Analisis Susu Global
                    </a>

                </div>
            </li>
        </ul>
    </nav>

    <div class="absolute bottom-4 w-full text-center">
        <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="w-9 h-9 mx-auto rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center">
            <svg class="w-5 h-5 text-white arrow-icon" :class="{ 'rotate-180': sidebarCollapsed }" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>
</aside>
