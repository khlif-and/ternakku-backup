<?php

namespace App\Livewire\Admin\MilkProductionGlobal;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\MilkProductionGlobal\MilkProductionGlobalCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, MilkProductionGlobalCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function render(MilkProductionGlobalCoreService $coreService)
    {
        $items = $coreService->list($this->farm, [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        return view('livewire.admin.milk-production-global.index-component', [
            'items' => $items
        ]);
    }
}