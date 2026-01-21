<div>
    @if (session('success'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-green-100 border border-green-400 text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-red-100 border border-red-400 text-red-700 font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-lg border p-4 space-y-3">
                @if($reception->photo)
                    <div class="mb-4">
                        <img src="{{ asset($reception->photo) }}" alt="Foto Ternak" class="w-full h-48 object-cover rounded-lg">
                    </div>
                @endif

                <div>
                    <div class="text-xs text-gray-500">Eartag</div>
                    <div class="text-sm font-semibold text-gray-800">{{ $reception->eartag_number ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">RFID</div>
                    <div class="text-sm font-semibold text-gray-800">{{ $reception->rfid_number ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Jenis Ternak</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $reception->livestockType?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Ras</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $reception->livestockBreed?->name ?? '-' }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Jenis Kelamin</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $reception->livestockSex?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Klasifikasi</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $reception->livestockClassification?->name ?? '-' }}</div>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Kandang</div>
                    <div class="text-sm font-semibold text-gray-800">{{ $reception->pen?->name ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Umur</div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $reception->age_years ?? 0 }} tahun {{ $reception->age_months ?? 0 }} bulan
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Berat</div>
                        <div class="text-sm font-semibold text-gray-800">{{ number_format($reception->weight ?? 0, 2, ',', '.') }} kg</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Harga/kg</div>
                        <div class="text-sm font-semibold text-gray-800">Rp {{ number_format($reception->price_per_kg ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Harga/ekor</div>
                        <div class="text-sm font-semibold text-gray-800">Rp {{ number_format($reception->price_per_head ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>

                <button wire:click="delete"
                    wire:confirm="Yakin ingin menghapus data penerimaan ternak ini?"
                    class="w-full inline-flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-semibold rounded-lg px-4 py-2 text-sm transition-all">
                    Hapus
                </button>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-lg border p-4">
                <div class="font-semibold text-gray-700 mb-3">Info Transaksi</div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-gray-500">Tanggal Penerimaan</div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $reception->livestockReceptionH?->transaction_date ? date('d M Y', strtotime($reception->livestockReceptionH->transaction_date)) : '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Supplier</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $reception->livestockReceptionH?->supplier ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border p-4">
                <div class="font-semibold text-gray-700 mb-3">Catatan & Karakteristik</div>
                <div class="space-y-3">
                    <div>
                        <div class="text-xs text-gray-500">Catatan</div>
                        <div class="text-sm text-gray-800">{{ $reception->notes ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Karakteristik</div>
                        <div class="text-sm text-gray-800">{{ $reception->characteristics ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
