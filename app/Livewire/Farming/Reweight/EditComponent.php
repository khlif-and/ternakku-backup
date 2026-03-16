<?php

namespace App\Livewire\Farming\Reweight;

use App\Models\Farm;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Helpers\web\ReweightFormService;
use App\Services\Web\Farming\Reweight\ReweightCoreService;

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

    public $livestocks = [];

    public function mount(Farm $farm, $id, ReweightCoreService $service, ReweightFormService $formService)
    {
        $this->farm = $farm;
        $this->reweightId = $id;

        $formData = $formService->getDropdownData($farm);
        $this->livestocks = $formData['livestocks'];

        try {
            $reweight = $service->get($farm, $id);

            $this->livestock_id = $reweight->livestock_id;
            $this->transaction_date = $reweight->livestockReweightH->transaction_date;
            $this->weight = $reweight->weight;
            $this->notes = $reweight->livestockReweightH->notes;
            $this->current_photo = $reweight->photo;

        } catch (\Exception $e) {
            session()->flash('error', 'Data tidak ditemukan.');
            return redirect()->route('admin.care-livestock.reweight.index', $this->farm->id);
        }
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
            report($e);
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Terjadi kesalahan pada sistem.']);
        }
    }

    public function render()
    {
        return view('livewire.farming.reweight.edit-component', [
            'livestocks' => $this->livestocks,
        ]);
    }
}
