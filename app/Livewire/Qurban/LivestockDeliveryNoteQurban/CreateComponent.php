<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteCoreService;

class CreateComponent extends Component
{
    public Farm $farm;
    public $qurban_customer_id;
    public $livestock_id;
    public $livestocks = [];
    public $breed_name;
    public $delivery_date;
    public $notes;

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->delivery_date = date('Y-m-d');
        $this->livestocks = [];
    }

    public function updatedQurbanCustomerId($value)
    {
        $this->reset('livestock_id', 'breed_name', 'livestocks');

        if ($value) {
            $this->livestocks = \App\Models\QurbanSaleLivestockD::whereHas('qurbanSaleLivestockH', function ($q) use ($value) {
                $q->where('qurban_customer_id', $value);
            })->with('livestock.livestockBreed')->get()->pluck('livestock')->flatten();
        }
    }

    public function updatedLivestockId($value)
    {
        // Re-fetch livestocks if lost (similar to Payment component logic)
        if (empty($this->livestocks) && $this->qurban_customer_id) {
            $this->livestocks = \App\Models\QurbanSaleLivestockD::whereHas('qurbanSaleLivestockH', function ($q) {
                $q->where('qurban_customer_id', $this->qurban_customer_id);
            })->with('livestock.livestockBreed')->get()->pluck('livestock')->flatten();
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
            $coreService->store([
                'farm_id' => $this->farm->id,
                'qurban_customer_id' => $this->qurban_customer_id,
                'livestock_id' => $this->livestock_id,
                'delivery_date' => $this->delivery_date,
                'notes' => $this->notes,
                'status' => 'pending',
            ]);

            return redirect()->route('admin.qurban.livestock-delivery-note.index', $this->farm->id)
                ->with('success', 'Surat jalan berhasil dibuat.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Re-hydrate livestocks on render if needed, or rely on update lifecycle. 
        // For safety/persistence, consistent regeneration is better if Livewire doesn't persist collection properties perfectly.
        // But for standard component, let's keep it simple.

        return view('livewire.qurban.livestock-delivery-note-qurban.create-component', [
            'customers' => QurbanCustomer::where('farm_id', $this->farm->id)->get(),
        ]);
    }
}
