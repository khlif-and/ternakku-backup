<?php

namespace App\Livewire\Admin\FeedingColony;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\FeedingColony\FeedingColonyCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $pen_id;

    protected $queryString = ['start_date', 'end_date', 'pen_id'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, FeedingColonyCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data pemberian pakan berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(FeedingColonyCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'pen_id' => $this->pen_id,
        ];

        $items = $coreService->listFeedingColonies($this->farm, $filters);

        return view('livewire.admin.feeding-colony.index-component', [
            'items' => $items,
            'pens' => $this->farm->pens,
        ]);
    }
}
