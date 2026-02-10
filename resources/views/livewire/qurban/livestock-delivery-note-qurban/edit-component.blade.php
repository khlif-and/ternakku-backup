<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Transaksi</label>
                    <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                        {{ $deliveryNote->transaction_number ?? '-' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Pelanggan</label>
                    <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                        {{ $deliveryNote->qurbanCustomerAddress->qurbanCustomer->user->name ?? $deliveryNote->qurbanCustomerAddress->qurbanCustomer->name ?? '-' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                    <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                        {{ $deliveryNote->transaction_date ? date('d/m/Y', strtotime($deliveryNote->transaction_date)) : '-' }}
                    </div>
                </div>

                <x-form.date wire:model="delivery_schedule" name="delivery_schedule" label="Jadwal Pengiriman" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('qurban.livestock-delivery-note.index') }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan Perubahan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>