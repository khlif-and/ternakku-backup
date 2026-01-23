<x-sidebar.menu-group name="dataAwalOpen" label="Data Awal">
    <x-sidebar.menu-link :href="route('admin.care-livestock.pens.index', $farm->id)" label="Tambah Kandang" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.livestock-reception.index', $farm->id)" label="Registrasi Ternak" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.livestock-sale-weight.index', $farm->id)" label="Penjualan Ternak" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.livestock-death.index', $farm->id)" label="Kematian Ternak" />
</x-sidebar.menu-group>
