<header class="px-4 lg:px-6 py-3 flex items-center justify-between">
    <div class="flex items-center space-x-2">
        <button class="block lg:hidden p-2 rounded hover:bg-gray-100 transition" aria-label="Toggle Sidebar">
            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <a href="{{ url('dashboard') }}" class="flex items-center space-x-2">
            {{-- <span class="text-2xl font-extrabold tracking-tight text-orange-600 select-none">Ternakku</span> --}}
        </a>
    </div>
    <div class="flex items-center space-x-3">
        <button class="p-2 rounded hover:bg-gray-100 transition hidden lg:block" aria-label="Topbar More">
            <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="6" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="18" r="1.5"/>
            </svg>
        </button>

        <div class="relative">
            <button id="profileDropdownBtn" class="flex items-center space-x-2 focus:outline-none" aria-haspopup="true" type="button">
                <img src="{{ asset('admin/img/profile.jpg') }}" alt="Profile" class="w-8 h-8 rounded-full border-2 border-gray-200" />
                <span class="hidden sm:inline-block text-gray-900">
                    <span class="opacity-70">Hi,</span>
                    <span class="font-semibold">{{ explode(' ', auth()->user()->name)[0] }}</span>
                </span>
                <svg class="w-4 h-4 text-gray-600 opacity-60 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div id="profileDropdown" class="absolute right-0 mt-3 w-64 origin-top-right bg-white rounded-2xl shadow-2xl shadow-black/10 z-40 hidden">

                <div class="p-4 bg-gradient-to-br from-green-50 to-cyan-100 rounded-t-2xl">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('admin/img/profile.jpg') }}" alt="Profile" class="w-12 h-12 rounded-full" />
                        <div class="min-w-0">
                            <div class="font-bold text-gray-800 leading-tight">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="text-sm text-gray-500 truncate">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-2">
                    <a href="#" class="flex items-center w-full px-3 py-2 text-left text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                        Pengaturan Akun
                    </a>

                    <button id="logoutBtn" type="button" class="flex items-center w-full px-3 py-2 text-left text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
