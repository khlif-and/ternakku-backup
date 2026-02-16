<?php

namespace App\Livewire\Reports\Qurban\ReceptionReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\LivestockType;
use App\Services\Web\Report\QurbanReception\Services\QurbanReceptionReportService;
use App\Services\Web\Report\QurbanReception\Resources\QurbanReceptionReportResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $supplier;
    public $livestock_type_id;

    public $livestockTypeOptions = [];

    public $showReport = false;

    protected $queryString = ['start_date', 'end_date', 'supplier', 'livestock_type_id', 'showReport'];

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
        $this->supplier = request('supplier');
        $this->livestock_type_id = request('livestock_type_id');

        $this->livestockTypeOptions = LivestockType::pluck('name', 'id')->toArray();

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
            'total_penerimaan' => 0,
            'total_ternak' => 0,
            'total_berat' => 0,
        ];

        if ($this->showReport) {
            $service = new QurbanReceptionReportService();
            $filters = [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'supplier' => $this->supplier,
                'livestock_type_id' => $this->livestock_type_id,
            ];

            $collection = $service->generateReport($this->farm->id, $filters);

            $transformedItems = $collection->map(function ($item) {
                return (new QurbanReceptionReportResource($item))->resolve();
            });

            $stats['total_penerimaan'] = $transformedItems->count();
            $stats['total_ternak'] = $transformedItems->sum('total_livestock');
            $stats['total_berat'] = $transformedItems->sum(function ($item) {
                return collect($item['livestock_items'] ?? [])->sum('weight');
            });

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

        return view('livewire.reports.qurban.reception-report.index', [
            'data' => $data,
            'stats' => $stats,
        ])
            ->extends(request()->is('qurban*') ? 'layouts.qurban.index' : 'layouts.care_livestock.index')
            ->section('content')
            ->layoutData(['farm' => $this->farm]);
    }
}
