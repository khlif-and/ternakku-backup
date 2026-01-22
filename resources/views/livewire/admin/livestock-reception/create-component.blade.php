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
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Transaksi" required />
            <x-form.file-upload wire:model="photo" name="photo" label="Foto (opsional)" accept="image/*" />
        </div>

        <x-form.input wire:model="supplier" name="supplier" label="Supplier (opsional)" class="mb-8" />

        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.input wire:model="eartag_number" name="eartag_number" label="Eartag Number" required />
            <x-form.input wire:model="rfid_number" name="rfid_number" label="RFID Number (opsional)" />
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Jenis Ternak <span class="text-red-500">*</span></label>
                <select wire:model.live="livestock_type_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">Pilih Jenis Ternak</option>
                    @foreach($livestockTypes as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                <x-form.error name="livestock_type_id" />
            </div>
            <x-form.select wire:model="livestock_sex_id" name="livestock_sex_id" label="Jenis Kelamin" :options="$sexes" placeholder="Pilih Jenis Kelamin" required />
            <x-form.select wire:model="livestock_group_id" name="livestock_group_id" label="Grup" :options="$groups" placeholder="Pilih Grup" required />
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Ras <span class="text-red-500">*</span></label>
                <select wire:model="livestock_breed_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">{{ $livestock_type_id ? 'Pilih Ras' : 'Pilih jenis ternak dulu' }}</option>
                    @foreach($breeds as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                <x-form.error name="livestock_breed_id" />
            </div>
            <x-form.select wire:model="livestock_classification_id" name="livestock_classification_id" label="Klasifikasi" :options="$classifications" placeholder="Pilih Klasifikasi" required />
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Kandang <span class="text-red-500">*</span></label>
                <select wire:model="pen_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">Pilih Kandang</option>
                    @foreach($pens as $pen)
                        <option value="{{ $pen->id }}">{{ $pen->name }} (Kapasitas: {{ $pen->capacity }})</option>
                    @endforeach
                </select>
                <x-form.error name="pen_id" />
            </div>
        </div>

        <div class="mb-8">
            <label class="block mb-2 text-base font-semibold text-gray-700">Usia (Tahun / Bulan)</label>
            <div class="flex gap-4">
                <x-form.number wire:model="age_years" min="0" placeholder="Tahun" class="w-1/2" />
                <x-form.number wire:model="age_months" min="0" max="11" placeholder="Bulan" class="w-1/2" />
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form.number wire:model.live="weight" name="weight" label="Berat (kg)" step="0.01" required />
            <x-form.number wire:model.live="price_per_kg" name="price_per_kg" label="Harga per Kg" step="0.01" required />
            <x-form.disabled label="Harga per Kepala" :value="number_format($price_per_head ?? 0, 0, ',', '.')" />
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Catatan (opsional)" rows="3" class="mb-8" />
        <x-form.textarea wire:model="characteristics" name="characteristics" label="Karakteristik (opsional)" rows="2" class="mb-8" />

        <div class="flex justify-end mt-8 gap-3">
            <a href="{{ route('admin.care-livestock.livestock-reception.index', $farm->id) }}" class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all">
                Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-all disabled:opacity-50" wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan Registrasi</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
