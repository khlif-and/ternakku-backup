<?php

namespace App\Livewire\Shared\Driver;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Shared\Driver\DriverCoreService;

class ShowComponent extends Component
{
    public Farm $farm;
    public $driver;
    public $user;

    public function mount(Farm $farm, $id, DriverCoreService $service)
    {
        $this->farm = $farm;
        $this->driver = $service->get($farm->id, $id);
        $this->user = $this->driver->user;
    }

    public function delete(DriverCoreService $service)
    {
        $response = $service->destroy($this->farm->id, $this->driver->id);

        if ($response['error']) {
            session()->flash('error', 'Gagal menghapus pengemudi.');
            return;
        }

        session()->flash('success', 'Pengemudi berhasil dihapus.');
        return redirect()->route('shared.driver.index', $this->farm->id);
    }

    public function render()
    {
        return view('livewire.shared.driver.show-component');
    }
}
