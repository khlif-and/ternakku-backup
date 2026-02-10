<div>
    <div class="mb-6">
        <a href="{{ route('marketing.customer.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Pelanggan</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Nama</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ $customer->user->name ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Email</label>
                <p class="mt-1 text-gray-900">{{ $customer->user->email ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">No. Telepon</label>
                <p class="mt-1 text-gray-900">{{ $customer->user->phone_number ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Peternakan</label>
                <p class="mt-1 text-gray-900">{{ $customer->farm->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    @if($customer->qurbanCustomerAddresses && $customer->qurbanCustomerAddresses->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Alamat Pengiriman</h3>

            <div class="space-y-4">
                @foreach($customer->qurbanCustomerAddresses as $address)
                    <div class="border rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Label</label>
                                <p class="mt-1 text-gray-900">{{ $address->label ?? 'Alamat' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Alamat</label>
                                <p class="mt-1 text-gray-900 text-sm">{{ $address->address_line ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>