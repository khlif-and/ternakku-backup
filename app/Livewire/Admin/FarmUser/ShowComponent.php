<?php

namespace App\Livewire\Admin\FarmUser;

use App\Models\Farm;
use App\Models\FarmUser;
use Livewire\Component;
use App\Services\Web\Farming\FarmUser\FarmUserCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public FarmUser $farmUser;

    public function mount(Farm $farm, $id, FarmUserCoreService $service)
    {
        $this->farm = $farm;
        $this->farmUser = $service->get($farm->id, $id);
    }

    public function delete(FarmUserCoreService $service)
    {
        try {
            $service->destroy($this->farm->id, $this->farmUser->id);
            session()->flash('success', 'Pengguna berhasil dihapus dari farm.');
            return redirect()->route('admin.care-livestock.farm-users.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.admin.farm-user.show-component');
    }
}
