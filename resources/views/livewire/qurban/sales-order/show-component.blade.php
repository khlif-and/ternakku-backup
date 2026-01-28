<div> <x-alert.session />

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Sisi Kiri: Informasi Transaksi --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-lg border p-5 space-y-4 shadow-sm">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold text-gray-800">Detail Transaksi</h3>
                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase tracking-wider">
                    {{ $salesOrder->transaction_number }}
                </span>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tanggal Order</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ date('d M Y', strtotime($salesOrder->order_date)) }}
                    </div>
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Deskripsi</div>
                <div class="text-sm text-gray-800 italic">
                    {{ $salesOrder->description ?: '-' }}
                </div>
            </div>

            <div class="pt-4 border-t space-y-2">
                <x-button.action href="{{ route('admin.care-livestock.sales-order.edit', [$farm->id, $salesOrder->id]) }}" color="blue" class="w-full justify-center">
                    Edit Order
                </x-button.action>
                
                <x-button.primary type="button" wire:click="delete" wire:confirm="Apakah Anda yakin ingin menghapus Sales Order ini?" color="red" class="w-full justify-center">
                    Hapus Order
                </x-button.primary>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="bg-white rounded-lg border p-5 shadow-sm">
            <div class="mb-4 font-bold text-gray-800 border-b pb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Informasi Pelanggan
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Nama Pelanggan</span>
                    <span class="text-sm font-bold text-gray-900">{{ $salesOrder->qurbanCustomer->name ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">No. Telepon</span>
                    <span class="text-sm font-medium text-gray-800">{{ $salesOrder->qurbanCustomer->phone ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Detail Hewan --}}
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b bg-gray-50 font-bold text-gray-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Daftar Hewan Pesanan
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 tracking-wider">
                            <th class="px-6 py-3 font-semibold">Jenis Hewan</th>
                            <th class="px-6 py-3 font-semibold text-center">Jumlah (Ekor)</th>
                            <th class="px-6 py-3 font-semibold text-center">Total Berat (Kg)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($salesOrder->qurbanSalesOrderD as $detail)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $detail->livestockType->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-center text-gray-700">
                                    {{ $detail->quantity }}
                                </td>
                                <td class="px-6 py-4 text-sm text-center text-gray-700">
                                    {{ $detail->total_weight }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada detail hewan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($salesOrder->qurbanSalesOrderD->isNotEmpty())
                        <tfoot class="bg-gray-50 border-t border-gray-100">
                            <tr>
                                <td class="px-6 py-3 text-sm font-bold text-gray-900 text-right">Total</td>
                                <td class="px-6 py-3 text-sm font-bold text-gray-900 text-center">
                                    {{ $salesOrder->qurbanSalesOrderD->sum('quantity') }}
                                </td>
                                <td class="px-6 py-3 text-sm font-bold text-gray-900 text-center">
                                    {{ $salesOrder->qurbanSalesOrderD->sum('total_weight') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Status Visual (Optional) --}}
        <div class="bg-blue-900 rounded-lg p-6 text-white shadow-md relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                <div class="flex-1">
                    <h4 class="text-lg font-bold mb-2">Status Pesanan</h4>
                    <p class="text-sm text-blue-100 leading-relaxed">
                        Pastikan pesanan ini sudah diverifikasi dan hewan ternak disiapkan sesuai dengan spesifikasi yang diminta pelanggan.
                    </p>
                </div>
                <div class="hidden md:block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-blue-500 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            {{-- Background Decoration --}}
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-800 rounded-full opacity-50"></div>
        </div>
    </div>
</div>
</div>
