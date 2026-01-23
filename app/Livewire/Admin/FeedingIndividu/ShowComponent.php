<?php

namespace App\Livewire\Admin\FeedingIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\FeedingIndividuD;
use App\Services\Web\Farming\FeedingColony\FeedingIndividuCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public FeedingIndividuD $feedingIndividu;

    public function mount(Farm $farm, FeedingIndividuD $feedingIndividu)
    {
        $this->farm = $farm;
        $this->feedingIndividu = $feedingIndividu->load(['feedingH', 'livestock', 'feedingIndividuItems']);
    }

    public function delete(FeedingIndividuCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->feedingIndividu->id);
            session()->flash('success', 'Data pemberian pakan individu berhasil dihapus.');
            return redirect()->route('admin.care-livestock.feeding-individu.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('FeedingIndividu Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.feeding-individu.show-component');
    }
}
