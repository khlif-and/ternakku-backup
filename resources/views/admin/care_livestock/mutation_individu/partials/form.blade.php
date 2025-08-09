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

<form action="{{ route('admin.care-livestock.mutation-individu.store', ['farm_id' => $farm->id]) }}" method="POST" class="w-full max-w-full">
    @csrf

    {{-- Baris 1: Tanggal transaksi, Pilih ternak, Pilih kandang tujuan --}}
    <div class="grid md:grid-cols-3 md:gap-6">
        <div class="mb-8">
            <label for="transaction-date-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">
                Tanggal Transaksi
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
                        $penAsal = optional($livestock->pen)->name ?? '-';

                        $label = trim(($eartag ?: '-').' - '.($nama ?: '-'));
                        $label .= ' ('.$jenis.($ras !== '-' ? ' - '.$ras : '').')';
                    @endphp

                    <option value="{{ $livestock->id }}"
                            data-eartag="{{ $eartag ?: '' }}"
                            data-nama="{{ $nama ?: '' }}"
                            data-jenis="{{ $jenis }}"
                            data-ras="{{ $ras }}"
                            data-pen="{{ $penAsal }}"
                            data-pen-id="{{ $livestock->pen_id ?? '' }}"
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
            <label for="pen_destination" class="block mb-2 text-sm font-medium text-gray-700">Kandang Tujuan</label>
            <select id="pen_destination" name="pen_destination"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                <option value="" disabled {{ old('pen_destination') ? '' : 'selected' }}>Pilih Kandang Tujuan</option>
                @foreach ($pens as $pen)
                    <option value="{{ $pen->id }}" {{ old('pen_destination') == $pen->id ? 'selected' : '' }}>
                        {{ $pen->name ?? 'Tanpa Nama' }}
                    </option>
                @endforeach
            </select>
            @error('pen_destination')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Ringkasan ternak & kandang (readonly) --}}
    <div class="grid md:grid-cols-5 md:gap-6">
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
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Kandang Asal</label>
            <input id="inputPenAsal" type="text" value="(Otomatis dari pilihan)"
                   class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
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
    <div class="flex items-center justify-end mt-4">
        <button type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-base px-8 py-3 transition-all">
            Simpan Mutasi
        </button>
    </div>
</form>

<script>
    // Sync ringkasan ternak & kandang asal
    (function () {
        const sel   = document.getElementById('livestock_id');
        const eart  = document.getElementById('inputEartag');
        const nama  = document.getElementById('inputNamaTernak');
        const jenis = document.getElementById('inputJenisTernak');
        const ras   = document.getElementById('inputRasTernak');
        const penAsal = document.getElementById('inputPenAsal');
        const penDest = document.getElementById('pen_destination');

        if (!sel) return;

        const sync = function () {
            const opt = sel.options[sel.selectedIndex];
            if (!opt) return;

            const asalId = opt.getAttribute('data-pen-id');

            if (eart)    eart.value    = opt.getAttribute('data-eartag') || '';
            if (nama)    nama.value    = opt.getAttribute('data-nama')   || '';
            if (jenis)   jenis.value   = opt.getAttribute('data-jenis')  || '';
            if (ras)     ras.value     = opt.getAttribute('data-ras')    || '';
            if (penAsal) penAsal.value = opt.getAttribute('data-pen')    || '';

            // opsional: cegah pilih kandang tujuan sama dgn kandang asal (disable option)
            if (penDest) {
                const options = penDest.querySelectorAll('option');
                options.forEach(o => {
                    if (!o.value) return;
                    o.disabled = (asalId && o.value === asalId);
                });

                // jika yang terpilih kebetulan disabled, reset ke default
                const selected = penDest.options[penDest.selectedIndex];
                if (selected && selected.disabled) {
                    penDest.selectedIndex = 0; // ke placeholder
                }
            }
        };

        sel.addEventListener('change', sync);
        if (sel.value) sync();
    })();
</script>
