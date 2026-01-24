<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Produksi" required />
            <x-form.input wire:model="milker_name" name="milker_name" label="Nama Pemerah" placeholder="Siapa pemerahnya?" required />
            <x-form.input wire:model="milk_condition" name="milk_condition" label="Kondisi Susu" placeholder="Contoh: Normal, Asam, Masam" required />
        </div>

        <div class="bg-white border rounded-lg overflow-hidden mb-8 shadow-sm">
            <div class="px-4 py-3 bg-gray-50 border-b flex justify-between items-center">
                <span class="font-bold text-gray-700 text-xs uppercase tracking-widest">Rincian Waktu & Volume</span>
                <button type="button" wire:click="addItem" class="text-xs text-blue-700 font-black hover:underline">+ TAMBAH BARIS</button>
            </div>
            <div class="p-4 space-y-4">
                @foreach($items as $index => $item)
                    <div class="flex items-end gap-4" wire:key="item-{{ $index }}">
                        <div class="flex-1">
                            <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase">Jam Perah</label>
                            <input type="time" wire:model="items.{{ $index }}.milking_time" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase">Volume (Liter)</label>
                            <input type="number" step="0.01" wire:model="items.{{ $index }}.volume" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        @if(count($items) > 1)
                            <button type="button" wire:click="removeItem({{ $index }})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg mb-0.5 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Catatan Tambahan" rows="2" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.milk-production-global.index', $farm->id) }}"
            submitLabel="Simpan Produksi" 
        />
    </form>
</div>