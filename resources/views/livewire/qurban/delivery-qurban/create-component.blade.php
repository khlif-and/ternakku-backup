<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.select wire:model.live="qurban_sales_livestock_id" name="qurban_sales_livestock_id"
                    label="Transaksi Penjualan" :options="$transactions->mapWithKeys(fn($t) => [$t->id => $t->transaction_number . ' - ' . ($t->qurbanCustomer->user->name ?? $t->qurbanCustomer->name ?? '-') . ' (' . $t->transaction_date . ')'])" placeholder="Pilih Transaksi" />

                <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Pengiriman" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('admin.qurban.qurban_delivery.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan Pengiriman
                </x-button.primary>
            </div>
        </form>
    </div>
</div>