<?php

namespace App\Livewire\Shared\Fleet;

use App\Models\Farm;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Web\Shared\Fleet\FleetCoreService;

class CreateComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;

    public $name;
    public $police_number;
    public $photo;

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function save(FleetCoreService $service)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'police_number' => 'required|string|max:20',
            'photo' => 'nullable|image|max:5120',
        ]);

        try {
            $data = [
                'name' => $this->name,
                'police_number' => $this->police_number,
                'photo' => $this->photo,
            ];

            $service->store($this->farm, $data);

            session()->flash('success', 'Data armada berhasil ditambahkan.');
            return redirect()->route('shared.fleet.index', $this->farm->id);

        } catch (\Exception $e) {
            report($e);
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Terjadi kesalahan pada sistem.']);
        }
    }

    public function render()
    {
        return view('livewire.shared.fleet.create-component');
    }
}
