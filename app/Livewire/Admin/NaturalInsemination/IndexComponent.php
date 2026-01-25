<?php

namespace App\Livewire\Admin\NaturalInsemination;

use Livewire\Component;
use App\Models\Farm;
use App\Models\InseminationNatural;
use App\Services\Web\Farming\NaturalInsemination\NaturalInseminationCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $livestock_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'livestock_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, NaturalInseminationCoreService $coreService)
    {
        try {
            $item = $coreService->find($this->farm, $id);
            $coreService->delete($item);
            session()->flash('success', 'Natural insemination record deleted successfully.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Failed to delete record: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = InseminationNatural::with([
            'insemination',
            'reproductionCycle.livestock',
            'sireBreed'
        ])->whereHas('insemination', function ($q) {
            $q->where('farm_id', $this->farm->id)->where('type', 'natural');

            if ($this->start_date) {
                $q->where('transaction_date', '>=', $this->start_date);
            }
            if ($this->end_date) {
                $q->where('transaction_date', '<=', $this->end_date);
            }
        });

        if ($this->livestock_id) {
            $query->whereHas('reproductionCycle', function ($q) {
                $q->where('livestock_id', $this->livestock_id);
            });
        }

        $items = $query->latest()->get();

        return view('livewire.admin.natural-insemination.index-component', [
            'items' => $items,
            'livestocks' => $this->farm->livestocks()
                ->whereHas('livestockSex', function($q) {
                    $q->where('name', 'Female')->orWhere('name', 'Betina');
                })->get(),
        ]);
    }
}