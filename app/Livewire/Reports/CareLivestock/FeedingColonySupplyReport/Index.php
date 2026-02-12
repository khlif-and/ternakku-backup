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

    public $statistics = [];

    protected $queryString = ['start_date', 'end_date', 'pen_id'];

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
            // Return empty paginator
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        return FeedingColonyD::select('feeding_colony_d.*')
            ->join('feeding_h', 'feeding_colony_d.feeding_h_id', '=', 'feeding_h.id')
            ->where('feeding_h.farm_id', $this->farm->id)
            ->where('feeding_h.type', 'colony')
            ->whereBetween('feeding_h.transaction_date', [$this->start_date, $this->end_date])
            ->when($this->pen_id, function ($q) {
                $q->where('feeding_colony_d.pen_id', $this->pen_id);
            })
            ->with(['feedingH', 'pen', 'feedingColonyItems'])
            ->orderBy('feeding_h.transaction_date', 'desc')
            ->paginate(10);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = FeedingColonyD::with(['feedingH', 'feedingColonyItems'])
            ->whereHas('feedingH', function ($q) {
                $q->where('farm_id', $this->farm->id)
                    ->where('type', 'colony')
                    ->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
            })
            ->when($this->pen_id, function ($q) {
                $q->where('pen_id', $this->pen_id);
            })
            ->get();

        $totalKg = 0;
        $totalCost = 0;

        foreach ($query as $record) {
            foreach ($record->feedingColonyItems as $item) {
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
        return view('livewire.reports.care-livestock.feeding-colony-supply-report.index', [
            'pens' => $this->farm->pens,
            'feedings' => $this->feedingData,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
