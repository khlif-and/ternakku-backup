<?php

namespace App\Livewire\Qurban\QurbanDelivery;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryInstructionH;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;

    public QurbanDeliveryInstructionH $delivery;

    public function mount(Farm $farm, QurbanDeliveryInstructionH $delivery)
    {
        $this->farm = $farm;
        $this->delivery = $delivery->load([
            'driver',
            'fleet',
            'farm',
            'qurbanDeliveryInstructionD.qurbanDeliveryOrderH.qurbanCustomerAddress.qurbanCustomer.user',
            'qurbanDeliveryInstructionD.qurbanDeliveryOrderH.qurbanDeliveryOrderD.livestock.livestockBreed',
        ]);
    }

    public function delete(QurbanDeliveryCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm->id, $this->delivery->id);

            session()->flash('success', 'Instruksi pengiriman berhasil dihapus.');
            return redirect()->route('admin.qurban.qurban_delivery.index');
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function setReadyToDeliver(QurbanDeliveryCoreService $coreService)
    {
        try {
            $coreService->setReadyToDeliver($this->farm->id, $this->delivery->id);

            session()->flash('success', 'Status berhasil diubah ke Ready to Deliver.');
            $this->delivery = $this->delivery->fresh([
                'driver',
                'fleet',
                'farm',
                'qurbanDeliveryInstructionD.qurbanDeliveryOrderH.qurbanCustomerAddress.qurbanCustomer.user',
                'qurbanDeliveryInstructionD.qurbanDeliveryOrderH.qurbanDeliveryOrderD.livestock.livestockBreed',
            ]);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.qurban.delivery-qurban.show-component');
    }
}