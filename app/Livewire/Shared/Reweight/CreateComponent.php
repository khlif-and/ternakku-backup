<?php

namespace App\Livewire\Shared\Reweight;

use App\Models\Farm;
use App\Models\Livestock;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Web\Farming\Reweight\ReweightCoreService;
use App\Enums\LivestockStatusEnum;

class CreateComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;

    public $livestock_id;
    public $transaction_date;
    public $weight;
    public $notes;
    public $photo;

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = date('Y-m-d');
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
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $livestocks = Livestock::where('farm_id', $this->farm->id)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->get();

        return view('livewire.shared.reweight.create-component', [
            'livestocks' => $livestocks
        ]);
    }
}
