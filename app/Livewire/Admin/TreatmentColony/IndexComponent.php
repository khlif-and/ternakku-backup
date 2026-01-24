<?php

namespace App\Livewire\Admin\TreatmentColony;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\TreatmentColony\TreatmentColonyCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $pen_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'pen_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, TreatmentColonyCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data treatment koloni berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(TreatmentColonyCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'pen_id' => $this->pen_id,
        ];

        $items = $coreService->listTreatmentColonies($this->farm, $filters);

        return view('livewire.admin.treatment-colony.index-component', [
            'items' => $items,
            'pens' => $this->farm->pens,
        ]);
    }
}