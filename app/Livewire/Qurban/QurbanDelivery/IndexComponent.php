<?php

namespace App\Livewire\Qurban\QurbanDelivery;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $qurban_customer_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'qurban_customer_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, QurbanDeliveryCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm->id, $id);
            session()->flash('success', 'Data pengiriman berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(QurbanDeliveryCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'qurban_customer_id' => $this->qurban_customer_id,
        ];

        $items = $coreService->listDeliveries($this->farm->id, $filters);

        return view('livewire.qurban.delivery-qurban.index-component', [
            'items' => $items,
            'customers' => QurbanCustomer::where('farm_id', $this->farm->id)->get(),
        ]);
    }
}