<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Treatment" required />
            
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

            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Penyakit <span class="text-red-500">*</span></label>
                <select wire:model="disease_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">Pilih Penyakit</option>
                    @foreach($diseases as $disease)
                        <option value="{{ $disease->id }}">{{ $disease->name }}</option>
                    @endforeach
                </select>
                <x-form.error name="disease_id" />
            </div>
        </div>

        <div class="space-y-8 mb-8">
            @include('livewire.admin.partials.treatment-medicine-items-repeater', ['medicines' => $medicines])
            
            @include('livewire.admin.partials.treatment-action-items-repeater', ['treatments' => $treatments])
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Catatan (opsional)" rows="2" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.treatment-colony.index', $farm->id) }}"
            submitLabel="Simpan Treatment Koloni" 
        />
    </form>
</div>