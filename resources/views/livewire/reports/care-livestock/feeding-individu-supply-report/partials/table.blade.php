<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-800">Riwayat Penggunaan Pakan</h3>
        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full">
            Total Record: {{ $feedings->total() }} Data
        </span>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ternak (Eartag)
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kandang
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Detail Pakan
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total Biaya
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($feedings as $index => $item)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-top">
                            {{ $feedings->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 align-top font-medium">
                            {{ \Carbon\Carbon::parse($item['transaction_date'])->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 align-top font-medium">
                            <div class="text-sm font-medium text-gray-900">{{ $item['livestock_name'] }}</div>
                            <div class="text-sm text-gray-500">{{ $item['eartag'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-top">
                            {{ $item['pen_name'] }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 align-top">
                            @foreach($item['items'] as $feedItem)
                                <div class="mb-1 pb-1 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                                    <span class="font-semibold text-gray-700">{{ $feedItem['name'] }}</span>
                                    <br>
                                    <span class="text-xs">
                                        {{ $feedItem['qty_kg'] }} Kg x Rp
                                        {{ number_format($feedItem['price_per_kg'], 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 align-top font-medium">
                            Rp {{ number_format($item['total_cost'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.care-livestock.feeding-individu-supply-report.export-row-pdf', ['farm_id' => $farmId, 'id' => $item['id']]) }}"
                                target="_blank"
                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-colors duration-150 flex items-center justify-center w-fit ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.586l4 4a1 1 0 01.586 1.414V19a2 2 0 01-2 2z" />
                                </svg>
                                PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-base font-medium text-gray-900">Tidak ada data pemberian pakan</p>
                                <p class="text-sm text-gray-500 mt-1">Coba ubah filter tanggal atau kandang.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($feedings->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $feedings->links() }}
        </div>
    @endif
</div>