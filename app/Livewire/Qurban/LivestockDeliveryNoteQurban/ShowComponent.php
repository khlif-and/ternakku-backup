<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryOrderH;

class ShowComponent extends Component
{
    public Farm $farm;
    public QurbanDeliveryOrderH $deliveryNote;

    public function mount(Farm $farm, QurbanDeliveryOrderH $deliveryNote)
    {
        $this->farm = $farm;
        $this->deliveryNote = $deliveryNote->load([
            'qurbanSaleLivestockH.qurbanCustomer.user',
            'qurbanDeliveryOrderD.livestock.livestockBreed',
            'farm'
        ]);
    }

    public function delete()
    {
        try {
            $this->deliveryNote->qurbanDeliveryOrderD()->delete();
            $this->deliveryNote->delete();
            return redirect()->route('qurban.livestock-delivery-note.index', $this->farm->id)
                ->with('success', 'Surat jalan berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.livestock-delivery-note-qurban.show-component');
    }
}
