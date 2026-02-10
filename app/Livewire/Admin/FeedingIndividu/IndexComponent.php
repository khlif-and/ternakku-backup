<?php

namespace App\Livewire\Admin\FeedingIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Helpers\Web\FeedingIndividuFormService;
use App\Services\Web\Farming\FeedingColony\FeedingIndividuCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $livestock_id;

    public $livestocks = [];

    protected $queryString = ['start_date', 'end_date', 'livestock_id'];

    public function mount(Farm $farm, FeedingIndividuFormService $formService)
    {
        $this->farm = $farm;

        $formData = $formService->getDropdownData($farm);
        $this->livestocks = $formData['livestocks'];
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

        $items = $coreService->list($this->farm, $filters);

        return view('livewire.admin.feeding-individu.index-component', [
            'items' => $items,
            'livestocks' => $this->livestocks,
        ]);
    }
}
