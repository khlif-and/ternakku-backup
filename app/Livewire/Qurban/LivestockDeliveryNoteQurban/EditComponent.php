<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteCoreService;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanDeliveryOrderH $deliveryNote;

    public $qurban_customer_id;
    public $livestock_id;
    public $livestocks = [];
    public $breed_name;
    public $delivery_date;
    public $notes;

    public function mount(Farm $farm, QurbanDeliveryOrderH $deliveryNote)
    {
        $this->farm = $farm;
        $this->deliveryNote = $deliveryNote->load(['qurbanSaleLivestockH', 'qurbanDeliveryOrderD.livestock.livestockBreed']);

        // Map QurbanDeliveryOrderH structure to form fields
        $this->qurban_customer_id = $this->deliveryNote->qurbanSaleLivestockH->qurban_customer_id ?? null;

        // Assume first detail item
        $detail = $this->deliveryNote->qurbanDeliveryOrderD->first();
        $this->livestock_id = $detail ? $detail->livestock_id : null;

        $this->delivery_date = $this->deliveryNote->transaction_date;
        // Notes not present in QurbanDeliveryOrderH, skipping mapping

        // Load initial livestocks for this customer
        $this->loadLivestocks();

        // Set breed name
        if ($this->livestock_id) {
            $this->updatedLivestockId($this->livestock_id);
        }
    }

    public function loadLivestocks()
    {
        if ($this->qurban_customer_id) {
            $this->livestocks = \App\Models\QurbanSaleLivestockD::whereHas('qurbanSaleLivestockH', function ($q) {
                $q->where('qurban_customer_id', $this->qurban_customer_id);
            })->with('livestock.livestockBreed')->get()->pluck('livestock')->flatten();
        } else {
            $this->livestocks = [];
        }
    }

    public function updatedQurbanCustomerId($value)
    {
        $this->reset('livestock_id', 'breed_name');
        $this->loadLivestocks();
    }

    public function updatedLivestockId($value)
    {
        if (empty($this->livestocks) && $this->qurban_customer_id) {
            $this->loadLivestocks();
        }

        $livestock = collect($this->livestocks)->where('id', $value)->first();
        $this->breed_name = $livestock ? ($livestock->livestockBreed->name ?? '-') : null;
    }

    public function save(LivestockDeliveryNoteCoreService $coreService)
    {
        $this->validate([
            'qurban_customer_id' => 'required',
            'livestock_id' => 'required',
            'delivery_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $coreService->update($this->deliveryNote->id, [
                'farm_id' => $this->farm->id,
                'qurban_customer_id' => $this->qurban_customer_id,
                'livestock_id' => $this->livestock_id,
                'delivery_date' => $this->delivery_date,
                // 'notes' => $this->notes, // Skipped
            ]);

            return redirect()->route('qurban.livestock-delivery-note.index', $this->farm->id)
                ->with('success', 'Surat jalan berhasil diperbarui.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.livestock-delivery-note-qurban.edit-component', [
            'customers' => QurbanCustomer::where('farm_id', $this->farm->id)->get(),
        ]);
    }
}
