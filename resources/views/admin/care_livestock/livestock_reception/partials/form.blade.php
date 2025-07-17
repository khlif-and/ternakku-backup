@if (session('error'))
    <div class="mb-6 px-4 py-3 rounded bg-red-100 border border-red-400 text-red-700 font-semibold">
        {{ session('error') }}
    </div>
@endif

<form
    action="{{ $mode === 'edit'
        ? route('admin.care-livestock.livestock-reception.update', [$farm->id, $reception->id])
        : route('admin.care-livestock.livestock-reception.store', $farm->id)
    }}"
    method="POST"
    enctype="multipart/form-data"
    class="w-full max-w-full"
>
    @csrf
    @if ($mode === 'edit')
        @method('PUT')
    @endif



<div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Tanggal Transaksi --}}
    <div>
        <label for="tanggal-airdatepicker" class="block mb-2 text-base font-semibold text-gray-700">
            Tanggal Transaksi
        </label>
        <div class="relative w-full">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                </svg>
            </div>
            <input
                id="tanggal-airdatepicker"
                name="transaction_date"
                type="text"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 py-3"
                placeholder="Pilih tanggal"
                autocomplete="off"
                value="{{ old('transaction_date', isset($reception) ? $reception->livestockReceptionH->transaction_date ?? '' : '') }}"
                required>
        </div>
        @error('transaction_date')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Foto --}}
    <div>
        <label class="block mb-2 text-base font-semibold text-gray-700">Foto (opsional)</label>
        <input
            type="file"
            name="photo"
            accept="image/*"
            class="w-full border px-4 py-3 rounded-md text-base">
        @error('photo')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>
</div>


<div class="mb-8">
    <label class="block mb-2 text-base font-semibold text-gray-700">Supplier (opsional)</label>
    <input
        type="text"
        name="supplier"
        value="{{ old('supplier', isset($reception) ? $reception->supplier : '') }}"
        class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
    @error('supplier')
        <span class="text-red-500 text-xs">{{ $message }}</span>
    @enderror
</div>


<div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block mb-2 text-base font-semibold text-gray-700">Eartag Number</label>
        <input
            type="text"
            name="eartag_number"
            value="{{ old('eartag_number', isset($reception) ? $reception->eartag_number : '') }}"
            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300"
            required>
        @error('eartag_number')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-base font-semibold text-gray-700">RFID Number (opsional)</label>
        <input
            type="text"
            name="rfid_number"
            value="{{ old('rfid_number', isset($reception) ? $reception->rfid_number : '') }}"
            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
        @error('rfid_number')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>
</div>


{{-- Jenis Ternak, Jenis Kelamin, Grup --}}
@php
    $selectedTypeKey = old('livestock_type_id', isset($reception) ? $reception->livestock_type_id : null);
    $selectedType = $selectedTypeKey && isset($livestockTypes[$selectedTypeKey])
        ? $livestockTypes[$selectedTypeKey]
        : 'Pilih Jenis Ternak';

    $selectedSexKey = old('livestock_sex_id', isset($reception) ? $reception->livestock_sex_id : null);
    $selectedSex = $selectedSexKey && isset($sexes[$selectedSexKey])
        ? $sexes[$selectedSexKey]
        : 'Pilih Jenis Kelamin';

    $selectedGroupKey = old('livestock_group_id', isset($reception) ? $reception->livestock_group_id : null);
    $selectedGroup = $selectedGroupKey && isset($groups[$selectedGroupKey])
        ? $groups[$selectedGroupKey]
        : 'Pilih Grup';
@endphp

<div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
    {{-- Jenis Ternak --}}
    <div x-data="{ open: false, selectedType: '{{ $selectedType }}' }" class="relative">
        <label class="block mb-2 text-base font-semibold text-gray-700">Jenis Ternak</label>
        <button @click="open = !open" type="button"
            class="w-full flex justify-between items-center px-4 py-3 border rounded-md text-base bg-white focus:ring-2 focus:ring-blue-300"
            :class="{ 'ring-2 ring-blue-400': open }">
            <span x-text="selectedType"></span>
            <svg class="w-4 h-4 text-gray-500 transform transition-transform duration-200"
                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" x-transition.opacity.scale @click.away="open = false" x-cloak
            class="absolute z-10 mt-2 w-full bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto">
            @foreach ($livestockTypes as $key => $label)
                <div @click="
                    selectedType = '{{ $label }}';
                    open = false;
                    $nextTick(() => {
                        document.getElementById('type_{{ $key }}').checked = true;
                        document.dispatchEvent(new CustomEvent('livestock-type-changed', { detail: {{ $key }} }));
                    });
                "
                    class="px-4 py-2 cursor-pointer hover:bg-blue-50 text-sm"
                    :class="{ 'bg-blue-100': selectedType === '{{ $label }}' }">
                    <input type="radio" id="type_{{ $key }}" name="livestock_type_id"
                        value="{{ $key }}" class="hidden"
                        {{ $selectedTypeKey == $key ? 'checked' : '' }}>
                    {{ $label }}
                </div>
            @endforeach
        </div>
        @error('livestock_type_id')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Jenis Kelamin --}}
    <div x-data="{ open: false, selected: '{{ $selectedSex }}' }" class="relative">
        <label class="block mb-2 text-base font-semibold text-gray-700">Jenis Kelamin</label>
        <button @click="open = !open" type="button"
            class="w-full flex justify-between items-center px-4 py-3 border rounded-md text-base bg-white focus:ring-2 focus:ring-blue-300"
            :class="{ 'ring-2 ring-blue-400': open }">
            <span x-text="selected"></span>
            <svg class="w-4 h-4 text-gray-500 transform transition-transform duration-200"
                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" x-transition.opacity.scale @click.away="open = false" x-cloak
            class="absolute z-10 mt-2 w-full bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto">
            @foreach ($sexes as $key => $label)
                <div @click="
                    selected = '{{ $label }}';
                    open = false;
                    $nextTick(() => document.getElementById('sex_{{ $key }}').checked = true);
                "
                    class="px-4 py-2 cursor-pointer hover:bg-blue-50 text-sm"
                    :class="{ 'bg-blue-100': selected === '{{ $label }}' }">
                    <input type="radio" id="sex_{{ $key }}" name="livestock_sex_id"
                        value="{{ $key }}" class="hidden"
                        {{ $selectedSexKey == $key ? 'checked' : '' }}>
                    {{ $label }}
                </div>
            @endforeach
        </div>
        @error('livestock_sex_id')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Grup --}}
    <div x-data="{ open: false, selected: '{{ $selectedGroup }}' }" class="relative">
        <label class="block mb-2 text-base font-semibold text-gray-700">Grup</label>
        <button @click="open = !open" type="button"
            class="w-full flex justify-between items-center px-4 py-3 border rounded-md text-base bg-white focus:ring-2 focus:ring-blue-300"
            :class="{ 'ring-2 ring-blue-400': open }">
            <span x-text="selected"></span>
            <svg class="w-4 h-4 text-gray-500 transform transition-transform duration-200"
                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" @click.away="open = false" x-cloak
            class="absolute z-10 mt-2 w-full bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto">
            @foreach ($groups as $key => $label)
                <div @click="
                    selected = '{{ $label }}';
                    open = false;
                    $nextTick(() => document.getElementById('group_{{ $key }}').checked = true);
                "
                    class="px-4 py-2 cursor-pointer hover:bg-blue-50 text-sm"
                    :class="{ 'bg-blue-100': selected === '{{ $label }}' }">
                    <input type="radio" id="group_{{ $key }}" name="livestock_group_id"
                        value="{{ $key }}" class="hidden"
                        {{ $selectedGroupKey == $key ? 'checked' : '' }}>
                    {{ $label }}
                </div>
            @endforeach
        </div>
        @error('livestock_group_id')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>
</div>



@php
    $selectedBreedId = old('livestock_breed_id', isset($reception) ? $reception->livestock_breed_id : null);
    $selectedClassKey = old('livestock_classification_id', isset($reception) ? $reception->livestock_classification_id : null);
    $selectedClass = $selectedClassKey && isset($classifications[$selectedClassKey]) ? $classifications[$selectedClassKey] : 'Pilih Klasifikasi';

    $selectedPenKey = old('pen_id', isset($reception) ? $reception->pen_id : null);
    $selectedPen = $selectedPenKey ? ($farm->pens->firstWhere('id', $selectedPenKey)?->name ?? 'Pilih Kandang') : 'Pilih Kandang';
@endphp

<div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
    {{-- Ras --}}
    <div x-data="breedDropdown()" x-init="init()" data-old="{{ $selectedBreedId }}" class="relative">
        <label class="block mb-2 text-base font-semibold text-gray-700">Ras</label>
        <button @click="open = !open" type="button"
            class="w-full flex justify-between items-center px-4 py-3 border rounded-md text-base bg-white focus:ring-2 focus:ring-blue-300"
            :class="{ 'ring-2 ring-blue-400': open }">
            <span x-text="selected"></span>
            <svg class="w-4 h-4 text-gray-500 transform transition-transform duration-200"
                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" @click.away="open = false" x-cloak
            class="absolute z-10 mt-2 w-full bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto">
            <template x-if="items.length === 0">
                <div class="px-4 py-2 text-sm text-gray-500">Data akan muncul setelah pilih jenis ternak</div>
            </template>
            <template x-for="item in items" :key="item.id">
                <div @click="
                    selected = item.name;
                    open = false;
                    $nextTick(() => {
                        const el = document.getElementById('breed_radio_' + item.id);
                        if (el) el.checked = true;
                    });
                "
                    class="px-4 py-2 cursor-pointer hover:bg-blue-50 text-sm"
                    :class="{ 'bg-blue-100': selected === item.name }">
                    <input type="radio" :id="'breed_radio_' + item.id" name="livestock_breed_id"
                        :value="item.id" class="hidden"
                        :checked="item.id == {{ $selectedBreedId ?? 'null' }}">
                    <span x-text="item.name"></span>
                </div>
            </template>
        </div>
        @error('livestock_breed_id')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Klasifikasi --}}
    <div x-data="{ open: false, selected: '{{ $selectedClass }}' }" class="relative">
        <label class="block mb-2 text-base font-semibold text-gray-700">Klasifikasi</label>
        <button @click="open = !open" type="button"
            class="w-full flex justify-between items-center px-4 py-3 border rounded-md text-base bg-white focus:ring-2 focus:ring-blue-300"
            :class="{ 'ring-2 ring-blue-400': open }">
            <span x-text="selected"></span>
            <svg class="w-4 h-4 text-gray-500 transform transition-transform duration-200"
                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" @click.away="open = false" x-cloak
            class="absolute z-10 mt-2 w-full bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto">
            @foreach ($classifications as $key => $label)
                <div @click="
                    selected = '{{ $label }}';
                    open = false;
                    $nextTick(() => document.getElementById('classification_{{ $key }}').checked = true);
                "
                    class="px-4 py-2 cursor-pointer hover:bg-blue-50 text-sm"
                    :class="{ 'bg-blue-100': selected === '{{ $label }}' }">
                    <input type="radio" id="classification_{{ $key }}"
                        name="livestock_classification_id" value="{{ $key }}" class="hidden"
                        {{ $selectedClassKey == $key ? 'checked' : '' }}>
                    {{ $label }}
                </div>
            @endforeach
        </div>
        @error('livestock_classification_id')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Kandang --}}
    <div x-data="{ open: false, selected: '{{ $selectedPen }}' }" class="relative">
        <label class="block mb-2 text-base font-semibold text-gray-700">Kandang</label>
        <button @click="open = !open" type="button"
            class="w-full flex justify-between items-center px-4 py-3 border rounded-md text-base bg-white focus:ring-2 focus:ring-blue-300"
            :class="{ 'ring-2 ring-blue-400': open }">
            <span x-text="selected"></span>
            <svg class="w-4 h-4 text-gray-500 transform transition-transform duration-200"
                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" @click.away="open = false" x-cloak
            class="absolute z-10 mt-2 w-full bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto">
            @foreach ($farm->pens as $pen)
                <div @click="
                    selected = '{{ $pen->name }}';
                    open = false;
                    $nextTick(() => document.getElementById('pen_{{ $pen->id }}').checked = true);
                "
                    class="px-4 py-2 cursor-pointer hover:bg-blue-50 text-sm"
                    :class="{ 'bg-blue-100': selected === '{{ $pen->name }}' }">
                    <input type="radio" id="pen_{{ $pen->id }}" name="pen_id"
                        value="{{ $pen->id }}" class="hidden"
                        {{ $selectedPenKey == $pen->id ? 'checked' : '' }}>
                    {{ $pen->name }} (Kapasitas: {{ $pen->capacity }}, Luas: {{ $pen->area }} m²)
                </div>
            @endforeach
        </div>
        @error('pen_id')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>
</div>


@php
    $ageYears = old('age_years', isset($reception) ? $reception->age_years : '');
    $ageMonths = old('age_months', isset($reception) ? $reception->age_months : '');
@endphp

<div class="mb-8">
    <label class="block mb-2 text-base font-semibold text-gray-700">Usia (Tahun / Bulan)</label>
    <div class="flex gap-4">
        <input type="number" name="age_years" value="{{ $ageYears }}"
            class="w-1/2 px-4 py-3 border rounded-md text-base outline-none" placeholder="Tahun" min="0">
        <input type="number" name="age_months" value="{{ $ageMonths }}"
            class="w-1/2 px-4 py-3 border rounded-md text-base outline-none" placeholder="Bulan" min="0" max="11">
    </div>
    @error('age_years')
        <span class="text-red-500 text-xs">{{ $message }}</span>
    @enderror
    @error('age_months')
        <span class="text-red-500 text-xs">{{ $message }}</span>
    @enderror
</div>


@php
    $weight = old('weight', isset($reception) ? $reception->weight : '');
    $pricePerKg = old('price_per_kg', isset($reception) ? $reception->price_per_kg : '');
    $pricePerHead = old('price_per_head', isset($reception) ? $reception->price_per_head : '');
@endphp

<div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
    {{-- Berat --}}
    <div>
        <label class="block mb-2 text-base font-semibold text-gray-700">Berat (kg)</label>
        <input type="number" step="0.01" name="weight" value="{{ $weight }}"
            class="w-full px-4 py-3 border rounded-md text-base outline-none">
        @error('weight')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Harga per Kg --}}
    <div class="relative">
        <label class="block mb-2 text-base font-semibold text-gray-700">Harga per Kg</label>
        <div class="relative">
            <input type="text" name="price_per_kg" value="{{ $pricePerKg }}"
                class="w-full px-4 py-3 border rounded-md text-base outline-none" autocomplete="off">
        </div>
        @error('price_per_kg')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Harga per Kepala --}}
    <div class="relative">
        <label class="block mb-2 text-base font-semibold text-gray-700">Harga per Kepala</label>
        <div class="relative">
            <input type="text" name="price_per_head" value="{{ $pricePerHead }}"
                class="w-full px-4 py-3 border rounded-md text-base outline-none bg-gray-100" readonly>
        </div>
        @error('price_per_head')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>
</div>


    <div class="mb-8">
        <label class="block mb-2 text-base font-semibold text-gray-700">Catatan (opsional)</label>
        <textarea name="notes" rows="3" class="w-full px-4 py-3 border rounded-md text-base outline-none">{{ old('notes') }}</textarea>
        @error('notes')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>
<div class="flex justify-end mt-8">
    <button type="submit"
        class="{{ $mode === 'edit' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-green-400 hover:bg-green-500' }} text-white font-semibold rounded-lg px-8 py-3 text-base shadow transition-all font-sans">
        {{ $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Registrasi' }}
    </button>
</div>

</form>
