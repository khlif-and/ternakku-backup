@props([
    'user',
    'profileImage' => null,
])

@php
    $profileSrc = $profileImage ?? asset('admin/img/profile.jpg');
@endphp

<header class="px-4 lg:px-6 py-3 flex items-center justify-between">
    <div class="flex items-center space-x-2">
        <button class="block lg:hidden p-2 rounded hover:bg-gray-100 transition" aria-label="Toggle Sidebar">
            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <a href="{{ url('dashboard') }}" class="flex items-center space-x-2">
            {{-- Brand logo --}}
        </a>
    </div>
    
    <div class="flex items-center space-x-3">
        <button class="p-2 rounded hover:bg-gray-100 transition hidden lg:block" aria-label="Topbar More">
            <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="6" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="18" r="1.5"/>
            </svg>
        </button>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none" aria-haspopup="true" type="button">
                <img src="{{ $profileSrc }}" alt="Profile" class="w-8 h-8 rounded-full border-2 border-gray-200" />
                <span class="hidden sm:inline-block text-gray-900">
                    <span class="opacity-70">Hi,</span>
                    <span class="font-semibold">{{ explode(' ', $user->name)[0] }}</span>
                </span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-gray-600 opacity-60 ml-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-cloak x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-3 w-64 origin-top-right bg-white rounded-2xl shadow-2xl shadow-black/10 z-40">

                <div class="p-4 bg-gradient-to-br from-green-50 to-cyan-100 rounded-t-2xl">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $profileSrc }}" alt="Profile" class="w-12 h-12 rounded-full" />
                        <div class="min-w-0">
                            <div class="font-bold text-gray-800 leading-tight">
                                {{ $user->name }}
                            </div>
                            <div class="text-sm text-gray-500 truncate">
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-2">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</header>
