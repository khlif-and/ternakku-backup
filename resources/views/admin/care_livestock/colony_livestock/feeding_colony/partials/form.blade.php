@if($errors->any())
    <div class="bg-red-100 text-red-700 p-2 mb-3 rounded-lg">
        <ul class="list-disc pl-6">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="flex items-center p-4 mb-6 text-sm font-medium text-red-800 rounded-lg bg-red-100" role="alert">
        <svg class="w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

<form
    action="{{ route('admin.care-livestock.feeding-colony.store', $farm->id) }}"
    method="POST"
    enctype="multipart/form-data"
    class="w-full max-w-full"
    x-data="{
        items: [{ type: '', name: '', qty_kg: '', price_per_kg: '', total_price: 0 }],
        addItem() { this.items.push({ type: '', name: '', qty_kg: '', price_per_kg: '', total_price: 0 }); },
        removeItem(idx) { if(this.items.length > 1) this.items.splice(idx, 1); },
        calcTotalPrice(item) {
            let qty = parseFloat(item.qty_kg) || 0;
            let price = parseFloat(item.price_per_kg) || 0;
            item.total_price = qty * price;
            return item.total_price;
        },
        calcTotalCost() {
            return this.items.reduce((sum, i) => sum + (parseFloat(i.total_price) || 0), 0);
        }
    }"
>
    @csrf

    {{-- Tanggal & Kandang --}}
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-8">
            <label for="tanggal-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Pemberian Pakan</label>
            <input id="tanggal-airdatepicker" name="transaction_date" type="text" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Pilih tanggal" autocomplete="off" value="{{ old('transaction_date') }}" required>
            @error('transaction_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <label for="pen_id" class="block mb-2 text-sm font-medium text-gray-700">Kandang</label>
            <select id="pen_id" name="pen_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                <option value="" selected disabled>Pilih Kandang</option>
                @foreach ($farm->pens as $pen)
                    <option value="{{ $pen->id }}" data-livestock="{{ $pen->livestocks->count() }}">
                        {{ $pen->name }} ({{ $pen->livestocks->count() }} ekor)
                    </option>
                @endforeach
            </select>
            @error('pen_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Jumlah ternak --}}
    <div class="mb-8">
        <label class="block mb-2 text-sm font-medium text-gray-700">Jumlah Ternak</label>
        <input id="inputJumlahTernak" type="number" value="0" readonly
               class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed">
    </div>

    {{-- Items Pakan --}}
    <div class="mb-8">
        <label class="block mb-4 text-sm font-medium text-gray-700">Daftar Pakan yang Diberikan</label>
<template x-for="(item, idx) in items" :key="idx">
    <div class="grid md:grid-cols-5 gap-3 mb-3 items-end">
        <div>
            <label class="block mb-1 text-xs text-gray-600">Tipe</label>
            <select x-model="item.type" :name="'items['+idx+'][type]'" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2" required>
                <option value="" disabled selected>Pilih tipe</option>
                <option value="forage">Hijauan (Forage)</option>
                <option value="concentrate">Konsentrat (Concentrate)</option>
                <option value="feed_material">Bahan Pakan (Feed Material)</option>
            </select>
        </div>
        <div>
            <label class="block mb-1 text-xs text-gray-600">Nama Pakan</label>
            <input x-model="item.name" :name="'items['+idx+'][name]'" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2" required placeholder="Nama Pakan">
        </div>
        <div>
            <label class="block mb-1 text-xs text-gray-600">Qty (kg)</label>
            <input x-model="item.qty_kg" @input="calcTotalPrice(item)" type="number" step="0.01" :name="'items['+idx+'][qty_kg]'" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2" required>
        </div>
        <div>
            <label class="block mb-1 text-xs text-gray-600">Harga/kg</label>
            <input x-model="item.price_per_kg" @input="calcTotalPrice(item)" type="number" step="0.01" :name="'items['+idx+'][price_per_kg]'" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2" required>
        </div>
        <input type="hidden" :name="'items['+idx+'][total_price]'" :value="item.total_price">
        <div class="flex gap-1 mt-1">
            <button type="button" @click="removeItem(idx)" class="px-2 py-1 text-xs bg-red-500 text-white rounded-lg" x-show="items.length > 1">Hapus</button>
            <button type="button" @click="addItem()" class="px-2 py-1 text-xs bg-green-600 text-white rounded-lg">Tambah</button>
        </div>
    </div>
</template>

        <div class="text-xs text-gray-400 mt-1">* Minimal 1 item pakan. Qty & harga harus diisi.</div>
    </div>

    {{-- HIDDEN INPUT total_cost (WAJIB buat validasi backend) --}}
    <input type="hidden" name="total_cost" :value="calcTotalCost()">

    {{-- Catatan --}}
    <div class="mb-8">
        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan (opsional)</label>
        <textarea id="notes" name="notes" rows="3" class="block w-full text-sm border border-gray-300 rounded-lg p-3 bg-gray-50" placeholder="Tambahkan catatan jika perlu...">{{ old('notes') }}</textarea>
        @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    <div class="flex justify-end mt-4">
        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-base px-8 py-3 transition-all">Simpan Data</button>
    </div>
</form>

<script>
document.getElementById('pen_id').addEventListener('change', function () {
    let count = this.selectedOptions[0]?.getAttribute('data-livestock') ?? 0;
    document.getElementById('inputJumlahTernak').value = count;
});
</script>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
