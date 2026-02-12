<?php

namespace App\Livewire\Reports\CareLivestock\MilkAnalysisIndividuReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\MilkAnalysisIndividuD;
use App\Models\Pen;
use App\Models\Livestock;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $pen_id;
    public $livestock_id;
    public $showReport = false;

    public $statistics = [];

    protected $queryString = ['start_date', 'end_date', 'pen_id', 'livestock_id'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->start_date = request('start_date', now()->format('Y-m-d'));
        $this->end_date = request('end_date', now()->format('Y-m-d'));
        $this->pen_id = request('pen_id');
        $this->livestock_id = request('livestock_id');

        if (request('start_date')) {
            $this->generateReport();
        }
    }

    public function updatedPenId()
    {
        $this->livestock_id = null;
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

        $query = MilkAnalysisIndividuD::query()
            ->with(['milkAnalysisH', 'livestock.pen'])
            ->whereHas('milkAnalysisH', function ($q) {
                $q->where('farm_id', $this->farm->id)
                    ->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
            });

        if ($this->pen_id) {
            $query->whereHas('livestock', function ($q) {
                $q->where('pen_id', $this->pen_id);
            });
        }

        if ($this->livestock_id) {
            $query->where('livestock_id', $this->livestock_id);
        }

        $query->join('milk_analysis_h', 'milk_analysis_individu_d.milk_analysis_h_id', '=', 'milk_analysis_h.id')
            ->select('milk_analysis_individu_d.*')
            ->orderBy('milk_analysis_h.transaction_date', 'desc');

        return $query->paginate(15);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = MilkAnalysisIndividuD::query()
            ->whereHas('milkAnalysisH', function ($q) {
                $q->where('farm_id', $this->farm->id)
                    ->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
            });

        if ($this->pen_id) {
            $query->whereHas('livestock', function ($q) {
                $q->where('pen_id', $this->pen_id);
            });
        }

        if ($this->livestock_id) {
            $query->where('livestock_id', $this->livestock_id);
        }

        return [
            'avg_fat' => $query->avg('fat'),
            'avg_snf' => $query->avg('snf'),
            'avg_protein' => $query->avg('protein'),
            'avg_bj' => $query->avg('bj'),
        ];
    }

    public function render()
    {
        $pens = Pen::where('farm_id', $this->farm->id)->get();

        $livestocks = [];
        if ($this->pen_id) {
            $livestocks = Livestock::where('farm_id', $this->farm->id)
                ->where('pen_id', $this->pen_id)
                ->get();
        }

        return view('livewire.reports.care-livestock.milk-analysis-individu-report.index', [
            'analyses' => $this->analysisData,
            'stats' => $this->statistics,
            'pens' => $pens,
            'livestocks' => $livestocks
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
