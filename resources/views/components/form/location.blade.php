@props([
    'label' => 'Wilayah / Region',
    'search' => '',
    'options' => [],
    'placeholder' => '🔍 Ketik nama kelurahan/kecamatan...',
    'required' => false,
    'name' => '', // Untuk keperluan error message
])

<div class="{{ $attributes->get('class') }}">
    <x-form.input 
        wire:model.live.debounce.300ms="{{ $search }}"
        :label="$label"
        :placeholder="$placeholder"
        :required="$required"
        class="mb-2"
        :error="false"
    />

    @php
        $selectOptions = [];
        if(!empty($options)) {
            // Check if options is a Collection or Array and transform
            $collection = is_array($options) ? collect($options) : $options;
            // Use the accessor or map manually if it's an array of arrays
            $selectOptions = $collection->mapWithKeys(function($item) {
                 // Check if item is array (from toArray) or Model
                 $id = is_array($item) ? $item['id'] : $item->id;
                 $name = is_array($item) 
                    ? ($item['name'] . ' - ' . $item['district_name'] . ', ' . $item['regency_name'])
                    : $item->formatted_name;
                 return [$id => $name];
            })->toArray();
        }
    @endphp

    <x-form.select 
        {{ $attributes->whereStartsWith('wire:model') }}
        :options="$selectOptions"
        placeholder="-- Pilih dari hasil pencarian --"
        :required="$required"
        :name="$name"
    />
</div>
