@section('title', 'Laporan Perawatan Individu')

<div>
    <x-admin.feature-card title="Laporan Perawatan Individu" :breadcrumbs="[
        ['label' => 'Care Livestock', 'route' => 'admin.care-livestock.index'],
        ['label' => 'Laporan', 'route' => ''],
        ['label' => 'Perawatan Individu', 'route' => ''],
    ]">

        <div class="space-y-6">
            {{-- Alert Section --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Laporan ini menampilkan aktivitas perawatan (pengobatan/tindakan) pada ternak individu.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            @include('livewire.reports.care-livestock.treatment-individu-report.partials.filter')

            {{-- Statistics Cards --}}
            @include('livewire.reports.care-livestock.treatment-individu-report.partials.statistics')

            {{-- Data Table --}}
            @include('livewire.reports.care-livestock.treatment-individu-report.partials.table')

        </div>
    </x-admin.feature-card>
</div>