@php
    $isEdit = isset($customer);
    $action = $isEdit
        ? route('admin.care-livestock.customer.update', ['farm_id' => $farmId, 'id' => $customer->id])
        : route('admin.care-livestock.customer.store', ['farm_id' => $farmId]);
@endphp

<form action="{{ $action }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="space-y-6">
        
        {{-- NAMA --}}
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">
                Nama Customer <span class="text-red-600">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name', $customer->name ?? '') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                   placeholder="Masukkan nama customer"
                   required>
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- PHONE --}}
        <div>
            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">
                Nomor HP
            </label>
            <input type="text"
                   id="phone"
                   name="phone"
                   value="{{ old('phone', $customer->phone ?? '') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                   placeholder="Contoh: 08123456789">
            @error('phone')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- EMAIL --}}
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
                Email
            </label>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email', $customer->email ?? '') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                   placeholder="Contoh: email@example.com">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- BUTTONS --}}
        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Customer' }}
            </button>

            <a href="{{ route('admin.care-livestock.customer.index', ['farm_id' => $farmId]) }}"
               class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                Batal
            </a>
        </div>

    </div>
</form>
