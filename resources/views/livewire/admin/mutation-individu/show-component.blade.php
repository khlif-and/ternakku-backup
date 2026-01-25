<div>
    <x-alert.session />

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Sisi Kiri: Ringkasan Mutasi --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg border p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-800">Ringkasan Mutasi</h3>
                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase">Individu</span>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tanggal Mutasi</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $mutationIndividu->mutationH?->transaction_date ? date('d M Y', strtotime($mutationIndividu->mutationH->transaction_date)) : '-' }}
                    </div>
                </div>

                <div class="space-y-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Dari Kandang</div>
                        <div class="text-sm font-bold text-gray-700">
                            {{ $mutationIndividu->fromPen?->name ?? 'Kandang ' . $mutationIndividu->from }}
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Kandang Tujuan</div>
                        <div class="text-sm font-bold text-blue-700">
                            {{ $mutationIndividu->toPen?->name ?? 'Kandang ' . $mutationIndividu->to }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Catatan / Alasan</div>
                    <div class="text-sm text-gray-800 italic">
                        {{ $mutationIndividu->notes ?: 'Tidak ada catatan.' }}
                    </div>
                </div>

                <div class="pt-4 flex flex-col gap-2 border-t">
                    <x-button.action href="{{ route('admin.care-livestock.mutation-individu.edit', [$farm->id, $mutationIndividu->id]) }}" color="blue" class="w-full justify-center">
                        Edit Data
                    </x-button.action>

                    <x-button.primary wire:click="delete" wire:confirm="Yakin ingin menghapus data mutasi ini? Posisi ternak akan dikembalikan ke kandang asal." color="red" class="w-full justify-center">
                        Hapus Mutasi
                    </x-button.primary>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Profil Ternak --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
                <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Informasi Ternak yang Dimutasi
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Nomor Identitas (Eartag/RFID)</span>
                            <span class="text-lg font-bold text-gray-900">{{ $mutationIndividu->livestock?->identification_number ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Nama Panggilan</span>
                            <span class="text-sm font-medium text-gray-700">{{ $mutationIndividu->livestock?->nickname ?? '-' }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Jenis / Bangsa</span>
                            <span class="text-sm font-medium text-gray-700">{{ $mutationIndividu->livestock?->livestockBreed?->name ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Jenis Kelamin</span>
                            <span class="text-sm font-medium text-gray-700">{{ $mutationIndividu->livestock?->livestockSex?->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-blue-50 border-t border-blue-100 flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="text-sm text-blue-800">
                        Lokasi kandang saat ini: <strong>{{ $mutationIndividu->livestock?->pen?->name ?? 'Tidak diketahui' }}</strong>
                    </div>
                </div>
            </div>

            {{-- Info Riwayat (Opsional) --}}
            <div class="bg-gray-50 rounded-lg border p-4 flex gap-3 items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-xs text-gray-600">
                    Data riwayat mutasi ini bersifat permanen. Jika terjadi kesalahan input kandang, disarankan untuk mengedit data ini atau menghapusnya (yang akan mengembalikan ternak ke kandang asal) sebelum mencatat mutasi baru.
                </div>
            </div>
        </div>
    </div>
</div>