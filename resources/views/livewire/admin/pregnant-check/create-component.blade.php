<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        {{-- Section 1: General Info --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Tanggal Transaksi --}}
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Transaction Date" required />

            {{-- Waktu Tindakan --}}
            <x-form.input wire:model="action_time" type="time" name="action_time" label="Action Time" required />

            {{-- Pilihan Ternak --}}
            <x-form.select 
                wire:model="livestock_id" 
                name="livestock_id" 
                label="Female Livestock" 
                :options="$livestocks->pluck('identification_number', 'id')->toArray()"
                placeholder="Select Female"
                required 
            />
        </div>

        {{-- Section 2: Check Results --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Nama Petugas --}}
            <x-form.input wire:model="officer_name" name="officer_name" label="Officer Name" placeholder="Example: Dr. Smith" required />

            {{-- Status Kehamilan --}}
            <x-form.select 
                wire:model.live="status" 
                name="status" 
                label="Status" 
                :options="$checkStatuses"
                placeholder="Select Status"
                required 
            />

            {{-- Usia Kehamilan (Hanya jika Pregnant) --}}
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
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form.number wire:model="cost" name="cost" label="Check Cost (Rp)" :min="0" required />
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Notes (optional)" rows="3" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care_livestock.pregnant_check.index', ['farm_id' => $farm->id]) }}"
            submitLabel="Save Pregnant Check Data" 
        />
    </form>
</div>