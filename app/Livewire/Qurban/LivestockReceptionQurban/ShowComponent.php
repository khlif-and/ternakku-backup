<?php

namespace App\Livewire\Qurban\LivestockReceptionQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\LivestockReceptionD;
use App\Services\Web\Qurban\LivestockReceptionQurban\LivestockReceptionCoreService;
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
            'pen',
            'livestock.qurbanLivestock',
        ]);
    }

    public function delete(LivestockReceptionCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->reception->id);

            session()->flash('success', 'Penerimaan ternak berhasil dihapus.');
            return redirect()->route('qurban.livestock-reception.index');
        } catch (\Throwable $e) {
            Log::error('Qurban Reception Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.livestock-reception.show-component');
    }
}
