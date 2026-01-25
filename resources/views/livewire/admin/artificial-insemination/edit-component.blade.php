<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Ternak Indukan</label>
                    <div class="font-bold text-gray-900 text-lg">
                        {{ $aiRecord->reproductionCycle->livestock->identification_number }} - {{ $aiRecord->reproductionCycle->livestock->nickname ?? 'Tanpa Nama' }}
                    </div>
                </div>
                <div class="flex gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Inseminasi Ke-</label>
                        <div class="font-bold text-gray-900 text-lg">{{ $aiRecord->insemination_number }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Inseminasi" required />
            
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Waktu Tindakan <span class="text-red-500">*</span></label>
                <input type="time" wire:model="action_time" class="w-full px-4 py-3 border rounded-lg text-base" required>
                <x-form.error name="action_time" />
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Ras Semen <span class="text-red-500">*</span></label>
                <select wire:model="semen_breed_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">Pilih Ras Semen</option>
                    @foreach($breeds as $breed)
                        <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                    @endforeach
                </select>
                <x-form.error name="semen_breed_id" />
            </div>

            <x-form.input wire:model="officer_name" name="officer_name" label="Nama Inseminator / Petugas" required />
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4 p-6 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
            <x-form.input wire:model="sire_name" name="sire_name" label="Nama Pejantan (Sire)" placeholder="Contoh: Bull 305" required />
            <x-form.input wire:model="semen_producer" name="semen_producer" label="Produsen Semen" placeholder="Contoh: BIB Lembang" required />
            <x-form.input wire:model="semen_batch" name="semen_batch" label="Kode/Batch Semen" placeholder="Contoh: A-123" required />
        </div>

        <div class="mb-8">
             <x-form.input wire:model="cost" type="number" name="cost" label="Biaya Inseminasi (Rp)" required />
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Catatan Perubahan (opsional)" rows="3" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.artificial-inseminasi.show', [$farm->id, $aiRecord->id]) }}"
            submitLabel="Perbarui Data Inseminasi" 
        />
    </form>
</div>