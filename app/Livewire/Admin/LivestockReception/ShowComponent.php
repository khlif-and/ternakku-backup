<?php

namespace App\Livewire\Admin\LivestockReception;

use Livewire\Component;
use App\Models\Farm;
use App\Models\LivestockReceptionD;
use App\Services\Web\Farming\LivestockReception\LivestockReceptionCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public LivestockReceptionD $reception;

    public function mount(Farm $farm, LivestockReceptionD $reception)
    {
        $this->farm = $farm;
        $this->reception = $reception->load([
            'livestockReceptionH',
            'livestockType',
            'livestockBreed',
            'livestockSex',
            'livestockClassification',
            'pen',
        ]);
    }

    public function delete(LivestockReceptionCoreService $coreService)
    {
        try {
            $coreService->deleteReception($this->farm, $this->reception->id);
            session()->flash('success', 'Data penerimaan ternak berhasil dihapus.');
            return redirect()->route('admin.care-livestock.livestock-reception.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-reception.show-component');
    }
}
