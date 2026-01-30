<?php

namespace App\Livewire\Farming\Reweight;

use App\Models\Farm;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Web\Farming\Reweight\ReweightService;
use App\Services\Web\Farming\Reweight\ReweightCoreService;
use Livewire\Attributes\Url;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    
    #[Url]
    public $start_date = '';
    
    #[Url]
    public $end_date = '';
    
    #[Url]
    public $search = '';

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete(ReweightCoreService $service, $id)
    {
        try {
            $service->destroy($this->farm, $id);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Data berhasil dihapus.']);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }

    public function render(ReweightService $service)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'search' => $this->search,
        ];

        $reweights = $service->list($this->farm->id, $filters);

        return view('livewire.farming.reweight.index-component', [
            'reweights' => $reweights
        ]);
    }
}
