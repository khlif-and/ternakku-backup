<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Customer Selection --}}
                <x-form.select wire:model.live="qurban_customer_id" name="qurban_customer_id" label="Pelanggan"
                    :options="$customers->mapWithKeys(fn($c) => [$c->id => $c->user->name ?? '-'])"
                    placeholder="Pilih Pelanggan" />

                {{-- Livestock Selection --}}
                <x-form.select wire:model="livestock_id" name="livestock_id" label="Ternak"
                    :options="collect($livestocks)->pluck('eartag_number', 'id')"
                    placeholder="{{ empty($livestocks) ? 'Pilih Pelanggan Terlebih Dahulu' : 'Pilih Ternak' }}"
                    :disabled="empty($livestocks)" />

                <x-form.input wire:model="breed_name" name="breed_name" label="Ras Ternak" readonly />

                {{-- Transaction Date --}}
                <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Transaksi" />

                {{-- Amount --}}
                <x-form.number wire:model="amount" name="amount" label="Jumlah Bayar (Rp)" min="0" />
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('admin.qurban.payment.index') }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan Perubahan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>