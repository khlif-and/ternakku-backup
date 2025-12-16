{{-- resources/views/admin/care_livestock/livestock_birth/partials/form.blade.php --}}

@if (session('error'))
    <div class="flex items-center p-4 mb-4 text-sm font-medium text-red-800 rounded-lg bg-red-100" role="alert">
        <svg class="w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

@if (session('success'))
    <div class="p-4 mb-4 text-sm font-medium text-green-800 rounded-lg bg-green-100" role="alert">
        {{ session('success') }}
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

@php
    // Fallback: terima $livestocks ATAU $femaleLivestocks agar tidak undefined
    $listLivestocks = $livestocks ?? $femaleLivestocks ?? collect();
@endphp

<form action="{{ route('admin.care_livestock.livestock_birth.store', ['farm_id' => $farm->id]) }}" method="POST" class="w-full max-w-full">
    @csrf

    {{-- Baris 1: Tanggal kelahiran, Pilih induk (betina) --}}
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-8">
            <label for="transaction-date-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">
                Tanggal Kelahiran
            </label>
            <input id="transaction-date-airdatepicker" name="transaction_date" type="text"
                   class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3"
                   placeholder="Pilih tanggal" autocomplete="off" value="{{ old('transaction_date') }}" required>
            @error('transaction_date')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-8">
            <label for="livestock_id" class="block mb-2 text-sm font-medium text-gray-700">Induk (Eartag / Nama) — Betina</label>
            <select id="livestock_id" name="livestock_id"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                <option value="" disabled {{ old('livestock_id') ? '' : 'selected' }}>Pilih Induk</option>

                @forelse ($listLivestocks as $livestock)
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
                @empty
                    <option value="" disabled>Tidak ada ternak betina tersedia</option>
                @endforelse
            </select>
            @error('livestock_id')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Ringkasan induk (readonly) --}}
    <div class="grid md:grid-cols-4 md:gap-6">
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Eartag</label>
            <input id="inputEartag" type="text" value="(Otomatis dari pilihan)"
                   class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Nama Induk</label>
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

    {{-- Detail Kelahiran (sesuai controller) --}}
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-6">
            <label class="block mb-1 text-xs font-medium text-gray-700">Nama Petugas</label>
            <input type="text" name="officer_name" value="{{ old('officer_name') }}"
                   class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                   placeholder="Nama petugas">
            @error('officer_name') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-1 text-xs font-medium text-gray-700">Status</label>
            <input type="text" name="status" value="{{ old('status') }}"
                   class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                   placeholder="contoh: ABORTUS atau GAVE_BIRTH" required>
            @error('status') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-1 text-xs font-medium text-gray-700">Perkiraan Sapih (Estimated Weaning)</label>
            <input type="date" name="estimated_weaning" value="{{ old('estimated_weaning') }}"
                   class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
            @error('estimated_weaning') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-1 text-xs font-medium text-gray-700">Biaya (Rp)</label>
            <input type="number" step="1" min="0" name="cost" value="{{ old('cost') }}"
                   class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="0" required>
            @error('cost') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
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

    <div class="flex items-center justify-end mt-4">
        <button type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-base px-8 py-3 transition-all">
            Simpan Data
        </button>
    </div>
</form>

<script>
    (function () {
        const sel   = document.getElementById('livestock_id');
        const eart  = document.getElementById('inputEartag');
        const nama  = document.getElementById('inputNamaTernak');
        const jenis = document.getElementById('inputJenisTernak');
        const ras   = document.getElementById('inputRasTernak');

        if (!sel) return;

        function sync() {
            const opt = sel.options[sel.selectedIndex];
            if (!opt) return;
            if (eart)  eart.value  = opt.getAttribute('data-eartag') || '';
            if (nama)  nama.value  = opt.getAttribute('data-nama')   || '';
            if (jenis) jenis.value = opt.getAttribute('data-jenis')  || '';
            if (ras)   ras.value   = opt.getAttribute('data-ras')    || '';
        }

        sel.addEventListener('change', sync);
        if (sel.value) sync();
    })();
</script>
