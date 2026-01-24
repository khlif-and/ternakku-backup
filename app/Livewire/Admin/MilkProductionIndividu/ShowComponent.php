<?php

namespace App\Livewire\Admin\MilkProductionIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MilkProductionIndividuD;
use App\Services\Web\Farming\MilkProductionIndividu\MilkProductionIndividuCoreService;

class ShowComponent extends Component
{
    public Farm $farm;
    public MilkProductionIndividuD $milkProductionIndividu;

    public function mount(Farm $farm, MilkProductionIndividuD $milkProductionIndividu)
    {
        $this->farm = $farm;
        $this->milkProductionIndividu = $milkProductionIndividu;
    }

    public function delete(MilkProductionIndividuCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->milkProductionIndividu->id);
            
            session()->flash('success', 'Data produksi individu berhasil dihapus.');
            
            return redirect()->route('admin.care-livestock.milk-production-individu.index', $this->farm->id);
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-production-individu.show-component');
    }
}