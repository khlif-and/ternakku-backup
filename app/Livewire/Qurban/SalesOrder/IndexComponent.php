<?php

namespace App\Livewire\Qurban\SalesOrder;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Services\Web\Qurban\SalesOrder\SalesOrderCoreService;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $qurban_customer_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'qurban_customer_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, SalesOrderCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Sales Order berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus Sales Order: ' . $e->getMessage());
        }
    }

    public function render(SalesOrderCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'qurban_customer_id' => $this->qurban_customer_id,
        ];

        $salesOrders = $coreService->list($this->farm, $filters);
        $customers = QurbanCustomer::with('user')->get()->map(function($customer) {
            $customer->name = $customer->user->name ?? $customer->phone_number ?? 'Customer #' . $customer->id;
            return $customer;
        });

        return view('livewire.qurban.sales-order.index-component', [
            'salesOrders' => $salesOrders,
            'customers' => $customers,
        ]);
    }
}
