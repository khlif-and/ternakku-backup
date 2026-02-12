<?php

namespace App\Livewire\Reports\CareLivestock\SalesLivestockReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\QurbanSaleLivestockH;
use App\Models\QurbanCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $farm;
    public $start_date;
    public $end_date;
    public $qurban_customer_id;
    public $customers;

    public $showReport = false;

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->customers = QurbanCustomer::where('farm_id', $farm->id)->get();
    }

    public function filter()
    {
        $this->showReport = true;
        $this->resetPage();
    }

    public function getSalesDataProperty()
    {
        if (!$this->showReport) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        $query = QurbanSaleLivestockH::query()
            ->with(['qurbanCustomer', 'qurbanSaleLivestockD.livestock'])
            ->where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date]);

        if ($this->qurban_customer_id) {
            $query->where('qurban_customer_id', $this->qurban_customer_id);
        }

        return $query->latest()->paginate(10);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = QurbanSaleLivestockH::query()
            ->where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date]);

        if ($this->qurban_customer_id) {
            $query->where('qurban_customer_id', $this->qurban_customer_id);
        }

        // Calculate totals
        $sales = $query->with('qurbanSaleLivestockD')->get();

        $totalRevenue = 0;
        $totalLivestockSold = 0;

        foreach ($sales as $sale) {
            foreach ($sale->qurbanSaleLivestockD as $detail) {
                $totalRevenue += ($detail->price_per_head ?? 0) + (($detail->price_per_kg ?? 0) * ($detail->weight ?? 0));
                $totalLivestockSold++;
            }
        }

        return [
            'total_revenue' => $totalRevenue,
            'total_livestock_sold' => $totalLivestockSold,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.sales-livestock-report.index', [
            'sales' => $this->salesData,
            'statistics' => $this->statistics
        ]);
    }
}
