<?php

namespace App\Livewire\Reports\CareLivestock\CustomerReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $search = '';
    public $showReport = false;

    public $statistics = [];

    protected $queryString = ['search'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->search = request('search', '');

        // Auto load if parameters exist or just by default
        $this->showReport = true;
    }

    public function generateReport()
    {
        $this->showReport = true;
        $this->resetPage();
    }

    public function getCustomersProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        return QurbanCustomer::with(['user', 'salesOrders'])
            ->where('farm_id', $this->farm->id)
            ->when($this->search, function ($q) {
            $q->whereHas('user', function ($u) {
                    $u->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                }
                );
            })
            // Sort by user name
            ->get()
            ->sortBy(fn($c) => $c->user->name ?? '-')
            ->toQuery()
            ->paginate(10);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = QurbanCustomer::where('farm_id', $this->farm->id)
            ->when($this->search, function ($q) {
            $q->whereHas('user', function ($u) {
                    $u->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                }
                );
            });

        $totalCustomers = $query->count();
        $totalOrders = $query->withCount('salesOrders')->get()->sum('sales_orders_count');

        return [
            'total_customers' => $totalCustomers,
            'total_orders' => $totalOrders,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.customer-report.index', [
            'customers' => $this->customers,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
