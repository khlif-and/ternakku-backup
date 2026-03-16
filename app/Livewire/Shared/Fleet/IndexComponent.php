<?php

namespace App\Livewire\Shared\Fleet;

use App\Models\Farm;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Web\Shared\Fleet\FleetService;
use App\Services\Web\Shared\Fleet\FleetCoreService;
use Livewire\Attributes\Url;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;

    #[Url]
    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete(FleetCoreService $service, $id)
    {
        try {
            $service->destroy($this->farm, $id);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Data armada berhasil dihapus.']);
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Terjadi kesalahan pada sistem.']);
        }
    }

    public function render(FleetService $service)
    {
        $filters = [
            'search' => $this->search,
        ];

        $fleets = $service->list($this->farm->id, $filters);

        return view('livewire.shared.fleet.index-component', [
            'fleets' => $fleets
        ]);
    }
}
