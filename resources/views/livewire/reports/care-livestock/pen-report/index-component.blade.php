<div>
    <x-alert.session />

    @include('livewire.reports.care-livestock.pen-report.partials.filter')

    @if ($showReport && $pen)
        @include('livewire.reports.care-livestock.pen-report.partials.statistics')

        @include('livewire.reports.care-livestock.pen-report.partials.pen-info')

        <div x-data="{ activeTab: 'livestock' }" class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="flex border-b overflow-x-auto">
                <button @click="activeTab = 'livestock'" :class="activeTab === 'livestock' ? 'bg-emerald-50 text-emerald-700 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-medium transition whitespace-nowrap">
                    Daftar Ternak ({{ count($livestocks) }})
                </button>
                <button @click="activeTab = 'feeding'" :class="activeTab === 'feeding' ? 'bg-emerald-50 text-emerald-700 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-medium transition whitespace-nowrap">
                    Riwayat Pakan ({{ count($feedingHistory) }})
                </button>
                <button @click="activeTab = 'treatment'" :class="activeTab === 'treatment' ? 'bg-emerald-50 text-emerald-700 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-medium transition whitespace-nowrap">
                    Riwayat Pengobatan ({{ count($treatmentHistory) }})
                </button>
                <button @click="activeTab = 'milk'" :class="activeTab === 'milk' ? 'bg-emerald-50 text-emerald-700 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-medium transition whitespace-nowrap">
                    Produksi Susu ({{ count($milkProduction) }})
                </button>
            </div>

            @include('livewire.reports.care-livestock.pen-report.partials.tab-livestock')
            @include('livewire.reports.care-livestock.pen-report.partials.tab-feeding')
            @include('livewire.reports.care-livestock.pen-report.partials.tab-treatment')
            @include('livewire.reports.care-livestock.pen-report.partials.tab-milk')
        </div>
    @endif
</div>
