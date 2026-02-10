<?php

namespace App\Livewire\Marketing\Customer;

use Livewire\Component;
use App\Services\Web\Marketing\MarketingService;

class ShowComponent extends Component
{
    public $customerId;
    public $customer;

    public function mount($id, MarketingService $service)
    {
        $this->customerId = $id;
        $userId = auth()->id();
        $this->customer = $service->getCustomerById($userId, $id);

        if (!$this->customer) {
            abort(404, 'Pelanggan tidak ditemukan.');
        }
    }

    public function render()
    {
        return view('livewire.marketing.customer.show-component');
    }
}
