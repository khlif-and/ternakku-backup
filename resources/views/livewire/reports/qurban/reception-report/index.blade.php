@section('title', 'Laporan Penerimaan Hewan Qurban')

<div>
    <x-admin.feature-card title="Laporan Penerimaan Hewan Qurban" :breadcrumbs="[
        ['label' => 'Qurban', 'route' => ''],
        ['label' => 'Laporan', 'route' => ''],
        ['label' => 'Penerimaan Hewan', 'route' => ''],
    ]">
        <div class="space-y-6">
            @include('livewire.reports.qurban.reception-report.partials.filter')

            @if ($showReport)
                @include('livewire.reports.qurban.reception-report.partials.statistics')

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="font-semibold text-gray-700">Daftar Penerimaan Hewan Qurban</h3>

                        @if ($data->isNotEmpty())
                                        <a href="{{ route('qurban.report.reception.export', [
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'supplier' => $supplier,
                                'livestock_type_id' => $livestock_type_id,
                            ]) }}" target="_blank" class="btn-sm btn-primary flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Export PDF
                                        </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">No Transaksi</th>
                                    <th class="px-6 py-3">Supplier</th>
                                    <th class="px-6 py-3">Jumlah Ternak</th>
                                    <th class="px-6 py-3">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($data as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($item['transaction_date'])->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $item['transaction_number'] }}
                                        </td>
                                        <td class="px-6 py-4">{{ $item['supplier'] ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $item['total_livestock'] }} Ekor
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $item['notes'] ?? '-' }}</td>
                                    </tr>

                                    @if(count($item['livestock_items'] ?? []) > 0)
                                        <tr class="bg-gray-50/50">
                                            <td colspan="5" class="px-6 py-3">
                                                <div class="border rounded-lg overflow-hidden bg-white">
                                                    <table class="w-full text-xs">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-gray-600 font-semibold">Eartag
                                                                </th>
                                                                <th class="px-3 py-2 text-left text-gray-600 font-semibold">Jenis
                                                                </th>
                                                                <th class="px-3 py-2 text-left text-gray-600 font-semibold">Ras</th>
                                                                <th class="px-3 py-2 text-center text-gray-600 font-semibold">
                                                                    Kelamin</th>
                                                                <th class="px-3 py-2 text-center text-gray-600 font-semibold">Umur
                                                                </th>
                                                                <th class="px-3 py-2 text-center text-gray-600 font-semibold">Berat
                                                                    (Kg)</th>
                                                                <th class="px-3 py-2 text-right text-gray-600 font-semibold">Harga
                                                                    Qurban</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-100">
                                                            @foreach($item['livestock_items'] as $livestock)
                                                                <tr class="hover:bg-gray-50">
                                                                    <td class="px-3 py-2 font-medium text-gray-800">
                                                                        {{ $livestock['eartag_number'] }}
                                                                    </td>
                                                                    <td class="px-3 py-2 text-gray-600">
                                                                        {{ $livestock['livestock_type'] }}
                                                                    </td>
                                                                    <td class="px-3 py-2 text-gray-600">
                                                                        {{ $livestock['livestock_breed'] }}
                                                                    </td>
                                                                    <td class="px-3 py-2 text-center text-gray-600">
                                                                        {{ $livestock['livestock_sex'] }}
                                                                    </td>
                                                                    <td class="px-3 py-2 text-center text-gray-600">
                                                                        {{ $livestock['age_years'] ?? 0 }} Thn
                                                                        {{ $livestock['age_months'] ?? 0 }} Bln
                                                                    </td>
                                                                    <td class="px-3 py-2 text-center font-bold text-gray-800">
                                                                        {{ number_format($livestock['weight'], 1) }}
                                                                    </td>
                                                                    <td class="px-3 py-2 text-right text-gray-800">
                                                                        @if($livestock['qurban_price'])
                                                                            Rp {{ number_format($livestock['qurban_price'], 0, ',', '.') }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                                <p>Tidak ada data penerimaan ditemukan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $data->links() }}
                    </div>
                </div>
            @endif
        </div>
    </x-admin.feature-card>
</div>