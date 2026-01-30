<x-sidebar.menu-group name="aktivitasOpen" label="Aktivitas">
    <x-sidebar.menu-section label="Pembelian Pakan & Obat" />

    <x-sidebar.menu-link :href="route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)" label="Pembelian Pakan / Obat" />

    <x-sidebar.menu-section label="Mutasi & Reweight" />

    <x-sidebar.menu-link :href="route('admin.care-livestock.mutation-individu.index', ['farm_id' => $farm->id])" label="Mutasi Ternak" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.reweight.index', $farm->id)" label="Reweight / Timbang Ulang" />


    <x-sidebar.menu-submenu name="inseminasiInnerOpen" label="Kelahiran Ternak">
        <x-sidebar.menu-link :href="route('admin.care-livestock.artificial-inseminasi.index', ['farm_id' => $farm->id])" label="Inseminasi Buatan" />
        <x-sidebar.menu-link :href="route('admin.care-livestock.natural-insemination.index', ['farm_id' => $farm->id])" label="Inseminasi Alami" />
    </x-sidebar.menu-submenu>

    <x-sidebar.menu-section label="Kebuntingan & Kelahiran" />

    <x-sidebar.menu-link :href="route('admin.care_livestock.pregnant_check.index', ['farm_id' => $farm->id])" label="Pregnant Check" />
    <x-sidebar.menu-link :href="route('admin.care_livestock.livestock_birth.index', ['farm_id' => $farm->id])" label="Livestock Birth" />

    <x-sidebar.menu-section label="Penjualan" />

    <x-sidebar.menu-link :href="route('admin.care-livestock.sales-order.index', $farm->id)" label="Sales Order" />
    <x-sidebar.menu-link :href="route('admin.care-livestock.customer.index', $farm->id)" label="Customer" />
</x-sidebar.menu-group>
