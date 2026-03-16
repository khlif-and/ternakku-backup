<?php

namespace App\Livewire\Reports\Qurban\DeliveryOrderReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Services\Web\Report\QurbanDeliveryOrder\Services\QurbanDeliveryOrderReportService;
use App\Services\Web\Report\QurbanDeliveryOrder\Resources\QurbanDeliveryOrderReportResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\QurbanCustomer;

class Index extends Component
{
    use WithPagination;

    public $farmId;
    public $start_date;
    public $end_date;
    public $qurban_customer_id;
    public $status;
    public $readyToLoad = false;

    protected $service;

    public function boot(QurbanDeliveryOrderReportService $service)
    {
        $this->service = $service;
    }

    public function mount()
    {
        $this->farmId = session('selected_farm') ?? 1;
        $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = Carbon::now()->endOfMonth()->format('Y-m-d');

        if (request()->routeIs('qurban.*')) {
            $this->layout = 'layouts.qurban.app';
        } else {
            $farm = Farm::find($this->farmId);
            if (!$farm) {
                abort(404, 'Farm not found');
            }
        }
    }

    public function generateReport()
    {
        $this->readyToLoad = true;
        $this->resetPage();
    }

    public function render()
    {
        $data = [];
        $summary = [];

        if ($this->readyToLoad) {
            $request = new Request([
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'qurban_customer_id' => $this->qurban_customer_id,
                'status' => $this->status,
                'per_page' => 10,
            ]);

            $result = $this->service->getReportData($request, $this->farmId);

            $summary = [
                'total_transactions' => $result->total(),
            ];

            $data = $result;
        }

        $customerOptions = QurbanCustomer::where('farm_id', $this->farmId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $view = request()->routeIs('qurban.*')
            ? 'livewire.reports.qurban.delivery-order-report.index'
            : 'livewire.reports.qurban.delivery-order-report.index';

        $layout = request()->routeIs('qurban.*') ? 'layouts.qurban.app' : 'layouts.care_livestock.app';

        return view($view, [
            'data' => $this->readyToLoad ? $data : [],
            'farm' => Farm::find($this->farmId),
            'customerOptions' => $customerOptions,
            'summary' => $summary,
            'showReport' => $this->readyToLoad
        ])->layout($layout);
    }
}
