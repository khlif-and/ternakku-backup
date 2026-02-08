<div>
    <div class="mb-6">
        <a href="{{ route('marketing.sales-order.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">
                    {{ $salesOrder->transaction_number ?? 'Sales Order' }}
                </h2>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'confirmed' => 'bg-blue-100 text-blue-700',
                        'completed' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    ];
                    $statusLabels = [
                        'pending' => 'Pending',
                        'confirmed' => 'Dikonfirmasi',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ];
                @endphp
                <span
                    class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$salesOrder->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$salesOrder->status] ?? $salesOrder->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Pelanggan</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ $salesOrder->qurbanCustomer->user->name ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Peternakan</label>
                <p class="mt-1 text-gray-900">{{ $salesOrder->farm->name ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Tanggal</label>
                <p class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($salesOrder->created_at)->format('d M Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Total</label>
                <p class="mt-1 text-gray-900 font-semibold">Rp {{ number_format($salesOrder->total ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    @if($salesOrder->salesOrderDetails && $salesOrder->salesOrderDetails->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pesanan</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($salesOrder->salesOrderDetails as $detail)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $detail->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $detail->quantity ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp {{ number_format($detail->price ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format(($detail->quantity ?? 0) * ($detail->price ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>