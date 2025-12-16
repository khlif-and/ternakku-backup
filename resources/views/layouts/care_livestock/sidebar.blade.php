<aside
    x-data="{
        sidebarCollapsed: false,
        dataAwalOpen: false,
        persediaanOpen: false,
        aktivitasOpen: false,
        laporanAktivitasOpen: false,

        pelengkapInnerOpen: false,
        pakanInnerOpen: false,
        perawatanInnerOpen: false,
        produksiInnerOpen: false,
        analisisInnerOpen: false,
        inseminasiInnerOpen: false
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
