<div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
    <h3 class="text-lg font-semibold text-gray-800">Riwayat Perawatan</h3>
    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full">
        Total Record: {{ $treatments->total() }} Data
    </span>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    No
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Tanggal
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Ternak (Eartag)
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kandang
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Penyakit
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Obat / Tindakan
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($treatments as $index => $item)
                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-top">
                        {{ $treatments->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 align-top font-medium">
                        {{ \Carbon\Carbon::parse($item['transaction_date'])->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 align-top font-medium">
                        <div>{{ $item['livestock_name'] }}</div>
                        <div class="text-xs text-gray-400">{{ $item['livestock_id'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-top">
                        {{ $item['pen_name'] ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-top">
                        {{ $item['disease_name'] ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 align-top">
                        @if(count($item['medicine_items']) > 0)
                            <div class="mb-2">
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Obat:</span>
                                <ul class="list-disc list-inside mt-1">
                                    @foreach($item['medicine_items'] as $med)
                                        <li>{{ $med['name'] }} ({{ $med['qty'] }} {{ $med['uom'] }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(count($item['treatment_items']) > 0)
                            <div>
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tindakan:</span>
                                <ul class="list-disc list-inside mt-1">
                                    @foreach($item['treatment_items'] as $act)
                                        <li>{{ $act['name'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(count($item['medicine_items']) === 0 && count($item['treatment_items']) === 0)
                            <span class="text-gray-400 italic">Tidak ada detail</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-top text-right">
                        <a href="{{ route('admin.care-livestock.treatment-individu-report.export-row-pdf', ['farm_id' => $farmId, 'id' => $item['id']]) }}"
                            target="_blank" class="text-red-600 hover:text-red-900" title="Export PDF">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-base font-medium text-gray-900">Tidak ada data perawatan</p>
                            <p class="text-sm text-gray-500 mt-1">Coba ubah filter tanggal atau kandang.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($treatments->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $treatments->links() }}
    </div>
@endif
</div>