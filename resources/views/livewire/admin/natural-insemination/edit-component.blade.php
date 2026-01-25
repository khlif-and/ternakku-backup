<div> <x-alert.session /> <x-alert.validation-errors :errors="$errors" />

<form wire:submit.prevent="save" class="w-full">
    <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Female Livestock</label>
                <div class="font-bold text-gray-900 text-lg">
                    {{ $niRecord->reproductionCycle->livestock->identification_number }} - {{ $niRecord->reproductionCycle->livestock->nickname ?? 'No Name' }}
                </div>
            </div>
            <div class="flex gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Insemination No.</label>
                    <div class="font-bold text-gray-900 text-lg">{{ $niRecord->insemination_number }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form.date wire:model="transaction_date" name="transaction_date" label="Transaction Date" required />
        
        <div>
            <label class="block mb-2 text-base font-semibold text-gray-700">Action Time <span class="text-red-500">*</span></label>
            <input type="time" wire:model="action_time" class="w-full px-4 py-3 border rounded-lg text-base" required>
            <x-form.error name="action_time" />
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-2 text-base font-semibold text-gray-700">Sire Breed <span class="text-red-500">*</span></label>
            <select wire:model="sire_breed_id" class="w-full px-4 py-3 border rounded-lg text-base" required>
                <option value="">Select Breed</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
            <x-form.error name="sire_breed_id" />
        </div>

        <x-form.input wire:model="sire_owner_name" name="sire_owner_name" label="Sire Owner Name" required />
    </div>

    <div class="mb-8">
         <x-form.input wire:model="cost" type="number" name="cost" label="Insemination Cost (Rp)" required />
    </div>

    <x-form.textarea wire:model="notes" name="notes" label="Change Notes (optional)" rows="3" class="mb-8" />

    <x-form.footer 
        backRoute="{{ route('admin.care-livestock.natural-insemination.show', [$farm->id, $niRecord->id]) }}"
        submitLabel="Update Insemination Data" 
    />
</form>
</div>