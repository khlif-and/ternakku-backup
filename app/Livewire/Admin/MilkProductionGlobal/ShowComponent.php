<?php

namespace App\Livewire\Admin\MilkProductionGlobal;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MilkProductionGlobal;
use App\Services\Web\Farming\MilkProductionGlobal\MilkProductionGlobalCoreService;

class ShowComponent extends Component
{
    public Farm $farm;
    public MilkProductionGlobal $milkProductionGlobal;

    public function mount(Farm $farm, MilkProductionGlobal $milkProductionGlobal)
    {
        $this->farm = $farm;
        $this->milkProductionGlobal = $milkProductionGlobal;
    }

    public function delete(MilkProductionGlobalCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->milkProductionGlobal->id);

            session()->flash('success', 'Data produksi susu global berhasil dihapus.');
            return redirect()->route('admin.care-livestock.milk-production-global.index', $this->farm->id);
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-production-global.show-component');
    }
}