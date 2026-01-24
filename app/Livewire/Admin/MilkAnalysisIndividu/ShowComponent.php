<?php

namespace App\Livewire\Admin\MilkAnalysisIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MilkAnalysisIndividuD;
use App\Services\Web\Farming\MilkAnalysisIndividu\MilkAnalysisIndividuCoreService;

class ShowComponent extends Component
{
    public Farm $farm;
    public MilkAnalysisIndividuD $milkAnalysisIndividu;

    public function mount(Farm $farm, MilkAnalysisIndividuD $milkAnalysisIndividu)
    {
        $this->farm = $farm;
        $this->milkAnalysisIndividu = $milkAnalysisIndividu;
    }

    public function delete(MilkAnalysisIndividuCoreService $coreService)
    {
        try {
            $coreService->deleteAnalysis($this->farm, $this->milkAnalysisIndividu->id);
            
            session()->flash('success', 'Data analisis susu individu berhasil dihapus.');
            return redirect()->route('admin.care-livestock.milk-analysis-individu.index', $this->farm->id);
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-analysis-individu.show-component');
    }
}