<?php

namespace App\Livewire\Reports\CareLivestock\MilkProductionIndividuReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;

use App\Services\Web\Report\MilkProductionIndividu\Services\MilkProductionIndividuReportService;
use App\Services\Web\Report\MilkProductionIndividu\Resources\MilkProductionIndividuReportResource;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $livestock_id;
    public $livestocks = [];
    public $livestockOptions = [];
    public $showReport = false;

    protected $queryString = ['start_date', 'end_date', 'livestock_id'];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->start_date = request('start_date', now()->format('Y-m-d'));
        $this->end_date = request('end_date', now()->format('Y-m-d'));
        $this->livestock_id = request('livestock_id');
        $this->livestocks = $this->farm->livestocks()
            ->with('livestockType')
            ->where('livestock_sex_id', \App\Enums\LivestockSexEnum::BETINA->value)
            ->get();

        $this->livestockOptions = $this->livestocks->mapWithKeys(function ($item) {
            return [$item->id => $item->eartag_number . ' (' . ($item->livestockType->name ?? '-') . ')'];
        })->toArray();

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
        $service = new MilkProductionIndividuReportService();


        $productions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $stats = [];

        if ($this->showReport) {
            $filters = [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'livestock_id' => $this->livestock_id,
            ];

            $data = $service->getReportData($this->farm->id, $filters);
            $summary = $service->getSummary($this->farm->id, $filters);

            $productions = $data;
            if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $transformedCollection = $data->getCollection()->map(function ($item) {
                    return (new MilkProductionIndividuReportResource($item))->resolve();
                });
                $data->setCollection($transformedCollection);
                $productions = $data;
            }
            $stats = $summary;
        }

        return view('livewire.reports.care-livestock.milk-production-individu-report.index', [
            'productions' => $productions,
            'stats' => $stats,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
