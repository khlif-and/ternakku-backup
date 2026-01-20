<aside
    x-data="{
        sidebarCollapsed: false,
        dataAwalOpen: {{ request()->is('care-livestock/*/pens*', 'care-livestock/*/livestock-reception*', 'care-livestock/*/livestock-sale-weight*', 'care-livestock/*/livestock-death*', 'care-livestock/*/dashboard*') ? 'true' : 'true' }},
        persediaanOpen: {{ request()->is('care-livestock/*/feeding*', 'care-livestock/*/treatment*', 'care-livestock/*/milk*', 'care-livestock/*/sales-livestock*') ? 'true' : 'false' }},
        aktivitasOpen: {{ request()->is('care-livestock/*/feed-medicine*', 'care-livestock/*/mutation*', 'care-livestock/*/artificial*', 'care-livestock/*/natural*', 'care-livestock/*/pregnant*', 'care-livestock/*/birth*', 'care-livestock/*/sales-order*', 'care-livestock/*/customer*') ? 'true' : 'false' }},
        laporanAktivitasOpen: {{ request()->is('care-livestock/*/report*') ? 'true' : 'false' }},

        pelengkapInnerOpen: false,
        pakanInnerOpen: {{ request()->is('care-livestock/*/feeding*') ? 'true' : 'false' }},
        perawatanInnerOpen: {{ request()->is('care-livestock/*/treatment*') ? 'true' : 'false' }},
        produksiInnerOpen: {{ request()->is('care-livestock/*/milk-production*') ? 'true' : 'false' }},
        analisisInnerOpen: {{ request()->is('care-livestock/*/milk-analysis*') ? 'true' : 'false' }},
        inseminasiInnerOpen: {{ request()->is('care-livestock/*/artificial*', 'care-livestock/*/natural*') ? 'true' : 'false' }}
    }"
    :class="{ 'closed': sidebarCollapsed }"
    class="sidebar text-white relative z-30"
>

    {{-- HEADER --}}
    @include('layouts.care_livestock.part._header')

    <div class="border-t border-white/20 mx-4 my-3"></div>

    <nav class="px-2 text-sm">
        <ul class="space-y-5"><!-- JARAK DIPERJAUH! -->

            {{-- DATA AWAL --}}
            @include('layouts.care_livestock.part._data_awal')

            {{-- PERSEDIAAN --}}
            @include('layouts.care_livestock.part._persediaan')

            {{-- AKTIVITAS --}}
            @include('layouts.care_livestock.part._aktivitas')

            {{-- LAPORAN AKTIVITAS --}}
            @include('layouts.care_livestock.part._laporan_aktivitas')

        </ul>
    </nav>

    {{-- TOGGLE BUTTON --}}
    @include('layouts.care_livestock.part._toggle_button')

</aside>
