<?php

namespace App\Livewire\Reports\CareLivestock\MilkAnalysisGlobalReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\MilkAnalysisGlobal;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $showReport = false;

    public $statistics = [];

    protected $queryString = ['start_date', 'end_date'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
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

    public function getAnalysisDataProperty()
    {
        if (!$this->showReport) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        return MilkAnalysisGlobal::where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date])
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = MilkAnalysisGlobal::where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date]);

        return [
            'avg_fat' => $query->avg('fat'),
            'avg_snf' => $query->avg('snf'),
            'avg_protein' => $query->avg('protein'),
            'avg_bj' => $query->avg('bj'),
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.milk-analysis-global-report.index', [
            'analyses' => $this->analysisData,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
