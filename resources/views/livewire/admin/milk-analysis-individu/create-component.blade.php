<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Analisis" required />
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-widest">Pilih Ternak (Betina)</label>
                <select wire:model="livestock_id" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Pilih Ternak --</option>
                    @foreach($livestocks as $livestock)
                        <option value="{{ $livestock->id }}">{{ $livestock->full_name }} [{{ $livestock->eartag_code }}]</option>
                    @endforeach
                </select>
            </div>

            <x-form.input wire:model="notes" name="notes" label="Keterangan Sampel" placeholder="Contoh: Sampel Pagi / Pemerahan Ke-2" />
        </div>

        <div class="bg-white border rounded-lg overflow-hidden mb-8 shadow-sm">
            <div class="px-4 py-3 bg-gray-50 border-b">
                <span class="font-bold text-gray-700 text-xs uppercase tracking-widest">Hasil Pengujian Laboratorium Individu</span>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <x-form.input type="number" step="0.0001" wire:model="bj" name="bj" label="Berat Jenis (BJ)" placeholder="1.028" />
                    
                    <div class="flex flex-col">
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Uji Alkohol (AT)</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="at" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-600">{{ $at ? 'Positif' : 'Negatif' }}</span>
                        </label>
                    </div>

                    <div class="flex flex-col">
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Uji Didih (AB)</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="ab" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-600">{{ $ab ? 'Positif' : 'Negatif' }}</span>
                        </label>
                    </div>

                    <x-form.input type="number" step="0.1" wire:model="mbrt" name="mbrt" label="MBRT (Jam)" placeholder="5.0" />

                    <x-form.input type="number" step="0.01" wire:model="fat" name="fat" label="Lemak / Fat (%)" placeholder="0.00" />
                    <x-form.input type="number" step="0.01" wire:model="protein" name="protein" label="Protein (%)" placeholder="0.00" />
                    <x-form.input type="number" step="0.01" wire:model="snf" name="snf" label="SNF (%)" placeholder="0.00" />
                    <x-form.input type="number" step="0.01" wire:model="ts" name="ts" label="Total Solid (TS)" placeholder="0.00" />

                    <x-form.input type="number" step="0.01" wire:model="a_water" name="a_water" label="Added Water (%)" placeholder="0.00" />
                </div>
            </div>
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Catatan Tambahan / Kesimpulan" rows="3" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.milk-analysis-individu.index', $farm->id) }}"
            submitLabel="Simpan Analisis Individu" 
        />
    </form>
</div>