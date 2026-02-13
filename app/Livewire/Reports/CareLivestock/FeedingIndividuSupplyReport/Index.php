<?php

namespace App\Livewire\Reports\CareLivestock\FeedingIndividuSupplyReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\FeedingIndividuD;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $farmId;
    public $start_date;
    public $end_date;
    public $pen_id = '';
    public $livestock_id = '';
    public $showReport = false;




    protected $queryString = ['start_date', 'end_date', 'pen_id', 'livestock_id'];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->farmId = $farm_id;
        $this->start_date = request('start_date', now()->format('Y-m-d'));
        $this->end_date = request('end_date', now()->format('Y-m-d'));
        $this->pen_id = request('pen_id');
        $this->livestock_id = request('livestock_id');

        if (request('start_date')) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        $this->showReport = true;
        // Optimization: Don't store report data in public property to avoid serialization issues
    }

    public function render()
    {
        // Use the controller to fetch data
        // We use dependency injection equivalent or just instantiate for this refactor
        $reportController = new \App\Services\Web\Report\FeedingIndividu\Controllers\FeedingIndividuReportController(
            new \App\Services\Web\Report\FeedingIndividu\Services\FeedingIndividuReportService()
        );

        $reportData = [
            'details' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
            'summary' => ['total_cost' => 0, 'total_kg' => 0]
        ];

        if ($this->showReport) {
            $filters = [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'pen_id' => $this->pen_id,
                'livestock_id' => $this->livestock_id,
            ];

            $reportData = $reportController->index($this->farm, $filters);
        }

        return view('livewire.reports.care-livestock.feeding-individu-supply-report.index', [
            'farm' => $this->farm,
            'farmId' => $this->farmId,
            'pens' => $this->farm->pens,
            'feedings' => $reportData['details'],
            'stats' => $reportData['summary'],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'pen_id' => $this->pen_id,
            'livestock_id' => $this->livestock_id,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
