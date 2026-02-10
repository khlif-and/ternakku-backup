<?php

namespace App\Livewire\Shared\Fleet;

use App\Models\Farm;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Web\Shared\Fleet\FleetCoreService;

class EditComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;
    public $fleetId;

    public $name;
    public $police_number;
    public $photo;
    public $current_photo;

    public function mount(Farm $farm, $id, FleetCoreService $service)
    {
        $this->farm = $farm;
        $this->fleetId = $id;

        try {
            $fleet = $service->get($farm, $id);

            $this->name = $fleet->name;
            $this->police_number = $fleet->police_number;
            $this->current_photo = $fleet->photo;

        } catch (\Exception $e) {
            session()->flash('error', 'Data tidak ditemukan.');
            return redirect()->route('shared.fleet.index', $this->farm->id);
        }
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

            $service->update($this->farm, $this->fleetId, $data);

            session()->flash('success', 'Data armada berhasil diperbarui.');
            return redirect()->route('shared.fleet.index', $this->farm->id);

        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.shared.fleet.edit-component');
    }
}
