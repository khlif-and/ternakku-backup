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

<form action="{{ route('admin.care-livestock.treatment-schedule-individu.store', ['farm_id' => $farm->id]) }}" method="POST" class="w-full max-w-full">
    @csrf

    {{-- Baris 1: Tanggal transaksi, Tanggal jadwal, Pilih ternak --}}
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
            <label for="schedule-date-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">
                Tanggal Jadwal
            </label>
            <input id="schedule-date-airdatepicker" name="schedule_date" type="text"
                   class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3"
                   placeholder="Pilih tanggal jadwal" autocomplete="off" value="{{ old('schedule_date') }}" required>
            @error('schedule_date')
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

    {{-- OBAT (opsional, single field set) --}}
    <div class="mb-8">
        <div class="grid md:grid-cols-12 gap-3">
            <div class="md:col-span-5">
                <label class="block mb-1 text-xs font-medium text-gray-700">Nama Obat</label>
                <input type="text" name="medicine_name" value="{{ old('medicine_name') }}"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="Nama obat">
                @error('medicine_name') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="md:col-span-3">
                <label class="block mb-1 text-xs font-medium text-gray-700">Satuan</label>
                <input type="text" name="medicine_unit" value="{{ old('medicine_unit') }}"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="ampul, tablet, ml, dll">
                @error('medicine_unit') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="md:col-span-4">
                <label class="block mb-1 text-xs font-medium text-gray-700">Qty / Satuan</label>
                <input type="number" step="0.01" min="0" name="medicine_qty_per_unit" value="{{ old('medicine_qty_per_unit') }}"
                       class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="0">
                @error('medicine_qty_per_unit') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- TINDAKAN (opsional, single) --}}
    <div class="mb-6">
        <label class="block mb-1 text-xs font-medium text-gray-700">Nama Tindakan</label>
        <input type="text" name="treatment_name" value="{{ old('treatment_name') }}"
               class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="Suntik vitamin, deworming, dll">
        @error('treatment_name') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
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
            Simpan Jadwal
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
</script>
