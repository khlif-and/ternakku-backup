<?php

namespace App\Livewire\Shared\Reweight;

use App\Models\Farm;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Helpers\web\ReweightFormService;
use App\Services\Web\Farming\Reweight\ReweightCoreService;

class CreateComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;

    public $livestock_id;
    public $transaction_date;
    public $weight;
    public $notes;
    public $photo;

    public $livestocks = [];

    public function mount(Farm $farm, ReweightFormService $formService)
    {
        $this->farm = $farm;
        $this->transaction_date = date('Y-m-d');

        $formData = $formService->getDropdownData($farm);
        $this->livestocks = $formData['livestocks'];
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

            $service->store($this->farm, $data);

            session()->flash('success', 'Data penimbangan berhasil ditambahkan.');
            return redirect()->route('shared.reweight.index', $this->farm->id);

        } catch (\Exception $e) {
            report($e);
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Terjadi kesalahan pada sistem.']);
        }
    }

    public function render()
    {
        return view('livewire.shared.reweight.create-component', [
            'livestocks' => $this->livestocks,
        ]);
    }
}
