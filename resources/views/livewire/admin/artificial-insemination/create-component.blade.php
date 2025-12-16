<div>
    {{-- 🔹 Notifikasi error dari session --}}
    @if (session('error'))
        <div class="flex items-center p-4 mb-4 text-sm font-medium text-red-800 rounded-lg bg-red-100 border border-red-300" role="alert">
            <svg class="w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    {{-- 🔹 Notifikasi validasi dari Livewire --}}
    @if ($errors->any())
        <div class="p-4 mb-4 text-sm rounded-lg bg-red-50 border border-red-200 text-red-800">
            <div class="font-semibold mb-2">Terjadi kesalahan:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="save" class="w-full max-w-full">
        {{-- 🔹 Baris 1: tanggal + ternak + waktu --}}
        <div class="grid md:grid-cols-3 md:gap-6">
            {{-- tanggal --}}
            <div class="mb-8">
                <label for="transaction-date" class="block mb-2 text-sm font-medium text-gray-700">
                    Tanggal Inseminasi
                </label>
                <input id="transaction-date" wire:model.defer="transaction_date" type="date"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3
                       @error('transaction_date') border-red-500 @enderror" required>
                @error('transaction_date')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            {{-- dropdown ternak --}}
            <div class="mb-8">
                <label for="livestock_id" class="block mb-2 text-sm font-medium text-gray-700">
                    Eartag / Nama Ternak (Betina)
                </label>
                <select id="livestock_id" wire:model.defer="livestock_id"
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3
                        @error('livestock_id') border-red-500 @enderror" required>
                    <option value="">Pilih Ternak</option>
                    @foreach ($livestocks as $livestock)
                        @php
                            $eartag = $livestock->eartag_number ?? $livestock->eartag ?? $livestock->ear_tag ?? $livestock->rfid_number ?? '-';
                            $nama = $livestock->name ?? $livestock->nama ?? null;
                            $jenis = optional($livestock->livestockType)->name ?? '-';
                            $ras = optional($livestock->livestockBreed)->name ?? '-';
                            $label = trim($eartag . ($nama ? ' - ' . $nama : ''));
                            $label .= ' (' . $jenis . ($ras !== '-' ? ' - ' . $ras : '') . ')';
                        @endphp
                        <option value="{{ $livestock->id }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('livestock_id')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            {{-- waktu tindakan --}}
            <div class="mb-8">
                <label for="action_time" class="block mb-2 text-sm font-medium text-gray-700">Waktu Tindakan</label>
                <input id="action_time" wire:model.defer="action_time" type="time"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3
                       @error('action_time') border-red-500 @enderror" required>
                @error('action_time')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- 🔹 Ringkasan ternak --}}
        @if ($livestock_id)
            @php
                $selectedLivestock = $livestocks->firstWhere('id', $livestock_id);
            @endphp
            @if ($selectedLivestock)
                <div class="grid md:grid-cols-4 md:gap-6">
                    <div class="mb-8">
                        <label class="block mb-2 text-sm">Eartag</label>
                        <input type="text" value="{{ $selectedLivestock->eartag_number ?? '-' }}"
                               class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed"
                               readonly>
                    </div>
                    <div class="mb-8">
                        <label class="block mb-2 text-sm">Nama Ternak</label>
                        <input type="text" value="{{ $selectedLivestock->name ?? '-' }}"
                               class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed"
                               readonly>
                    </div>
                    <div class="mb-8">
                        <label class="block mb-2 text-sm">Jenis</label>
                        <input type="text" value="{{ optional($selectedLivestock->livestockType)->name ?? '-' }}"
                               class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed"
                               readonly>
                    </div>
                    <div class="mb-8">
                        <label class="block mb-2 text-sm">Ras</label>
                        <input type="text" value="{{ optional($selectedLivestock->livestockBreed)->name ?? '-' }}"
                               class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed"
                               readonly>
                    </div>
                </div>
            @endif
        @endif

        {{-- 🔹 Detail inseminasi --}}
        <div class="grid md:grid-cols-2 md:gap-6">
            <div class="mb-6">
                <label class="block mb-1 text-xs">Nama Petugas</label>
                <input type="text" wire:model.defer="officer_name"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5
                       @error('officer_name') border-red-500 @enderror" required>
                @error('officer_name')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block mb-1 text-xs">Ras/Breed Semen</label>
                <select wire:model.defer="semen_breed_id"
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5
                        @error('semen_breed_id') border-red-500 @enderror" required>
                    <option value="">Pilih ras/breed semen</option>
                    @foreach ($breeds as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
                @error('semen_breed_id')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block mb-1 text-xs">Nama Pejantan (Sire)</label>
                <input type="text" wire:model.defer="sire_name"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
            </div>

            <div class="mb-6">
                <label class="block mb-1 text-xs">Produsen Semen</label>
                <input type="text" wire:model.defer="semen_producer"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
            </div>

            <div class="mb-6">
                <label class="block mb-1 text-xs">Batch Semen</label>
                <input type="text" wire:model.defer="semen_batch"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
            </div>

            <div class="mb-6">
                <label class="block mb-1 text-xs">Biaya (Rp)</label>
                <input type="number" step="1" min="0" wire:model.defer="cost"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5
                       @error('cost') border-red-500 @enderror" required>
                @error('cost')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- catatan --}}
        <div class="mb-8">
            <label for="notes" class="block mb-2 text-sm">Catatan (opsional)</label>
            <textarea wire:model.defer="notes" rows="4"
                      class="block w-full text-sm border border-gray-300 rounded-lg p-3 bg-gray-50"></textarea>
        </div>

        {{-- tombol --}}
        <div class="flex justify-end mt-4 gap-3">
            <a href="{{ route('admin.care_livestock.artificial_inseminasi.index', ['farm_id' => $farm->id]) }}"
               class="text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-base px-8 py-3 transition-all">
                Batal
            </a>
            <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-base px-8 py-3 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan Data</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>

    {{-- 🔹 Event listener untuk error dari Livewire --}}
    <script>
        window.addEventListener('showError', event => {
            const message = event.detail.message || 'Terjadi kesalahan tidak diketahui.';
            alert(message);
        });
    </script>
</div>
