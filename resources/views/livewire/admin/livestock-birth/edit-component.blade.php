<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        {{-- Header Information (Read Only) --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Info Indukan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-500">Mother (Dam)</label>
                    <div class="font-bold text-gray-900 text-lg">
                        {{ $birth->reproductionCycle->livestock->identification_number }} - 
                        {{ $birth->reproductionCycle->livestock->nickname ?? 'No Name' }}
                    </div>
                </div>

                {{-- Info ID Kelahiran --}}
                <div class="flex gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Birth ID</label>
                        <div class="font-bold text-gray-900 text-lg">
                            #{{ $birth->id }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 1: General Info --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Tanggal Transaksi --}}
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Transaction Date" required />

            {{-- Nama Petugas --}}
            <x-form.input wire:model="officer_name" name="officer_name" label="Officer Name" placeholder="Dr. Name" />

            {{-- Estimasi Sapih --}}
            <x-form.date wire:model="estimated_weaning" name="estimated_weaning" label="Est. Weaning Date" />
        </div>

        {{-- Section 2: Status & Cost --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Status --}}
            <x-form.select 
                wire:model.live="status" 
                name="status" 
                label="Birth Status" 
                :options="$birthStatuses" 
                placeholder="Select Status"
                required 
            />

            {{-- Biaya --}}
            <x-form.number wire:model="cost" name="cost" label="Total Cost (Rp)" :min="0" required />
        </div>

        {{-- Section 3: Offspring Details (Dynamic) --}}
        @if($status !== 'ABORTUS')
            <div class="mb-8 border-t pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Offspring Details</h3>
                    <x-button.primary type="button" wire:click="addDetail" class="text-sm">
                        + Add Offspring
                    </x-button.primary>
                </div>

                @if(empty($details))
                    <x-table.empty message="No offspring data. Please add details." />
                @endif

                @foreach($details as $index => $detail)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4 bg-gray-50 relative">
                        <div class="absolute top-4 right-4">
                            @if(count($details) > 1)
                                <button type="button" wire:click="removeDetail({{ $index }})" class="text-red-500 hover:text-red-700 font-bold text-sm">
                                    Remove
                                </button>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            {{-- Urutan Lahir --}}
                            <x-form.number 
                                wire:model="details.{{ $index }}.birth_order" 
                                name="details.{{ $index }}.birth_order" 
                                label="Birth Order" 
                                :min="1" 
                                required 
                            />

                            {{-- Jenis Kelamin --}}
                            <x-form.select 
                                wire:model="details.{{ $index }}.livestock_sex_id" 
                                name="details.{{ $index }}.livestock_sex_id" 
                                label="Sex" 
                                :options="[
                                    \App\Enums\LivestockSexEnum::JANTAN->value => 'Jantan (Male)',
                                    \App\Enums\LivestockSexEnum::BETINA->value => 'Betina (Female)',
                                ]"
                                placeholder="Select Sex"
                                required 
                            />

                            {{-- Ras/Breed --}}
                            <x-form.select 
                                wire:model="details.{{ $index }}.livestock_breed_id" 
                                name="details.{{ $index }}.livestock_breed_id" 
                                label="Breed" 
                                :options="$breeds->pluck('name', 'id')->toArray()"
                                placeholder="Select Breed"
                                required 
                            />

                            {{-- Berat Lahir --}}
                            <x-form.number 
                                wire:model="details.{{ $index }}.weight" 
                                name="details.{{ $index }}.weight" 
                                label="Weight (kg)" 
                                step="0.01"
                                required 
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Kondisi --}}
                            <x-form.select 
                                wire:model.live="details.{{ $index }}.status" 
                                name="details.{{ $index }}.status" 
                                label="Condition" 
                                :options="$offspringStatuses"
                                placeholder="Select Condition"
                                required 
                            />

                            {{-- Conditional Inputs --}}
                            @if($details[$index]['status'] === 'alive')
                                <x-form.number 
                                    wire:model="details.{{ $index }}.offspring_value" 
                                    name="details.{{ $index }}.offspring_value" 
                                    label="Value (Rp)" 
                                    placeholder="0"
                                />
                            @else
                                <x-form.select 
                                    wire:model="details.{{ $index }}.disease_id" 
                                    name="details.{{ $index }}.disease_id" 
                                    label="Disease" 
                                    :options="$diseases->pluck('name', 'id')->toArray()"
                                    placeholder="Select Disease (Optional)"
                                />
                                <x-form.input 
                                    wire:model="details.{{ $index }}.indication" 
                                    name="details.{{ $index }}.indication" 
                                    label="Indication" 
                                    placeholder="Cause of death"
                                />
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Section 4: Notes --}}
        <x-form.textarea wire:model="notes" name="notes" label="Notes (optional)" rows="3" class="mb-8" />

        <x-form.footer 
            backRoute="{{ route('admin.care_livestock.livestock_birth.show', ['farm_id' => $farm->id, 'id' => $birth->id]) }}"
            submitLabel="Update Birth Data" 
        />
    </form>
</div>