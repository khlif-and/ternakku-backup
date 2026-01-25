<?php

namespace App\Livewire\Admin\NaturalInsemination;

use Livewire\Component;
use App\Models\Farm;
use App\Models\InseminationNatural;
use App\Models\LivestockBreed;
use App\Services\Web\Farming\NaturalInsemination\NaturalInseminationCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public InseminationNatural $niRecord;

    public $transaction_date;
    public $action_time;
    public $sire_breed_id;
    public $sire_owner_name;
    public $cost;
    public $notes;

    public $breeds = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'action_time' => 'required',
            'sire_breed_id' => 'required|exists:livestock_breeds,id',
            'sire_owner_name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Transaction date is required.',
        'action_time.required' => 'Action time is required.',
        'sire_breed_id.required' => 'Sire breed must be selected.',
        'sire_owner_name.required' => 'Sire owner name is required.',
    ];

    public function mount(Farm $farm, InseminationNatural $item)
    {
        $this->farm = $farm;
        $this->niRecord = $item;
        $this->breeds = LivestockBreed::all();
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->niRecord->insemination?->transaction_date;
        $this->action_time = $this->niRecord->action_time;
        $this->sire_breed_id = $this->niRecord->sire_breed_id;
        $this->sire_owner_name = $this->niRecord->sire_owner_name;
        $this->cost = $this->niRecord->cost;
        $this->notes = $this->niRecord->insemination?->notes;
    }

    public function save(NaturalInseminationCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->farm, $this->niRecord->id, [
                'transaction_date' => $this->transaction_date,
                'action_time' => $this->action_time,
                'sire_breed_id' => $this->sire_breed_id,
                'sire_owner_name' => $this->sire_owner_name,
                'cost' => $this->cost,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Natural Insemination data has been updated.');
            return redirect()->route('admin.care-livestock.natural-insemination.show', [$this->farm->id, $this->niRecord->id]);
        } catch (\Throwable $e) {
            Log::error('Natural Insemination Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.natural-insemination.edit-component');
    }
}