<?php

namespace App\Livewire\Admin\FeedingColony;

use Livewire\Component;
use App\Models\Farm;
use App\Models\FeedingColonyD;
use App\Services\Web\Farming\FeedingColony\FeedingColonyCoreService;

class ShowComponent extends Component
{
    public Farm $farm;
    public FeedingColonyD $feedingColony;

    public function mount(Farm $farm, FeedingColonyD $feedingColony)
    {
        $this->farm = $farm;
        $this->feedingColony = $feedingColony->load(['feedingH', 'pen', 'livestocks', 'feedingColonyItems']);
    }

    public function delete(FeedingColonyCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $this->feedingColony->id);
            session()->flash('success', 'Data pemberian pakan berhasil dihapus.');
            return redirect()->route('admin.care-livestock.feeding-colony.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Gagal menghapus data pemberian pakan koloni.');
        }
    }

    public function render()
    {
        return view('livewire.admin.feeding-colony.show-component');
    }
}
