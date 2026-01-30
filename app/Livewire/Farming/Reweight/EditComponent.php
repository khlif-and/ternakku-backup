<?php

namespace App\Livewire\Farming\Reweight;

use App\Models\Farm;
use App\Models\Livestock;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Web\Farming\Reweight\ReweightCoreService;
use App\Enums\LivestockStatusEnum;

class EditComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;
    public $reweightId;
    
    public $livestock_id;
    public $transaction_date;
    public $weight;
    public $notes;
    public $photo;
    public $current_photo;

    // Search properties for Livestock
    public $search_livestock = '';
    public $selected_livestock_label = '';

    public function mount(Farm $farm, $id, ReweightCoreService $service)
    {
        $this->farm = $farm;
        $this->reweightId = $id;

        try {
            $reweight = $service->get($farm, $id);
            
            $this->livestock_id = $reweight->livestock_id;
            $this->transaction_date = $reweight->livestockReweightH->transaction_date;
            $this->weight = $reweight->weight;
            $this->notes = $reweight->livestockReweightH->notes;
            $this->current_photo = $reweight->photo;

            if ($reweight->livestock) {
                $this->selected_livestock_label = $reweight->livestock->eartag . ($reweight->livestock->name ? ' - ' . $reweight->livestock->name : '');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Data tidak ditemukan.');
            return redirect()->route('admin.care-livestock.reweight.index', $this->farm->id);
        }
    }

    public function selectLivestock($id, $label)
    {
        $this->livestock_id = $id;
        $this->selected_livestock_label = $label;
        $this->search_livestock = ''; 
    }

    public function save(ReweightCoreService $service)
    {
        $this->validate([
            'livestock_id' => 'required|exists:livestocks,id',
            'transaction_date' => 'required|date',
            'weight' => 'required|numeric|min:0',
            'photo' => 'nullable|image|max:5120',
            'notes' => 'nullable|string',
        ]);

        try {
            $data = [
                'livestock_id' => $this->livestock_id,
                'transaction_date' => $this->transaction_date,
                'weight' => $this->weight,
                'notes' => $this->notes,
                'photo' => $this->photo,
            ];

            $service->update($this->farm, $this->reweightId, $data);

            session()->flash('success', 'Data penimbangan berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.reweight.index', $this->farm->id);

        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        // Simple search for livestock
        $livestocks = [];
        if (strlen($this->search_livestock) > 1) {
            $livestocks = Livestock::where('farm_id', $this->farm->id)
                ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
                ->where(function($q) {
                    $q->where('eartag', 'like', '%' . $this->search_livestock . '%')
                      ->orWhere('name', 'like', '%' . $this->search_livestock . '%');
                })
                ->limit(10)
                ->get();
        }

        return view('livewire.farming.reweight.edit-component', [
            'livestocks' => $livestocks
        ]);
    }
}
