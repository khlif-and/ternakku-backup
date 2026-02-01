<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Readonly Info -->
                <x-form.input wire:model="transaction_number" name="transaction_number" label="No. Transaksi Penjualan"
                    readonly />
                <x-form.input wire:model="customer_name" name="customer_name" label="Pelanggan" readonly />

                <!-- Editable -->
                <x-form.date wire:model="delivery_date" name="delivery_date" label="Tanggal Pengiriman" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('qurban.livestock-delivery-note.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan Perubahan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>