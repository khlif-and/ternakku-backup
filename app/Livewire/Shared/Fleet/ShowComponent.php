<?php

namespace App\Livewire\Shared\Fleet;

use App\Models\Farm;
use Livewire\Component;
use App\Services\Web\Shared\Fleet\FleetCoreService;

class ShowComponent extends Component
{
    public Farm $farm;
    public $fleet;

    public function mount(Farm $farm, $id, FleetCoreService $service)
    {
        $this->farm = $farm;
        try {
            $this->fleet = $service->get($farm, $id);
        } catch (\Exception $e) {
            session()->flash('error', 'Data tidak ditemukan.');
            return redirect()->route('shared.fleet.index', $this->farm->id);
        }
    }

    public function delete(FleetCoreService $service)
    {
        try {
            $service->destroy($this->farm, $this->fleet->id);
            session()->flash('success', 'Data berhasil dihapus.');
            return redirect()->route('shared.fleet.index', $this->farm->id);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.shared.fleet.show-component');
    }
}
