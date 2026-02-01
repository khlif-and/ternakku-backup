<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Read Only Transaction Info -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Surat Jalan</label>
                    <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                        {{ $delivery->transaction_number ?? 'Auto-generated' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Transaksi Penjualan</label>
                    <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                        {{ $delivery->qurbanSaleLivestockH->transaction_number ?? '-' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Pelanggan</label>
                    <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                        {{ $delivery->qurbanSaleLivestockH->qurbanCustomer->user->name ?? $delivery->qurbanSaleLivestockH->qurbanCustomer->name ?? '-' }}
                    </div>
                </div>

                <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Pengiriman" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('admin.qurban.qurban_delivery.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan Perubahan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>