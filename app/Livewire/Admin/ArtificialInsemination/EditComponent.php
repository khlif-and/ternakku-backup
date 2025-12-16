<?php

namespace App\Http\Livewire\Admin\ArtificialInsemination;

use Livewire\Component;
use App\Models\InseminationArtificial;
use App\Models\LivestockBreed;
use App\Services\Web\Farming\ArtificialInsemination\ArtificialInseminationService;

class EditComponent extends Component
{
    public $farm;
    public $item;
    public $transaction_date;
    public $action_time;
    public $officer_name;
    public $semen_breed_id;
    public $sire_name;
    public $semen_producer;
    public $semen_batch;
    public $cost;
    public $notes;

    protected $rules = [
        'transaction_date' => 'required|date',
        'action_time' => 'required',
        'officer_name' => 'required|string|max:255',
        'semen_breed_id' => 'required|exists:livestock_breeds,id',
        'sire_name' => 'nullable|string|max:255',
        'semen_producer' => 'nullable|string|max:255',
        'semen_batch' => 'nullable|string|max:255',
        'cost' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    public function mount($farm, $id)
    {
        $this->farm = $farm;

        $this->item = InseminationArtificial::with(['insemination', 'reproductionCycle.livestock'])
            ->whereHas('insemination', function ($q) {
                $q->where('farm_id', $this->farm->id)->where('type', 'artificial');
            })
            ->findOrFail($id);

        $this->transaction_date = $this->item->insemination->transaction_date;
        $this->action_time = $this->item->action_time;
        $this->officer_name = $this->item->officer_name;
        $this->semen_breed_id = $this->item->semen_breed_id;
        $this->sire_name = $this->item->sire_name;
        $this->semen_producer = $this->item->semen_producer;
        $this->semen_batch = $this->item->semen_batch;
        $this->cost = $this->item->cost;
        $this->notes = $this->item->insemination->notes;
    }

    public function update(ArtificialInseminationService $service)
    {
        $this->validate();

        try {
            $service->updateInsemination($this->item, [
                'transaction_date' => $this->transaction_date,
                'action_time' => $this->action_time,
                'officer_name' => $this->officer_name,
                'semen_breed_id' => $this->semen_breed_id,
                'sire_name' => $this->sire_name,
                'semen_producer' => $this->semen_producer,
                'semen_batch' => $this->semen_batch,
                'cost' => $this->cost,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data updated successfully.');

            return redirect()->route('admin.care_livestock.artificial_inseminasi.index', [
                'farm_id' => $this->farm->id
            ]);
        } catch (\InvalidArgumentException $e) {
            $this->addError('semen_breed_id', $e->getMessage());
        } catch (\Throwable $e) {
            session()->flash('error', 'An error occurred while updating the data.');
        }
    }

    public function render()
    {
        $livestock = $this->item->reproductionCycle->livestock;

        $breeds = LivestockBreed::where('livestock_type_id', $livestock->livestock_type_id)
            ->orderBy('name')
            ->get(['id', 'name', 'livestock_type_id']);

        return view('livewire.admin.artificial-insemination.edit-component', [
            'breeds' => $breeds,
            'livestock' => $livestock,
        ]);
    }
}
