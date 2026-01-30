<div>
    <x-alert.session />

    <x-admin.feature-card title="Detail Pembayaran Qurban" subtitle="Informasi detail pembayaran qurban">
        <x-slot:actions>
            <x-button.link href="{{ route('admin.qurban.payment.index') }}" color="gray">
                Kembali
            </x-button.link>
        </x-slot:actions>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <x-sales.transaction-info 
                    transactionAccount="-"
                    :orderDate="$payment->transaction_date"
                    description="-"
                    :editUrl="route('admin.qurban.payment.edit', $payment->id)"
                    deleteAction="delete"
                >
                    <div class="mt-4 space-y-3">
                        <div>
                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Ternak</div>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $payment->livestock->eartag_number ?? '-' }} ({{ $payment->livestock->gender ?? '-' }}) - {{ $payment->livestock->livestockBreed->name ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Bayar</div>
                            <div class="text-lg font-bold text-green-600">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </x-sales.transaction-info>

                <x-sales.customer-info 
                    :name="$payment->qurbanCustomer->user->name ?? '-'"
                    :phone="$payment->qurbanCustomer->phone_number ?? '-'"
                    :address="$payment->qurbanCustomer->address ?? '-'"
                />
            </div>

            <div class="lg:col-span-2 space-y-6">
                {{-- Payment Details Section --}}
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Detail Pembayaran</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                             <div class="text-gray-600">Dibuat Oleh:</div>
                             <div class="font-medium text-right">{{ $payment->createdBy->name ?? '-' }}</div>

                             <div class="text-gray-600">Tanggal Transaksi:</div>
                             <div class="font-medium text-right">{{ date('d F Y', strtotime($payment->transaction_date)) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin.feature-card>
</div>