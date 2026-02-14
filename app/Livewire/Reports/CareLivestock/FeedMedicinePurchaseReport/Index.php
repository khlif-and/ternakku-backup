<?php

namespace App\Livewire\Reports\CareLivestock\FeedMedicinePurchaseReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Services\Web\Report\FeedMedicinePurchase\Services\FeedMedicinePurchaseReportService;
use App\Services\Web\Report\FeedMedicinePurchase\Resources\FeedMedicinePurchaseReportResource;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $purchase_type;
    public $supplier;

    public $showReport = true;

    protected $queryString = [
        'start_date',
        'end_date',
        'purchase_type',
        'supplier',
    ];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->start_date = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $this->end_date = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $this->purchase_type = request('purchase_type');
        $this->supplier = request('supplier');
    }

    public function generateReport()
    {
        $this->resetPage();
    }

    public function render()
    {
        $service = new FeedMedicinePurchaseReportService();

        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'purchase_type' => $this->purchase_type,
            'supplier' => $this->supplier,
        ];

        $query = $service->getQuery($this->farm->id, $filters);
        $data = $query->latest('transaction_date')->paginate(50);

        $transformedCollection = $data->getCollection()->map(function ($item) {
            return (new FeedMedicinePurchaseReportResource($item))->resolve();
        });
        $data->setCollection($transformedCollection);

        return view('livewire.reports.care-livestock.feed-medicine-purchase-report.index', [
            'data' => $data,
            'farm' => $this->farm,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
