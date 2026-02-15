<div>
    <x-admin.feature-card title="Laporan Sales Order" subtitle="Lihat riwayat transaksi penjualan">
        <x-slot:actions>
            {{-- Actions if needed --}}
        </x-slot:actions>

        @include('livewire.reports.care-livestock.sales-order-report.partials.filter')

        @include('livewire.reports.care-livestock.sales-order-report.partials.table')

    </x-admin.feature-card>
</div>