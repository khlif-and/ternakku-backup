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
    public $start_date;
    public $end_date;
    public $pen_id = '';
    public $livestock_id = '';
    public $showReport = false;

    public $statistics = [];

    protected $queryString = ['start_date', 'end_date', 'pen_id', 'livestock_id'];

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

    public function getFeedingDataProperty()
    {
        if (!$this->showReport) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        return FeedingIndividuD::select('feeding_individu_d.*')
            ->join('feeding_h', 'feeding_individu_d.feeding_h_id', '=', 'feeding_h.id')
            ->join('livestocks', 'feeding_individu_d.livestock_id', '=', 'livestocks.id')
            ->where('feeding_h.farm_id', $this->farm->id)
            ->where('feeding_h.type', 'individu')
            ->whereBetween('feeding_h.transaction_date', [$this->start_date, $this->end_date])
            ->when($this->pen_id, function ($q) {
                $q->where('livestocks.pen_id', $this->pen_id);
            })
            ->when($this->livestock_id, function ($q) {
                $q->where('feeding_individu_d.livestock_id', $this->livestock_id);
            })
            ->with(['feedingH', 'livestock.pen', 'feedingIndividuItems'])
            ->orderBy('feeding_h.transaction_date', 'desc')
            ->paginate(10);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = FeedingIndividuD::select('feeding_individu_d.*')
            ->join('feeding_h', 'feeding_individu_d.feeding_h_id', '=', 'feeding_h.id')
            ->join('livestocks', 'feeding_individu_d.livestock_id', '=', 'livestocks.id')
            ->where('feeding_h.farm_id', $this->farm->id)
            ->where('feeding_h.type', 'individu')
            ->whereBetween('feeding_h.transaction_date', [$this->start_date, $this->end_date])
            ->when($this->pen_id, function ($q) {
                $q->where('livestocks.pen_id', $this->pen_id);
            })
            ->when($this->livestock_id, function ($q) {
                $q->where('feeding_individu_d.livestock_id', $this->livestock_id);
            })
            ->with(['feedingIndividuItems'])
            ->get();

        $totalKg = 0;
        $totalCost = 0;

        foreach ($query as $record) {
            foreach ($record->feedingIndividuItems as $item) {
                $totalKg += $item->qty_kg;
                $totalCost += $item->total_price;
            }
        }

        return [
            'total_kg' => $totalKg,
            'total_cost' => $totalCost,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.feeding-individu-supply-report.index', [
            'pens' => $this->farm->pens,
            'feedings' => $this->feedingData,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
