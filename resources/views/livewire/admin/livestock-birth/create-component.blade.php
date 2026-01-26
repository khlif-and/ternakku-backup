<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        {{-- Section 1: General Info --}}
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Umum</h3>
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Tanggal Transaksi --}}
            <x-form.date wire:model="transaction_date" name="transaction_date" label="Transaction Date" required />

            {{-- Nama Petugas --}}
            <x-form.input wire:model="officer_name" name="officer_name" label="Officer Name" placeholder="Example: Dr. Smith" />

            {{-- Pilihan Indukan (Dam) --}}
            <x-form.select 
                wire:model="livestock_id" 
                name="livestock_id" 
                label="Mother (Dam)" 
                :options="$femaleLivestocks->pluck('identification_number', 'id')->toArray()" 
                placeholder="Select Mother"
                required 
            />
        </div>

        {{-- Section 2: Birth Status & Cost --}}
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Status & Biaya</h3>
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Status Kelahiran --}}
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

            {{-- Estimasi Sapih --}}
            <x-form.date wire:model="estimated_weaning" name="estimated_weaning" label="Est. Weaning Date" />
        </div>

        {{-- Section 3: Offspring Details (Dynamic) --}}
        @if($status !== 'ABORTUS')
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-lg font-bold text-gray-800">Detail Anak Ternak (Offspring)</h3>
                    <x-button.primary type="button" wire:click="addDetail" class="text-sm">
                        + Add Offspring
                    </x-button.primary>
                </div>

                @if(empty($details))
                    <x-table.empty message="Belum ada data anak ternak. Klik tombol di atas untuk menambahkan." />
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
                                    label="Offspring Value (Rp)" 
                                    placeholder="Estimasi nilai"
                                />
                            @else
                                <x-form.select 
                                    wire:model="details.{{ $index }}.disease_id" 
                                    name="details.{{ $index }}.disease_id" 
                                    label="Disease Cause" 
                                    :options="$diseases->pluck('name', 'id')->toArray()"
                                    placeholder="Select Disease (Optional)"
                                />
                                <x-form.input 
                                    wire:model="details.{{ $index }}.indication" 
                                    name="details.{{ $index }}.indication" 
                                    label="Indication/Cause" 
                                    placeholder="Explain cause of death"
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
            backRoute="{{ route('admin.care_livestock.livestock_birth.index', ['farm_id' => $farm->id]) }}"
            submitLabel="Save Birth Data" 
        />
    </form>
</div>