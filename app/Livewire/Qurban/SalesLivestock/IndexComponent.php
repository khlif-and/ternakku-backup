<?php

namespace App\Livewire\Admin\SalesLivestock;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Services\Qurban\SalesLivestockCoreService;

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

    public function delete($id, SalesLivestockCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm, $id);
            session()->flash('success', 'Data penjualan ternak berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(SalesLivestockCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'qurban_customer_id' => $this->qurban_customer_id,
        ];

        $sales = $coreService->list($this->farm, $filters);
        $customers = QurbanCustomer::all();

        return view('livewire.admin.sales-livestock.index-component', [
            'sales' => $sales,
            'customers' => $customers,
        ]);
    }
}