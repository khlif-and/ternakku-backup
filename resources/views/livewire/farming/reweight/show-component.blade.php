<div>
    <x-alert.session />

    <x-admin.feature-card title="Detail Penimbangan Ulang" subtitle="Informasi detail data penimbangan">
        <x-slot:actions>
            <x-button.link href="{{ route('admin.care-livestock.reweight.index', $farm->id) }}" color="gray">
                Kembali
            </x-button.link>
        </x-slot:actions>

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Sisi Kiri: Informasi Transaksi --}}
            <div class="lg:col-span-1 space-y-6">
                <x-sales.transaction-info 
                    :transactionAccount="$reweight->livestockReweightH->transaction_number"
                    :orderDate="$reweight->livestockReweightH->transaction_date"
                    :description="$reweight->livestockReweightH->notes"
                    :editUrl="route('admin.care-livestock.reweight.edit', [$farm->id, $reweight->id])"
                    deleteAction="delete"
                />
            </div>

            {{-- Sisi Kanan: Detail Ternak --}}
            <div class="lg:col-span-2 space-y-6">
               <div class="bg-white rounded-lg shadow-sm border p-6">
                   <h3 class="text-lg font-semibold text-gray-800 border-b pb-4 mb-4">Informasi Ternak & Hasil Timbang</h3>
                   
                   <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                       <div>
                           <label class="block text-sm font-medium text-gray-500">Ternak</label>
                           <div class="mt-1 flex items-center gap-3">
                                <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">
                                    {{ substr($reweight->livestock->eartag, 0, 2) }}
                                </div>
                                <div>
                                     <div class="font-bold text-gray-900">{{ $reweight->livestock->eartag }}</div>
                                     <div class="text-xs text-gray-500">{{ $reweight->livestock->livestockType->name ?? '-' }}</div>
                                </div>
                           </div>
                       </div>

                       <div>
                           <label class="block text-sm font-medium text-gray-500">Berat Timbang</label>
                           <div class="mt-1 text-3xl font-bold text-blue-600">
                               {{ $reweight->weight }} <span class="text-sm font-normal text-gray-500">Kg</span>
                           </div>
                       </div>
                   </div>

                   @if($reweight->photo)
                       <div class="mt-6 border-t pt-4">
                           <label class="block text-sm font-medium text-gray-500 mb-2">Foto Dokumentasi</label>
                           <img src="{{ getNeoObject($reweight->photo) }}" class="rounded-lg border shadow-sm max-h-96 w-full object-contain bg-gray-50">
                       </div>
                   @endif
               </div>
            </div>
        </div>
    </x-admin.feature-card>
</div>
