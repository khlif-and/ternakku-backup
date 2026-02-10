<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Produksi" required />
            <x-form.input wire:model="milker_name" name="milker_name" label="Nama Pemerah"
                placeholder="Siapa pemerahnya?" required />
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Shift Perah <span
                        class="text-red-500">*</span></label>
                <select wire:model="milking_shift"
                    class="w-full px-4 py-3 border rounded-lg text-base focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="">Pilih Shift</option>
                    <option value="morning">Pagi (Morning)</option>
                    <option value="afternoon">Sore (Afternoon)</option>
                </select>
                <x-form.error name="milking_shift" />
            </div>

            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Jam Perah <span
                        class="text-red-500">*</span></label>
                <input type="time" wire:model="milking_time"
                    class="w-full px-4 py-3 border rounded-lg text-base focus:ring-blue-500 focus:border-blue-500"
                    required>
                <x-form.error name="milking_time" />
            </div>

            <x-form.number wire:model="quantity_liters" name="quantity_liters" label="Volume Susu (Liter)" step="0.01"
                min="0" required />
        </div>

        <div class="mb-8">
            <x-form.input wire:model="milk_condition" name="milk_condition" label="Kondisi Susu"
                placeholder="Contoh: Normal, Asam, Masam" />
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Catatan Tambahan" rows="2" class="mb-8" />

        <x-form.footer backRoute="{{ route('admin.care-livestock.milk-production-global.index', $farm->id) }}"
            submitLabel="Simpan Produksi" />
    </form>
</div>