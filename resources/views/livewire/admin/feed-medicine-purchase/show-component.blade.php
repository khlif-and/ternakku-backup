<div>
    <x-alert.session />

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg border p-4 space-y-4">
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tanggal Pembelian</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $purchase->transaction_date ? date('d M Y', strtotime($purchase->transaction_date)) : '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Supplier / Toko</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $purchase->supplier ?? '-' }}
                    </div>
                </div>

                <div class="pt-2 border-t">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Pengeluaran</div>
                    <div class="text-lg font-bold text-green-700">
                        Rp {{ number_format($purchase->total_amount ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Jumlah Item</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $purchase->feedMedicinePurchaseItem->count() }} Item Terdaftar
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Catatan</div>
                    <div class="text-sm text-gray-800 italic">
                        {{ $purchase->notes ?: 'Tidak ada catatan.' }}
                    </div>
                </div>

                <div class="flex flex-col gap-2 pt-2">
                    <x-button.action href="{{ route('admin.care-livestock.feed-medicine-purchase.edit', [$farm->id, $purchase->id]) }}" color="blue" class="w-full justify-center">
                        Edit Data
                    </x-button.action>
                    
                    <x-button.primary wire:click="delete" wire:confirm="Yakin ingin menghapus data pembelian ini?" color="red" class="w-full justify-center">
                        Hapus Pembelian
                    </x-button.primary>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg border overflow-hidden">
                <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700 flex items-center gap-2">
                    Rincian Item Pembelian
                </div>

                @php
                    $headers = [
                        ['label' => 'No', 'class' => 'text-left w-12'],
                        ['label' => 'Jenis', 'class' => 'text-left'],
                        ['label' => 'Nama Item', 'class' => 'text-left'],
                        ['label' => 'Qty', 'class' => 'text-right'],
                        ['label' => 'Harga Satuan', 'class' => 'text-right'],
                        ['label' => 'Subtotal', 'class' => 'text-right'],
                    ];
                @endphp

                <x-table.wrapper :headers="$headers">
                    @forelse($purchase->feedMedicinePurchaseItem as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm uppercase">
                                <span class="px-2 py-0.5 rounded text-xs font-bold border {{ $item->purchase_type === 'medicine' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-green-50 text-green-700 border-green-100' }}">
                                    {{ $item->purchase_type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 font-medium">{{ $item->item_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">
                                {{ number_format($item->quantity, 2, ',', '.') }} <span class="text-xs text-gray-500">{{ $item->unit }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">
                                Rp {{ number_format($item->price_per_unit, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right font-semibold">
                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <x-table.empty colspan="6" empty="Tidak ada rincian item." />
                    @endforelse

                    <x-slot:footer>
                        <tr class="bg-gray-50">
                            <td colspan="5" class="px-4 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-widest">Grand Total</td>
                            <td class="px-4 py-4 text-base font-bold text-blue-700 text-right bg-blue-50/50">
                                Rp {{ number_format($purchase->total_amount ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </x-slot:footer>
                </x-table.wrapper>
            </div>
        </div>
    </div>
</div>