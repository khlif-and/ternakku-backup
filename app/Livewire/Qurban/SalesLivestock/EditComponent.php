<?php

namespace App\Livewire\Qurban\SalesLivestock;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\QurbanCustomerAddress;
use App\Models\QurbanSalesOrder;
use App\Models\QurbanSaleLivestockH;
use App\Services\Web\Qurban\SalesLivestock\SalesLivestockCoreService;

class EditComponent extends Component
{
    public Farm $farm;
    public $salesLivestockId;
    public $customer_id;
    public $sales_order_id;
    public $sales_order_number;
    public $transaction_date;
    public $notes;
    public $items = [];

    public function mount(Farm $farm, $id)
    {
        $this->farm = $farm;
        $this->salesLivestockId = $id;
        
        $header = QurbanSaleLivestockH::where('farm_id', $farm->id)->with('qurbanSaleLivestockD')->findOrFail($id);
        
        $this->customer_id = $header->qurban_customer_id;
        $this->sales_order_id = $header->qurban_sales_order_id; 
        $this->transaction_date = $header->transaction_date;
        $this->notes = $header->notes;

        if ($this->sales_order_id) {
            $salesOrder = QurbanSalesOrder::find($this->sales_order_id);
            $this->sales_order_number = $salesOrder->transaction_number ?? '-';
        } else {
            $this->sales_order_number = '-';
        }

        foreach ($header->qurbanSaleLivestockD as $detail) {
            $this->items[] = [
                'livestock_id' => $detail->livestock_id,
                'customer_address_id' => $detail->qurban_customer_address_id,
                'weight' => $detail->weight,
                'price_per_kg' => $detail->price_per_kg,
                'price_per_head' => $detail->price_per_head,
                'delivery_plan_date' => $detail->delivery_plan_date,
            ];
        }

        if (empty($this->items)) {
            $this->addItem();
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'livestock_id' => '',
            'customer_address_id' => '',
            'weight' => 0,
            'price_per_kg' => 0,
            'price_per_head' => 0,
            'delivery_plan_date' => date('Y-m-d'),
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedItems($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) < 2) return;
        
        $index = $parts[0];
        $field = $parts[1];

        // Auto-fill weight when livestock is selected
        if ($field === 'livestock_id') {
            $livestockId = $value;
            if ($livestockId) {
                $livestock = \App\Models\Livestock::find($livestockId);
                if ($livestock) {
                    $this->items[$index]['weight'] = $livestock->current_weight ?? 0;
                    
                    // Recalculate total if price per kg exists
                    $pricePerKg = (float) ($this->items[$index]['price_per_kg'] ?? 0);
                    $this->items[$index]['price_per_head'] = $this->items[$index]['weight'] * $pricePerKg;
                }
            }
        }

        // Calculate Total Price when Weight or Price/Kg changes
        if ($field === 'weight' || $field === 'price_per_kg') {
            $weight = (float) ($this->items[$index]['weight'] ?? 0);
            $pricePerKg = (float) ($this->items[$index]['price_per_kg'] ?? 0);
            
            $this->items[$index]['price_per_head'] = $weight * $pricePerKg;
        }
    }

    public function update(SalesLivestockCoreService $coreService)
    {
        $this->validate([
            'customer_id' => 'required',
            'transaction_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.livestock_id' => 'required',
            'items.*.customer_address_id' => 'required',
            'items.*.weight' => 'required|numeric|min:0',
            'items.*.price_per_kg' => 'required|numeric|min:0',
            'items.*.price_per_head' => 'required|numeric|min:0',
            'items.*.delivery_plan_date' => 'required|date',
        ]);

        try {
            $data = [
                'customer_id' => $this->customer_id,
                'sales_order_id' => $this->sales_order_id,
                'transaction_date' => $this->transaction_date,
                'notes' => $this->notes,
                'items' => $this->items,
            ];

            $coreService->update($this->farm, $this->salesLivestockId, $data);

            session()->flash('success', 'Data penjualan ternak berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.sales-livestock.index', $this->farm->id);

        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function render(SalesLivestockCoreService $coreService)
    {
        $customers = QurbanCustomer::with('user')->get()->mapWithKeys(function($customer) {
            $name = $customer->user->name ?? $customer->phone_number ?? 'Customer #' . $customer->id;
            return [$customer->id => $name];
        });

        $addresses = collect([]);
        if ($this->customer_id) {
            $addresses = QurbanCustomerAddress::where('qurban_customer_id', $this->customer_id)
                ->get()
                ->mapWithKeys(function($addr) {
                    $label = $addr->name ? ($addr->name . ' - ' . $addr->address_line) : $addr->address_line;
                    return [$addr->id => $label];
                });
        }

        // Untuk Edit, kita perlu menggabungkan livestock yang tersedia dengan livestock yang sudah dipilih di transaksi ini
        // agar tidak hilang dari dropdown saat diedit
        $availableLivestock = $coreService->getAvailableLivestock($this->farm->id);
        
        // Ambil livestock yang ada di transaksi ini (header id)
        $currentLivestockIds = collect($this->items)->pluck('livestock_id')->filter();
        
        // Load livestock detail untuk item yang sedang diedit, jika belum ada di available list
        if ($currentLivestockIds->isNotEmpty()) {
             $currentLivestocks = \App\Models\Livestock::whereIn('id', $currentLivestockIds)->get();
             $availableLivestock = $availableLivestock->merge($currentLivestocks)->unique('id');
        }

        $salesOrders = collect([]);
        if ($this->customer_id) {
            $salesOrders = QurbanSalesOrder::where('farm_id', $this->farm->id)
                ->where('qurban_customer_id', $this->customer_id)
                ->get();
        }

        return view('livewire.qurban.sales-livestock.edit-component', [
            'customers' => $customers,
            'addresses' => $addresses,
            'availableLivestock' => $availableLivestock,
            'salesOrders' => $salesOrders,
        ]);
    }
}