<div id="logoutModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none"
    style="display:none;">

    <div class="bg-white w-full max-w-sm mx-4 rounded-2xl shadow-xl transition-transform duration-300 transform scale-95">
        <div class="p-8 text-center">

            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 flex items-center justify-center bg-red-100 rounded-full">
                    <svg class="w-10 h-10 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-gray-900">Konfirmasi Logout</h3>
            <p class="mt-2 text-gray-600">
                Anda yakin ingin mengakhiri sesi Anda saat ini?
            </p>

            <div class="grid grid-cols-2 gap-4 mt-8">
                <button onclick="closeLogoutModal()"
                        class="w-full px-4 py-3 rounded-lg text-gray-800 bg-gray-100 hover:bg-gray-200 transition-colors font-semibold">
                    Batal
                </button>

                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit"
                            class="w-full px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white font-bold shadow-md shadow-red-500/30 hover:shadow-lg hover:shadow-red-500/40 transition">
                        Ya, Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
