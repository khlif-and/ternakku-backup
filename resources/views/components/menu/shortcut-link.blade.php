@props([
    'href' => '#',
    'label',
    'iconName' => 'add-circle-outline',
])

<a href="{{ $href }}" 
   class="flex items-center p-3 rounded-lg font-semibold bg-indigo-100 text-indigo-800 hover:bg-indigo-700 hover:text-white transition-all duration-200 transform hover:scale-103">
    <ion-icon name="{{ $iconName }}" class="text-2xl mr-3"></ion-icon>
    {{ $label }}
</a>
