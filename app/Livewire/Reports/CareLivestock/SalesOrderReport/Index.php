<?php

namespace App\Livewire\Reports\CareLivestock\SalesOrderReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\LivestockType;
use App\Services\Web\Report\SalesOrder\Services\SalesOrderReportService;
use App\Services\Web\Report\SalesOrder\Resources\SalesOrderReportResource;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;

    public $qurban_customer_id;
    public $livestock_type_id;

    public $customers = [];
    public $livestockTypes = [];

    protected $queryString = [
        'start_date',
        'end_date',
        'qurban_customer_id',
        'livestock_type_id',
    ];

    public function mount($farm_id = null)
    {
        if (!$farm_id) {
            $farm_id = session('selected_farm');
        }

        if (!$farm_id) {
            abort(404, 'Farm Selection Required');
        }

        $this->farm = Farm::findOrFail($farm_id);
        $this->start_date = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $this->end_date = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $this->qurban_customer_id = request('qurban_customer_id');
        $this->livestock_type_id = request('livestock_type_id');

        $this->loadDropdowns();
    }

    public function loadDropdowns()
    {
        $this->customers = QurbanCustomer::with('user')
            ->where('farm_id', $this->farm->id)
            ->get()
            ->mapWithKeys(function ($customer) {
                return [$customer->id => $customer->user->name ?? 'Unknown Customer'];
            })
            ->toArray();
        $this->livestockTypes = LivestockType::all()->pluck('name', 'id')->toArray();
    }

    public function generateReport()
    {
        $this->resetPage();
    }

    public function render()
    {
        $service = new SalesOrderReportService();

        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'qurban_customer_id' => $this->qurban_customer_id,
            'livestock_type_id' => $this->livestock_type_id,
        ];

        $query = $service->getQuery($this->farm->id, $filters);
        $data = $query->latest('id')->paginate(50);

        $transformedCollection = $data->getCollection()->map(function ($item) {
            return (new SalesOrderReportResource($item))->resolve();
        });
        $data->setCollection($transformedCollection);

        return view('livewire.reports.care-livestock.sales-order-report.index', [
            'data' => $data,
        ])
            ->extends(request()->is('qurban*') ? 'layouts.qurban.index' : 'layouts.care_livestock.index')
            ->section('content');
    }
}
