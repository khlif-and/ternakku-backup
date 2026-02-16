<?php

namespace App\Livewire\Admin\Pen;

use App\Models\Farm;
use App\Models\Pen;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Farming\PenService;
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

    public function delete($id, PenService $penService)
    {
        try {
            $pen = $this->farm->pens()->findOrFail($id);
            $penService->delete($pen);
            session()->flash('success', 'Data kandang berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Pen Delete Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Pen::where('farm_id', $this->farm->id);

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        $pens = $query->latest('updated_at')->paginate($this->perPage);

        return view('livewire.admin.pen.index-component', [
            'pens' => $pens,
        ]);
    }
}
