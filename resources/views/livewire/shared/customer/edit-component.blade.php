<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Editing Customer</label>
                    <div class="font-bold text-gray-900 text-lg">
                        {{ $customer->name }}
                    </div>
                </div>
                <div class="flex gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Customer ID</label>
                        <div class="font-bold text-gray-900 text-lg text-right">#{{ $customer->id }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-md font-semibold text-gray-700 mb-4 uppercase tracking-wider text-xs border-b pb-2">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-form.input wire:model="name" name="name" label="Nama Lengkap" required />
                <x-form.input wire:model="phone" name="phone" label="No. Telepon" />
                <x-form.input wire:model="email" name="email" label="Email" type="email" />
            </div>
        </div>

        <div class="mb-8">
            <div class="flex justify-between items-end mb-4">
                <h3 class="text-md font-semibold text-gray-700 uppercase tracking-wider text-xs border-b pb-2 flex-grow">Daftar Alamat</h3>
                <button type="button" wire:click="addAddress" class="ml-4 text-sm bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition shadow-sm">
                    + Tambah Alamat
                </button>
            </div>

            @foreach($addresses as $index => $address)
                <div class="bg-white border border-gray-200 rounded-lg p-5 mb-4 shadow-sm relative transition duration-150 hover:shadow-md">
                    @if(count($addresses) > 1)
                        <button type="button" wire:click="removeAddress({{ $index }})" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-4 space-y-4">
                            <x-form.input 
                                wire:model="addresses.{{ $index }}.name" 
                                name="addresses.{{ $index }}.name" 
                                label="Label (ex: Rumah)" 
                                placeholder="Label Alamat"
                                required 
                            />
                            
                            <x-form.location 
                                class="block"
                                label="Wilayah / Region"
                                search="addresses.{{ $index }}.region_search"
                                wire:model="addresses.{{ $index }}.region_id"
                                :options="$availableRegions[$index] ?? []"
                                name="addresses.{{ $index }}.region_id"
                                required
                            />

                            <x-form.input 
                                wire:model="addresses.{{ $index }}.postal_code" 
                                name="addresses.{{ $index }}.postal_code" 
                                label="Kode Pos" 
                            />
                        </div>

                        <div class="md:col-span-8 space-y-4">
                             <x-form.textarea 
                                wire:model="addresses.{{ $index }}.address_line" 
                                name="addresses.{{ $index }}.address_line" 
                                label="Alamat Lengkap" 
                                rows="3" 
                                required 
                            />

                            <div class="grid grid-cols-2 gap-4">
                                <x-form.input 
                                    wire:model="addresses.{{ $index }}.latitude" 
                                    name="addresses.{{ $index }}.latitude" 
                                    label="Latitude" 
                                    placeholder="-6.xxxxx" 
                                />
                                <x-form.input 
                                    wire:model="addresses.{{ $index }}.longitude" 
                                    name="addresses.{{ $index }}.longitude" 
                                    label="Longitude" 
                                    placeholder="107.xxxxx" 
                                />
                            </div>
                             <x-form.textarea 
                                wire:model="addresses.{{ $index }}.description" 
                                name="addresses.{{ $index }}.description" 
                                label="Catatan Tambahan (Opsional)" 
                                rows="1" 
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-form.footer 
            backRoute="{{ route('admin.care-livestock.customer.index', $farm->id) }}"
            submitLabel="Update Customer Data" 
        />
    </form>
</div>