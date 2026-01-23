<?php

namespace App\Livewire\Admin\FeedingIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\FeedingColony\FeedingIndividuCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $livestock_id;

    protected $queryString = ['start_date', 'end_date', 'livestock_id'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, FeedingIndividuCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data pemberian pakan individu berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(FeedingIndividuCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'livestock_id' => $this->livestock_id,
        ];

        // Assuming the method name on service, but not implementing it as per instruction to only create this component.
        $items = $coreService->listFeedingIndividu($this->farm, $filters);
        
        $livestocks = $this->farm->livestocks()
            ->with(['livestockType:id,name', 'livestockBreed:id,name'])
            ->get()
            ->sortBy(function ($livestock) {
                return $livestock->eartag_number ?? $livestock->eartag ?? $livestock->id;
            });

        return view('livewire.admin.feeding-individu.index-component', [
            'items' => $items,
            'livestocks' => $livestocks,
        ]);
    }
}
