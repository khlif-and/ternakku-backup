<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        {{-- Header Information (Read Only) --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Info Ternak --}}
                <div>
                    <label class="block text-sm font-medium text-gray-500">Female Livestock</label>
                    <div class="font-bold text-gray-900 text-lg">
                        {{ $item->reproductionCycle->livestock->identification_number }} - 
                        {{ $item->reproductionCycle->livestock->nickname ?? 'No Name' }}
                    </div>
                </div>

                {{-- Info Nomor Transaksi --}}
                <div class="flex gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Transaction No.</label>
                        <div class="font-bold text-gray-900 text-lg">
                            {{ $item->pregnantCheck->transaction_number ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 1: Timing --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Transaction Date" required />
            
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Action Time <span class="text-red-500">*</span></label>
                <input type="time" wire:model="action_time" class="w-full px-4 py-3 border rounded-lg text-base" required>
                <x-form.error name="action_time" />
            </div>
        </div>

        {{-- Section 2: Result Data --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Officer Name --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Officer Name <span class="text-red-500">*</span></label>
                <input type="text" wire:model="officer_name" class="w-full px-4 py-3 border rounded-lg text-base" placeholder="Dr. Name" required>
                <x-form.error name="officer_name" />
            </div>

            {{-- Status --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Status <span class="text-red-500">*</span></label>
                <select wire:model.live="status" class="w-full px-4 py-3 border rounded-lg text-base" required>
                    <option value="">Select Status</option>
                    <option value="PREGNANT">Pregnant (Bunting)</option>
                    <option value="NOT_PREGNANT">Not Pregnant (Tidak Bunting)</option>
                </select>
                <x-form.error name="status" />
            </div>

            {{-- Pregnant Age (Conditional) --}}
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">
                    Pregnant Age (Month) 
                    @if($status === 'PREGNANT') <span class="text-red-500">*</span> @endif
                </label>
                <input type="number" 
                       wire:model="pregnant_age" 
                       class="w-full px-4 py-3 border rounded-lg text-base bg-white disabled:bg-gray-100 disabled:text-gray-400" 
                       min="0"
                       step="0.1"
                       @if($status !== 'PREGNANT') disabled @endif
                >
                <x-form.error name="pregnant_age" />
            </div>
        </div>

        {{-- Section 3: Cost & Notes --}}
        <div class="mb-8">
             <x-form.input wire:model="cost" type="number" name="cost" label="Check Cost (Rp)" required />
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Change Notes (optional)" rows="3" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care_livestock.pregnant_check.show', ['farm_id' => $farm->id, 'id' => $item->id]) }}"
            submitLabel="Update Pregnant Check" 
        />
    </form>
</div>