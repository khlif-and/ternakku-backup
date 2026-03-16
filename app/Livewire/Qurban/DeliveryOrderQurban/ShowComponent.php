<?php

namespace App\Livewire\Qurban\DeliveryOrderQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\DeliveryOrderQurban\QurbanDeliveryOrderCoreService;
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

    public function delete(QurbanDeliveryOrderCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm->id, $this->delivery->id);

            session()->flash('success', 'Data pengiriman berhasil dihapus.');
            return redirect()->route('admin.qurban.delivery_order_qurban.index');
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.qurban.delivery-order-qurban.show-component');
    }
}