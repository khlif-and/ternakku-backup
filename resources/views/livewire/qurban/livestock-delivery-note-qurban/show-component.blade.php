<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border">
        <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
            <h3 class="font-semibold text-gray-800">Detail Surat Jalan</h3>
            <div class="flex gap-2">
                @if($deliveryNote->file)
                    <x-button.link href="{{ getNeoObject($deliveryNote->file) }}" target="_blank" color="blue" size="sm">
                        <i class="icon-download mr-1"></i> Download PDF
                    </x-button.link>
                @endif
                <x-button.action href="{{ route('qurban.livestock-delivery-note.edit', $deliveryNote->id) }}"
                    color="blue">
                    Edit Jadwal
                </x-button.action>
                <x-button.primary type="button" wire:click="delete"
                    wire:confirm="Apakah Anda yakin ingin menghapus surat jalan ini?" color="red" size="sm">
                    Hapus
                </x-button.primary>
            </div>
        </div>

        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">No. Transaksi</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $deliveryNote->transaction_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Transaksi</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $deliveryNote->transaction_date ? date('d/m/Y', strtotime($deliveryNote->transaction_date)) : '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jadwal Pengiriman</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $deliveryNote->delivery_schedule ? date('d/m/Y', strtotime($deliveryNote->delivery_schedule)) : '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pelanggan</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $deliveryNote->qurbanCustomerAddress->qurbanCustomer->user->name ?? $deliveryNote->qurbanCustomerAddress->qurbanCustomer->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Alamat Pengiriman</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $deliveryNote->qurbanCustomerAddress->address ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Farm</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $deliveryNote->farm->name ?? '-' }}</dd>
                </div>

                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Daftar Ternak</dt>
                    <dd class="mt-1">
                        <div class="bg-gray-50 rounded-lg border">
                            @forelse($deliveryNote->qurbanDeliveryOrderD as $detail)
                                <div class="p-3 {{ !$loop->last ? 'border-b' : '' }}">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span
                                                class="font-semibold text-sm">{{ $detail->livestock->eartag ?? '-' }}</span>
                                            <span class="text-sm text-gray-600">
                                                - {{ $detail->livestock->livestockBreed->name ?? '-' }}
                                                ({{ $detail->livestock->livestockType->name ?? '-' }})
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-sm text-gray-500">Tidak ada data ternak.</div>
                            @endforelse
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <x-button.link href="{{ route('qurban.livestock-delivery-note.index') }}" color="gray">
                Kembali
            </x-button.link>
        </div>
    </div>
</div>