<?php

namespace App\Livewire\Reports\CareLivestock\PenReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Services\Web\Report\Pen\Services\PenReportService;
use App\Services\Web\Report\Pen\Resources\PenReportResource;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $search = '';
    public $showReport = true;

    protected $queryString = ['search'];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->search = request('search', '');
    }

    public function filter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $service = new PenReportService();

        $filters = [
            'search' => $this->search,
        ];

        $query = $service->getQuery($this->farm->id, $filters);
        $data = $query->paginate(10);

        $transformedCollection = $data->getCollection()->map(function ($item) {
            return (new PenReportResource($item))->resolve();
        });
        $data->setCollection($transformedCollection);

        $summary = $service->getSummary($this->farm->id, $filters);

        return view('livewire.reports.care-livestock.pen-report.index', [
            'data' => $data,
            'summary' => $summary,
            'farm' => $this->farm,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
