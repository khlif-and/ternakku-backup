<x-sidebar.menu-group name="pengirimanOpen" label="Pengiriman">
    <x-sidebar.menu-link :href="route('qurban.delivery-order-data.index')" label="Data DO" />
    <x-sidebar.menu-link :href="route('qurban.livestock-delivery-note.index')" label="Surat Jalan" />
    <x-sidebar.menu-link :href="route('admin.qurban.qurban_delivery.index')" label="Pengiriman" />
    <x-sidebar.menu-link :href="route('qurban.fleet-tracking.index')" label="Tracking Armada" />
</x-sidebar.menu-group>