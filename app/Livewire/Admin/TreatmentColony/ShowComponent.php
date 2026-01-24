<?php

namespace App\Livewire\Admin\TreatmentColony;

use Livewire\Component;
use App\Models\Farm;
use App\Models\TreatmentColonyD;
use App\Services\Web\Farming\TreatmentColony\TreatmentColonyCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public TreatmentColonyD $treatmentColony;

    public function mount(Farm $farm, TreatmentColonyD $treatmentColony)
    {
        $this->farm = $farm;
        $this->treatmentColony = $treatmentColony->load([
            'treatmentH', 
            'pen', 
            'disease',
            'livestocks', 
            'treatmentColonyMedicineItems', 
            'treatmentColonyTreatmentItems'
        ]);
    }

    public function delete(TreatmentColonyCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->treatmentColony->id);
            
            session()->flash('success', 'Data treatment koloni berhasil dihapus.');
            return redirect()->route('admin.care-livestock.treatment-colony.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('TreatmentColony Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.treatment-colony.show-component');
    }
}