<?php

namespace App\Livewire\Reports\CareLivestock\MilkProductionGlobalReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;

use App\Services\Web\Report\MilkProductionGlobal\Services\MilkProductionGlobalReportService;
use App\Services\Web\Report\MilkProductionGlobal\Resources\MilkProductionGlobalReportResource;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $showReport = false;

    protected $queryString = ['start_date', 'end_date'];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->start_date = request('start_date', now()->format('Y-m-d'));
        $this->end_date = request('end_date', now()->format('Y-m-d'));

        if (request('start_date')) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        $this->showReport = true;
        $this->resetPage();
    }

    public function render()
    {
        $service = new MilkProductionGlobalReportService();


        $productions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $stats = [];

        if ($this->showReport) {
            $filters = [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ];

            $data = $service->getReportData($this->farm->id, $filters);
            $summary = $service->getSummary($this->farm->id, $filters);

            $productions = $data;
            if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $transformedCollection = $data->getCollection()->map(function ($item) {
                    return (new MilkProductionGlobalReportResource($item))->resolve();
                });
                $data->setCollection($transformedCollection);
                $productions = $data;
            }
            $stats = $summary;
        }

        return view('livewire.reports.care-livestock.milk-production-global-report.index', [
            'productions' => $productions,
            'stats' => $stats,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
