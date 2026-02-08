<?php

namespace App\Livewire\Marketing;

use Livewire\Component;
use App\Services\Web\Marketing\MarketingService;

class DashboardComponent extends Component
{
    public $stats = [];
    public $farms = [];

    public function mount(MarketingService $service)
    {
        $userId = auth()->id();
        $this->stats = $service->getStats($userId);
        $this->farms = $service->getMarketingFarms($userId);
    }

    public function render()
    {
        return view('livewire.marketing.dashboard-component');
    }
}
