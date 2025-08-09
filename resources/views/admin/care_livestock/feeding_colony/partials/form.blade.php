@if (session('error'))
    <div class="flex items-center p-4 mb-4 text-sm font-medium text-red-800 rounded-lg bg-red-100" role="alert">
        <svg class="w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="p-4 mb-4 text-sm rounded-lg bg-red-50 border border-red-200 text-red-800">
        <div class="font-semibold mb-2">Gagal menyimpan:</div>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (config('app.debug'))
    <details class="mb-4">
        <summary class="cursor-pointer text-xs text-gray-600">Debug: Old input & errors</summary>
        <pre class="mt-2 p-3 bg-gray-100 rounded text-xs overflow-auto">
Old Input:
{{ json_encode(old(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}

Errors:
{{ json_encode($errors->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}
        </pre>
    </details>
@endif

<form action="{{ route('admin.care-livestock.feeding-colony.store', ['farm_id' => $farm->id]) }}" method="POST" class="w-full max-w-full">
    @csrf

    {{-- Baris 1: Tanggal transaksi & Pilih kandang --}}
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-8">
            <label for="transaction-date-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">
                Tanggal Pemberian Pakan Koloni
            </label>
            <input id="transaction-date-airdatepicker" name="transaction_date" type="text"
                   class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3"
                   placeholder="Pilih tanggal" autocomplete="off" value="{{ old('transaction_date') }}" required>
            @error('transaction_date')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-8">
            <label for="pen_id" class="block mb-2 text-sm font-medium text-gray-700">
                Pilih Kandang
                @isset($fromPen)
                    <span class="ml-2 text-xs text-gray-500">(Prefilter: {{ $fromPen->name }})</span>
                @endisset
            </label>

            <select id="pen_id" name="pen_id"
                    class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                <option value="" disabled {{ old('pen_id') ? '' : 'selected' }}>Pilih Kandang</option>

                @foreach ($pens as $pen)
                    @php
                        $name       = $pen->name ?? 'Tanpa Nama';
                        $capacity   = $pen->capacity ?? '-';
                        $population = $pen->population ?? 0;
                        $label      = $name.' — Kapasitas: '.$capacity.' | Populasi: '.$population;
                        $selected   = old('pen_id') == $pen->id || (isset($fromPen) && $fromPen->id == $pen->id && !old('pen_id'));
                    @endphp
                    <option value="{{ $pen->id }}"
                            data-name="{{ $name }}"
                            data-capacity="{{ $capacity }}"
                            data-population="{{ $population }}"
                            {{ $selected ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('pen_id')
                <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Ringkasan kandang (readonly) --}}
    <div class="grid md:grid-cols-3 md:gap-6">
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Nama Kandang</label>
            <input id="inputPenName" type="text" value="(Otomatis dari pilihan)"
                   class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Kapasitas</label>
            <input id="inputPenCapacity" type="text" value="(Otomatis dari pilihan)"
                   class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Populasi</label>
            <input id="inputPenPopulation" type="text" value="(Otomatis dari pilihan)"
                   class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
    </div>

    {{-- ITEMS: Repeater daftar bahan pakan --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-semibold text-gray-800">Daftar Bahan Pakan (Koloni)</label>
            <button type="button" id="btnAddItem"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-3 py-2 text-xs shadow-sm transition-all">
                + Tambah Item
            </button>
        </div>

        <div id="itemsContainer" class="space-y-3">
            {{-- Template baris item --}}
            <template id="itemRowTemplate">
                <div class="grid md:grid-cols-12 gap-3 items-end">
                    <div class="md:col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Tipe</label>
                        <select name="__NAME_PREFIX__[type]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required>
                            <option value="" disabled selected>Pilih tipe</option>
                            <option value="forage">Hijauan</option>
                            <option value="concentrate">Konsentrat</option>
                            <option value="feed_material">Bahan campuran</option>
                        </select>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Nama Pakan</label>
                        <input type="text" name="__NAME_PREFIX__[name]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="Contoh: Rumput Gajah" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Qty (kg)</label>
                        <input type="number" step="0.01" min="0" name="__NAME_PREFIX__[qty_kg]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5 item-qty" placeholder="0" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Harga/kg (Rp)</label>
                        <input type="number" step="1" min="0" name="__NAME_PREFIX__[price_per_kg]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5 item-price" placeholder="0" required>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Total</label>
                        {{-- tampil ke user --}}
                        <input type="text" class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-2.5 item-total-display" value="0" readonly>
                        {{-- dikirim ke server --}}
                        <input type="hidden" name="__NAME_PREFIX__[total_price]" class="item-total" value="0">
                    </div>
                    <div class="md:col-span-1 flex">
                        <button type="button" class="w-full bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-semibold rounded-lg px-2.5 py-2 remove-item">
                            Hapus
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Error untuk items --}}
        @error('items') <div class="mt-2 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('items.*.type') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('items.*.name') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('items.*.qty_kg') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('items.*.price_per_kg') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('items.*.total_price') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
    </div>

    {{-- Catatan --}}
    <div class="mb-8">
        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan (opsional)</label>
        <textarea id="notes" name="notes" rows="4"
                  class="block w-full text-sm border border-gray-300 rounded-lg p-3 bg-gray-50"
                  placeholder="Tambahkan catatan jika perlu...">{{ old('notes') }}</textarea>
        @error('notes')
            <span class="text-xs text-red-600">{{ $message }}</span>
        @enderror
    </div>

    {{-- Footer --}}
    <div class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-600">
            <span class="font-semibold">Perkiraan Total:</span>
            <span id="grandTotal" class="font-bold">Rp 0</span>
        </div>

        {{-- kirim total_cost ke server --}}
        <input type="hidden" name="total_cost" id="total_cost" value="{{ old('total_cost', 0) }}">

        <button type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-base px-8 py-3 transition-all">
            Simpan Data
        </button>
    </div>
</form>

<script>
    // Sync ringkasan kandang saat select berubah
    (function () {
        const sel   = document.getElementById('pen_id');
        const name  = document.getElementById('inputPenName');
        const cap   = document.getElementById('inputPenCapacity');
        const pop   = document.getElementById('inputPenPopulation');
        if (!sel) return;

        const sync = function () {
            const opt = sel.options[sel.selectedIndex];
            if (!opt) return;
            if (name) name.value = opt.getAttribute('data-name') || '';
            if (cap)  cap.value  = opt.getAttribute('data-capacity') || '';
            if (pop)  pop.value  = opt.getAttribute('data-population') || '';
        };

        sel.addEventListener('change', sync);
        if (sel.value) sync(); // prefill saat reload (termasuk preselect fromPen / old pen_id)
    })();

    // Repeater Items
    (function () {
        let idx = 0;
        const container = document.getElementById('itemsContainer');
        const tpl = document.getElementById('itemRowTemplate');
        const btnAdd = document.getElementById('btnAddItem');
        const grandTotalEl = document.getElementById('grandTotal');
        const totalCostHidden = document.getElementById('total_cost');

        const oldItems = @json(old('items', []));

        function formatRupiah(n) {
            n = Number(n || 0);
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function recalcRowTotal(row) {
            const qty = parseFloat(row.querySelector('.item-qty')?.value || 0);
            const price = parseFloat(row.querySelector('.item-price')?.value || 0);
            const total = (isFinite(qty) ? qty : 0) * (isFinite(price) ? price : 0);

            const totalHidden = row.querySelector('.item-total');
            const totalDisplay = row.querySelector('.item-total-display');

            if (totalHidden) totalHidden.value = isFinite(total) ? total : 0;
            if (totalDisplay) totalDisplay.value = isFinite(total) ? total : 0;

            recalcGrandTotal();
        }

        function recalcGrandTotal() {
            let sum = 0;
            container.querySelectorAll('.item-total').forEach(i => {
                sum += parseFloat(i.value || 0);
            });
            grandTotalEl.textContent = formatRupiah(sum);
            if (totalCostHidden) totalCostHidden.value = isFinite(sum) ? sum : 0;
        }

        function attachRowEvents(row) {
            row.querySelectorAll('.item-qty, .item-price').forEach(inp => {
                inp.addEventListener('input', () => recalcRowTotal(row));
            });
            row.querySelector('.remove-item').addEventListener('click', () => {
                row.remove();
                recalcGrandTotal();
            });
        }

        function addRow(defaults = {}) {
            const node = document.importNode(tpl.content, true);
            const row = node.firstElementChild;

            // set name prefix (untuk input & select & hidden)
            row.querySelectorAll('[name^="__NAME_PREFIX__"]').forEach((el) => {
                const newName = el.getAttribute('name').replace('__NAME_PREFIX__', `items[${idx}]`);
                el.setAttribute('name', newName);
            });

            // defaults (opsional)
            const selType = row.querySelector('select[name$="[type]"]');
            if (selType && defaults.type) selType.value = defaults.type;

            const nameEl = row.querySelector('input[name$="[name]"]');
            if (nameEl && defaults.name) nameEl.value = defaults.name;

            const qtyEl = row.querySelector('input[name$="[qty_kg]"]');
            if (qtyEl && (defaults.qty_kg ?? '') !== '') qtyEl.value = defaults.qty_kg;

            const priceEl = row.querySelector('input[name$="[price_per_kg]"]');
            if (priceEl && (defaults.price_per_kg ?? '') !== '') priceEl.value = defaults.price_per_kg;

            const totalHidden = row.querySelector('.item-total');
            const totalDisplay = row.querySelector('.item-total-display');
            if ((defaults.total_price ?? '') !== '') {
                if (totalHidden) totalHidden.value = defaults.total_price;
                if (totalDisplay) totalDisplay.value = defaults.total_price;
            }

            attachRowEvents(row);
            container.appendChild(row);
            recalcRowTotal(row);

            idx++;
        }

        // Render dari old input kalau ada, else 1 baris kosong
        if (Array.isArray(oldItems) && oldItems.length > 0) {
            oldItems.forEach(it => addRow(it || {}));
        } else {
            addRow();
        }

        btnAdd.addEventListener('click', () => addRow());
    })();
</script>
