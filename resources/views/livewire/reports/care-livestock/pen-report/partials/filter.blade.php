<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
        <div class="w-full">
            <label class="block mb-2 text-base font-semibold text-gray-700">Cari Kandang</label>
            <input type="text"
                class="w-full px-4 py-3 border rounded-lg text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                wire:model.live.debounce.500ms="search" placeholder="Cari berdasarkan nama kandang...">
        </div>
    </div>
</div>