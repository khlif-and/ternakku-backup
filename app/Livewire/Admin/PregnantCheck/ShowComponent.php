<?php

namespace App\Livewire\Admin\PregnantCheck;

use Livewire\Component;
use App\Models\Farm;
use App\Models\PregnantCheckD;
use App\Services\Web\Farming\PregnantCheck\PregnantCheckCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public PregnantCheckD $item;

    public function mount(Farm $farm, PregnantCheckD $item)
    {
        $this->farm = $farm;
        $this->item = $item->load([
            'pregnantCheck',
            'reproductionCycle.livestock.livestockType',
            'reproductionCycle.livestock.livestockBreed',
            'reproductionCycle.livestock.pen',
        ]);
    }

    public function delete(PregnantCheckCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->item->id);
            
            session()->flash('success', 'Data pemeriksaan kehamilan berhasil dihapus.');
            return redirect()->route('admin.care_livestock.pregnant_check.index', ['farm_id' => $this->farm->id]);
        } catch (\Throwable $e) {
            Log::error('PregnantCheck Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.pregnant-check.show-component');
    }
}