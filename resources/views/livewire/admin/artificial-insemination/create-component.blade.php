<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Inseminasi" required />
            
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Indukan Betina <span class="text-red-500">*</span></label>
                <select wire:model="livestock_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">Pilih Indukan</option>
                    @foreach($livestocks as $livestock)
                        <option value="{{ $livestock->id }}">
                            {{ $livestock->identification_number }} - {{ $livestock->nickname ?? 'Tanpa Nama' }}
                        </option>
                    @endforeach
                </select>
                <x-form.error name="livestock_id" />
            </div>

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
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Waktu Tindakan <span class="text-red-500">*</span></label>
                <input type="time" wire:model="action_time" class="w-full px-4 py-3 border rounded-lg text-base" required>
                <x-form.error name="action_time" />
            </div>

            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Nama Inseminator <span class="text-red-500">*</span></label>
                <input type="text" wire:model="officer_name" class="w-full px-4 py-3 border rounded-lg text-base" placeholder="Contoh: Drh. Budi" required>
                <x-form.error name="officer_name" />
            </div>

            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Biaya Tindakan (Rp) <span class="text-red-500">*</span></label>
                <input type="number" wire:model="cost" class="w-full px-4 py-3 border rounded-lg text-base" required>
                <x-form.error name="cost" />
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4 p-6 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Nama Pejantan (Sire) <span class="text-red-500">*</span></label>
                <input type="text" wire:model="sire_name" class="w-full px-4 py-3 border rounded-lg text-base" placeholder="Contoh: Bull 305" required>
                <x-form.error name="sire_name" />
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Produsen Semen (BIB) <span class="text-red-500">*</span></label>
                <input type="text" wire:model="semen_producer" class="w-full px-4 py-3 border rounded-lg text-base" placeholder="Contoh: BIB Lembang" required>
                <x-form.error name="semen_producer" />
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Kode/Batch Semen <span class="text-red-500">*</span></label>
                <input type="text" wire:model="semen_batch" class="w-full px-4 py-3 border rounded-lg text-base" placeholder="Contoh: A-123" required>
                <x-form.error name="semen_batch" />
            </div>
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Catatan (opsional)" rows="2" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.artificial-inseminasi.index', $farm->id) }}"
            submitLabel="Simpan Data Inseminasi" 
        />
    </form>
</div>