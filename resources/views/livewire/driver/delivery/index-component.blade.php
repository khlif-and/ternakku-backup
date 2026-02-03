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

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-wrap gap-3">
            <select wire:model.live="status" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 bg-white">
                <option value="">Semua Status</option>
                <option value="scheduled">Terjadwal</option>
                <option value="ready_to_deliver">Siap Kirim</option>
                <option value="in_delivery">Dalam Perjalanan</option>
                <option value="delivered">Selesai</option>
            </select>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($instructions as $instruction)
            <div class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $instruction->transaction_number ?? 'Instruksi #' . $instruction->id }}
                            </h3>
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
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$instruction->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$instruction->status] ?? $instruction->status }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p>
                                <span class="font-medium">Peternakan:</span> 
                                {{ $instruction->farm->name ?? '-' }}
                            </p>
                            <p>
                                <span class="font-medium">Tanggal:</span> 
                                {{ \Carbon\Carbon::parse($instruction->delivery_date)->format('d M Y') }}
                            </p>
                            <p>
                                <span class="font-medium">Armada:</span> 
                                {{ $instruction->fleet->name ?? '-' }} ({{ $instruction->fleet->police_number ?? '-' }})
                            </p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('driver.delivery.show', $instruction->id) }}" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada instruksi pengiriman</h3>
                <p class="text-gray-500">Belum ada instruksi pengiriman yang ditugaskan kepada Anda.</p>
            </div>
        @endforelse
    </div>
</div>
