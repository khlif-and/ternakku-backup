<?php

namespace App\Livewire\Admin\SalesLivestock;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanSaleLivestockH;
use App\Services\Qurban\SalesLivestockCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public QurbanSaleLivestockH $salesLivestock;

    public function mount(Farm $farm, QurbanSaleLivestockH $salesLivestock)
    {
        $this->farm = $farm;
        $this->salesLivestock = $salesLivestock->load([
            'qurbanCustomer',
            'qurbanSalesOrder',
            'qurbanSaleLivestockD.livestock',
        ]);
    }

    public function delete(SalesLivestockCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->salesLivestock->id);
            
            session()->flash('success', 'Data penjualan ternak berhasil dihapus.');
            return redirect()->route('admin.care-livestock.sales-livestock.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('SalesLivestock Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.sales-livestock.show-component');
    }
}