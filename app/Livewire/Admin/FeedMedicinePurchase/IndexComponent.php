<?php

namespace App\Livewire\Admin\FeedMedicinePurchase;

use Livewire\Component;
use App\Models\Farm;
use App\Helpers\web\FeedMedicinePurchaseFormService;
use App\Services\Web\Farming\FeedMedicinePurchase\FeedMedicinePurchaseCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $purchase_type;
    public $purchaseTypes = [];

    public function mount(Farm $farm, FeedMedicinePurchaseFormService $formService)
    {
        $this->farm = $farm;

        $formData = $formService->getDropdownData();
        $this->purchaseTypes = $formData['purchaseTypes'];
    }

    public function render(FeedMedicinePurchaseCoreService $coreService)
    {
        $items = $coreService->listPurchases($this->farm, [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'purchase_type' => $this->purchase_type,
        ]);

        return view('livewire.admin.feed-medicine-purchase.index-component', [
            'items' => $items
        ]);
    }

    public function confirmDelete($id)
    {
        $this->dispatch('show-delete-confirmation', id: $id);
    }
}