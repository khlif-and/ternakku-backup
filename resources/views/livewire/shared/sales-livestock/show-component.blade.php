<div>
    <x-alert.session />

    <x-admin.feature-card title="Detail Penjualan Ternak" subtitle="Informasi detail penjualan hewan ternak">
        <x-slot:actions>
            <x-button.link href="{{ route('admin.care-livestock.sales-livestock.index', $farm->id) }}" color="gray">
                Kembali
            </x-button.link>
        </x-slot:actions>

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Sisi Kiri: Informasi Transaksi --}}
            <div class="lg:col-span-1 space-y-6">
                <x-sales.transaction-info 
                    :transactionAccount="$salesLivestock->transaction_number"
                    :orderDate="$salesLivestock->transaction_date"
                    :description="$salesLivestock->notes"
                    :editUrl="route('admin.care-livestock.sales-livestock.edit', [$farm->id, $salesLivestock->id])"
                    deleteAction="delete"
                >
                    <div class="mt-4">
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Sales Order Terkait</div>
                        <div class="text-sm text-gray-800">
                             {{ $salesLivestock->qurbanSalesOrder->transaction_number ?? '-' }}
                        </div>
                    </div>
                </x-sales.transaction-info>

                <x-sales.customer-info 
                    :name="$salesLivestock->qurbanCustomer->user->name ?? $salesLivestock->qurbanCustomer->phone_number"
                    :phone="$salesLivestock->qurbanCustomer->phone_number"
                />
            </div>

            {{-- Sisi Kanan: Detail Table --}}
            <div class="lg:col-span-2 space-y-6">
               <x-sales.livestock-sale-table 
                    :items="$salesLivestock->qurbanSaleLivestockD"
               />
            </div>
        </div>
    </x-admin.feature-card>
</div>