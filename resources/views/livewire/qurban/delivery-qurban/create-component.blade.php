<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.date wire:model="delivery_date" name="delivery_date" label="Tanggal Pengiriman" />

                <x-form.select wire:model="driver_id" name="driver_id" label="Driver"
                    :options="collect($drivers)->mapWithKeys(fn($d) => [$d->id => $d->name])"
                    placeholder="Pilih Driver" />

                <x-form.select wire:model="fleet_id" name="fleet_id" label="Armada"
                    :options="collect($fleets)->mapWithKeys(fn($f) => [$f->id => $f->name . ' (' . $f->police_number . ')'])" placeholder="Pilih Armada" />
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Surat Jalan (DO)</label>
                @error('delivery_order_ids')
                    <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                @enderror

                @if($availableOrders->isEmpty())
                    <div class="text-gray-500 text-sm">Tidak ada surat jalan yang tersedia untuk dijadwalkan.</div>
                @else
                    <div class="space-y-2 max-h-64 overflow-y-auto border rounded-lg p-3">
                        @foreach($availableOrders as $order)
                            <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                <input type="checkbox" wire:model="delivery_order_ids" value="{{ $order->id }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm">
                                    <span class="font-semibold">{{ $order->transaction_number }}</span>
                                    -
                                    {{ $order->qurbanCustomerAddress->qurbanCustomer->user->name ?? $order->qurbanCustomerAddress->qurbanCustomer->name ?? '-' }}
                                    ({{ $order->transaction_date }})
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('admin.qurban.qurban_delivery.index') }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Buat Instruksi Pengiriman
                </x-button.primary>
            </div>
        </form>
    </div>
</div>