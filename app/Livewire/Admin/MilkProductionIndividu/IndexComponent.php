<?php

namespace App\Livewire\Admin\MilkProductionIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\MilkProductionIndividu\MilkProductionIndividuCoreService;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $livestock_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'livestock_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, MilkProductionIndividuCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data produksi individu berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function render(MilkProductionIndividuCoreService $coreService)
    {
        $data = $coreService->list($this->farm, [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'livestock_id' => $this->livestock_id,
        ]);

        return view('livewire.admin.milk-production-individu.index-component', [
            'items' => $data['productions'],
            'livestocks' => $data['livestocks']
        ]);
    }
}