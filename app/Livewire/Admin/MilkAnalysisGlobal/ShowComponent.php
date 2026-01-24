<?php

namespace App\Livewire\Admin\MilkAnalysisGlobal;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MilkAnalysisGlobal;
use App\Services\Web\Farming\MilkAnalysisGlobal\MilkAnalysisGlobalCoreService;

class ShowComponent extends Component
{
    public Farm $farm;
    public MilkAnalysisGlobal $milkAnalysisGlobal;

    public function mount(Farm $farm, MilkAnalysisGlobal $milkAnalysisGlobal)
    {
        $this->farm = $farm;
        $this->milkAnalysisGlobal = $milkAnalysisGlobal;
    }

    public function delete(MilkAnalysisGlobalCoreService $coreService)
    {
        try {
            $coreService->deleteAnalysis($this->farm, $this->milkAnalysisGlobal->id);
            
            session()->flash('success', 'Data analisis susu berhasil dihapus.');
            return redirect()->route('admin.care-livestock.milk-analysis-global.index', $this->farm->id);
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-analysis-global.show-component');
    }
}