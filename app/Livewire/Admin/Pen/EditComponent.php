<?php

namespace App\Livewire\Admin\Pen;

use App\Models\Farm;
use App\Models\Pen;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Farming\PenService;

class EditComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;
    public Pen $pen;

    public $name = '';
    public $area = '';
    public $capacity = '';
    public $photo;
    public $existingPhoto = null;

    public function mount(Farm $farm, $id)
    {
        $this->farm = $farm;
        $this->pen = $farm->pens()->findOrFail($id);

        $this->name = $this->pen->name;
        $this->area = $this->pen->area;
        $this->capacity = $this->pen->capacity;
        $this->existingPhoto = $this->pen->photo;
    }

    public function save(PenService $penService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'area' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'photo' => 'nullable|image|max:5120',
        ]);

        try {
            $data = [
                'name' => $this->name,
                'area' => $this->area,
                'capacity' => $this->capacity,
            ];

            $penService->update($this->pen, $data, $this->photo);

            session()->flash('success', 'Data kandang berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.pens.index', $this->farm->id);

        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.admin.pen.edit-component');
    }
}
