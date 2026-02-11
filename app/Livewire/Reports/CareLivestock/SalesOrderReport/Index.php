<?php

namespace App\Livewire\Reports\CareLivestock\SalesOrderReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\QurbanSalesOrder;
use App\Models\QurbanSalesOrderD;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $customer_id = '';
    public $showReport = false;

    public $statistics = [];

    protected $queryString = ['start_date', 'end_date', 'customer_id'];

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    protected $messages = [
        'start_date.required' => 'Tanggal mulai wajib diisi.',
        'end_date.required' => 'Tanggal akhir wajib diisi.',
        'end_date.after_or_equal' => 'Tanggal akhir harus setelah tanggal mulai.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->start_date = request('start_date', now()->subMonth()->format('Y-m-d'));
        $this->end_date = request('end_date', now()->format('Y-m-d'));

        if (request('start_date')) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        $this->validate();
        $this->showReport = true;
        $this->resetPage();
    }

    public function getOrdersProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        return QurbanSalesOrder::with(['qurbanSalesOrderD.livestockType', 'qurbanCustomer'])
            ->where('farm_id', $this->farm->id)
            ->whereBetween('order_date', [$this->start_date, $this->end_date])
            ->when($this->customer_id, function ($q) {
            $q->where('qurban_customer_id', $this->customer_id);
        })
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = QurbanSalesOrder::where('farm_id', $this->farm->id)
            ->whereBetween('order_date', [$this->start_date, $this->end_date])
            ->when($this->customer_id, function ($q) {
            $q->where('qurban_customer_id', $this->customer_id);
        });

        $totalOrders = $query->count();
        $totalQuantity = $query->sum('quantity');
        $totalWeight = $query->sum('total_weight');

        return [
            'total_orders' => $totalOrders,
            'total_quantity' => $totalQuantity,
            'total_weight' => $totalWeight,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.sales-order-report.index', [
            // Get customers for filter
            'customers' => \App\Models\QurbanCustomer::with('user')
            ->where('farm_id', $this->farm->id)
            ->get()
            ->sortBy(fn($c) => $c->user->name ?? '-'),
            'orders' => $this->orders,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
