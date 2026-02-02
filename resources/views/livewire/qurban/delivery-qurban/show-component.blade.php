<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border">
        <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
            <h3 class="font-semibold text-gray-800">Detail Instruksi Pengiriman</h3>
            <div class="flex gap-2">
                @if($delivery->status === 'scheduled')
                    <x-button.primary type="button" wire:click="setReadyToDeliver"
                        wire:confirm="Apakah Anda yakin ingin mengubah status ke Ready to Deliver? Driver akan menerima notifikasi WhatsApp."
                        color="green">
                        Set Ready
                    </x-button.primary>
                    <x-button.primary type="button" wire:click="delete"
                        wire:confirm="Apakah Anda yakin ingin menghapus instruksi pengiriman ini?" color="red">
                        Hapus
                    </x-button.primary>
                @endif
            </div>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">No. Transaksi</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                        {{ $delivery->transaction_number }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Pengiriman</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $delivery->delivery_date ? date('d/m/Y', strtotime($delivery->delivery_date)) : '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Driver</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $delivery->driver->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Armada</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $delivery->fleet->name ?? '-' }} ({{ $delivery->fleet->police_number ?? '-' }})
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        @php
                            $statusColors = [
                                'scheduled' => 'bg-gray-100 text-gray-800',
                                'ready_to_deliver' => 'bg-yellow-100 text-yellow-800',
                                'in_delivery' => 'bg-blue-100 text-blue-800',
                                'delivered' => 'bg-green-100 text-green-800',
                            ];
                            $color = $statusColors[$delivery->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $color }}">
                            {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                        </span>
                    </dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Daftar Surat Jalan (DO)</dt>
                    <dd class="mt-1">
                        <div class="bg-gray-50 rounded-lg border">
                            @foreach($delivery->qurbanDeliveryInstructionD as $detail)
                                <div class="p-3 {{ !$loop->last ? 'border-b' : '' }}">
                                    <div class="font-semibold text-sm">
                                        {{ $detail->qurbanDeliveryOrderH->transaction_number ?? '-' }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ $detail->qurbanDeliveryOrderH->qurbanCustomerAddress->qurbanCustomer->user->name ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Ternak:
                                        @foreach($detail->qurbanDeliveryOrderH->qurbanDeliveryOrderD as $orderDetail)
                                            {{ $orderDetail->livestock->eartag ?? '-' }}
                                            ({{ $orderDetail->livestock->livestockBreed->name ?? '-' }}){{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <x-button.link href="{{ route('admin.qurban.qurban_delivery.index') }}" color="gray">
                Kembali
            </x-button.link>
        </div>
    </div>
</div>