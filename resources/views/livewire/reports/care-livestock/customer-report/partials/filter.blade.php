<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="w-full">
            <x-form.input label="Cari Nama / No. HP / Email" wire:model.live.debounce.500ms="search"
                placeholder="Cari..." />
        </div>
    </div>
</div>