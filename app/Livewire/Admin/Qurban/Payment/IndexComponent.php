<?php

namespace App\Livewire\Admin\Qurban\Payment;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Services\Web\Qurban\Payment\PaymentCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $qurban_customer_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date'   => ['except' => ''],
        'qurban_customer_id'   => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, PaymentCoreService $coreService)
    {
        try {
            $coreService->delete($id);
            session()->flash('success', 'Data pembayaran berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(PaymentCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'qurban_customer_id'   => $this->qurban_customer_id,
        ];

        $items = $coreService->listPayments($filters);

        return view('livewire.admin.qurban.payment.index-component', [
            'items'  => $items,
            'customers' => QurbanCustomer::where('farm_id', $this->farm->id)->get(),
        ]);
    }
}