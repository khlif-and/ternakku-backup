<?php

namespace App\Livewire\Driver\Delivery;

use Livewire\Component;
use App\Services\Web\Driver\DriverDeliveryService;

class IndexComponent extends Component
{
    public $status = '';

    protected $queryString = ['status'];

    public function render(DriverDeliveryService $service)
    {
        $userId = auth()->id();
        $params = [];

        if ($this->status) {
            $params['status'] = $this->status;
        }

        $instructions = $service->getDeliveryInstructions($userId, $params);

        return view('livewire.driver.delivery.index-component', [
            'instructions' => $instructions,
        ]);
    }
}
