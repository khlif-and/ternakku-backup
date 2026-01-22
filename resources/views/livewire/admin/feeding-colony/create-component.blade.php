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
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Pemberian Pakan" required />
            
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Kandang <span class="text-red-500">*</span></label>
                <select wire:model="pen_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">Pilih Kandang</option>
                    @foreach($pens as $pen)
                        <option value="{{ $pen->id }}">{{ $pen->name }} ({{ $pen->livestocks->count() }} ekor)</option>
                    @endforeach
                </select>
                <x-form.error name="pen_id" />
            </div>
        </div>

        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <label class="text-base font-semibold text-gray-700">Item Pakan</label>
                <button type="button" wire:click="addItem" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-all">
                    + Tambah Item
                </button>
            </div>

            <div class="space-y-4">
                @foreach($items as $index => $item)
                    <div class="p-4 bg-gray-50 rounded-lg border">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <x-form.select wire:model="items.{{ $index }}.type" label="Jenis" :options="['forage' => 'Hijauan', 'concentrate' => 'Konsentrat', 'feed_material' => 'Bahan Pakan']" />
                            <x-form.input wire:model="items.{{ $index }}.name" label="Nama" placeholder="Nama item" />
                            <x-form.number wire:model="items.{{ $index }}.qty_kg" label="Jumlah (kg)" step="0.01" placeholder="0.00" />
                            <x-form.number wire:model="items.{{ $index }}.price_per_kg" label="Harga/kg" step="0.01" placeholder="0" />
                            <div class="flex items-end">
                                @if(count($items) > 1)
                                    <button type="button" wire:click="removeItem({{ $index }})" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-all">
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <x-form.error name="items" />
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Catatan (opsional)" rows="2" class="mb-8" />

        <div class="flex justify-end mt-8 gap-3">
            <a href="{{ route('admin.care-livestock.feeding-colony.index', $farm->id) }}" class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all">
                Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-all disabled:opacity-50" wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan Pemberian Pakan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
