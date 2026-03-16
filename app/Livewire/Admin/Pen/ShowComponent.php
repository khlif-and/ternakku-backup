<?php

namespace App\Livewire\Admin\Pen;

use App\Models\Farm;
use App\Models\Pen;
use Livewire\Component;
use App\Services\Farming\PenService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public Pen $pen;

    public function mount(Farm $farm, $id)
    {
        $this->farm = $farm;
        $this->pen = $farm->pens()->findOrFail($id);
    }

    public function delete(PenService $penService)
    {
        try {
            $penService->delete($this->pen);
            session()->flash('success', 'Data kandang berhasil dihapus.');
            return redirect()->route('admin.care-livestock.pens.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.admin.pen.show-component');
    }
}
