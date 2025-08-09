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

<form action="{{ route('admin.care-livestock.treatment-individu.store', ['farm_id' => $farm->id]) }}" method="POST" class="w-full max-w-full">
    @csrf

    {{-- Baris 1: Tanggal transaksi, Pilih ternak, Pilih penyakit --}}
    <div class="grid md:grid-cols-3 md:gap-6">
        <div class="mb-8">
            <label for="transaction-date-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">
                Tanggal Treatment
            </label>
            <input id="transaction-date-airdatepicker" name="transaction_date" type="text"
                   class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3"
                   placeholder="Pilih tanggal" autocomplete="off" value="{{ old('transaction_date') }}" required>
            @error('transaction_date')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-8">
            <label for="livestock_id" class="block mb-2 text-sm font-medium text-gray-700">Eartag / Nama Ternak</label>
            <select id="livestock_id" name="livestock_id"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                <option value="" disabled {{ old('livestock_id') ? '' : 'selected' }}>Pilih Ternak</option>

                @foreach ($livestocks as $livestock)
                    @php
                        $eartag = $livestock->eartag
                                  ?? $livestock->eartag_number
                                  ?? $livestock->ear_tag
                                  ?? $livestock->tag
                                  ?? $livestock->code
                                  ?? $livestock->rfid_number
                                  ?? null;

                        $nama = $livestock->name
                                ?? $livestock->nama
                                ?? $livestock->display_name
                                ?? $livestock->nickname
                                ?? null;

                        $jenis = optional($livestock->livestockType)->name ?? '-';
                        $ras   = optional($livestock->livestockBreed)->name ?? '-';

                        $label = trim(($eartag ?: '-').' - '.($nama ?: '-'));
                        $label .= ' ('.$jenis.($ras !== '-' ? ' - '.$ras : '').')';
                    @endphp

                    <option value="{{ $livestock->id }}"
                            data-eartag="{{ $eartag ?: '' }}"
                            data-nama="{{ $nama ?: '' }}"
                            data-jenis="{{ $jenis }}"
                            data-ras="{{ $ras }}"
                            {{ old('livestock_id') == $livestock->id ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('livestock_id')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-8">
            <label for="disease_id" class="block mb-2 text-sm font-medium text-gray-700">Penyakit</label>
            <select id="disease_id" name="disease_id"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                <option value="" disabled {{ old('disease_id') ? '' : 'selected' }}>Pilih Penyakit</option>
                @foreach ($diseases as $d)
                    <option value="{{ $d->id }}" {{ old('disease_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
            @error('disease_id')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Ringkasan ternak (readonly) --}}
    <div class="grid md:grid-cols-4 md:gap-6">
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Eartag</label>
            <input id="inputEartag" type="text" value="(Otomatis dari pilihan)"
                   class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Nama Ternak</label>
            <input id="inputNamaTernak" type="text" value="(Otomatis dari pilihan)"
                   class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Jenis</label>
            <input id="inputJenisTernak" type="text" value="(Otomatis dari pilihan)"
                   class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Ras</label>
            <input id="inputRasTernak" type="text" value="(Otomatis dari pilihan)"
                   class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
    </div>

    {{-- OBAT: Repeater --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-semibold text-gray-800">Daftar Obat</label>
            <button type="button" id="btnAddMed"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-3 py-2 text-xs shadow-sm transition-all">
                + Tambah Obat
            </button>
        </div>

        <div id="medsContainer" class="space-y-3">
            <template id="medRowTemplate">
                <div class="grid md:grid-cols-12 gap-3 items-end">
                    <div class="md:col-span-3">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Nama Obat</label>
                        <input type="text" name="__NAME_PREFIX__[name]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="Nama obat" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Satuan</label>
                        <input type="text" name="__NAME_PREFIX__[unit]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="ampul, tablet, ml, dll" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Qty / Satuan</label>
                        <input type="number" step="0.01" min="0" name="__NAME_PREFIX__[qty_per_unit]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5 med-qty" placeholder="0" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Harga / Satuan (Rp)</label>
                        <input type="number" step="1" min="0" name="__NAME_PREFIX__[price_per_unit]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5 med-price" placeholder="0" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Total</label>
                        <input type="text" class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-2.5 med-total-display" value="0" readonly>
                        <input type="hidden" name="__NAME_PREFIX__[total_price]" class="med-total-hidden" value="0">
                    </div>
                    <div class="md:col-span-1 flex">
                        <button type="button" class="w-full bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-semibold rounded-lg px-2.5 py-2 remove-med">
                            Hapus
                        </button>
                    </div>
                </div>
            </template>
        </div>

        @error('medicines') <div class="mt-2 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('medicines.*.name') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('medicines.*.unit') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('medicines.*.qty_per_unit') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('medicines.*.price_per_unit') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
    </div>

    {{-- TINDAKAN: Repeater --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-semibold text-gray-800">Daftar Tindakan</label>
            <button type="button" id="btnAddTreat"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-3 py-2 text-xs shadow-sm transition-all">
                + Tambah Tindakan
            </button>
        </div>

        <div id="treatsContainer" class="space-y-3">
            <template id="treatRowTemplate">
                <div class="grid md:grid-cols-12 gap-3 items-end">
                    <div class="md:col-span-7">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Nama Tindakan</label>
                        <input type="text" name="__NAME_PREFIX__[name]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="Suntik vitamin, deworming, dll" required>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block mb-1 text-xs font-medium text-gray-700">Biaya (Rp)</label>
                        <input type="number" step="1" min="0" name="__NAME_PREFIX__[cost]" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5 treat-cost" placeholder="0" required>
                    </div>
                    <div class="md:col-span-1 flex">
                        <button type="button" class="w-full bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-semibold rounded-lg px-2.5 py-2 remove-treat">
                            Hapus
                        </button>
                    </div>
                </div>
            </template>
        </div>

        @error('treatments') <div class="mt-2 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('treatments.*.name') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        @error('treatments.*.cost') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
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

        <input type="hidden" name="total_cost" id="total_cost" value="{{ old('total_cost', 0) }}">

        <button type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-base px-8 py-3 transition-all">
            Simpan Data
        </button>
    </div>
</form>

<script>
    // Sync ringkasan ternak
    (function () {
        const sel   = document.getElementById('livestock_id');
        const eart  = document.getElementById('inputEartag');
        const nama  = document.getElementById('inputNamaTernak');
        const jenis = document.getElementById('inputJenisTernak');
        const ras   = document.getElementById('inputRasTernak');

        if (!sel) return;

        const sync = function () {
            const opt = sel.options[sel.selectedIndex];
            if (!opt) return;
            if (eart)  eart.value  = opt.getAttribute('data-eartag') || '';
            if (nama)  nama.value  = opt.getAttribute('data-nama')   || '';
            if (jenis) jenis.value = opt.getAttribute('data-jenis')  || '';
            if (ras)   ras.value   = opt.getAttribute('data-ras')    || '';
        };

        sel.addEventListener('change', sync);
        if (sel.value) sync();
    })();

    (function () {
        const grandTotalEl = document.getElementById('grandTotal');
        const totalCostHidden = document.getElementById('total_cost');

        function formatRupiah(n) {
            n = Number(n || 0);
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function recalcGrandTotal() {
            let sum = 0;

            document.querySelectorAll('.med-total-hidden').forEach(i => {
                sum += parseFloat(i.value || 0);
            });

            document.querySelectorAll('.treat-cost').forEach(i => {
                sum += parseFloat(i.value || 0);
            });

            grandTotalEl.textContent = formatRupiah(sum);
            if (totalCostHidden) totalCostHidden.value = isFinite(sum) ? sum : 0;
        }

        // ====== OBAT ======
        let idxMed = 0;
        const medsContainer = document.getElementById('medsContainer');
        const medTpl = document.getElementById('medRowTemplate');
        const btnAddMed = document.getElementById('btnAddMed');
        const oldMeds = @json(old('medicines', []));

        function recalcMedRow(row) {
            const qty   = parseFloat(row.querySelector('.med-qty')?.value || 0);
            const price = parseFloat(row.querySelector('.med-price')?.value || 0);
            const total = (isFinite(qty) ? qty : 0) * (isFinite(price) ? price : 0);

            const totalHidden  = row.querySelector('.med-total-hidden');
            const totalDisplay = row.querySelector('.med-total-display');

            if (totalHidden)  totalHidden.value  = isFinite(total) ? total : 0;
            if (totalDisplay) totalDisplay.value = isFinite(total) ? total : 0;

            recalcGrandTotal();
        }

        function attachMedEvents(row) {
            row.querySelectorAll('.med-qty, .med-price').forEach(inp => {
                inp.addEventListener('input', () => recalcMedRow(row));
            });
            row.querySelector('.remove-med').addEventListener('click', () => {
                row.remove();
                recalcGrandTotal();
            });
        }

        function addMedRow(defaults = {}) {
            const node = document.importNode(medTpl.content, true);
            const row = node.firstElementChild;

            row.querySelectorAll('[name^="__NAME_PREFIX__"]').forEach((el) => {
                const newName = el.getAttribute('name').replace('__NAME_PREFIX__', `medicines[${idxMed}]`);
                el.setAttribute('name', newName);
            });

            row.querySelector('input[name$="[name]"]').value = defaults.name ?? '';
            row.querySelector('input[name$="[unit]"]').value = defaults.unit ?? '';
            row.querySelector('input[name$="[qty_per_unit]"]').value = (defaults.qty_per_unit ?? '') !== '' ? defaults.qty_per_unit : '';
            row.querySelector('input[name$="[price_per_unit]"]').value = (defaults.price_per_unit ?? '') !== '' ? defaults.price_per_unit : '';

            const totalHidden  = row.querySelector('.med-total-hidden');
            const totalDisplay = row.querySelector('.med-total-display');
            const initialTotal = defaults.total_price ?? 0;
            totalHidden.value  = initialTotal;
            totalDisplay.value = initialTotal;

            attachMedEvents(row);
            medsContainer.appendChild(row);
            recalcMedRow(row);

            idxMed++;
        }

        if (Array.isArray(oldMeds) && oldMeds.length > 0) {
            oldMeds.forEach(it => addMedRow(it || {}));
        } else {
            addMedRow();
        }

        btnAddMed.addEventListener('click', () => addMedRow());

        // ====== TINDAKAN ======
        let idxTreat = 0;
        const treatsContainer = document.getElementById('treatsContainer');
        const treatTpl = document.getElementById('treatRowTemplate');
        const btnAddTreat = document.getElementById('btnAddTreat');
        const oldTreats = @json(old('treatments', []));

        function attachTreatEvents(row) {
            row.querySelectorAll('.treat-cost').forEach(inp => {
                inp.addEventListener('input', recalcGrandTotal);
            });
            row.querySelector('.remove-treat').addEventListener('click', () => {
                row.remove();
                recalcGrandTotal();
            });
        }

        function addTreatRow(defaults = {}) {
            const node = document.importNode(treatTpl.content, true);
            const row = node.firstElementChild;

            row.querySelectorAll('[name^="__NAME_PREFIX__"]').forEach((el) => {
                const newName = el.getAttribute('name').replace('__NAME_PREFIX__', `treatments[${idxTreat}]`);
                el.setAttribute('name', newName);
            });

            row.querySelector('input[name$="[name]"]').value = defaults.name ?? '';
            row.querySelector('input[name$="[cost]"]').value = (defaults.cost ?? '') !== '' ? defaults.cost : '';

            attachTreatEvents(row);
            treatsContainer.appendChild(row);
            recalcGrandTotal();

            idxTreat++;
        }

        if (Array.isArray(oldTreats) && oldTreats.length > 0) {
            oldTreats.forEach(it => addTreatRow(it || {}));
        } else {
            addTreatRow();
        }

        btnAddTreat.addEventListener('click', () => addTreatRow());
    })();
</script>
