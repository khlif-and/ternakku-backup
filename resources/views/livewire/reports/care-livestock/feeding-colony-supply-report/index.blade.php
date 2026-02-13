@section('title', 'Laporan Persediaan Pakan Koloni')

<div>
    <x-admin.feature-card title="Laporan Persediaan Pakan Koloni" :breadcrumbs="[
        ['label' => 'Care Livestock', 'route' => 'admin.care-livestock.index'],
        ['label' => 'Laporan', 'route' => ''],
        ['label' => 'Persediaan Pakan Koloni', 'route' => ''],
    ]">

        <div class="space-y-6">
            {{-- Alert Section --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex justify-between items-center">
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
                                Laporan ini menampilkan penggunaan pakan pada koloni ternak berdasarkan catatan
                                pemberian
                                pakan harian.
                            </p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.care-livestock.feeding-colony-supply-report.export-pdf', [
    'farm_id' => $farm->id,
    'start_date' => $start_date,
    'end_date' => $end_date,
    'pen_id' => $pen_id
]) }}" target="_blank"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.586l4 4a1 1 0 01.586 1.414V19a2 2 0 01-2 2z" />
                            </svg>
                            Export PDF
                        </a>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            @include('livewire.reports.care-livestock.feeding-colony-supply-report.partials.filter')

            {{-- Statistics Cards --}}
            @include('livewire.reports.care-livestock.feeding-colony-supply-report.partials.statistics')

            {{-- Data Table --}}
            @include('livewire.reports.care-livestock.feeding-colony-supply-report.partials.table')

        </div>
    </x-admin.feature-card>
</div>