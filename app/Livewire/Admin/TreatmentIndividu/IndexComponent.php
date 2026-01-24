<?php

namespace App\Livewire\Admin\TreatmentIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Disease;
use App\Services\Web\Farming\TreatmentIndividu\TreatmentIndividuCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $disease_id;
    public $pen_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'disease_id' => ['except' => ''],
        'pen_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, TreatmentIndividuCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data treatment individu berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(TreatmentIndividuCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'disease_id' => $this->disease_id,
            'pen_id' => $this->pen_id,
        ];

        $items = $coreService->listTreatments($this->farm, $filters);

        return view('livewire.admin.treatment-individu.index-component', [
            'items' => $items,
            'pens' => $this->farm->pens,
            'diseases' => Disease::all(),
        ]);
    }
}