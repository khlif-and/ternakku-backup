<?php

namespace App\Livewire\Marketing\Customer;

use Livewire\Component;
use App\Services\Web\Marketing\MarketingService;

class IndexComponent extends Component
{
    public $search = '';

    protected $queryString = ['search'];

    public function render(MarketingService $service)
    {
        $userId = auth()->id();
        $params = [];

        if ($this->search) {
            $params['search'] = $this->search;
        }

        $customers = $service->getCustomers($userId, $params);

        return view('livewire.marketing.customer.index-component', [
            'customers' => $customers,
        ]);
    }
}
