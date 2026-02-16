<?php

namespace App\Livewire\Qurban\LivestockReceptionQurban;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\LivestockType;
use App\Services\Web\Qurban\LivestockReceptionQurban\LivestockReceptionCoreService;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $supplier;
    public $livestock_type_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'supplier' => ['except' => ''],
        'livestock_type_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, LivestockReceptionCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Penerimaan ternak berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(LivestockReceptionCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'supplier' => $this->supplier,
            'livestock_type_id' => $this->livestock_type_id,
        ];

        $items = $coreService->list($this->farm->id, $filters);

        $livestockTypes = LivestockType::pluck('name', 'id')->toArray();

        return view('livewire.qurban.livestock-reception.index-component', [
            'items' => $items,
            'livestockTypes' => $livestockTypes,
        ]);
    }
}
