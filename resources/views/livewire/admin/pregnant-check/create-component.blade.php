<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        {{-- Section 1: General Info --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Tanggal Transaksi --}}
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Transaction Date" required />

            {{-- Waktu Tindakan --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Action Time <span class="text-red-500">*</span></label>
                <input type="time" wire:model="action_time" class="w-full px-4 py-3 border rounded-lg text-base" required>
                <x-form.error name="action_time" />
            </div>

            {{-- Pilihan Ternak --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Female Livestock <span class="text-red-500">*</span></label>
                <select wire:model="livestock_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">Select Female</option>
                    @foreach($livestocks as $livestock)
                        <option value="{{ $livestock->id }}">
                            {{ $livestock->identification_number }} - {{ $livestock->nickname ?? 'No Name' }}
                        </option>
                    @endforeach
                </select>
                <x-form.error name="livestock_id" />
            </div>
        </div>

        {{-- Section 2: Check Results --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Nama Petugas --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Officer Name <span class="text-red-500">*</span></label>
                <input type="text" wire:model="officer_name" class="w-full px-4 py-3 border rounded-lg text-base" placeholder="Example: Dr. Smith" required>
                <x-form.error name="officer_name" />
            </div>

            {{-- Status Kehamilan --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Status <span class="text-red-500">*</span></label>
                <select wire:model.live="status" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">Select Status</option>
                    <option value="PREGNANT">Pregnant (Bunting)</option>
                    <option value="NOT_PREGNANT">Not Pregnant (Tidak Bunting)</option>
                </select>
                <x-form.error name="status" />
            </div>

            {{-- Usia Kehamilan (Hanya jika Pregnant) --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">
                    Pregnant Age (Month) 
                    @if($status === 'PREGNANT') <span class="text-red-500">*</span> @endif
                </label>
                <input type="number" 
                       wire:model="pregnant_age" 
                       class="w-full px-4 py-3 border rounded-lg text-base bg-gray-50 disabled:bg-gray-200 disabled:text-gray-400" 
                       min="0"
                       step="0.1"
                       @if($status !== 'PREGNANT') disabled @endif
                       placeholder="0"
                >
                <x-form.error name="pregnant_age" />
            </div>
        </div>

        {{-- Section 3: Cost & Notes --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Check Cost (Rp) <span class="text-red-500">*</span></label>
                <input type="number" wire:model="cost" class="w-full px-4 py-3 border rounded-lg text-base" required>
                <x-form.error name="cost" />
            </div>
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Notes (optional)" rows="3" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care_livestock.pregnant_check.index', ['farm_id' => $farm->id]) }}"
            submitLabel="Save Pregnant Check Data" 
        />
    </form>
</div>