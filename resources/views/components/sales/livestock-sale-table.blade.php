@props(['items'])

<div class="bg-white rounded-lg shadow-sm border p-6">
    <div class="flex items-center justify-between border-b pb-4 mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Detail Ternak</h3>
        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
            {{ $items->count() }} Ekor
        </span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ternak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (Kg)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga/Kg</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat Kirim</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rencana Kirim</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($items as $detail)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-medium">{{ $detail->livestock->livestockType->name ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $detail->livestock->eartag }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $detail->weight }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            {{ number_format($detail->price_per_kg, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">
                            {{ number_format($detail->price_per_head, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $detail->qurbanCustomerAddress->address_line ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ date('d/m/Y', strtotime($detail->delivery_plan_date)) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">Total</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                        {{ number_format($items->sum('price_per_head'), 0, ',', '.') }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
