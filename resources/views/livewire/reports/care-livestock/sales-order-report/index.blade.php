<div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
    <div class="mb-6">
        <h3 class="font-bold text-2xl text-gray-800">Laporan Sales Order (Qurban)</h3>
        <p class="text-gray-500 mt-1">Laporan riwayat penjualan hewan qurban.</p>
    </div>

    <x-alert.session />

    @include('livewire.reports.care-livestock.sales-order-report.partials.filter')

    @if ($showReport)
        @include('livewire.reports.care-livestock.sales-order-report.partials.statistics')
        @include('livewire.reports.care-livestock.sales-order-report.partials.table')
    @endif
</div>
