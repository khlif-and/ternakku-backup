<x-sidebar.menu-group name="laporanAktivitasOpen" label="Laporan Aktivitas">
    <x-sidebar.menu-link :href="route('admin.care-livestock.pen-report.index', ['farm_id' => $farm->id])" label="Laporan Kandang" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.mutation-individu-report.index', ['farm_id' => $farm->id])" label="Laporan Mutasi Individu" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.artificial-inseminasi-report.index', ['farm_id' => $farm->id])" label="Laporan Artificial Inseminasi" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.natural-inseminasi-report.index', ['farm_id' => $farm->id])" label="Laporan Natural Inseminasi" />
</x-sidebar.menu-group>
