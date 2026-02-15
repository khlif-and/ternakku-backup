<div>
    <x-admin.feature-card title="Laporan Customer" subtitle="Daftar customer Qurban">
        <x-slot:actions>
            {{-- Actions if needed --}}
        </x-slot:actions>

        @include('livewire.reports.care-livestock.customer-report.partials.filter')

        @include('livewire.reports.care-livestock.customer-report.partials.table')

    </x-admin.feature-card>
</div>