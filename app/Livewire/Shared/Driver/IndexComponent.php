<?php

namespace App\Livewire\Shared\Driver;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Services\Web\Shared\Driver\DriverService;
use App\Services\Web\Shared\Driver\DriverCoreService;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $search = '';

    protected $queryString = ['search'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id, DriverCoreService $service)
    {
        $response = $service->destroy($this->farm->id, $id);

        if ($response['error']) {
            session()->flash('error', 'Gagal menghapus data pengemudi.');
            return;
        }

        session()->flash('success', 'Pengemudi berhasil dihapus.');
    }

    public function render(DriverService $service)
    {
        $filters = ['search' => $this->search];
        $drivers = $service->list($this->farm->id, $filters);

        return view('livewire.shared.driver.index-component', [
            'drivers' => $drivers,
        ]);
    }
}
