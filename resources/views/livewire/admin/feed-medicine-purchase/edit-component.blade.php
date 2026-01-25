<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Pembelian" required />
            <x-form.input wire:model="supplier" name="supplier" label="Supplier / Toko" required />
        </div>

        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Daftar Item Pembelian</h3>
                <button type="button" wire:click="addItem" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    + Tambah Item
                </button>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Item</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Satuan</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Harga Satuan</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $index => $item)
                            <tr wire:key="edit-item-{{ $index }}">
                                <td class="px-2 py-3">
                                    <select wire:model="items.{{ $index }}.purchase_type" class="w-full px-3 py-2 border rounded-md text-sm focus:ring-blue-500">
                                        <option value="">Pilih Jenis</option>
                                        @foreach($purchaseTypes as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-2 py-3">
                                    <input type="text" wire:model="items.{{ $index }}.item_name" class="w-full px-3 py-2 border rounded-md text-sm focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-3">
                                    <input type="number" wire:model="items.{{ $index }}.quantity" class="w-full px-3 py-2 border rounded-md text-sm focus:ring-blue-500" step="any">
                                </td>
                                <td class="px-2 py-3">
                                    <input type="text" wire:model="items.{{ $index }}.unit" class="w-full px-3 py-2 border rounded-md text-sm focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-3">
                                    <input type="number" wire:model="items.{{ $index }}.price_per_unit" class="w-full px-3 py-2 border rounded-md text-sm focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <button type="button" wire:click="removeItem({{ $index }})" class="p-2 text-red-600 hover:bg-red-50 rounded-full transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Catatan Tambahan (opsional)" rows="2" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.feed-medicine-purchase.show', [$farm->id, $purchase->id]) }}"
            submitLabel="Simpan Perubahan" 
        />
    </form>
</div>