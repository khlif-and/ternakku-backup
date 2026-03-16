<?php

namespace App\Livewire\Admin\TreatmentIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\TreatmentIndividuD;
use App\Services\Web\Farming\TreatmentIndividu\TreatmentIndividuCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public TreatmentIndividuD $treatmentIndividu;

    public function mount(Farm $farm, TreatmentIndividuD $treatmentIndividu)
    {
        $this->farm = $farm;
        $this->treatmentIndividu = $treatmentIndividu->load([
            'treatmentH',
            'livestock',
            'disease',
            'treatmentIndividuMedicineItems',
            'treatmentIndividuTreatmentItems'
        ]);
    }

    public function delete(TreatmentIndividuCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->treatmentIndividu->id);

            session()->flash('success', 'Data treatment individu berhasil dihapus.');
            return redirect()->route('admin.care-livestock.treatment-individu.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.admin.treatment-individu.show-component');
    }
}