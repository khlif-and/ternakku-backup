@section('title', 'Laporan Produksi Susu Individu')

<div>
    <x-admin.feature-card title="Laporan Produksi Susu Individu" :breadcrumbs="[
        ['label' => 'Care Livestock', 'route' => 'admin.care-livestock.index'],
        ['label' => 'Laporan', 'route' => ''],
        ['label' => 'Produksi Susu Individu', 'route' => ''],
    ]">

        <div class="space-y-6">
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
                            Laporan ini menampilkan rekapitulasi produksi susu harian per ekor ternak.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.care-livestock.milk-production-individu-report.export-pdf', ['farm_id' => $farm->id] + request()->query()) }}"
                    target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export PDF
                </a>
            </div>

            @include('livewire.reports.care-livestock.milk-production-individu-report.partials.filter')

            @include('livewire.reports.care-livestock.milk-production-individu-report.partials.statistics')

            @include('livewire.reports.care-livestock.milk-production-individu-report.partials.table')

        </div>
    </x-admin.feature-card>
</div>