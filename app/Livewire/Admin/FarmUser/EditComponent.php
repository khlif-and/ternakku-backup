<?php

namespace App\Livewire\Admin\FarmUser;

use App\Models\Farm;
use App\Models\FarmUser;
use Livewire\Component;
use App\Services\Web\Farming\FarmUser\FarmUserCoreService;

class EditComponent extends Component
{
    public Farm $farm;
    public FarmUser $farmUser;

    public $farm_role = '';
    public $userName = '';
    public $userEmail = '';

    public $roles = ['ABK', 'ADMIN', 'DRIVER', 'MARKETING'];

    public function mount(Farm $farm, $id, FarmUserCoreService $service)
    {
        $this->farm = $farm;
        $this->farmUser = $service->get($farm->id, $id);
        $this->farm_role = $this->farmUser->farm_role;
        $this->userName = $this->farmUser->user->name;
        $this->userEmail = $this->farmUser->user->email;
    }

    public function save(FarmUserCoreService $service)
    {
        $this->validate([
            'farm_role' => 'required|string|in:ABK,ADMIN,DRIVER,MARKETING',
        ]);

        try {
            $service->update($this->farm->id, $this->farmUser->id, [
                'farm_role' => $this->farm_role,
            ]);

            session()->flash('success', 'Role pengguna berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.farm-users.index', $this->farm->id);

        } catch (\Exception $e) {
            report($e);
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Terjadi kesalahan pada sistem.']);
        }
    }

    public function render()
    {
        return view('livewire.admin.farm-user.edit-component');
    }
}
