<?php

namespace App\Livewire\Qurban\SalesOrder;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanSalesOrder;
use App\Services\Web\Qurban\SalesOrder\SalesOrderCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public QurbanSalesOrder $salesOrder;

    public function mount(Farm $farm, $salesOrder)
    {
        $this->farm = $farm;
        
        if (is_numeric($salesOrder)) {
             $this->salesOrder = QurbanSalesOrder::where('farm_id', $farm->id)->findOrFail($salesOrder);
        } else {
             $this->salesOrder = $salesOrder;
        }

        $this->salesOrder->load([
            'qurbanCustomer',
            'qurbanSalesOrderD.livestockType'
        ]);
    }

    public function delete(SalesOrderCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->salesOrder->id);
            
            session()->flash('success', 'Sales Order berhasil dihapus.');
            return redirect()->route('admin.care-livestock.sales-order.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.qurban.sales-order.show-component');
    }
}
