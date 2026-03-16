<?php

namespace App\Livewire\Admin\Pen;

use App\Models\Farm;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Farming\PenService;

class CreateComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;

    public $name = '';
    public $area = '';
    public $capacity = '';
    public $photo;

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
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

            $penService->create($data, $this->farm, $this->photo);

            session()->flash('success', 'Data kandang berhasil ditambahkan.');
            return redirect()->route('admin.care-livestock.pens.index', $this->farm->id);

        } catch (\Exception $e) {
            report($e);
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Terjadi kesalahan pada sistem.']);
        }
    }

    public function render()
    {
        return view('livewire.admin.pen.create-component');
    }
}
