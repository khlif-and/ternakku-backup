<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">No. Transaksi</label>
                <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                    {{ $delivery->transaction_number ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Pengiriman</label>
                <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                    {{ $delivery->delivery_date ? date('d/m/Y', strtotime($delivery->delivery_date)) : '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Driver</label>
                <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                    {{ $delivery->driver->name ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Armada</label>
                <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                    {{ $delivery->fleet->name ?? '-' }} ({{ $delivery->fleet->police_number ?? '-' }})
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <div class="mt-1 px-3 py-2 bg-gray-100 border rounded-md text-gray-700">
                    {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Surat Jalan</label>
            <ul class="list-disc list-inside bg-gray-50 p-3 rounded-lg">
                @foreach($delivery->qurbanDeliveryInstructionD as $detail)
                    <li class="text-sm text-gray-700">
                        {{ $detail->qurbanDeliveryOrderH->transaction_number ?? '-' }}
                        - {{ $detail->qurbanDeliveryOrderH->qurbanCustomerAddress->qurbanCustomer->user->name ?? '-' }}
                    </li>
                @endforeach
            </ul>
        </div>

        @if($delivery->status === 'scheduled')
            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('admin.qurban.qurban_delivery.index') }}" color="gray">
                    Kembali
                </x-button.link>
                <x-button.primary type="button" wire:click="setReadyToDeliver"
                    wire:confirm="Apakah Anda yakin ingin mengubah status ke Ready to Deliver? Driver akan menerima notifikasi WhatsApp.">
                    Set Ready to Deliver
                </x-button.primary>
            </div>
        @else
            <div class="flex justify-end pt-4 border-t">
                <x-button.link href="{{ route('admin.qurban.qurban_delivery.index') }}" color="gray">
                    Kembali
                </x-button.link>
            </div>
        @endif
    </div>
</div>