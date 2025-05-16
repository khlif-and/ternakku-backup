<!-- Logout Modal Tailwind + JS (NO Alpine) -->
<div id="logoutModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm transition-opacity duration-150 opacity-0 pointer-events-none"
    style="display:none;"
>
    <div class="bg-white w-full max-w-md mx-4 rounded-xl shadow-xl overflow-hidden scale-95 transition-transform duration-200">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-bold text-gray-900">Logout</h3>
            <button onclick="closeLogoutModal()" class="text-gray-400 hover:text-gray-600 transition" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <!-- Body -->
        <div class="px-6 py-5 text-gray-800">
            Anda yakin mau logout?
        </div>
        <!-- Footer -->
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
