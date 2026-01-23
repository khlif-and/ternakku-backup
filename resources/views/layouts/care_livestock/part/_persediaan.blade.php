<x-sidebar.menu-group name="persediaanOpen" label="Persediaan">
    <x-sidebar.menu-section label="Feeding & Treatment" />

    <x-sidebar.menu-submenu name="pakanInnerOpen" label="Pemberian Pakan">
        <x-sidebar.menu-link :href="route('admin.care-livestock.feeding-colony.index', $farm->id)" label="Pemberian Pakan Koloni" />
        <x-sidebar.menu-link :href="route('admin.care-livestock.feeding-individu.index', $farm->id)" label="Pemberian Pakan Individu" />
    </x-sidebar.menu-submenu>

    <x-sidebar.menu-submenu name="perawatanInnerOpen" label="Perawatan Pengobatan">
        <x-sidebar.menu-link :href="route('admin.care-livestock.treatment-colony.index', $farm->id)" label="Perawatan Koloni" />
        <x-sidebar.menu-link :href="route('admin.care-livestock.treatment-individu.index', $farm->id)" label="Perawatan Individu" />
    </x-sidebar.menu-submenu>

    <x-sidebar.menu-section label="Produksi & Analisis Susu Ternak" />

    <x-sidebar.menu-submenu name="produksiInnerOpen" label="Produksi Susu">
        <x-sidebar.menu-link :href="route('admin.care-livestock.milk-production-global.index', $farm->id)" label="Produksi Susu Global" />
        <x-sidebar.menu-link :href="route('admin.care-livestock.milk-production-individu.index', $farm->id)" label="Produksi Susu Individu" />
    </x-sidebar.menu-submenu>

    <x-sidebar.menu-submenu name="analisisInnerOpen" label="Analisis Susu">
        <x-sidebar.menu-link :href="route('admin.care-livestock.milk-analysis-global.index', $farm->id)" label="Analisis Susu Global" />
        <x-sidebar.menu-link :href="route('admin.care-livestock.milk-analysis-individu.index', $farm->id)" label="Analisis Susu Individu" />
    </x-sidebar.menu-submenu>

    <x-sidebar.menu-link :href="route('admin.care-livestock.sales-livestock.index', $farm->id)" label="Penjualan Ternak" />
</x-sidebar.menu-group>
