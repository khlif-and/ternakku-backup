<?php

namespace App\Livewire\Reports\CareLivestock\MilkProductionGlobalReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\MilkProductionGlobal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function getProductionDataProperty()
    {
        if (!$this->showReport) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        // Fetch raw data to group by date
        $query = MilkProductionGlobal::where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date])
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Group by date
        $grouped = $query->groupBy('transaction_date')->map(function ($items, $date) {
            return [
                'date' => $date,
                'morning' => $items->where('milking_shift', 'morning')->sum('quantity_liters'),
                'afternoon' => $items->where('milking_shift', 'afternoon')->sum('quantity_liters'),
                'evening' => $items->where('milking_shift', 'evening')->sum('quantity_liters'), // Just in case, though validation said morning/afternoon
                'total' => $items->sum('quantity_liters'),
                'details' => $items // Keep details if needed
            ];
        });

        // Paginate the collection manually
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->all();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $totalMilk = MilkProductionGlobal::where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date])
            ->sum('quantity_liters');

        return [
            'total_milk' => $totalMilk,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.milk-production-global-report.index', [
            'productions' => $this->productionData,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
