<?php

namespace App\Livewire\Admin\LivestockDeath;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Services\Web\Farming\LivestockDeath\LivestockDeathCoreService;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $search = '';
    public $start_date;
    public $end_date;

    protected $queryString = ['search', 'start_date', 'end_date'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id, LivestockDeathCoreService $coreService)
    {
        try {
            $coreService->deleteDeath($this->farm, $id);
            session()->flash('success', 'Data kematian ternak berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(LivestockDeathCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];

        $result = $coreService->listDeaths($this->farm, $filters);

        return view('livewire.admin.livestock-death.index-component', [
            'deaths' => $result['deaths'],
        ]);
    }
}
