<?php

namespace App\Livewire\Admin\ArtificialInsemination;

use Livewire\Component;
use App\Models\InseminationArtificial;

class IndexComponent extends Component
{
    public $farm;
    public $search = '';
    public $filters = [
        'start_date' => '',
        'end_date' => '',
        'livestock_type_id' => '',
        'livestock_group_id' => '',
        'livestock_breed_id' => '',
        'pen_id' => '',
    ];

    protected $queryString = ['search', 'filters'];

    public function mount($farm)
    {
        $this->farm = $farm;
    }

    public function resetFilters()
    {
        $this->filters = [
            'start_date' => '',
            'end_date' => '',
            'livestock_type_id' => '',
            'livestock_group_id' => '',
            'livestock_breed_id' => '',
            'pen_id' => '',
        ];

        $this->search = '';
    }

    public function render()
    {
        $query = InseminationArtificial::with([
                'insemination',
                'reproductionCycle.livestock.livestockType',
                'reproductionCycle.livestock.livestockBreed',
                'reproductionCycle.livestock.pen',
            ])
            ->whereHas('insemination', function ($q) {
                $q->where('farm_id', $this->farm->id)
                  ->where('type', 'artificial');

                if (!empty($this->filters['start_date'])) {
                    $q->where('transaction_date', '>=', $this->filters['start_date']);
                }

                if (!empty($this->filters['end_date'])) {
                    $q->where('transaction_date', '<=', $this->filters['end_date']);
                }
            });

        if (!empty($this->filters['livestock_type_id'])) {
            $query->whereHas('reproductionCycle.livestock', function ($q) {
                $q->where('livestock_type_id', $this->filters['livestock_type_id']);
            });
        }

        if (!empty($this->filters['livestock_group_id'])) {
            $query->whereHas('reproductionCycle.livestock', function ($q) {
                $q->where('livestock_group_id', $this->filters['livestock_group_id']);
            });
        }

        if (!empty($this->filters['livestock_breed_id'])) {
            $query->whereHas('reproductionCycle.livestock', function ($q) {
                $q->where('livestock_breed_id', $this->filters['livestock_breed_id']);
            });
        }

        if (!empty($this->filters['pen_id'])) {
            $query->whereHas('reproductionCycle.livestock', function ($q) {
                $q->where('pen_id', $this->filters['pen_id']);
            });
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('reproductionCycle.livestock', function ($subQ) {
                    $subQ->where('eartag', 'like', '%' . $this->search . '%')
                         ->orWhere('eartag_number', 'like', '%' . $this->search . '%')
                         ->orWhere('ear_tag', 'like', '%' . $this->search . '%')
                         ->orWhere('name', 'like', '%' . $this->search . '%')
                         ->orWhere('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhere('officer_name', 'like', '%' . $this->search . '%');
            });
        }

        $items = $query->latest()->get();

        return view('livewire.admin.artificial-insemination.index-component', [
            'items' => $items,
        ]);
    }
}
