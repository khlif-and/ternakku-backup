<div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
    <div class="mb-6">
        <h3 class="font-bold text-2xl text-gray-800">Laporan Timbang Ulang</h3>
        <p class="text-gray-500 mt-1">Laporan riwayat penimbangan ulang ternak.</p>
    </div>

    <x-alert.session />

    @include('livewire.reports.care-livestock.reweight-report.partials.filter')

    @if ($showReport)
        @include('livewire.reports.care-livestock.reweight-report.partials.statistics')
        @include('livewire.reports.care-livestock.reweight-report.partials.table')
    @endif
</div>
