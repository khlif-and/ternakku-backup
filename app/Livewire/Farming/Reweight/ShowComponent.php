<?php

namespace App\Livewire\Farming\Reweight;

use App\Models\Farm;
use Livewire\Component;
use App\Services\Web\Farming\Reweight\ReweightCoreService;

class ShowComponent extends Component
{
    public Farm $farm;
    public $reweight;

    public function mount(Farm $farm, $id, ReweightCoreService $service)
    {
        $this->farm = $farm;
        try {
            $this->reweight = $service->get($farm, $id);
        } catch (\Exception $e) {
            session()->flash('error', 'Data tidak ditemukan.');
            return redirect()->route('admin.care-livestock.reweight.index', $this->farm->id);
        }
    }

    public function delete(ReweightCoreService $service)
    {
        try {
            $service->destroy($this->farm, $this->reweight->id);
            session()->flash('success', 'Data berhasil dihapus.');
            return redirect()->route('admin.care-livestock.reweight.index', $this->farm->id);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.farming.reweight.show-component');
    }
}
