<?php

namespace App\Livewire\Reports\CareLivestock\MilkProductionIndividuReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\MilkProductionIndividuD;
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

    public function getProductionDataProperty()
    {
        if (!$this->showReport) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        $query = MilkProductionIndividuD::query()
            ->with(['milkProductionH', 'livestock.pen'])
            ->whereHas('milkProductionH', function ($q) {
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

        // Order by Date (desc), then Shift (morning first maybe? alphabetical afternoon, evening, morning.. let's just use ID or time)
        // Usually reports want latest first.
        $query->join('milk_production_h', 'milk_production_individu_d.milk_production_h_id', '=', 'milk_production_h.id')
            ->select('milk_production_individu_d.*') // Avoid column collision
            ->orderBy('milk_production_h.transaction_date', 'desc')
            ->orderBy('milk_production_individu_d.milking_time', 'asc');

        return $query->paginate(15);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = MilkProductionIndividuD::query()
            ->whereHas('milkProductionH', function ($q) {
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

        $totalMilk = $query->sum('quantity_liters');

        return [
            'total_milk' => $totalMilk,
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

        return view('livewire.reports.care-livestock.milk-production-individu-report.index', [
            'productions' => $this->productionData,
            'stats' => $this->statistics,
            'pens' => $pens,
            'livestocks' => $livestocks
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
