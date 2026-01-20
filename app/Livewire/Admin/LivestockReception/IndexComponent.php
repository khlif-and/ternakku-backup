<?php

namespace App\Livewire\Admin\LivestockReception;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\LivestockReceptionD;
use App\Services\Web\Farming\LivestockReception\LivestockReceptionCoreService;
use Illuminate\Support\Facades\Log;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search', 'perPage'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id, LivestockReceptionCoreService $coreService)
    {
        try {
            $coreService->deleteReception($this->farm, $id);
            session()->flash('success', 'Registrasi ternak berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Livestock Reception Delete Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = LivestockReceptionD::with([
            'livestockReceptionH',
            'livestockType',
            'livestockBreed',
            'livestockSex',
            'pen',
        ])->whereHas('livestockReceptionH', fn($q) => $q->where('farm_id', $this->farm->id));

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('eartag_number', 'like', "%{$this->search}%")
                  ->orWhere('rfid_number', 'like', "%{$this->search}%")
                  ->orWhereHas('livestockType', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"))
                  ->orWhereHas('livestockBreed', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"));
            });
        }

        $receptions = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.livestock-reception.index-component', [
            'receptions' => $receptions,
        ]);
    }
}
