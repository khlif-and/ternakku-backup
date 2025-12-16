{{-- Pesan error dengan gaya modern --}}
@if (session('error'))
    <div class="flex items-center p-4 mb-6 text-sm font-medium text-red-800 rounded-lg bg-red-100" role="alert">
        <svg class="w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

<form
    action="{{ route('admin.care-livestock.sales-order.store', $farm_id) }}"
    method="POST"
    enctype="multipart/form-data"
    class="w-full max-w-full"
>
    @csrf

    {{-- Tanggal & Customer --}}
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">
                Tanggal Order
            </label>
            <input
                name="order_date"
                type="date"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3"
                value="{{ old('order_date') }}"
                required
            >
            @error('order_date')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-8">
            <label for="customer_id" class="block mb-2 text-sm font-medium text-gray-700">
                Customer
            </label>
            <select
                id="customer_id"
                name="customer_id"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3"
                required
            >
                <option value="" disabled selected>Pilih Customer</option>

                @foreach($customers as $cust)
                    <option value="{{ $cust->id }}" {{ old('customer_id') == $cust->id ? 'selected' : '' }}>
                        {{ $cust->name }}
                    </option>
                @endforeach
            </select>

            @error('customer_id')
                <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </div>
    </div>


    {{-- Detail Order (Dynamic Row) --}}
    <div class="mb-8">
        <label class="block mb-3 text-sm font-semibold text-gray-700">
            Detail Sales Order
        </label>

        <div id="detail-wrapper" class="space-y-4">

            {{-- SATU ROW TEMPLATE --}}
            <div class="grid md:grid-cols-3 gap-4 detail-row bg-gray-50 border border-gray-200 p-4 rounded-lg">

                {{-- Jenis Ternak --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Jenis Ternak</label>
                    <select name="details[0][livestock_type_id]"
                        class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-3"
                        required>
                        <option selected disabled>Pilih Jenis</option>
                        @foreach($livestockTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Berat Total --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Total Berat (kg)</label>
                    <input
                        type="number"
                        step="0.01"
                        name="details[0][total_weight]"
                        class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-3"
                        placeholder="cth: 120.5"
                        required
                    >
                </div>

                {{-- Quantity --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Jumlah (Qty)</label>
                    <input
                        type="number"
                        name="details[0][quantity]"
                        class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-3"
                        placeholder="cth: 10"
                        required
                    >
                </div>

            </div>
        </div>

        {{-- Tombol Tambah Baris --}}
        <button
            type="button"
            id="add-row"
            class="mt-4 inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
            + Tambah Baris
        </button>

    </div>


    {{-- Tombol Simpan --}}
    <div class="flex justify-end mt-4">
        <button type="submit"
            class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-base px-8 py-3 transition-all">
            Simpan Sales Order
        </button>
    </div>

</form>

{{-- Script Dynamic Detail --}}
<script>
let rowIndex = 1;

document.getElementById('add-row').addEventListener('click', function () {
    const wrapper = document.getElementById('detail-wrapper');

    let newRow = `
    <div class="grid md:grid-cols-3 gap-4 detail-row bg-gray-50 border border-gray-200 p-4 rounded-lg">

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Jenis Ternak</label>
            <select name="details[${rowIndex}][livestock_type_id]"
                class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-3"
                required>
                <option selected disabled>Pilih Jenis</option>
                @foreach($livestockTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Total Berat (kg)</label>
            <input type="number" step="0.01" name="details[${rowIndex}][total_weight]"
                class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-3"
                placeholder="cth: 120.5" required>
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Jumlah (Qty)</label>
            <input type="number" name="details[${rowIndex}][quantity]"
                class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-3"
                placeholder="cth: 10" required>
        </div>

    </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', newRow);
    rowIndex++;
});
</script>
