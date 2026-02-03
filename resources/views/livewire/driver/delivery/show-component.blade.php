<div>
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('driver.delivery.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">
                    {{ $instruction->transaction_number ?? 'Instruksi #' . $instruction->id }}
                </h2>
                @php
                    $statusColors = [
                        'scheduled' => 'bg-gray-100 text-gray-700',
                        'ready_to_deliver' => 'bg-yellow-100 text-yellow-700',
                        'in_delivery' => 'bg-orange-100 text-orange-700',
                        'delivered' => 'bg-green-100 text-green-700',
                    ];
                    $statusLabels = [
                        'scheduled' => 'Terjadwal',
                        'ready_to_deliver' => 'Siap Kirim',
                        'in_delivery' => 'Dalam Perjalanan',
                        'delivered' => 'Selesai',
                    ];
                @endphp
                <span
                    class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$instruction->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$instruction->status] ?? $instruction->status }}
                </span>
            </div>

            <div class="flex flex-wrap gap-2">
                @if($instruction->status === 'ready_to_deliver')
                    <button wire:click="startDelivery" wire:confirm="Mulai pengiriman sekarang?"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Mulai Pengiriman
                    </button>
                @endif

                @if($instruction->status === 'in_delivery')
                    <button wire:click="completeDelivery" wire:confirm="Selesaikan pengiriman ini?"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Selesai Kirim
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Peternakan</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ $instruction->farm->name ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Tanggal Pengiriman</label>
                <p class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($instruction->delivery_date)->format('d M Y') }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Armada</label>
                <p class="mt-1 text-gray-900">{{ $instruction->fleet->name ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Plat Nomor</label>
                <p class="mt-1 text-gray-900 font-mono">{{ $instruction->fleet->police_number ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Order</h3>

        <div class="space-y-4">
            @forelse($instruction->deliveryOrders ?? [] as $order)
                <div class="border rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Customer</label>
                            <p class="mt-1 text-gray-900">
                                {{ $order->qurbanCustomerAddress->qurbanCustomer->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Alamat</label>
                            <p class="mt-1 text-gray-900 text-sm">{{ $order->qurbanCustomerAddress->address_line ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">No. Telepon</label>
                            <p class="mt-1 text-gray-900">
                                {{ $order->qurbanCustomerAddress->qurbanCustomer->user->phone_number ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span
                                class="mt-1 inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Tidak ada order.</p>
            @endforelse
        </div>
    </div>

    @if($instruction->status === 'in_delivery')
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Foto Bukti</h3>

            <div class="space-y-4">
                <input type="file" wire:model="receiptPhoto" accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                @error('receiptPhoto')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

                @if($receiptPhoto)
                    <div class="mt-4">
                        <img src="{{ $receiptPhoto->temporaryUrl() }}" class="max-h-48 rounded-lg">
                    </div>
                    <button wire:click="uploadPhoto"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Upload Foto
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>