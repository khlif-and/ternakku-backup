<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tanggal Mutasi --}}
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Mutasi" required />
            
            {{-- Kandang Tujuan --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Kandang Tujuan <span class="text-red-500">*</span></label>
                <select wire:model="pen_destination" class="w-full px-4 py-3 border rounded-lg text-base focus:ring-blue-500 focus:border-blue-500" required>
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

        <div class="mb-8 p-4 bg-yellow-50 border border-yellow-100 rounded-lg flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="text-sm text-yellow-800">
                <strong>Informasi:</strong> Anda sedang mengubah data mutasi untuk ternak <strong>{{ $mutationIndividu->livestock->identification_number }}</strong>. 
                Kandang asal mutasi ini adalah <strong>{{ $mutationIndividu->fromPen?->name ?? 'N/A' }}</strong>.
            </div>
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Alasan Perubahan / Catatan (opsional)" rows="3" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.mutation-individu.show', [$farm->id, $mutationIndividu->id]) }}"
            submitLabel="Perbarui Mutasi Individu" 
        />
    </form>
</div>