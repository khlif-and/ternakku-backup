<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border">
        <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
            <h3 class="font-semibold text-gray-800">Detail Pengiriman Qurban</h3>
            <div class="flex gap-2">
                <x-button.action href="{{ route('admin.qurban.qurban_delivery.edit', $delivery->id) }}" color="blue">
                    Edit
                </x-button.action>
                <x-button.primary type="button" wire:click="delete"
                    wire:confirm="Apakah Anda yakin ingin menghapus data pengiriman ini?" color="red">
                    Hapus
                </x-button.primary>

                <x-pdf.download-button :file="$delivery->file" mode="link">
                    Download Surat Jalan
                </x-pdf.download-button>
            </div>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Pengiriman</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $delivery->transaction_date ? date('d/m/Y', strtotime($delivery->transaction_date)) : '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pelanggan</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $delivery->qurbanCustomerAddress->qurbanCustomer->user->name ?? $delivery->qurbanCustomerAddress->qurbanCustomer->name ?? '-' }}
                    </dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Daftar Hewan Qurban</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <ul class="list-disc list-inside">
                            @foreach($delivery->qurbanDeliveryOrderD as $detail)
                                <li>
                                    {{ $detail->livestock->eartag ?? '-' }}
                                    ({{ $detail->livestock->livestockBreed->name ?? '-' }})
                                </li>
                            @endforeach
                        </ul>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <x-button.link href="{{ route('admin.qurban.qurban_delivery.index', $farm->id) }}" color="gray">
                Kembali
            </x-button.link>
        </div>
    </div>
</div>