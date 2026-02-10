<?php

namespace App\Livewire\Marketing\SalesOrder;

use Livewire\Component;
use App\Services\Web\Marketing\MarketingService;

class ShowComponent extends Component
{
    public $salesOrderId;
    public $salesOrder;

    public function mount($id, MarketingService $service)
    {
        $this->salesOrderId = $id;
        $userId = auth()->id();
        $this->salesOrder = $service->getSalesOrderById($userId, $id);

        if (!$this->salesOrder) {
            abort(404, 'Sales Order tidak ditemukan.');
        }
    }

    public function render()
    {
        return view('livewire.marketing.sales-order.show-component');
    }
}
