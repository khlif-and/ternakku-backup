<div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
    <div class="mb-6">
        <h3 class="font-bold text-2xl text-gray-800">Laporan Mutasi Individu</h3>
        <p class="text-gray-500 mt-1">Laporan lengkap riwayat perpindahan ternak antar kandang.</p>
    </div>

    <x-alert.session />

    @include('livewire.reports.care-livestock.mutation-individu-report.partials.filter')

    @if ($showReport)
        @include('livewire.reports.care-livestock.mutation-individu-report.partials.statistics')
        @include('livewire.reports.care-livestock.mutation-individu-report.partials.table')
    @endif
</div>
