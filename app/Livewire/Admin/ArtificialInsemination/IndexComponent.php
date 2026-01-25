<?php

namespace App\Livewire\Admin\ArtificialInsemination;

use Livewire\Component;
use App\Models\Farm;
use App\Models\InseminationArtificial;
use App\Services\Web\Farming\ArtificialInsemination\ArtificialInseminationCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $livestock_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'livestock_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, ArtificialInseminationCoreService $coreService)
    {
        try {
            $item = $coreService->find($this->farm, $id);
            $coreService->delete($item);
            session()->flash('success', 'Data inseminasi buatan berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = InseminationArtificial::with([
            'insemination',
            'reproductionCycle.livestock',
            'semenBreed'
        ])->whereHas('insemination', function ($q) {
            $q->where('farm_id', $this->farm->id)->where('type', 'artificial');

            if ($this->start_date) {
                $q->where('transaction_date', '>=', $this->start_date);
            }
            if ($this->end_date) {
                $q->where('transaction_date', '<=', $this->end_date);
            }
        });

        if ($this->livestock_id) {
            $query->whereHas('reproductionCycle', function ($q) {
                $q->where('livestock_id', $this->livestock_id);
            });
        }

        $items = $query->latest()->get();

        return view('livewire.admin.artificial-insemination.index-component', [
            'items' => $items,
            'livestocks' => $this->farm->livestocks()
                ->whereHas('livestockSex', function($q) {
                    $q->where('name', 'Female')->orWhere('name', 'Betina');
                })->get(),
        ]);
    }
}