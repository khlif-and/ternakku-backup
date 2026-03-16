<?php

namespace App\Livewire\Admin\MutationIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MutationIndividuD;
use App\Services\Web\Farming\MutationIndividu\MutationIndividuCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public MutationIndividuD $mutationIndividu;

    public function mount(Farm $farm, MutationIndividuD $mutationIndividu)
    {
        $this->farm = $farm;
        $this->mutationIndividu = $mutationIndividu->load([
            'mutationH',
            'livestock'
        ]);
    }

    public function delete(MutationIndividuCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->mutationIndividu->id);

            session()->flash('success', 'Data mutasi individu berhasil dihapus.');
            return redirect()->route('admin.care-livestock.mutation-individu.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.admin.mutation-individu.show-component');
    }
}