<?php

namespace App\Livewire\Admin\Customer;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\Customer\CustomerCoreService;

class IndexComponent extends Component
{
    public Farm $farm;

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, CustomerCoreService $coreService)
    {
        try {
            $coreService->deleteCustomer($id);
            session()->flash('success', 'Data customer berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(CustomerCoreService $coreService)
    {
        $customers = $coreService->listCustomers($this->farm);

        return view('livewire.admin.customer.index-component', [
            'customers' => $customers,
        ]);
    }
}