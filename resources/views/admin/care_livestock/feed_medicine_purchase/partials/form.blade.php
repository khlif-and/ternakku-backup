@if (session('error'))
    <div class="flex items-center p-4 mb-6 text-sm font-medium text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
        role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http:
            fill="currentColor"
            viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <span class="sr-only">Info</span>
        <div>{{ session('error') }}</div>
    </div>
@endif

<form action="{{ route('admin.care-livestock.feed-medicine-purchase.store', $farm->id) }}" method="POST"
    enctype="multipart/form-data" class="w-full bg-white p-6 sm:p-8 rounded-xl shadow-lg space-y-6">
    @csrf

    <div class="grid md:grid-cols-2 md:gap-6">
        <div>
            <label for="tanggal-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">Tanggal
                Pembelian</label>
            <input id="tanggal-airdatepicker" name="transaction_date" type="text"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
                placeholder="Pilih tanggal" autocomplete="off" value="{{ old('transaction_date') }}" required>
            @error('transaction_date')
                <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="supplier" class="block mb-2 text-sm font-medium text-gray-700">Supplier</label>
            <input id="supplier" type="text" name="supplier" value="{{ old('supplier') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
                placeholder="Nama supplier" required>
            @error('supplier')
                <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <hr class="border-gray-200">

    <div class="space-y-4">
        <label class="block text-sm font-medium text-gray-700">Daftar Item Pembelian</label>

        <div class="hidden md:grid grid-cols-12 gap-x-3 text-xs font-semibold text-gray-500 px-2">
            <div class="col-span-2">Jenis</div>
            <div class="col-span-3">Nama Item</div>
            <div class="col-span-1 text-center">Qty</div>
            <div class="col-span-1">Satuan</div>
            <div class="col-span-2">Harga/Unit</div>
            <div class="col-span-2">Total Harga</div>
            <div class="col-span-1"></div>
        </div>

        <div id="items-container" class="space-y-3">
            @php
                $oldItems = old('items', [[]]);
            @endphp
            @foreach ($oldItems as $index => $item)
                <div class="grid grid-cols-12 gap-x-3 gap-y-2 item-row items-center">
                    <select name="items[{{ $index }}][purchase_type]"
                        class="col-span-12 md:col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
                        required>
                        <option value="" disabled {{ empty($item['purchase_type']) ? 'selected' : '' }}>Pilih
                            Jenis</option>
                        <option value="forage"
                            {{ isset($item['purchase_type']) && $item['purchase_type'] === 'forage' ? 'selected' : '' }}>
                            Pakan Hijauan</option>
                        <option value="concentrate"
                            {{ isset($item['purchase_type']) && $item['purchase_type'] === 'concentrate' ? 'selected' : '' }}>
                            Pakan Konsentrat</option>
                        <option value="medicine"
                            {{ isset($item['purchase_type']) && $item['purchase_type'] === 'medicine' ? 'selected' : '' }}>
                            Obat</option>
                    </select>
                    <input type="text" name="items[{{ $index }}][item_name]"
                        value="{{ $item['item_name'] ?? '' }}"
                        class="col-span-12 md:col-span-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
                        placeholder="Nama item" required>
                    <input type="number" name="items[{{ $index }}][quantity]"
                        value="{{ $item['quantity'] ?? '' }}"
                        class="col-span-4 md:col-span-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors item-qty"
                        placeholder="Qty" min="1" required>
                    <input type="text" name="items[{{ $index }}][unit]" value="{{ $item['unit'] ?? '' }}"
                        class="col-span-8 md:col-span-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
                        placeholder="Satuan" required>
                    <input type="number" name="items[{{ $index }}][price_per_unit]"
                        value="{{ $item['price_per_unit'] ?? '' }}"
                        class="col-span-6 md:col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors item-price"
                        placeholder="Harga/unit" min="0" required>
                    <input type="number" name="items[{{ $index }}][total_price]"
                        value="{{ $item['total_price'] ?? '' }}"
                        class="col-span-6 md:col-span-2 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed item-total"
                        placeholder="Total" min="0" required readonly>
                    <div class="col-span-12 md:col-span-1 flex justify-end md:justify-center">
                        <button type="button"
                            class="remove-item text-red-500 hover:text-red-700 hover:bg-red-100 rounded-md p-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http:
                                <path stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" id="add-item"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http:
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Tambah Item
        </button>
        @error('items')
            <div class="text-xs text-red-600">{{ $message }}</div>
        @enderror
    </div>

    <hr class="border-gray-200">

    <div>
        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan <span
                class="text-gray-400">(opsional)</span></label>
        <textarea id="notes" name="notes" rows="4"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            placeholder="Tambahkan catatan jika perlu...">{{ old('notes') }}</textarea>
        @error('notes')
            <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
        @enderror
    </div>

    <div class="flex justify-end pt-4">
        <button type="submit"
            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-6 py-3 text-center transition-all">
            Simpan Data Pembelian
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemIndex = {{ count($oldItems) }};
        const addItemBtn = document.getElementById('add-item');
        const itemsContainer = document.getElementById('items-container');

        addItemBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className =
                'grid grid-cols-12 gap-x-3 gap-y-2 item-row items-center animate-fade-in';
            row.innerHTML = `
            <select name="items[${itemIndex}][purchase_type]" class="col-span-12 md:col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors" required>
                <option value="" disabled selected>Pilih Jenis</option>
                <option value="forage">Pakan Hijauan</option>
                <option value="concentrate">Pakan Konsentrat</option>
                <option value="medicine">Obat</option>
            </select>
            <input type="text" name="items[${itemIndex}][item_name]" class="col-span-12 md:col-span-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors" placeholder="Nama item" required>
            <input type="number" name="items[${itemIndex}][quantity]" class="col-span-4 md:col-span-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors item-qty" placeholder="Qty" min="1" required>
            <input type="text" name="items[${itemIndex}][unit]" class="col-span-8 md:col-span-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors" placeholder="Satuan" required>
            <input type="number" name="items[${itemIndex}][price_per_unit]" class="col-span-6 md:col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors item-price" placeholder="Harga/unit" min="0" required>
            <input type="number" name="items[${itemIndex}][total_price]" class="col-span-6 md:col-span-2 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed item-total" placeholder="Total" min="0" required readonly>
            <div class="col-span-12 md:col-span-1 flex justify-end md:justify-center">
                <button type="button" class="remove-item text-red-500 hover:text-red-700 hover:bg-red-100 rounded-md p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http:
                </button>
            </div>
        `;
            itemsContainer.appendChild(row);
            itemIndex++;
        });

        function updateTotals() {
            document.querySelectorAll('.item-row').forEach(function(row) {
                const qtyInput = row.querySelector('.item-qty');
                const priceInput = row.querySelector('.item-price');
                const totalInput = row.querySelector('.item-total');
                if (qtyInput && priceInput && totalInput) {
                    const quantity = parseFloat(qtyInput.value) || 0;
                    const pricePerUnit = parseFloat(priceInput.value) || 0;
                    totalInput.value = (quantity * pricePerUnit).toFixed(0);
                }
            });
        }

        itemsContainer.addEventListener('input', function(e) {
            if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) {
                updateTotals();
            }
        });

        itemsContainer.addEventListener('click', function(e) {
            const removeButton = e.target.closest('.remove-item');
            if (removeButton) {
                removeButton.closest('.item-row').remove();
                updateTotals();
            }
        });

        updateTotals();

        const style = document.createElement('style');
        style.innerHTML = `
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }
    `;
        document.head.appendChild(style);
    });
</script>
