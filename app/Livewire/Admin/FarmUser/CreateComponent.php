<?php

namespace App\Livewire\Admin\FarmUser;

use App\Models\Farm;
use Livewire\Component;
use App\Services\Web\Farming\FarmUser\FarmUserCoreService;

class CreateComponent extends Component
{
    public Farm $farm;

    public $searchUser = '';
    public $foundUser = null;
    public $user_id = '';
    public $farm_role = '';

    public $roles = ['ABK', 'ADMIN', 'DRIVER', 'MARKETING'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function searchUserByEmailOrPhone(FarmUserCoreService $service)
    {
        $this->validate([
            'searchUser' => 'required|string|min:3',
        ]);

        $this->foundUser = $service->findUser($this->searchUser);

        if ($this->foundUser) {
            $this->user_id = $this->foundUser->id;
        } else {
            $this->user_id = '';
            $this->dispatch('alert', ['type' => 'error', 'message' => 'User tidak ditemukan dengan email/nomor telepon tersebut.']);
        }
    }

    public function save(FarmUserCoreService $service)
    {
        $this->validate([
            'user_id' => 'required|integer|exists:users,id',
            'farm_role' => 'required|string|in:ABK,ADMIN,DRIVER,MARKETING',
        ]);

        try {
            $service->store($this->farm->id, [
                'user_id' => $this->user_id,
                'farm_role' => $this->farm_role,
            ]);

            session()->flash('success', 'Pengguna berhasil ditambahkan ke farm.');
            return redirect()->route('admin.care-livestock.farm-users.index', $this->farm->id);

        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Gagal menambahkan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.admin.farm-user.create-component');
    }
}
