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
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Kematian" required />
            <x-form.select wire:model="disease_id" name="disease_id" label="Penyakit (opsional)" :options="$diseases" placeholder="Pilih Penyakit" />
        </div>

        <div class="mb-8">
            <label class="block mb-2 text-base font-semibold text-gray-700">Pilih Ternak <span class="text-red-500">*</span></label>
            <select wire:model="livestock_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                <option value="">Pilih Ternak</option>
                @foreach($livestocks as $livestock)
                    <option value="{{ $livestock->id }}">
                        {{ $livestock->eartag_number }} - {{ $livestock->livestockType?->name }} ({{ $livestock->livestockBreed?->name }})
                    </option>
                @endforeach
            </select>
            <x-form.error name="livestock_id" />
        </div>

        <x-form.textarea wire:model="indication" name="indication" label="Indikasi Kematian" rows="3" placeholder="Gejala atau indikasi yang terlihat..." class="mb-8" />
        <x-form.textarea wire:model="notes" name="notes" label="Catatan (opsional)" rows="2" class="mb-8" />

        <div class="flex justify-end mt-8 gap-3">
            <a href="{{ route('admin.care-livestock.livestock-death.index', $farm->id) }}" class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all">
                Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-all disabled:opacity-50" wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan Perubahan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
