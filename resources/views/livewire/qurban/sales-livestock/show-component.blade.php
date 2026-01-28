<div>
    <x-alert.session />

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Detail Penjualan Ternak</h2>
        <x-button.link href="{{ route('admin.care-livestock.sales-livestock.index', $farm->id) }}" color="gray">
            Kembali
        </x-button.link>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Transaksi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">No. Transaksi</label>
                    <div class="mt-1 text-base font-semibold text-gray-900">{{ $salesLivestock->transaction_number }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tanggal Transaksi</label>
                    <div class="mt-1 text-base font-semibold text-gray-900">{{ date('d F Y', strtotime($salesLivestock->transaction_date)) }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Customer</label>
                    <div class="mt-1 text-base text-gray-900">{{ $salesLivestock->qurbanCustomer->user->name ?? $salesLivestock->qurbanCustomer->phone_number }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Sales Order Terkait</label>
                    <div class="mt-1 text-base text-gray-900">{{ $salesLivestock->qurbanSalesOrder->transaction_number ?? '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500">Catatan</label>
                    <div class="mt-1 text-base text-gray-900">{{ $salesLivestock->notes ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Detail Ternak</h3>
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
                        @foreach($salesLivestock->qurbanSaleLivestockD as $detail)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $detail->livestock->livestockType->name ?? '-' }} - {{ $detail->livestock->eartag }}
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
                                {{ number_format($salesLivestock->qurbanSaleLivestockD->sum('price_per_head'), 0, ',', '.') }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>