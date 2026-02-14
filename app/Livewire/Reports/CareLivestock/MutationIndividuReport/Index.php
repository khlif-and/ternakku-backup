<?php

namespace App\Livewire\Reports\CareLivestock\MutationIndividuReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\Livestock;
use App\Services\Web\Report\MutationIndividu\Services\MutationIndividuReportService;
use App\Services\Web\Report\MutationIndividu\Resources\MutationIndividuReportResource;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $livestock_id;
    public $showReport = true;
    public $livestockOptions = [];

    protected $queryString = ['start_date', 'end_date', 'livestock_id'];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->start_date = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $this->end_date = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $this->livestock_id = request('livestock_id');

        $this->livestockOptions = Livestock::where('farm_id', $this->farm->id)
            ->pluck('eartag_number', 'id')
            ->toArray();
    }

    public function generateReport()
    {
        $this->resetPage();
    }

    public function render()
    {
        $service = new MutationIndividuReportService();

        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'livestock_id' => $this->livestock_id,
        ];

        $query = $service->getQuery($this->farm->id, $filters);
        $data = $query->latest()->paginate(10);

        $transformedCollection = $data->getCollection()->map(function ($item) {
            return (new MutationIndividuReportResource($item))->resolve();
        });
        $data->setCollection($transformedCollection);

        return view('livewire.reports.care-livestock.mutation-individu-report.index', [
            'data' => $data,
            'farm' => $this->farm,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
