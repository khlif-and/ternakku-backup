<?php

namespace App\Livewire\Reports\CareLivestock\QurbanPopulationReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\LivestockType;
use App\Models\LivestockBreed;
use App\Models\LivestockStatus;
use App\Services\Web\Report\QurbanPopulation\Services\QurbanPopulationReportService;
use App\Services\Web\Report\QurbanPopulation\Resources\QurbanPopulationReportResource;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $livestock_type_id;
    public $livestock_breed_id;
    public $livestock_status_id;

    public $livestockTypes = [];
    public $livestockBreeds = [];
    public $livestockStatuses = [];

    protected $queryString = [
        'livestock_type_id',
        'livestock_breed_id',
        'livestock_status_id',
    ];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->livestock_type_id = request('livestock_type_id');
        $this->livestock_breed_id = request('livestock_breed_id');
        $this->livestock_status_id = request('livestock_status_id');

        $this->loadDropdowns();
    }

    public function loadDropdowns()
    {
        $this->livestockTypes = LivestockType::all()->pluck('name', 'id')->toArray();
        $this->livestockStatuses = LivestockStatus::all()->pluck('name', 'id')->toArray();

        if ($this->livestock_type_id) {
            $this->livestockBreeds = LivestockBreed::where('livestock_type_id', $this->livestock_type_id)
                ->get()
                ->pluck('name', 'id')
                ->toArray();
        } else {
            $this->livestockBreeds = [];
        }
    }

    public function updatedLivestockTypeId()
    {
        $this->livestock_breed_id = null;
        $this->loadDropdowns();
    }

    public function generateReport()
    {
        $this->resetPage();
    }

    public function render()
    {
        $service = new QurbanPopulationReportService();

        $filters = [
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_breed_id' => $this->livestock_breed_id,
            'livestock_status_id' => $this->livestock_status_id,
        ];

        $query = $service->getQuery($this->farm->id, $filters);

        \Illuminate\Support\Facades\Log::info('Qurban Population Report Query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'filters' => $filters,
            'farm_id' => $this->farm->id,
        ]);

        $data = $query->latest('id')->paginate(50);

        \Illuminate\Support\Facades\Log::info('Qurban Population Report Query Result Count:', [
            'count' => $data->count(),
            'total' => $data->total(),
        ]);

        $transformedCollection = $data->getCollection()->map(function ($item) {
            return (new QurbanPopulationReportResource($item))->resolve();
        });
        $data->setCollection($transformedCollection);

        return view('livewire.reports.care-livestock.qurban-population-report.index', [
            'data' => $data,
        ])
            ->extends('layouts.qurban.index')
            ->section('content');
    }
}
