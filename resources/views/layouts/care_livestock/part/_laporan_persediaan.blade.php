<x-sidebar.menu-group name="laporanPersediaanOpen" label="Laporan Persediaan">
    <x-sidebar.menu-link :href="route('admin.care-livestock.feeding-colony-supply-report.index', ['farm_id' => $farm->id])" label="Laporan Pakan Koloni" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.feeding-individu-supply-report.index', ['farm_id' => $farm->id])" label="Laporan Pakan Individu" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.treatment-colony-report.index', ['farm_id' => $farm->id])"
        label="Laporan Perawatan Koloni" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.treatment-individu-report.index', ['farm_id' => $farm->id])"
        label="Laporan Perawatan Individu" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.milk-production-global-report.index', ['farm_id' => $farm->id])" label="Laporan Produksi Susu Global" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.milk-production-individu-report.index', ['farm_id' => $farm->id])" label="Laporan Produksi Susu Individu" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.milk-analysis-global-report.index', ['farm_id' => $farm->id])" label="Laporan Analisis Susu Global" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.milk-analysis-individu-report.index', ['farm_id' => $farm->id])" label="Laporan Analisis Susu Individu" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.qurban-sales-livestock-report.index', ['farm_id' => $farm->id])" label="Laporan Penjualan Qurban" />
</x-sidebar.menu-group>