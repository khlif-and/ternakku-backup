<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    @php
        $headers = [
            ['label' => 'Tanggal & No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Supplier', 'class' => 'text-left'],
            ['label' => 'Item Pembelian', 'class' => 'text-left'],
            ['label' => 'Total', 'class' => 'text-right'],
        ];
    @endphp
    
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Pembelian</h3>
        <x-table.wrapper :headers="$headers">
            @forelse ($purchases as $item)
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-500 font-mono">
                            {{ $item->transaction_number }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        {{ $item->supplier ?? '-' }}
                        @if($item->notes)
                            <div class="text-xs text-gray-500 italic mt-1">{{ $item->notes }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <ul class="text-sm space-y-1">
                            @foreach($item->feedMedicinePurchaseItem as $detail)
                                <li class="flex justify-between items-start border-b border-gray-100 pb-1 last:border-0 last:pb-0">
                                    <div>
                                        <span class="font-medium text-gray-700">{{ $detail->item_name }}</span>
                                        <div class="text-xs text-gray-500">
                                            {{ ucfirst($detail->purchase_type) }} • {{ $detail->quantity }} {{ $detail->unit }} @ {{ number_format($detail->price_per_unit, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="text-gray-600 pl-2">
                                        {{ number_format($detail->total_price, 0, ',', '.') }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-gray-800">
                        Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="4" empty="Tidak ada data pembelian pada periode ini." />
            @endforelse
        </x-table.wrapper>

        <div class="mt-4">
            {{ $purchases instanceof \Illuminate\Pagination\LengthAwarePaginator ? $purchases->links() : '' }}
        </div>
    </div>
</div>
