<!-- HEADER -->
<header class="bg-white w-full px-4 lg:px-8 py-3 flex items-center justify-between shadow-sm">
    <div class="flex items-center space-x-3">
        <!-- Sidebar Toggle (mobile) -->
        <button class="block xl:hidden p-2 rounded hover:bg-gray-100 transition" aria-label="Toggle Sidebar">
            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
    <div class="flex items-center space-x-3">
        <!-- More Button -->
        <button class="p-2 rounded hover:bg-gray-100 transition hidden lg:block" aria-label="Topbar More">
            <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="6" r="1.5"/>
                <circle cx="12" cy="12" r="1.5"/>
                <circle cx="12" cy="18" r="1.5"/>
            </svg>
        </button>
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
                        id="openLogoutModalBtn"
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

<!-- LOGOUT MODAL -->
<div id="logoutModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm transition-opacity duration-150 opacity-0 pointer-events-none"
    style="display:none;"
>
    <div class="bg-white w-full max-w-md mx-4 rounded-xl shadow-xl overflow-hidden scale-95 transition-transform duration-200">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-bold text-gray-900">Logout</h3>
            <button onclick="closeLogoutModal()" class="text-gray-400 hover:text-gray-600 transition" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-6 py-5 text-gray-800">
            Anda yakin mau logout?
        </div>
        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50">
            <button onclick="closeLogoutModal()"
                class="px-4 py-2 rounded-lg text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 transition font-medium">
                Cancel
            </button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-bold shadow transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<!-- JS: Dropdown & Modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Dropdown
    const btn = document.getElementById('profileDropdownBtn');
    const dropdown = document.getElementById('profileDropdown');
    btn && btn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });
    document.addEventListener('click', function () {
        if (!dropdown.classList.contains('hidden')) dropdown.classList.add('hidden');
    });
    dropdown && dropdown.addEventListener('click', function (e) { e.stopPropagation(); });

    // Logout Modal
    const logoutBtn = document.getElementById('openLogoutModalBtn');
    const logoutModal = document.getElementById('logoutModal');

    window.closeLogoutModal = function () {
        logoutModal.style.opacity = '0';
        logoutModal.style.pointerEvents = 'none';
        setTimeout(() => { logoutModal.style.display = 'none'; }, 150);
    }

    logoutBtn && logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        dropdown.classList.add('hidden');
        logoutModal.style.display = 'flex';
        setTimeout(() => {
            logoutModal.style.opacity = '1';
            logoutModal.style.pointerEvents = 'auto';
        }, 10);
    });

    // ESC to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") window.closeLogoutModal();
    });
});
</script>
