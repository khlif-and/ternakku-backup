<?php

namespace App\Livewire\Qurban\QurbanDelivery;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryInstructionH;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanDeliveryInstructionH $delivery;

    public function mount(Farm $farm, QurbanDeliveryInstructionH $delivery)
    {
        $this->farm = $farm;
        $this->delivery = $delivery->load([
            'driver',
            'fleet',
            'qurbanDeliveryInstructionD.qurbanDeliveryOrderH.qurbanCustomerAddress.qurbanCustomer.user',
        ]);
    }

    public function setReadyToDeliver(QurbanDeliveryCoreService $coreService)
    {
        try {
            $coreService->setReadyToDeliver($this->farm->id, $this->delivery->id);

            session()->flash('success', 'Status berhasil diubah ke Ready to Deliver.');
            return redirect()->route('admin.qurban.qurban_delivery.show', $this->delivery->id);

        } catch (\Throwable $e) {
            Log::error('Qurban Delivery SetReady Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.delivery-qurban.edit-component');
    }
}
