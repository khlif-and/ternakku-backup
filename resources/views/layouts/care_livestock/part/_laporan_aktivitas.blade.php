<x-sidebar.menu-group name="laporanAktivitasOpen" label="Laporan Aktivitas">
    <x-sidebar.menu-link :href="route('admin.care-livestock.pen-report.index', ['farm_id' => $farm->id])"
        label="Laporan Kandang" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.mutation-individu-report.index', ['farm_id' => $farm->id])"
        label="Laporan Mutasi Individu" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.artificial-inseminasi-report.index', ['farm_id' => $farm->id])" label="Laporan Artificial Inseminasi" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.natural-inseminasi-report.index', ['farm_id' => $farm->id])"
        label="Laporan Natural Inseminasi" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.feed-medicine-purchase-report.index', ['farm_id' => $farm->id])" label="Laporan Pembelian Pakan & Obat" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.reweight-report.index', ['farm_id' => $farm->id])"
        label="Laporan Timbang Ulang" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.pregnancy-check-report.index', ['farm_id' => $farm->id])"
        label="Laporan Cek Kehamilan" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.birth-report.index', ['farm_id' => $farm->id])"
        label="Laporan Kelahiran" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.sales-order-report.index', ['farm_id' => $farm->id])"
        label="Laporan Sales Order" />

    <x-sidebar.menu-link :href="route('admin.care-livestock.customer-report.index', ['farm_id' => $farm->id])"
        label="Laporan Pelanggan" />


</x-sidebar.menu-group>