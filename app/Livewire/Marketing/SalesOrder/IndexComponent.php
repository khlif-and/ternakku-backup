<?php

namespace App\Livewire\Marketing\SalesOrder;

use Livewire\Component;
use App\Services\Web\Marketing\MarketingService;

class IndexComponent extends Component
{
    public $search = '';
    public $status = '';

    protected $queryString = ['search', 'status'];

    public function render(MarketingService $service)
    {
        $userId = auth()->id();
        $params = [];

        if ($this->search) {
            $params['search'] = $this->search;
        }

        if ($this->status) {
            $params['status'] = $this->status;
        }

        $salesOrders = $service->getSalesOrders($userId, $params);

        return view('livewire.marketing.sales-order.index-component', [
            'salesOrders' => $salesOrders,
        ]);
    }
}
