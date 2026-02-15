<?php

namespace App\Livewire\Reports\CareLivestock\CustomerReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Services\Web\Report\Customer\Services\CustomerReportService;
use App\Services\Web\Report\Customer\Resources\CustomerReportResource;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $search;

    protected $queryString = [
        'search',
    ];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->search = request('search');
    }

    public function searchData()
    {
        $this->resetPage();
    }

    public function render()
    {
        $service = new CustomerReportService();

        $filters = [
            'search' => $this->search,
        ];

        $query = $service->getQuery($this->farm->id, $filters);
        $data = $query->latest('id')->paginate(50);

        $transformedCollection = $data->getCollection()->map(function ($item) {
            return (new CustomerReportResource($item))->resolve();
        });
        $data->setCollection($transformedCollection);

        return view('livewire.reports.care-livestock.customer-report.index', [
            'data' => $data,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
