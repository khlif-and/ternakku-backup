@props([
    'options' => [10, 25, 50],
])

<select {{ $attributes->merge(['class' => 'px-4 py-2 border rounded-lg text-sm']) }}>
    @foreach($options as $option)
        <option value="{{ $option }}">{{ $option }}</option>
    @endforeach
</select>
