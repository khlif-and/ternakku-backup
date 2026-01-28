<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="store" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.select 
                    wire:model.live="customer_id" 
                    name="customer_id"
                    label="Customer" 
                    :options="$customers" 
                    placeholder="Pilih Customer"
                />

                <x-form.disabled 
                    label="Nomor Transaksi" 
                    :value="$sales_order_number ?? '-'"
                />

                <x-form.date 
                    wire:model="transaction_date" 
                    name="transaction_date"
                    label="Tanggal Transaksi" 
                />

                <div class="md:col-span-2">
                    <x-form.textarea
                        wire:model="notes"
                        name="notes"
                        label="Catatan"
                        placeholder="Tambahkan catatan jika ada"
                    />
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between border-b pb-2">
                    <label class="block text-sm font-medium text-gray-700">Detail Ternak</label>
                    <x-button.action wire:click="addItem" type="button" size="xs" color="blue">
                        + Tambah Ternak
                    </x-button.action>
                </div>

                @if($errors->has('items'))
                    <span class="text-red-500 text-sm">{{ $errors->first('items') }}</span>
                @endif

                @foreach($items as $index => $item)
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-gray-50 p-4 rounded-lg border relative group" wire:key="item-{{ $index }}">
                        <div class="md:col-span-3">
                            <x-form.select 
                                wire:model.live="items.{{ $index }}.livestock_id" 
                                name="items.{{ $index }}.livestock_id"
                                label="Ternak" 
                                :options="$availableLivestock->mapWithKeys(fn($l) => [$l->id => ($l->livestockType->name ?? '') . ' - ' . $l->eartag . ' (' . ($l->current_weight ?? 0) . ' kg)'])" 
                                placeholder="Pilih Ternak"
                            />
                        </div>
                        <div class="md:col-span-3">
                            <x-form.select 
                                wire:model="items.{{ $index }}.customer_address_id" 
                                name="items.{{ $index }}.customer_address_id"
                                label="Alamat Pengiriman" 
                                :options="$addresses" 
                                placeholder="Pilih Alamat"
                            />
                        </div>
                        <div class="md:col-span-2">
                            <x-form.disabled 
                                label="Berat (Kg)" 
                                wire:model="items.{{ $index }}.weight"
                            />
                        </div>
                        <div class="md:col-span-2">
                            <x-form.number 
                                wire:model.blur="items.{{ $index }}.price_per_kg" 
                                name="items.{{ $index }}.price_per_kg"
                                label="Harga/Kg" 
                                min="0"
                            />
                        </div>
                        <div class="md:col-span-2">
                            <x-form.disabled 
                                label="Harga Total" 
                                :value="number_format($item['price_per_head'] ?? 0, 0, ',', '.')"
                            />
                        </div>
                         
                        <div class="md:col-span-2">
                           <x-form.date 
                                wire:model="items.{{ $index }}.delivery_plan_date" 
                                name="items.{{ $index }}.delivery_plan_date"
                                label="Rencana Kirim" 
                            />
                        </div>

                        <div class="md:col-span-1 flex justify-end">
                            @if(count($items) > 1)
                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('admin.care-livestock.sales-livestock.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>