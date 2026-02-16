<?php

namespace App\Livewire\Reports\Qurban\DetailedDeliveryReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\FarmUser;
use App\Models\User;
use App\Models\QurbanFleet;
use App\Services\Web\Report\QurbanDetailedDelivery\Services\QurbanDetailedDeliveryReportService;
use App\Services\Web\Report\QurbanDetailedDelivery\Resources\QurbanDetailedDeliveryReportResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $driver_id;
    public $fleet_id;
    public $status;

    public $driverOptions = [];
    public $fleetOptions = [];

    public $showReport = false;

    protected $queryString = ['start_date', 'end_date', 'driver_id', 'fleet_id', 'status', 'showReport'];

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

        $this->driver_id = request('driver_id');
        $this->fleet_id = request('fleet_id');
        $this->status = request('status');

        $driverUserIds = FarmUser::where('farm_id', $this->farm->id)
            ->where('farm_role', 'DRIVER')
            ->pluck('user_id');

        $this->driverOptions = User::whereIn('id', $driverUserIds)
            ->pluck('name', 'id')
            ->toArray();

        $this->fleetOptions = QurbanFleet::where('farm_id', $this->farm->id)
            ->pluck('police_number', 'id')
            ->toArray();

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
        $data = new LengthAwarePaginator([], 0, 10);
        $stats = [
            'total_pengiriman' => 0,
            'total_pesanan' => 0,
            'total_ternak' => 0,
            'total_terkirim' => 0,
        ];

        if ($this->showReport) {
            $service = new QurbanDetailedDeliveryReportService();
            $filters = [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'driver_id' => $this->driver_id,
                'fleet_id' => $this->fleet_id,
                'status' => $this->status,
            ];

            $collection = $service->generateReport($this->farm->id, $filters);

            $transformedItems = $collection->map(function ($item) {
                return (new QurbanDetailedDeliveryReportResource($item))->resolve();
            });

            $stats['total_pengiriman'] = $transformedItems->count();
            $stats['total_pesanan'] = $transformedItems->sum(function ($item) {
                return count($item['delivery_orders'] ?? []);
            });
            $stats['total_ternak'] = $transformedItems->sum(function ($item) {
                return collect($item['delivery_orders'] ?? [])->sum('livestock_count');
            });
            $stats['total_terkirim'] = $transformedItems->where('status', 'delivered')->count();

            $page = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $currentItems = $transformedItems->slice(($page - 1) * $perPage, $perPage)->values();

            $data = new LengthAwarePaginator(
                $currentItems,
                $collection->count(),
                $perPage,
                $page,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        }

        return view('livewire.reports.qurban.detailed-delivery-report.index', [
            'data' => $data,
            'stats' => $stats,
        ])
            ->extends(request()->is('qurban*') ? 'layouts.qurban.index' : 'layouts.care_livestock.index')
            ->section('content')
            ->layoutData(['farm' => $this->farm]);
    }
}
