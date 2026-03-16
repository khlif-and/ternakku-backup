<?php

namespace App\Livewire\Admin\LivestockDeath;

use Livewire\Component;
use App\Models\Farm;
use App\Models\LivestockDeath;
use App\Services\Web\Farming\LivestockDeath\LivestockDeathCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public LivestockDeath $death;

    public function mount(Farm $farm, LivestockDeath $death)
    {
        $this->farm = $farm;
        $this->death = $death->load(['livestock', 'disease']);
    }

    public function delete(LivestockDeathCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->death->id);
            session()->flash('success', 'Data kematian ternak berhasil dihapus.');
            return redirect()->route('admin.care-livestock.livestock-death.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-death.show-component');
    }
}
