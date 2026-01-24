<?php

namespace App\Livewire\Admin\MilkAnalysisGlobal;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\MilkAnalysisGlobal\MilkAnalysisGlobalCoreService;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithPagination;

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

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function delete($id, MilkAnalysisGlobalCoreService $coreService)
    {
        try {
            $coreService->deleteAnalysis($this->farm, $id);
            session()->flash('success', 'Data analisis susu berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function render(MilkAnalysisGlobalCoreService $coreService)
    {
        $data = $coreService->listAnalyses($this->farm, [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        return view('livewire.admin.milk-analysis-global.index-component', [
            'items' => $data['analyses']
        ]);
    }
}