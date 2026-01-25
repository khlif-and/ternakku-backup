<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Tanggal Mutasi --}}
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Mutasi" required />
            
            {{-- Pilih Ternak --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Ternak <span class="text-red-500">*</span></label>
                <select wire:model.live="livestock_id" class="w-full px-4 py-3 border rounded-lg text-base focus:ring-blue-500" required>
                    <option value="">Pilih Ternak</option>
                    @foreach($livestocks as $livestock)
                        <option value="{{ $livestock->id }}">
                            {{ $livestock->identification_number }} - {{ $livestock->nickname ?? 'Tanpa Nama' }} 
                            (Kandang: {{ $livestock->pen->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                <x-form.error name="livestock_id" />
            </div>

            {{-- Kandang Tujuan --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Kandang Tujuan <span class="text-red-500">*</span></label>
                <select wire:model="pen_destination" class="w-full px-4 py-3 border rounded-lg text-base focus:ring-blue-500" required>
                    <option value="">Pilih Kandang Tujuan</option>
                    @foreach($pens as $pen)
                        <option value="{{ $pen->id }}">
                            {{ $pen->name }} (Kapasitas: {{ $pen->livestocks->count() }} ekor)
                        </option>
                    @endforeach
                </select>
                <x-form.error name="pen_destination" />
            </div>
        </div>

        {{-- Info Alert (Opsional: Memberi konteks agar user tidak bingung) --}}
        <div class="mb-8 p-4 bg-blue-50 border border-blue-100 rounded-lg flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-blue-800">
                Memindahkan ternak akan secara otomatis memperbarui lokasi kandang ternak tersebut dan mencatat riwayat perpindahannya.
            </div>
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Alasan Mutasi / Catatan (opsional)" rows="3" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.mutation-individu.index', $farm->id) }}"
            submitLabel="Simpan Mutasi Individu" 
        />
    </form>
</div>