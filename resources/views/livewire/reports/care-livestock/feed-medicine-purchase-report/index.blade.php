<div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
    <div class="mb-6">
        <h3 class="font-bold text-2xl text-gray-800">Laporan Pembelian Pakan & Obat</h3>
        <p class="text-gray-500 mt-1">Laporan lengkap riwayat pembelian pakan, obat, dan kebutuhan ternak lainnya.</p>
    </div>

    <x-alert.session />

    @include('livewire.reports.care-livestock.feed-medicine-purchase-report.partials.filter')

    @if ($showReport)
        @include('livewire.reports.care-livestock.feed-medicine-purchase-report.partials.statistics')
        @include('livewire.reports.care-livestock.feed-medicine-purchase-report.partials.table')
    @endif
</div>
