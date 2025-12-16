<form x-data="formInsemination()" x-init="init()"
    action='{{ route('admin.care_livestock.natural_insemination.store', ['farm_id' => $farm->id]) }}' method='POST'
    class='w-full max-w-full'>
    @csrf

    {{-- Baris 1 --}}
    <div class="grid md:grid-cols-3 md:gap-6">
        <div class="mb-8">
            <label for="transaction_date" class="block mb-2 text-sm font-medium text-gray-700">
                Tanggal Inseminasi
            </label>
            <input id="transaction_date" name="transaction_date" type="date"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3"
                value="{{ old('transaction_date') }}" required>
        </div>

        <div class="mb-8">
            <label for="livestock_id" class="block mb-2 text-sm font-medium text-gray-700">
                Eartag / Nama Ternak (Betina)
            </label>
            <select id="livestock_id" name="livestock_id" x-model="selectedLivestock" @change="onLivestockChange"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                <option value="" disabled {{ old('livestock_id') ? '' : 'selected' }}>Pilih Ternak</option>
                @foreach ($livestocks as $livestock)
                    <option value="{{ $livestock->id }}" data-type-id="{{ $livestock->livestock_type_id }}"
                        data-eartag="{{ $livestock->eartag_number ?? '' }}"
                        data-jenis="{{ optional($livestock->livestockType)->name ?? '' }}"
                        data-ras="{{ optional($livestock->livestockBreed)->name ?? '' }}">
                        {{ $livestock->eartag_number ?? '-' }} - {{ optional($livestock->livestockBreed)->name ?? '-' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-8">
            <label for="action_time" class="block mb-2 text-sm font-medium text-gray-700">
                Waktu Tindakan
            </label>
            <input id="action_time" name="action_time" type="time"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3"
                value="{{ old('action_time') }}" required>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="grid md:grid-cols-3 md:gap-6 mb-8">
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Eartag</label>
            <input type="text" x-model="summary.eartag"
                class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed"
                readonly>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Jenis</label>
            <input type="text" x-model="summary.jenis"
                class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed"
                readonly>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Ras</label>
            <input type="text" x-model="summary.ras"
                class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed"
                readonly>
        </div>
    </div>

    {{-- Detail sejajar --}}
    <div class="flex flex-col md:flex-row md:items-end md:gap-6 mb-8">
        <div class="w-full md:w-1/3">
            <label class="block mb-1 text-xs font-medium text-gray-700">Ras/Breed Pejantan</label>
            <select name="sire_breed_id" id="sire_breed_id" x-model="selectedBreed"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required>
                <option value="">Pilih ras/breed pejantan</option>
                <template x-for="breed in filteredBreeds" :key="breed.id">
                    <option :value="breed.id" x-text="breed.name"></option>
                </template>
            </select>
        </div>

        <div class="w-full md:w-1/3">
            <label class="block mb-1 text-xs font-medium text-gray-700">Nama Pemilik Pejantan</label>
            <input type="text" name="sire_owner_name"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                placeholder="Nama pemilik pejantan" required>
        </div>

        <div class="w-full md:w-1/3">
            <label class="block mb-1 text-xs font-medium text-gray-700">Biaya (Rp)</label>
            <input type="number" name="cost" min="0"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" placeholder="0"
                required>
        </div>
    </div>

    {{-- Catatan --}}
    <div class="mb-8">
        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan (opsional)</label>
        <textarea id="notes" name="notes" rows="4"
            class="block w-full text-sm border border-gray-300 rounded-lg p-3 bg-gray-50"
            placeholder="Tambahkan catatan jika perlu...">{{ old('notes') }}</textarea>
    </div>

    {{-- Tombol Submit --}}
    <div class="flex items-center justify-end mt-4">
        <button type="submit"
            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-base px-8 py-3 transition-all">
            Simpan Data
        </button>
    </div>
</form>

<script>
    function formInsemination() {
        return {
            selectedLivestock: '',
            selectedBreed: '',
            allBreeds: @json($breedsJson),
            summary: {
                eartag: '(Otomatis dari pilihan)',
                jenis: '(Otomatis dari pilihan)',
                ras: '(Otomatis dari pilihan)',
            },
            filteredBreeds: [],

            init() {
                if (this.selectedLivestock) this.onLivestockChange();
            },

            onLivestockChange(event) {
                const select = event ? event.target : document.getElementById('livestock_id');
                const opt = select.options[select.selectedIndex];
                const typeId = opt?.dataset.typeId;

                this.summary.eartag = opt?.dataset.eartag || '(Tidak tersedia)';
                this.summary.jenis = opt?.dataset.jenis || '(Tidak tersedia)';
                this.summary.ras = opt?.dataset.ras || '(Tidak tersedia)';

                this.filteredBreeds = this.allBreeds.filter(
                    b => String(b.type_id) === String(typeId)
                );
            },
        }
    }
</script>
