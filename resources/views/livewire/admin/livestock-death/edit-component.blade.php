<div>
    @if (session('error'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-red-100 border border-red-400 text-red-700 font-medium">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <p class="font-semibold mb-2">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Tanggal Kematian</label>
                <input type="date" wire:model="transaction_date"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('transaction_date') border-red-500 @enderror" required>
                @error('transaction_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Penyakit (opsional)</label>
                <select wire:model="disease_id" class="w-full px-4 py-3 border rounded-lg text-base">
                    <option value="">Pilih Penyakit</option>
                    @foreach($diseases as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-8">
            <label class="block mb-2 text-base font-semibold text-gray-700">Pilih Ternak</label>
            <select wire:model="livestock_id"
                class="w-full px-4 py-3 border rounded-lg text-base @error('livestock_id') border-red-500 @enderror" required>
                <option value="">Pilih Ternak</option>
                @foreach($livestocks as $livestock)
                    <option value="{{ $livestock->id }}">
                        {{ $livestock->eartag_number }} - {{ $livestock->livestockType?->name }} ({{ $livestock->livestockBreed?->name }})
                    </option>
                @endforeach
            </select>
            @error('livestock_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-8">
            <label class="block mb-2 text-base font-semibold text-gray-700">Indikasi Kematian</label>
            <textarea wire:model="indication" rows="3"
                class="w-full px-4 py-3 border rounded-lg text-base" placeholder="Gejala atau indikasi yang terlihat..."></textarea>
        </div>

        <div class="mb-8">
            <label class="block mb-2 text-base font-semibold text-gray-700">Catatan (opsional)</label>
            <textarea wire:model="notes" rows="2" class="w-full px-4 py-3 border rounded-lg text-base"></textarea>
        </div>

        <div class="flex justify-end mt-8 gap-3">
            <a href="{{ route('admin.care-livestock.livestock-death.index', $farm->id) }}"
                class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-all disabled:opacity-50"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Perbarui Data</span>
                <span wire:loading>Memperbarui...</span>
            </button>
        </div>
    </form>
</div>
