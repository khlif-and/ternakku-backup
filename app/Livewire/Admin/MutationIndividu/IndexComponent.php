<?php

namespace App\Livewire\Admin\MutationIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\MutationIndividu\MutationIndividuCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $pen_id;
    public $livestock_id;
    public $livestock_type_id;
    public $livestock_group_id;
    public $livestock_breed_id;
    public $livestock_sex_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'pen_id' => ['except' => ''],
        'livestock_id' => ['except' => ''],
        'livestock_type_id' => ['except' => ''],
        'livestock_group_id' => ['except' => ''],
        'livestock_breed_id' => ['except' => ''],
        'livestock_sex_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, MutationIndividuCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data mutasi individu berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(MutationIndividuCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'pen_id' => $this->pen_id,
            'livestock_id' => $this->livestock_id,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_group_id' => $this->livestock_group_id,
            'livestock_breed_id' => $this->livestock_breed_id,
            'livestock_sex_id' => $this->livestock_sex_id,
        ];

        $items = $coreService->listMutations($this->farm, $filters);

        return view('livewire.admin.mutation-individu.index-component', [
            'items' => $items,
            'pens' => $this->farm->pens,
            'livestocks' => $this->farm->livestocks,
        ]);
    }
}