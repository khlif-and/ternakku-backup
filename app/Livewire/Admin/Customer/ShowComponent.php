<?php

namespace App\Livewire\Admin\Customer;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Services\Web\Farming\Customer\CustomerCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public QurbanCustomer $customer;

    public function mount(Farm $farm, QurbanCustomer $customer)
    {
        $this->farm = $farm;
        $this->customer = $customer->load([
            'addresses',
            'user'
        ]);
    }

    public function delete(CustomerCoreService $coreService)
    {
        try {
            $coreService->deleteCustomer($this->customer->id);
            
            session()->flash('success', 'Data customer berhasil dihapus.');
            return redirect()->route('admin.care-livestock.customer.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('Customer Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.customer.show-component');
    }
}