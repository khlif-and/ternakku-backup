@props(['label', 'name'])

<div class="flex items-center justify-between p-3 border rounded-lg">
    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $label }}</span>
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" {{ $attributes }} class="sr-only peer">
        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
    </label>
</div>