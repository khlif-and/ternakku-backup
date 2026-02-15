<div>
    <x-admin.feature-card title="Laporan Kelahiran" subtitle="Lihat riwayat kelahiran ternak">
        <x-slot:actions>
            {{-- Actions if needed --}}
        </x-slot:actions>

        @include('livewire.reports.care-livestock.birth-report.partials.filter')

        @include('livewire.reports.care-livestock.birth-report.partials.table')

    </x-admin.feature-card>
</div>