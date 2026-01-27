<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Transaction Date" required />

            <x-form.select
                wire:model="livestock_id"
                name="livestock_id"
                label="Female Livestock"
                :options="$livestocks->mapWithKeys(fn($l) => [$l->id => $l->identification_number . ' - ' . ($l->nickname ?? 'No Name')])->toArray()"
                placeholder="Select Female"
                required
            />

            <x-form.select
                wire:model="sire_breed_id"
                name="sire_breed_id"
                label="Sire Breed"
                :options="$breeds->pluck('name', 'id')->toArray()"
                placeholder="Select Breed"
                required
            />
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form.clock wire:model="action_time" name="action_time" label="Action Time" required />

            <x-form.input wire:model="sire_owner_name" name="sire_owner_name" label="Sire Owner Name" placeholder="Example: John Doe" required />

            <x-form.number wire:model="cost" name="cost" label="Action Cost (Rp)" required />
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Notes (optional)" rows="3" class="mb-8" />

        <x-form.footer
            backRoute="{{ route('admin.care-livestock.natural-insemination.index', $farm->id) }}"
            submitLabel="Save Natural Insemination Data"
        />
    </form>
</div>