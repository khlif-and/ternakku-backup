<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3">Informasi Penerimaan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Penerimaan" />
                <x-form.input wire:model="supplier" name="supplier" label="Supplier" placeholder="Nama supplier" />
            </div>

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 pt-2">Data Ternak</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input wire:model="eartag_number" name="eartag_number" label="Nomor Eartag"
                    placeholder="Nomor eartag" />
                <x-form.input wire:model="rfid_number" name="rfid_number" label="Nomor RFID"
                    placeholder="Nomor RFID (opsional)" />

                <x-form.select wire:model="livestock_type_id" name="livestock_type_id" label="Jenis Ternak"
                    :options="$livestockTypes->pluck('name', 'id')" placeholder="Pilih Jenis Ternak" />

                <x-form.select wire:model="livestock_breed_id" name="livestock_breed_id" label="Ras Ternak"
                    :options="$livestockBreeds->pluck('name', 'id')" placeholder="Pilih Ras" />

                <x-form.select wire:model="livestock_sex_id" name="livestock_sex_id" label="Jenis Kelamin"
                    :options="$livestockSexes->pluck('name', 'id')" placeholder="Pilih Kelamin" />

                <x-form.select wire:model="livestock_group_id" name="livestock_group_id" label="Kelompok Ternak"
                    :options="$livestockGroups->pluck('name', 'id')" placeholder="Pilih Kelompok" />

                <x-form.select wire:model="livestock_classification_id" name="livestock_classification_id"
                    label="Klasifikasi" :options="$livestockClassifications->pluck('name', 'id')"
                    placeholder="Pilih Klasifikasi" />

                <x-form.select wire:model="pen_id" name="pen_id" label="Kandang" :options="$pens->pluck('name', 'id')"
                    placeholder="Pilih Kandang" />

                <x-form.input wire:model="age_years" name="age_years" label="Umur (Tahun)" type="number" min="0" />
                <x-form.input wire:model="age_months" name="age_months" label="Umur (Bulan)" type="number" min="0"
                    max="11" />

                <x-form.input wire:model="weight" name="weight" label="Berat (Kg)" type="number" step="0.1" min="0" />
            </div>

            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 pt-2">Harga</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-form.input wire:model="price_per_kg" name="price_per_kg" label="Harga per Kg" type="number"
                    min="0" />
                <x-form.input wire:model="price_per_head" name="price_per_head" label="Harga per Ekor" type="number"
                    min="0" />
                <x-form.input wire:model="qurban_price" name="qurban_price" label="Harga Qurban" type="number"
                    min="0" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.textarea wire:model="notes" name="notes" label="Catatan" placeholder="Catatan (opsional)"
                    rows="3" />
                <x-form.input wire:model="characteristics" name="characteristics" label="Ciri-ciri"
                    placeholder="Ciri-ciri ternak (opsional)" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('qurban.livestock-reception.index') }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan Perubahan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>