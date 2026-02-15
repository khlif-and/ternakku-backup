<?php

namespace App\Livewire\Reports\CareLivestock\BirthReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockType;
use App\Models\LivestockBreed;
use App\Models\Pen;
use App\Services\Web\Report\Birth\Services\BirthReportService;
use App\Services\Web\Report\Birth\Resources\BirthReportResource;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;

    public $status;
    public $livestock_type_id;
    public $livestock_breed_id;
    public $pen_id;
    public $livestock_id;

    public $livestockTypes = [];
    public $livestockBreeds = [];
    public $pens = [];
    public $livestocks = [];

    protected $queryString = [
        'start_date',
        'end_date',
        'status',
        'livestock_type_id',
        'livestock_breed_id',
        'pen_id',
        'livestock_id',
    ];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->start_date = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $this->end_date = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $this->status = request('status');
        $this->livestock_type_id = request('livestock_type_id');
        $this->livestock_breed_id = request('livestock_breed_id');
        $this->pen_id = request('pen_id');
        $this->livestock_id = request('livestock_id');

        $this->loadDropdowns();
    }

    public function updatedLivestockTypeId()
    {
        $this->livestock_breed_id = null;
        $this->livestock_id = null;
        $this->loadDropdowns();
    }

    public function updatedLivestockBreedId()
    {
        $this->livestock_id = null;
        $this->loadDropdowns();
    }

    public function updatedPenId()
    {
        $this->livestock_id = null;
        $this->loadDropdowns();
    }

    public function loadDropdowns()
    {
        $this->livestockTypes = LivestockType::all()->pluck('name', 'id')->toArray();
        $this->pens = Pen::where('farm_id', $this->farm->id)->get()->pluck('name', 'id')->toArray();

        if ($this->livestock_type_id) {
            $this->livestockBreeds = LivestockBreed::where('livestock_type_id', $this->livestock_type_id)
                ->get()
                ->pluck('name', 'id')
                ->toArray();
        } else {
            $this->livestockBreeds = [];
        }

        $livestockQuery = Livestock::where('farm_id', $this->farm->id);

        if ($this->livestock_type_id) {
            $livestockQuery->where('livestock_type_id', $this->livestock_type_id);
        }

        if ($this->livestock_breed_id) {
            $livestockQuery->where('livestock_breed_id', $this->livestock_breed_id);
        }

        if ($this->pen_id) {
            $livestockQuery->where('pen_id', $this->pen_id);
        }

        $this->livestocks = $livestockQuery->pluck('eartag_number', 'id')->toArray();
    }

    public function generateReport()
    {
        $this->resetPage();
    }

    public function render()
    {
        $service = new BirthReportService();

        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_breed_id' => $this->livestock_breed_id,
            'pen_id' => $this->pen_id,
            'livestock_id' => $this->livestock_id,
        ];

        $query = $service->getQuery($this->farm->id, $filters);
        $data = $query->latest('id')->paginate(50);

        $transformedCollection = $data->getCollection()->map(function ($item) {
            return (new BirthReportResource($item))->resolve();
        });
        $data->setCollection($transformedCollection);

        return view('livewire.reports.care-livestock.birth-report.index', [
            'data' => $data,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
