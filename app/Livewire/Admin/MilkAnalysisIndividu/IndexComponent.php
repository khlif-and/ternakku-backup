<?php

namespace App\Livewire\Admin\MilkAnalysisIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\MilkAnalysisIndividu\MilkAnalysisIndividuCoreService;
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

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function updatingLivestockId()
    {
        $this->resetPage();
    }

    public function delete($id, MilkAnalysisIndividuCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data analisis susu individu berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render(MilkAnalysisIndividuCoreService $coreService)
    {
        $data = $coreService->list($this->farm, [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'livestock_id' => $this->livestock_id,
        ]);

        return view('livewire.admin.milk-analysis-individu.index-component', [
            'items' => $data['analyses'],
            'livestocks' => $data['livestocks']
        ]);
    }
}