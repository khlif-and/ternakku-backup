@section('title', 'Laporan Surat Jalan Qurban')

<div>
    <x-admin.feature-card title="Laporan Surat Jalan Qurban" :breadcrumbs="[
        ['label' => 'Qurban', 'route' => ''],
        ['label' => 'Laporan', 'route' => ''],
        ['label' => 'Surat Jalan', 'route' => ''],
    ]">

        <div class="space-y-6">
            @include('livewire.reports.qurban.delivery-order-report.partials.filter')

            @include('livewire.reports.qurban.delivery-order-report.partials.statistics')

            @include('livewire.reports.qurban.delivery-order-report.partials.table')

        </div>
    </x-admin.feature-card>
</div>