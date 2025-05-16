<header class="bg-white px-4 lg:px-6 py-3 flex items-center justify-between shadow-lg">
    <!-- Logo & Sidebar Toggle -->
    <div class="flex items-center space-x-2">
        <!-- Sidebar Toggle (mobile) -->
        <button class="block lg:hidden p-2 rounded hover:bg-gray-100 transition" aria-label="Toggle Sidebar">
            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <!-- Ternakku Brand -->
        <a href="{{ url('dashboard') }}" class="flex items-center space-x-2">
            <span class="text-2xl font-extrabold tracking-tight text-orange-600 select-none">Ternakku</span>
        </a>
    </div>
    <!-- Right (profile & menu) -->
    <div class="flex items-center space-x-3">
        <!-- Topbar Toggle (optional) -->
        <button class="p-2 rounded hover:bg-gray-100 transition hidden lg:block" aria-label="Topbar More">
            <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="6" r="1.5"/>
                <circle cx="12" cy="12" r="1.5"/>
                <circle cx="12" cy="18" r="1.5"/>
            </svg>
        </button>
        <!-- Profile Dropdown -->
<!-- Profile Dropdown -->
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
    <!-- Dropdown menu -->
    <div id="profileDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 z-40 hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center space-x-3">
            <img src="{{ asset('admin/img/profile.jpg') }}" alt="Profile" class="w-12 h-12 rounded-full border-2 border-gray-200" />
            <div>
                <div class="font-semibold text-gray-900 text-base leading-tight">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <div>
            <button
                id="logoutBtn"
                type="button"
                class="w-full px-5 py-3 text-left text-gray-700 hover:bg-orange-50 rounded-b-xl transition"
            >
                Logout
            </button>
        </div>
    </div>
</div>

    </div>
</header>
