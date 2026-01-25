<?php

namespace App\Livewire\Admin\PregnantCheck;

use Livewire\Component;
use App\Models\Farm;
use App\Models\LivestockType;
use App\Models\LivestockBreed;
use App\Services\Web\Farming\PregnantCheck\PregnantCheckCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    
    // Filter sesuai dukungan CoreService
    public $pen_id;
    public $livestock_type_id;
    public $livestock_breed_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'pen_id' => ['except' => ''],
        'livestock_type_id' => ['except' => ''],
        'livestock_breed_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, PregnantCheckCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data pemeriksaan kehamilan berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(PregnantCheckCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'pen_id' => $this->pen_id,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_breed_id' => $this->livestock_breed_id,
        ];

        // Memanggil method listPregnantChecks dari CoreService
        $items = $coreService->listPregnantChecks($this->farm, $filters);

        return view('livewire.admin.pregnant-check.index-component', [
            'items' => $items,
            'pens' => $this->farm->pens,
            'livestockTypes' => LivestockType::all(),
            'livestockBreeds' => LivestockBreed::all(),
        ]);
    }
}