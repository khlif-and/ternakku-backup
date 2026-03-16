<?php

namespace App\Livewire\Admin\LivestockBirth;

use Livewire\Component;
use App\Models\Farm;
use App\Models\LivestockBirth;
use App\Services\Web\Farming\LivestockBirth\LivestockBirthCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public LivestockBirth $birth;

    public function mount(Farm $farm, LivestockBirth $birth)
    {
        $this->farm = $farm;
        $this->birth = $birth->load([
            'reproductionCycle.livestock',
            'livestockBirthD.livestockBreed',
            'livestockBirthD.disease'
        ]);
    }

    public function delete(LivestockBirthCoreService $coreService)
    {
        try {
            $coreService->deleteBirth($this->farm, $this->birth->id);

            session()->flash('success', 'Data kelahiran berhasil dihapus.');
            return redirect()->route('admin.care_livestock.livestock_birth.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Gagal menghapus data kelahiran.');
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-birth.show-component');
    }
}