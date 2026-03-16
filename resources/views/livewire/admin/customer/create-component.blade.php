<div>
    <x-alert.session />
    <x-alert.validation-errors :errors="$errors" />

    <form wire:submit.prevent="save" class="w-full">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informasi Pelanggan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-form.input 
                    wire:model="name" 
                    name="name" 
                    label="Nama Pelanggan" 
                    placeholder="Nama Lengkap" 
                    required 
                />

                <x-form.input 
                    wire:model="phone" 
                    name="phone" 
                    label="Nomor Telepon" 
                    placeholder="Contoh: 0812..." 
                />

                <x-form.input 
                    wire:model="email" 
                    name="email" 
                    label="Email" 
                    type="email" 
                    placeholder="Contoh: user@email.com" 
                />
            </div>
        </div>

        <div class="mb-8">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Alamat</h3>
                <button type="button" wire:click="addAddress" class="text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition">
                    + Tambah Alamat
                </button>
            </div>

            @foreach($addresses as $index => $address)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 relative">
                    @if(count($addresses) > 1)
                        <button type="button" wire:click="removeAddress({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl" title="Hapus Alamat">
                            &times;
                        </button>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <x-form.input 
                            wire:model="addresses.{{ $index }}.name" 
                            name="addresses.{{ $index }}.name" 
                            label="Label Alamat" 
                            placeholder="Contoh: Rumah, Kandang A" 
                            required 
                        />

                        <div class="col-span-1">
                            <x-form.location 
                                label="Wilayah / Region"
                                search="addresses.{{ $index }}.region_search"
                                wire:model="addresses.{{ $index }}.region_id"
                                :options="$availableRegions[$index] ?? []"
                                name="addresses.{{ $index }}.region_id"
                                required
                            />
                        </div>

                        <x-form.input 
                            wire:model="addresses.{{ $index }}.postal_code" 
                            name="addresses.{{ $index }}.postal_code" 
                            label="Kode Pos" 
                        />
                    </div>

                    <div class="mb-4">
                        <x-form.textarea 
                            wire:model="addresses.{{ $index }}.address_line" 
                            name="addresses.{{ $index }}.address_line" 
                            label="Alamat Lengkap" 
                            rows="2" 
                            required 
                        />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    
                    <div class="mt-4">
                         <x-form.textarea 
                            wire:model="addresses.{{ $index }}.description" 
                            name="addresses.{{ $index }}.description" 
                            label="Catatan Tambahan (Opsional)" 
                            rows="1" 
                        />
                    </div>
                </div>
            @endforeach
        </div>

        <x-form.footer
            backRoute="{{ route('admin.care-livestock.customer.index', $farm->id) }}"
            submitLabel="Simpan Data Customer"
        />
    </form>
</div>