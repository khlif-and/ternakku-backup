<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public QurbanDeliveryOrderH $deliveryNote;

    public function mount(Farm $farm, QurbanDeliveryOrderH $deliveryNote)
    {
        $this->farm = $farm;
        $this->deliveryNote = $deliveryNote->load([
            'qurbanCustomerAddress.qurbanCustomer.user',
            'qurbanSaleLivestockH.qurbanCustomer.user',
            'qurbanDeliveryOrderD.livestock.livestockBreed',
            'qurbanDeliveryOrderD.livestock.livestockType',
            'farm'
        ]);
    }

    public function delete(LivestockDeliveryNoteCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm->id, $this->deliveryNote->id);

            session()->flash('success', 'Surat jalan berhasil dihapus.');
            return redirect()->route('qurban.livestock-delivery-note.index');
        } catch (\Throwable $e) {
            Log::error('Delivery Note Delete Error: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.livestock-delivery-note-qurban.show-component');
    }
}
