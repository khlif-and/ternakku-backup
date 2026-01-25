<?php

namespace App\Livewire\Admin\NaturalInsemination;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockBreed;
use App\Services\Web\Farming\NaturalInsemination\NaturalInseminationCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $action_time;
    public $livestock_id;
    public $sire_breed_id;
    public $sire_owner_name;
    public $cost = 0;
    public $notes;

    public $livestocks = [];
    public $breeds = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'action_time' => 'required',
            'livestock_id' => 'required|exists:livestocks,id',
            'sire_breed_id' => 'required|exists:livestock_breeds,id',
            'sire_owner_name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Transaction date is required.',
        'action_time.required' => 'Action time is required.',
        'livestock_id.required' => 'Female livestock must be selected.',
        'sire_breed_id.required' => 'Sire breed must be selected.',
        'sire_owner_name.required' => 'Sire owner name is required.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->action_time = now()->format('H:i');
        
        $this->livestocks = Livestock::where('farm_id', $this->farm->id)
            ->whereHas('livestockSex', function($q) {
                $q->where('name', 'Female')->orWhere('name', 'Betina');
            })->get();

        $this->breeds = LivestockBreed::all();
    }

    public function save(NaturalInseminationCoreService $coreService)
    {
        $this->validate();

        try {
            $niRecord = $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'action_time' => $this->action_time,
                'livestock_id' => $this->livestock_id,
                'sire_breed_id' => $this->sire_breed_id,
                'sire_owner_name' => $this->sire_owner_name,
                'cost' => $this->cost,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Natural Insemination record has been saved.');
            return redirect()->route('admin.care-livestock.natural-insemination.show', [$this->farm->id, $niRecord->id]);
            
        } catch (\Throwable $e) {
            Log::error('NI Create Component Error: ' . $e->getMessage());
            session()->flash('error', 'Failed to save data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.natural-insemination.create-component');
    }
}