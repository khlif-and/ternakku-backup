<div>
    <x-admin.feature-card title="Laporan Populasi Qurban" subtitle="Daftar ternak terdaftar Qurban">
        <x-slot:actions>
            {{-- Actions if needed --}}
        </x-slot:actions>

        @include('livewire.reports.care-livestock.qurban-population-report.partials.filter')

        @include('livewire.reports.care-livestock.qurban-population-report.partials.table')

    </x-admin.feature-card>
</div>