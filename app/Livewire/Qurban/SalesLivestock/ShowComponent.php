<?php

namespace App\Livewire\Qurban\SalesLivestock;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanSaleLivestockH;

class ShowComponent extends Component
{
    public Farm $farm;
    public $salesLivestock;

    public function mount(Farm $farm, $id)
    {
        $this->farm = $farm;
        $this->salesLivestock = QurbanSaleLivestockH::with([
                'qurbanCustomer.user', 
                'qurbanSaleLivestockD.livestock.livestockType',
                'qurbanSaleLivestockD.qurbanCustomerAddress'
            ])
            ->where('farm_id', $farm->id)
            ->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.qurban.sales-livestock.show-component');
    }
}