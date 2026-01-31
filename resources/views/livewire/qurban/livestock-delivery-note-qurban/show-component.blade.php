<div>
    <x-alert.session />

    <x-admin.feature-card title="Detail Surat Jalan Ternak" subtitle="Informasi detail pengiriman ternak qurban">
        <x-slot:actions>
            <x-button.link href="{{ route('admin.qurban.livestock-delivery-note.index') }}" color="gray">
                Kembali
            </x-button.link>
        </x-slot:actions>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <x-sales.transaction-info transactionAccount="-" :orderDate="$deliveryNote->delivery_date"
                    description="{{ $deliveryNote->notes ?? '-' }}"
                    :editUrl="route('admin.qurban.livestock-delivery-note.edit', $deliveryNote->id)"
                    deleteAction="delete">
                    <div class="mt-4 space-y-3">
                        <div>
                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Ternak</div>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $deliveryNote->livestock->eartag_number ?? '-' }}
                                ({{ $deliveryNote->livestock->gender ?? '-' }}) -
                                {{ $deliveryNote->livestock->livestockBreed->name ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Status Pengiriman
                            </div>
                            <div class="mt-1">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-bold {{ $deliveryNote->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($deliveryNote->status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-sales.transaction-info>

                <x-sales.customer-info :name="$deliveryNote->qurbanCustomer->user->name ?? '-'"
                    :phone="$deliveryNote->qurbanCustomer->user->phone_number ?? '-'"
                    :address="$deliveryNote->qurbanCustomer->address ?? '-'" />
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Detail Pengiriman</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="text-gray-600">Farm:</div>
                            <div class="font-medium text-right">{{ $deliveryNote->farm->name ?? '-' }}</div>

                            <div class="text-gray-600">Tanggal Pengiriman:</div>
                            <div class="font-medium text-right">
                                {{ date('d F Y', strtotime($deliveryNote->delivery_date)) }}</div>

                            <div class="text-gray-600">Catatan Tambahan:</div>
                            <div class="font-medium text-right">{{ $deliveryNote->notes ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin.feature-card>
</div>