<?php

namespace App\Livewire\Reports\CareLivestock\SalesLivestockReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Services\Web\Report\SalesLivestock\Services\SalesLivestockReportService;
use App\Services\Web\Report\SalesLivestock\Resources\SalesLivestockReportResource;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $qurban_customer_id;
    public $customers = [];
    public $customerOptions = [];
    public $showReport = false;

    protected $queryString = ['start_date', 'end_date', 'qurban_customer_id'];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->start_date = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $this->end_date = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $this->qurban_customer_id = request('qurban_customer_id');

        $this->customers = QurbanCustomer::with('user')->where('farm_id', $this->farm->id)->get();
        $this->customerOptions = $this->customers->mapWithKeys(function ($customer) {
            return [$customer->id => $customer->user->name ?? 'Unknown'];
        })->toArray();

        // If parameters are present in URL, auto-generate report
        if (request('start_date') || request('end_date')) {
            $this->showReport = true;
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

    public function render()
    {
        $service = new SalesLivestockReportService();

        $data = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $summary = [];

        if ($this->showReport) {
            $filters = [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'qurban_customer_id' => $this->qurban_customer_id,
            ];

            $query = $service->getQuery($this->farm->id, $filters);
            $data = $query->latest()->paginate(10);

            $transformedCollection = $data->getCollection()->map(function ($item) {
                return (new SalesLivestockReportResource($item))->resolve();
            });
            $data->setCollection($transformedCollection);

            $summary = $service->getSummary($this->farm->id, $filters);
        }

        return view('livewire.reports.care-livestock.sales-livestock-report.index', [
            'data' => $data,
            'summary' => $summary,
            'farm' => $this->farm,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
