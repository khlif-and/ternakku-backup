<li>
    <button @click="aktivitasOpen = !aktivitasOpen"
        class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
        <span class="sidebar-label">Aktivitas</span>
        <svg :class="{ 'rotate-180': aktivitasOpen }" class="w-4 h-4" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="aktivitasOpen" x-transition
        class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-4">

        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2">
            Pembelian Pakan & Obat
        </p>

        <a href="{{ route('admin.care-livestock.feed-medicine-purchase.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Pembelian Pakan / Obat
        </a>

        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2">
            Mutasi & Reweight
        </p>

        <a href="{{ route('admin.care-livestock.mutation-individu.index', ['farm_id' => $farm->id]) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Mutasi Ternak
        </a>

        <a href="#" class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Reweight / Timbang Ulang
        </a>

        <button @click="inseminasiInnerOpen = !inseminasiInnerOpen"
            class="w-full flex items-center justify-between px-3 py-1 text-sm hover:bg-gray-100 rounded">
            <span>Kelahiran Ternak</span>
            <svg :class="{ 'rotate-180': inseminasiInnerOpen }" class="w-4 h-4" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="inseminasiInnerOpen" class="ml-4 space-y-1" x-transition>
            <a href="{{ route('admin.care_livestock.artificial_inseminasi.index', ['farm_id' => $farm->id]) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                Inseminasi Buatan
            </a>

            <a href="{{ route('admin.care_livestock.natural_insemination.index', ['farm_id' => $farm->id]) }}"
                class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
                Inseminasi Alami
            </a>
        </div>

        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2">
            Kebuntingan & Kelahiran
        </p>

        <a href="{{ route('admin.care_livestock.pregnant_check.index', ['farm_id' => $farm->id]) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Pregnant Check
        </a>

        <a href="{{ route('admin.care_livestock.livestock_birth.index', ['farm_id' => $farm->id]) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Livestock Birth
        </a>

        {{-- ===================== --}}
        {{-- PENJUALAN --}}
        {{-- ===================== --}}
        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide px-2">
            Penjualan
        </p>

        {{-- Sales Order --}}
        <a href="{{ route('admin.care-livestock.sales-order.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Sales Order
        </a>

        {{-- Customer --}}
        <a href="{{ route('admin.care-livestock.customer.index', $farm->id) }}"
            class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
            Customer
        </a>

    </div>
</li>
