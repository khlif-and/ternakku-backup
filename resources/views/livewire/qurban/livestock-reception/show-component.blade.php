<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border">
        <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
            <h3 class="font-semibold text-gray-800">Detail Penerimaan Ternak Qurban</h3>
            <div class="flex gap-2">
                <x-button.action href="{{ route('qurban.livestock-reception.edit', $reception->id) }}" color="blue">
                    Edit
                </x-button.action>
                <x-button.primary type="button" wire:click="delete"
                    wire:confirm="Apakah Anda yakin ingin menghapus penerimaan ini?" color="red">
                    Hapus
                </x-button.primary>
            </div>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Penerimaan</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $reception->livestockReceptionH?->transaction_date ? date('d/m/Y', strtotime($reception->livestockReceptionH->transaction_date)) : '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">No Transaksi</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $reception->livestockReceptionH?->transaction_number ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Supplier</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $reception->livestockReceptionH?->supplier ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nomor Eartag</dt>
                    <dd class="mt-1 text-sm font-bold text-gray-900">
                        {{ $reception->eartag_number }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">RFID</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $reception->rfid_number ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jenis Ternak</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $reception->livestockType?->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Ras</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $reception->livestockBreed?->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $reception->livestockSex?->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Umur</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $reception->age_years ?? 0 }} Tahun {{ $reception->age_months ?? 0 }} Bulan
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Berat</dt>
                    <dd class="mt-1 text-sm font-bold text-gray-900">
                        {{ number_format($reception->weight ?? 0, 1) }} Kg
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Kandang</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $reception->pen?->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Harga per Kg</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        Rp {{ number_format($reception->price_per_kg ?? 0, 0, ',', '.') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Harga per Ekor</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        Rp {{ number_format($reception->price_per_head ?? 0, 0, ',', '.') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Harga Qurban</dt>
                    <dd class="mt-1 text-sm font-bold text-green-700">
                        Rp {{ number_format($reception->livestock?->qurbanLivestock?->price ?? 0, 0, ',', '.') }}
                    </dd>
                </div>
                @if($reception->characteristics)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Ciri-ciri</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $reception->characteristics }}
                        </dd>
                    </div>
                @endif
                @if($reception->livestockReceptionH?->notes)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $reception->livestockReceptionH->notes }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <x-button.link href="{{ route('qurban.livestock-reception.index') }}" color="gray">
                Kembali
            </x-button.link>
        </div>
    </div>
</div>