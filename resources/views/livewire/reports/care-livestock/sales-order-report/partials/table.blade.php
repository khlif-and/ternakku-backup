<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    @php
        $headers = [
            ['label' => 'Tanggal & No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Pelanggan', 'class' => 'text-left'],
            ['label' => 'Detail Pesanan', 'class' => 'text-left'],
            ['label' => 'Total', 'class' => 'text-right'],
            ['label' => 'Keterangan', 'class' => 'text-left'],
        ];
    @endphp
    
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Sales Order</h3>
        <x-table.wrapper :headers="$headers">
            @forelse ($orders as $item)
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">
                             {{ \Carbon\Carbon::parse($item->order_date)->format('d M Y') }}
                        </div>
                        <div class="text-sm text-emerald-600 font-mono mt-1">
                             {{ $item->transaction_number }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">
                            {{ $item->qurbanCustomer->user->name ?? '-' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $item->qurbanCustomer->user->phone_number ?? '-' }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <ul class="text-sm space-y-1">
                            @forelse($item->qurbanSalesOrderD as $detail)
                                <li class="flex justify-between items-center border-b border-gray-100 pb-1 last:border-0 last:pb-0">
                                    <span>
                                        {{ $detail->livestockType->name ?? 'Tipe Hewan?' }}
                                    </span>
                                    <span class="font-medium text-gray-700">
                                         {{ $detail->quantity }} Ekor
                                    </span>
                                </li>
                            @empty
                                <li class="text-gray-500 italic text-xs">Detail item tidak ditemukan.</li>
                            @endforelse
                        </ul>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="font-bold text-gray-800">
                             {{ $item->quantity }} Ekor
                        </div>
                        <div class="text-xs text-gray-500">
                             {{ number_format($item->total_weight, 2, ',', '.') }} kg
                        </div>
                    </td>
                     <td class="px-4 py-3 text-gray-600 italic text-sm">
                        {{ $item->description ?? '-' }}
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="5" empty="Tidak ada data sales order pada periode ini." />
            @endforelse
        </x-table.wrapper>

        <div class="mt-4">
            {{ $orders instanceof \Illuminate\Pagination\LengthAwarePaginator ? $orders->links() : '' }}
        </div>
    </div>
</div>
