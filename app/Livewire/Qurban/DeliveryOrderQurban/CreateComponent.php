<?php

namespace App\Livewire\Qurban\DeliveryOrderQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanSaleLivestockH;
use App\Services\Web\Qurban\DeliveryOrderQurban\QurbanDeliveryOrderCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $qurban_sales_livestock_id;

    public $transactions = [];

    protected function rules()
    {
        return [
            'qurban_sales_livestock_id' => 'required',
            'transaction_date' => 'required|date',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal pengiriman wajib diisi.',
        'qurban_sales_livestock_id.required' => 'Transaksi Penjualan wajib dipilih.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');

        $this->transactions = QurbanSaleLivestockH::with('qurbanCustomer.user')
            ->where('farm_id', $farm->id)
            ->latest('transaction_date')
            ->get();
    }

    public function save(QurbanDeliveryOrderCoreService $coreService)
    {
        $this->validate();

        try {
            $response = $coreService->store([
                'farm_id' => $this->farm->id,
                'qurban_sales_livestock_id' => $this->qurban_sales_livestock_id,
                'transaction_date' => $this->transaction_date,
            ]);

            if ($response['error']) {
                session()->flash('error', 'Gagal membuat pengiriman.');
                return;
            }

            $firstOrder = $response['data'][0] ?? null;

            session()->flash('success', 'Data pengiriman berhasil ditambahkan.');

            if ($firstOrder) {
                return redirect()->route('admin.qurban.delivery_order_qurban.show', $firstOrder->id);
            }

            return redirect()->route('admin.qurban.delivery_order_qurban.index');

        } catch (\Throwable $e) {
            Log::error('Qurban Delivery Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.delivery-order-qurban.create-component');
    }
}