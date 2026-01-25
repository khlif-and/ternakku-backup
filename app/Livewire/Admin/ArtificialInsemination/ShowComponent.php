<?php

namespace App\Livewire\Admin\ArtificialInsemination;

use Livewire\Component;
use App\Models\Farm;
use App\Models\InseminationArtificial;
use App\Services\Web\Farming\ArtificialInsemination\ArtificialInseminationCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public InseminationArtificial $aiRecord;

    public function mount(Farm $farm, InseminationArtificial $item)
    {
        $this->farm = $farm;
        $this->aiRecord = $item->load([
            'insemination',
            'semenBreed',
            'reproductionCycle.livestock'
        ]);
    }

    public function delete(ArtificialInseminationCoreService $coreService)
    {
        try {
            $coreService->delete($this->aiRecord);
            
            session()->flash('success', 'Data inseminasi buatan berhasil dihapus.');
            return redirect()->route('admin.care-livestock.artificial-inseminasi.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('ArtificialInsemination Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.artificial-insemination.show-component');
    }
}