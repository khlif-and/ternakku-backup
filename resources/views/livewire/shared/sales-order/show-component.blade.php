<div>
    <x-alert.session />

    <x-admin.feature-card title="Detail Sales Order" subtitle="Informasi detail pesanan penjualan">
        <x-slot:actions>
            <x-button.link href="{{ route('admin.care-livestock.sales-order.index', $farm->id) }}" color="gray">
                Kembali
            </x-button.link>
        </x-slot:actions>

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Sisi Kiri: Informasi Transaksi & Customer --}}
            <div class="lg:col-span-1 space-y-6">
                <x-sales.transaction-info 
                    :transactionAccount="$salesOrder->transaction_number"
                    :orderDate="$salesOrder->order_date"
                    :description="$salesOrder->description"
                    :editUrl="route('admin.care-livestock.sales-order.edit', [$farm->id, $salesOrder->id])"
                    deleteAction="delete"
                />

                <x-sales.customer-info 
                    :name="$salesOrder->qurbanCustomer->name"
                    :phone="$salesOrder->qurbanCustomer->phone"
                />
            </div>

            {{-- Sisi Kanan: Detail Hewan & Status --}}
            <div class="lg:col-span-2 space-y-6">
                <x-sales.livestock-list 
                    :items="$salesOrder->qurbanSalesOrderD"
                />

                <x-sales.order-status />
            </div>
        </div>
    </x-admin.feature-card>
</div>
