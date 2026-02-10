<?php

namespace App\Livewire\Driver;

use Livewire\Component;
use App\Services\Web\Driver\DriverDeliveryService;

class DashboardComponent extends Component
{
    public $stats = [];
    public $farms = [];

    public function mount(DriverDeliveryService $service)
    {
        $userId = auth()->id();
        $this->stats = $service->getDriverStats($userId);

        $instructions = $service->getDeliveryInstructions($userId, []);
        $this->farms = $instructions->pluck('farm')->unique('id')->filter();
    }

    public function render()
    {
        return view('livewire.driver.dashboard-component');
    }
}
