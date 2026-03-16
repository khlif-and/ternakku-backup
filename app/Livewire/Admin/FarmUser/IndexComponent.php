<?php

namespace App\Livewire\Admin\FarmUser;

use App\Models\Farm;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Web\Farming\FarmUser\FarmUserCoreService;
use Illuminate\Support\Facades\Log;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $search = '';
    public $perPage = 10;
    public $filterRole = '';

    protected $queryString = ['search', 'perPage', 'filterRole'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id, FarmUserCoreService $service)
    {
        try {
            $service->destroy($this->farm->id, $id);
            session()->flash('success', 'Pengguna berhasil dihapus dari farm.');
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render(FarmUserCoreService $service)
    {
        $filters = [
            'search' => $this->search,
            'per_page' => $this->perPage,
            'farm_role' => $this->filterRole,
        ];

        $farmUsers = $service->list($this->farm->id, $filters);

        return view('livewire.admin.farm-user.index-component', [
            'farmUsers' => $farmUsers,
        ]);
    }
}
