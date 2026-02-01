<?php

namespace App\Livewire\Qurban\QurbanDelivery;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanSaleLivestockH;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryCoreService;
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

        // Fetch transactions that do not have a delivery order yet?
        // Or fetch all valid transactions.
        // For simplicity, fetch all transactions for this farm.
        // Ideally filter those that are eligible for delivery.

        // FilterLogic: Transaction should be paid? or just existing?
        // API doesn't seem to enforce paid status in store method, so we list all.
        // Maybe filter out those that already have delivery orders if we want to be strict,
        // but `DeliveryOrderService::storeDeliveryOrder` handles duplication gracefully (returns existing).

        $this->transactions = QurbanSaleLivestockH::with('qurbanCustomer.user')
            ->where('farm_id', $farm->id)
            ->latest('transaction_date')
            ->get();
    }

    public function save(QurbanDeliveryCoreService $coreService)
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

            // Since API returns an array of created/existing orders, we redirect to index or the first one.
            // data is an array of QurbanDeliveryOrderH objects.

            $firstOrder = $response['data'][0] ?? null;

            session()->flash('success', 'Data pengiriman berhasil ditambahkan.');

            if ($firstOrder) {
                return redirect()->route('admin.qurban.qurban_delivery.show', $firstOrder->id);
            }

            return redirect()->route('admin.qurban.qurban_delivery.index');

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
        return view('livewire.qurban.delivery-qurban.create-component');
    }
}
