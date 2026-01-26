<?php

namespace App\Livewire\Admin\LivestockBirth;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\LivestockType;
use App\Models\LivestockGroup;
use App\Models\LivestockBreed;
use App\Services\Web\Farming\LivestockBirth\LivestockBirthCoreService;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $livestock_type_id;
    public $livestock_group_id;
    public $livestock_breed_id;
    public $pen_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'livestock_type_id' => ['except' => ''],
        'livestock_group_id' => ['except' => ''],
        'livestock_breed_id' => ['except' => ''],
        'pen_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function updated($propertyName)
    {
        $this->resetPage();
    }

    public function delete($id, LivestockBirthCoreService $coreService)
    {
        try {
            $coreService->deleteBirth($this->farm, $id);
            session()->flash('success', 'Data kelahiran berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(LivestockBirthCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_group_id' => $this->livestock_group_id,
            'livestock_breed_id' => $this->livestock_breed_id,
            'pen_id' => $this->pen_id,
        ];

        $data = $coreService->listBirths($this->farm, $filters);

        return view('livewire.admin.livestock-birth.index-component', [
            'births' => $data['births'],
            'pens' => $this->farm->pens,
            'types' => LivestockType::all(),
            'groups' => LivestockGroup::all(),
            'breeds' => LivestockBreed::all(),
        ]);
    }
}