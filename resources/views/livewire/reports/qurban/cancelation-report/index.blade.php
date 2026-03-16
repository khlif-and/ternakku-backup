@section('title', 'Laporan Pembatalan Qurban')

<div>
    <x-admin.feature-card title="Laporan Pembatalan Qurban" :breadcrumbs="[
        ['label' => 'Qurban', 'route' => ''],
        ['label' => 'Laporan', 'route' => ''],
        ['label' => 'Pembatalan', 'route' => ''],
    ]">

        <div class="space-y-6">
            @include('livewire.reports.qurban.cancelation-report.partials.filter')

            @include('livewire.reports.qurban.cancelation-report.partials.statistics')

            @include('livewire.reports.qurban.cancelation-report.partials.table')

        </div>
    </x-admin.feature-card>
</div>