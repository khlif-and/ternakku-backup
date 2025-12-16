{{-- Pesan error modern --}}
@if (session('error'))
    <div class="flex items-center p-4 mb-6 text-sm font-medium text-red-800 rounded-lg bg-red-100" role="alert">
        <svg class="w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

<form
    action="{{ isset($analysis) ? route('admin.care-livestock.milk-analysis-individu.update', [$farm->id, $analysis->id]) : route('admin.care-livestock.milk-analysis-individu.store', $farm->id) }}"
    method="POST"
    enctype="multipart/form-data"
    class="w-full max-w-full"
>
    @csrf
    @if(isset($analysis))
        @method('PUT')
    @endif

    {{-- 1. PILIH TERNAK (PENTING untuk Individu) --}}
    <div class="mb-8">
        <label for="livestock_id" class="block mb-2 text-sm font-medium text-gray-700">Pilih Ternak</label>
        <select id="livestock_id" name="livestock_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
            <option value="" disabled selected>-- Pilih ID Ternak --</option>
            @foreach ($livestocks as $livestock)
                <option value="{{ $livestock->id }}"
                    {{ old('livestock_id', $analysis->livestock_id ?? '') == $livestock->id ? 'selected' : '' }}>
                    {{ $livestock->eartag_number }}
                </option>
            @endforeach
        </select>
        @error('livestock_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    <div class="grid md:grid-cols-2 md:gap-6">
        {{-- Tanggal Produksi --}}
        <div class="mb-8">
            <label for="transaction_date" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Analisis</label>
            <input id="transaction_date" name="transaction_date" type="text" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Pilih tanggal" autocomplete="off" value="{{ old('transaction_date', $analysis->milkAnalysisH->transaction_date ?? '') }}" required>
            @error('transaction_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        {{-- BJ --}}
        <div class="mb-8">
            <label for="bj" class="block mb-2 text-sm font-medium text-gray-700">BJ</label>
            <input id="bj" type="number" step="any" name="bj" value="{{ old('bj', $analysis->bj ?? '') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="BJ (opsional)">
            @error('bj') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-3 md:gap-6">
        {{-- AT --}}
        <div class="mb-8">
            <label for="at" class="block mb-2 text-sm font-medium text-gray-700">AT</label>
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" id="at" name="at"
                       class="sr-only peer"
                       value="1"
                       {{ old('at', $analysis->at ?? false) ? 'checked' : '' }}>
                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                <span class="ml-3 text-sm text-gray-700 select-none">Aktif</span>
            </label>
            @error('at') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        {{-- AB --}}
        <div class="mb-8">
            <label for="ab" class="block mb-2 text-sm font-medium text-gray-700">AB</label>
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" id="ab" name="ab"
                       class="sr-only peer"
                       value="1"
                       {{ old('ab', $analysis->ab ?? false) ? 'checked' : '' }}>
                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                <span class="ml-3 text-sm text-gray-700 select-none">Aktif</span>
            </label>
            @error('ab') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        {{-- MBRT --}}
        <div class="mb-8">
            <label for="mbrt" class="block mb-2 text-sm font-medium text-gray-700">MBRT</label>
            <input id="mbrt" type="number" step="any" name="mbrt" value="{{ old('mbrt', $analysis->mbrt ?? '') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="MBRT (opsional)">
            @error('mbrt') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-3 md:gap-6">
        {{-- Air --}}
        <div class="mb-8">
            <label for="a_water" class="block mb-2 text-sm font-medium text-gray-700">Air</label>
            <input id="a_water" type="number" step="any" name="a_water" value="{{ old('a_water', $analysis->a_water ?? '') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Air (opsional)">
            @error('a_water') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        {{-- Protein --}}
        <div class="mb-8">
            <label for="protein" class="block mb-2 text-sm font-medium text-gray-700">Protein</label>
            <input id="protein" type="number" step="any" name="protein" value="{{ old('protein', $analysis->protein ?? '') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Protein (opsional)">
            @error('protein') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        {{-- Lemak --}}
        <div class="mb-8">
            <label for="fat" class="block mb-2 text-sm font-medium text-gray-700">Lemak</label>
            <input id="fat" type="number" step="any" name="fat" value="{{ old('fat', $analysis->fat ?? '') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Lemak (opsional)">
            @error('fat') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-3 md:gap-6">
        {{-- SNF --}}
        <div class="mb-8">
            <label for="snf" class="block mb-2 text-sm font-medium text-gray-700">SNF</label>
            <input id="snf" type="number" step="any" name="snf" value="{{ old('snf', $analysis->snf ?? '') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="SNF (opsional)">
            @error('snf') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        {{-- TS --}}
        <div class="mb-8">
            <label for="ts" class="block mb-2 text-sm font-medium text-gray-700">TS</label>
            <input id="ts" type="number" step="any" name="ts" value="{{ old('ts', $analysis->ts ?? '') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="TS (opsional)">
            @error('ts') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        {{-- RZN --}}
        <div class="mb-8">
            <label for="rzn" class="block mb-2 text-sm font-medium text-gray-700">RZN</label>
            <input id="rzn" type="number" step="any" name="rzn" value="{{ old('rzn', $analysis->rzn ?? '') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="RZN (opsional)">
            @error('rzn') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Catatan --}}
    <div class="mb-8">
        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan (opsional)</label>
        <textarea id="notes" name="notes" rows="4" class="block w-full text-sm border border-gray-300 rounded-lg p-3 bg-gray-50" placeholder="Tambahkan catatan jika perlu...">{{ old('notes', $analysis->notes ?? '') }}</textarea>
        @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Tombol Simpan --}}
    <div class="flex justify-end mt-4">
        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-base px-8 py-3 transition-all">
            {{ isset($analysis) ? 'Simpan Perubahan' : 'Simpan Data' }}
        </button>
    </div>
</form>
