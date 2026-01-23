<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

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

        @include('livewire.admin.partials.feed-items-repeater', ['items' => $items])

        <x-form.textarea wire:model="notes" name="notes" label="Catatan (opsional)" rows="2" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.feeding-colony.index', $farm->id) }}"
            submitLabel="Simpan Perubahan" 
        />
    </form>
</div>
