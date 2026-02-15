<div>
    <x-admin.feature-card title="Laporan Pemeriksaan Kehamilan" subtitle="Lihat riwayat pemeriksaan kehamilan ternak">
        <x-slot:actions>
            {{-- Actions if needed --}}
        </x-slot:actions>

        @include('livewire.reports.care-livestock.pregnancy-check-report.partials.filter')

        @include('livewire.reports.care-livestock.pregnancy-check-report.partials.table')

    </x-admin.feature-card>
</div>