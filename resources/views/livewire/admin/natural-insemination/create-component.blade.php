<div> <x-alert.session /> <x-alert.validation-errors :errors="$errors" />

<form wire:submit.prevent="save" class="w-full">
    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-form.date wire:model="transaction_date" name="transaction_date" label="Transaction Date" required />
        
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
    </div>

    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block mb-2 text-base font-semibold text-gray-700">Action Time <span class="text-red-500">*</span></label>
            <input type="time" wire:model="action_time" class="w-full px-4 py-3 border rounded-lg text-base" required>
            <x-form.error name="action_time" />
        </div>

        <div>
            <label class="block mb-2 text-base font-semibold text-gray-700">Sire Owner Name <span class="text-red-500">*</span></label>
            <input type="text" wire:model="sire_owner_name" class="w-full px-4 py-3 border rounded-lg text-base" placeholder="Example: John Doe" required>
            <x-form.error name="sire_owner_name" />
        </div>

        <div>
            <label class="block mb-2 text-base font-semibold text-gray-700">Action Cost (Rp) <span class="text-red-500">*</span></label>
            <input type="number" wire:model="cost" class="w-full px-4 py-3 border rounded-lg text-base" required>
            <x-form.error name="cost" />
        </div>
    </div>

    <x-form.textarea wire:model="notes" name="notes" label="Notes (optional)" rows="3" class="mb-8" />

    <x-form.footer 
        backRoute="{{ route('admin.care-livestock.natural-insemination.index', $farm->id) }}"
        submitLabel="Save Natural Insemination Data" 
    />
</form>
</div>