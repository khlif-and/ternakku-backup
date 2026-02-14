<?php

namespace App\Livewire\Reports\CareLivestock\MilkAnalysisGlobalReport;

use App\Models\Farm;
use App\Services\Web\Report\MilkAnalysisGlobal\Services\MilkAnalysisGlobalReportService;
use Livewire\Component;
use Livewire\WithPagination;

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
        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $this->showReport = true;
        $this->resetPage();
    }

    public function render(MilkAnalysisGlobalReportService $service)
    {
        $data = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        $summary = [];

        if ($this->showReport) {
            $filters = [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ];

            // $data = $service->generateReport($this->farm, $filters);
            // Pagination is tricky with Resource collection if not using ::collection(paginate()).
            // Let's adjust the Service to return Paginator for render, and Collection for Export if needed.
            // Actually, for the view, we need pagination.

            $query = $service->getQuery($this->farm, $filters);
            $data = $query->paginate(15);

            $summary = $service->getSummary($this->farm, $filters);
        }

        return view('livewire.reports.care-livestock.milk-analysis-global-report.index', [
            'data' => $data,
            'summary' => $summary,
            'farm' => $this->farm,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
