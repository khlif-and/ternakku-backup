@props([
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Ya',
    'cancelText' => 'Batal',
    'confirmAction' => null,
    'icon' => 'logout',
    'danger' => true,
])

@php
    $iconBg = $danger ? 'bg-red-100' : 'bg-blue-100';
    $iconColor = $danger ? 'text-red-600' : 'text-blue-600';
    $confirmBg = $danger ? 'bg-red-600 hover:bg-red-700 shadow-red-500/30 hover:shadow-red-500/40' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30 hover:shadow-blue-500/40';
@endphp

<div x-cloak x-show="logoutModal" 
     @click.self="logoutModal = false"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="logoutModal = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">

    <div x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white w-full max-w-sm mx-4 rounded-2xl shadow-xl">
        
        <div class="p-8 text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 flex items-center justify-center {{ $iconBg }} rounded-full">
                    @if($icon === 'logout')
                        <svg class="w-10 h-10 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    @else
                        {{ $iconSlot ?? '' }}
                    @endif
                </div>
            </div>

            <h3 class="text-2xl font-bold text-gray-900">{{ $title }}</h3>
            <p class="mt-2 text-gray-600">{{ $message }}</p>

            <div class="grid grid-cols-2 gap-4 mt-8">
                <button @click="logoutModal = false"
                        class="w-full px-4 py-3 rounded-lg text-gray-800 bg-gray-100 hover:bg-gray-200 transition-colors font-semibold">
                    {{ $cancelText }}
                </button>

                @if($confirmAction)
                    <form action="{{ $confirmAction }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full px-4 py-3 rounded-lg {{ $confirmBg }} text-white font-bold shadow-md transition">
                            {{ $confirmText }}
                        </button>
                    </form>
                @else
                    {{ $slot }}
                @endif
            </div>
        </div>
    </div>
</div>
