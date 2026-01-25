<?php

namespace App\Livewire\Admin\NaturalInsemination;

use Livewire\Component;
use App\Models\Farm;
use App\Models\InseminationNatural;
use App\Services\Web\Farming\NaturalInsemination\NaturalInseminationCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public InseminationNatural $niRecord;

    public function mount(Farm $farm, InseminationNatural $item)
    {
        $this->farm = $farm;
        $this->niRecord = $item->load([
            'insemination',
            'sireBreed',
            'reproductionCycle.livestock'
        ]);
    }

    public function delete(NaturalInseminationCoreService $coreService)
    {
        try {
            $coreService->delete($this->niRecord);
            
            session()->flash('success', 'Natural insemination record deleted successfully.');
            return redirect()->route('admin.care-livestock.natural-insemination.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('NaturalInsemination Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.natural-insemination.show-component');
    }
}