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
            <x-form.input wire:model="action_time" type="time" name="action_time" label="Action Time" required />
        </div>

        {{-- Section 2: Result Data --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Officer Name --}}
            <x-form.input wire:model="officer_name" name="officer_name" label="Officer Name" placeholder="Dr. Name" required />

            {{-- Status --}}
            <x-form.select 
                wire:model.live="status" 
                name="status" 
                label="Status" 
                :options="$checkStatuses"
                placeholder="Select Status"
                required 
            />

            {{-- Pregnant Age (Conditional) --}}
            <x-form.number 
                wire:model="pregnant_age" 
                name="pregnant_age" 
                label="Pregnant Age (Month)" 
                :min="0"
                step="0.1"
                :disabled="$status !== 'PREGNANT'"
                :required="$status === 'PREGNANT'"
            />
        </div>

        {{-- Section 3: Cost & Notes --}}
        <div class="mb-8">
            <x-form.number wire:model="cost" name="cost" label="Check Cost (Rp)" :min="0" required />
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Change Notes (optional)" rows="3" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care_livestock.pregnant_check.show', ['farm_id' => $farm->id, 'id' => $item->id]) }}"
            submitLabel="Update Pregnant Check" 
        />
    </form>
</div>