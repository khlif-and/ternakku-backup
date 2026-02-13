<?php

namespace App\Livewire\Reports\CareLivestock\FeedingColonySupplyReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\FeedingColonyD;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $pen_id = '';
    public $showReport = false;
    // Removed $reportData to avoid serialization issues

    protected $queryString = ['start_date', 'end_date', 'pen_id'];

    protected $reportController;

    public function boot(\App\Services\Web\Report\FeedingColony\Controllers\FeedingColonyReportController $reportController)
    {
        $this->reportController = $reportController;
    }

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
        $this->resetPage(); // Reset pagination when generating new report
    }

    public function render()
    {
        $feedings = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $stats = [];

        if ($this->showReport) {
            $filters = [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'pen_id' => $this->pen_id,
            ];

            $data = $this->reportController->index($this->farm, $filters);
            $feedings = $data['details'];
            $stats = $data['summary'];
        }

        return view('livewire.reports.care-livestock.feeding-colony-supply-report.index', [
            'pens' => $this->farm->pens,
            'feedings' => $feedings,
            'stats' => $stats,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
