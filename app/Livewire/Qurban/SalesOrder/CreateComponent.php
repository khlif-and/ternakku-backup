<?php

namespace App\Livewire\Qurban\SalesOrder;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Services\Web\Qurban\SalesOrder\SalesOrderCoreService;

class CreateComponent extends Component
{
    public Farm $farm;
    
    // Form properties
    public $customer_id;
    public $order_date;
    public $items = [];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->order_date = date('Y-m-d');
        // Initialize with one empty row
        $this->addItem();
    }

    public function addItem()
    {
        $this->items[] = [
            'livestock_type_id' => '',
            'quantity' => 1,
            'total_weight' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function store(SalesOrderCoreService $coreService)
    {
        $this->validate([
            'customer_id' => 'required',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.livestock_type_id' => 'required|exists:livestock_types,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total_weight' => 'required|numeric|min:0',
        ]);

        try {
            $data = [
                'customer_id' => $this->customer_id,
                'order_date' => $this->order_date,
                'items' => $this->items,
            ];

            $coreService->store($this->farm, $data);
            
            session()->flash('success', 'Sales Order berhasil ditambahkan.');
            return redirect()->route('admin.care-livestock.sales-order.index', $this->farm->id);
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menambahkan Sales Order: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $customers = QurbanCustomer::all();
        $livestockTypes = \App\Models\LivestockType::all();
        
        return view('livewire.qurban.sales-order.create-component', [
            'customers' => $customers,
            'livestockTypes' => $livestockTypes,
        ]);
    }
}
