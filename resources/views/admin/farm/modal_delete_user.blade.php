<!-- Modal Delete User Tailwind + JS -->
<div id="deleteUserModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm transition-opacity duration-200 opacity-0 pointer-events-none">
    <div class="bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden scale-95 transition-transform duration-200 relative">
        <!-- ICON WARNING -->
        <div class="flex justify-center pt-8">
            <div class="bg-gradient-to-tr from-red-100 to-pink-100 p-4 rounded-full shadow-lg animate-pulse-slow">
                <svg class="h-10 w-10 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" class="text-red-200" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" class="text-red-500" stroke-width="2" />
                </svg>
            </div>
        </div>
        <!-- Header -->
        <div class="flex flex-col items-center justify-center px-6 pt-4 pb-2">
            <h3 class="text-xl font-bold text-gray-900 mb-1">Hapus Pengguna?</h3>
            <p class="text-base text-gray-500 text-center" id="delete-user-message">
                Anda yakin ingin menghapus pengguna ini?
            </p>
        </div>
        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-6 border-t bg-gray-50">
            <button type="button" onclick="closeDeleteUserModal()"
                class="px-5 py-2.5 rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition font-medium text-base">
                Batal
            </button>
            <form id="delete-user-form" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-gradient-to-tr from-red-500 to-orange-400 hover:from-red-600 hover:to-orange-500 text-white font-bold shadow-md focus:outline-none focus:ring-2 focus:ring-red-300 text-base transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M9 6V4a2 2 0 012-2h2a2 2 0 012 2v2m-7 0v12a2 2 0 002 2h4a2 2 0 002-2V6"/>
                        <line x1="10" y1="11" x2="10" y2="17"/>
                        <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
        <!-- Close btn floating -->
        <button onclick="closeDeleteUserModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 rounded-full focus:outline-none focus:ring-2 focus:ring-gray-300 p-2 transition" aria-label="Close">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<style>
    @keyframes pulse-slow {
        0%, 100% { box-shadow: 0 0 0 0 #ef444433; }
        50% { box-shadow: 0 0 0 10px #ef44441a; }
    }
    .animate-pulse-slow { animation: pulse-slow 2s infinite; }
</style>

<script>
    // Modal open handler
    function openDeleteUserModal(deleteUrl, userName) {
        const modal = document.getElementById('deleteUserModal');
        document.getElementById('delete-user-form').action = deleteUrl;
        document.getElementById('delete-user-message').innerText =
            `Anda yakin ingin menghapus pengguna "${userName}"?`;
        modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.add('opacity-100');
    }
    // Modal close handler
    function closeDeleteUserModal() {
        const modal = document.getElementById('deleteUserModal');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
    }
</script>
