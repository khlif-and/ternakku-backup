<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form.date 
                wire:model="transaction_date" 
                name="transaction_date" 
                label="Transaction Date" 
                required 
            />

            <x-form.select
                wire:model="customer_id"
                name="customer_id"
                label="Customer"
                :options="$customers->pluck('name', 'id')->toArray()"
                placeholder="Select Customer"
                required
            />

            <x-form.select
                wire:model="sales_order_id"
                name="sales_order_id"
                label="Sales Order (Optional)"
                :options="$salesOrders->mapWithKeys(fn($o) => [$o->id => 'Order #' . $o->id . ' - ' . $o->order_date])->toArray()"
                placeholder="Select Order Ref"
            />
        </div>

        <div class="mb-8">
            <div class="flex items-center justify-between mb-4 border-b pb-2">
                <h3 class="text-lg font-semibold text-gray-700">Livestock Items</h3>
                <button type="button" wire:click="addDetail" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    + Add Item
                </button>
            </div>

            <div class="space-y-4">
                @foreach($details as $index => $detail)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 relative">
                        
                        @if(count($details) > 1)
                            <button type="button" wire:click="removeDetail({{ $index }})" class="absolute top-3 right-3 text-red-400 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 pr-8">
                            <x-form.select
                                wire:model="details.{{ $index }}.livestock_id"
                                name="details.{{ $index }}.livestock_id"
                                label="Livestock"
                                :options="$livestocks->mapWithKeys(fn($l) => [$l->id => $l->identification_number . ' - ' . ($l->nickname ?? 'No Name')])->toArray()"
                                placeholder="Select Livestock"
                                required
                            />
                            
                            <x-form.date 
                                wire:model="details.{{ $index }}.delivery_plan_date" 
                                name="details.{{ $index }}.delivery_plan_date" 
                                label="Delivery Plan Date" 
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-form.number 
                                wire:model="details.{{ $index }}.weight" 
                                name="details.{{ $index }}.weight" 
                                label="Weight (Kg)" 
                                step="0.01"
                                required 
                            />

                            <x-form.number 
                                wire:model="details.{{ $index }}.price_per_kg" 
                                name="details.{{ $index }}.price_per_kg" 
                                label="Price / Kg (Rp)" 
                                required 
                            />

                            <x-form.number 
                                wire:model="details.{{ $index }}.price_per_head" 
                                name="details.{{ $index }}.price_per_head" 
                                label="Total / Head (Rp)" 
                                required 
                            />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <x-form.textarea wire:model="notes" name="notes" label="Notes (optional)" rows="3" class="mb-8" />

        <x-form.footer
            backRoute="{{ route('admin.care-livestock.sales-livestock.show', [$farm->id, $salesLivestock->id]) }}"
            submitLabel="Update Sales Data"
        />
    </form>
</div>