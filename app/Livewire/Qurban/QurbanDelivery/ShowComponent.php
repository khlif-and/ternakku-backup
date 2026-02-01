<?php

namespace App\Livewire\Qurban\QurbanDelivery;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;

    public QurbanDeliveryOrderH $delivery;

    public function mount(Farm $farm, QurbanDeliveryOrderH $delivery)
    {
        $this->farm = $farm;
        $this->delivery = $delivery->load([
            'qurbanCustomerAddress.qurbanCustomer.user',
            'qurbanDeliveryOrderD.livestock.livestockBreed',
            'farm'
        ]);
    }

    public function delete(QurbanDeliveryCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm->id, $this->delivery->id);

            session()->flash('success', 'Data pengiriman berhasil dihapus.');
            return redirect()->route('admin.qurban.qurban_delivery.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('Qurban Delivery Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.delivery-qurban.show-component');
    }
}