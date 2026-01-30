<x-sidebar.menu-group name="transaksiOpen" label="Transaksi">
    <x-sidebar.menu-link :href="route('qurban.sales-order.index')" label="Sales Order" />
    <x-sidebar.menu-link :href="route('qurban.sales.index')" label="Penjualan" />
    <x-sidebar.menu-link :href="route('qurban.payment.index')" label="Pembayaran" />
    <x-sidebar.menu-link :href="route('qurban.cancelation.index')" label="Pembatalan" />
    <x-sidebar.menu-link :href="route('qurban.reweight.index')" label="Timbang Ulang" />
</x-sidebar.menu-group>
