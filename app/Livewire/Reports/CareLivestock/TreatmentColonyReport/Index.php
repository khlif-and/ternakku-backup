<?php

namespace App\Livewire\Reports\CareLivestock\TreatmentColonyReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Services\Web\Report\TreatmentColony\Controllers\TreatmentColonyReportController;
use App\Services\Web\Report\TreatmentColony\Services\TreatmentColonyReportService;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $farmId;
    public $start_date;
    public $end_date;
    public $pen_id = '';
    public $showReport = false;

    protected $queryString = ['start_date', 'end_date', 'pen_id'];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->farmId = $farm_id;
        $this->start_date = request('start_date', now()->format('Y-m-d'));
        $this->end_date = request('end_date', now()->format('Y-m-d'));
        $this->pen_id = request('pen_id');

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

        $service = new TreatmentColonyReportService();
        $controller = new TreatmentColonyReportController($service);

        $treatments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $stats = [];

        if ($this->showReport) {
            $filters = [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'pen_id' => $this->pen_id,
            ];

            $data = $service->getReportData($this->farmId, $filters);
            $summary = $service->getSummary($this->farmId, $filters);


            $treatments = $data;
            if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $transformedCollection = $data->getCollection()->map(function ($item) {
                    return (new \App\Services\Web\Report\TreatmentColony\Resources\TreatmentColonyReportResource($item))->resolve();
                });
                $data->setCollection($transformedCollection);
                $treatments = $data;
            }
            $stats = $summary;
        }

        return view('livewire.reports.care-livestock.treatment-colony-report.index', [
            'pens' => $this->farm->pens,
            'treatments' => $treatments,
            'stats' => $stats,
            'farmId' => $this->farmId,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
