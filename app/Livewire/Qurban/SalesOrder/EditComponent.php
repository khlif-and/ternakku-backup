<?php

namespace App\Livewire\Qurban\SalesOrder;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\QurbanSalesOrder;
use App\Services\Web\Qurban\SalesOrder\SalesOrderCoreService;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanSalesOrder $salesOrder;
    public $customers = [];
    public $livestockTypes = [];
    
    public $customer_id;
    public $order_date;
    public $items = [];

    public function mount(Farm $farm, $salesOrder)
    {
        $this->farm = $farm;
        if (is_numeric($salesOrder)) {
             $this->salesOrder = QurbanSalesOrder::with('qurbanSalesOrderD')->where('farm_id', $farm->id)->findOrFail($salesOrder);
        } else {
             $this->salesOrder = $salesOrder;
             $this->salesOrder->load('qurbanSalesOrderD');
        }

        $this->customers = QurbanCustomer::with('user')
            ->where('farm_id', $farm->id)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->user->name ?? $customer->phone_number ?? 'Customer #' . $customer->id,
                ];
            })
            ->pluck('name', 'id');
        $this->livestockTypes = \App\Models\LivestockType::all();

        $this->customer_id = $this->salesOrder->qurban_customer_id;
        $this->order_date = $this->salesOrder->order_date;

        if ($this->salesOrder->qurbanSalesOrderD->isNotEmpty()) {
            foreach ($this->salesOrder->qurbanSalesOrderD as $detail) {
                $this->items[] = [
                    'livestock_type_id' => $detail->livestock_type_id,
                    'quantity' => $detail->quantity,
                    'total_weight' => $detail->total_weight,
                ];
            }
        } else {
            $this->addItem();
        }
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

    public function update(SalesOrderCoreService $coreService)
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

            $coreService->update($this->farm, $this->salesOrder->id, $data);

            session()->flash('success', 'Sales Order berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.sales-order.index', $this->farm->id);
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal memperbarui Sales Order.');
        }
    }

    public function render()
    {
        return view('livewire.qurban.sales-order.edit-component', [
            'customers' => $this->customers,
            'livestockTypes' => $this->livestockTypes,
        ]);
    }
}
