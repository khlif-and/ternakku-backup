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

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg border p-4 space-y-3">
            <div class="font-semibold text-gray-700 mb-3">Informasi Ternak</div>

            <div>
                <div class="text-xs text-gray-500">Eartag</div>
                <div class="text-sm font-semibold text-gray-800">{{ $death->livestock?->eartag_number ?? '-' }}</div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <div class="text-xs text-gray-500">Jenis Ternak</div>
                    <div class="text-sm font-semibold text-gray-800">{{ $death->livestock?->livestockType?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Ras</div>
                    <div class="text-sm font-semibold text-gray-800">{{ $death->livestock?->livestockBreed?->name ?? '-' }}</div>
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Kandang</div>
                <div class="text-sm font-semibold text-gray-800">{{ $death->livestock?->pen?->name ?? '-' }}</div>
            </div>
        </div>

        <div class="bg-white rounded-lg border p-4 space-y-3">
            <div class="font-semibold text-gray-700 mb-3">Informasi Kematian</div>

            <div>
                <div class="text-xs text-gray-500">Tanggal Kematian</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ $death->transaction_date ? date('d M Y', strtotime($death->transaction_date)) : '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Penyakit</div>
                <div class="text-sm font-semibold text-gray-800">{{ $death->disease?->name ?? '-' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Indikasi Kematian</div>
                <div class="text-sm text-gray-800">{{ $death->indication ?: '-' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Catatan</div>
                <div class="text-sm text-gray-800">{{ $death->notes ?: '-' }}</div>
            </div>

            <button wire:click="delete"
                wire:confirm="Yakin ingin menghapus data kematian ternak ini?"
                class="w-full inline-flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-semibold rounded-lg px-4 py-2 text-sm transition-all">
                Hapus
            </button>
        </div>
    </div>
</div>
