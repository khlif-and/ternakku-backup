<?php

namespace App\Livewire\Qurban\SalesLivestock;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\QurbanCustomerAddress;
use App\Models\QurbanSalesOrder;
use App\Services\Web\Qurban\SalesLivestock\SalesLivestockCoreService;

class CreateComponent extends Component
{
    public Farm $farm;
    public $customer_id;
    public $sales_order_id;
    public $transaction_date;
    public $transaction_number;

    public $sales_order_number;
    public $notes;
    public $items = [];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = date('Y-m-d');
        
        // Generate prediction for transaction number
        $this->transaction_number = $this->generateTransactionNumberPreview('QSL', $this->transaction_date, $this->farm->id);

        $this->items = [
            [
                'livestock_id' => '',
                'customer_address_id' => '',
                'weight' => 0,
                'price_per_kg' => 0,
                'price_per_head' => 0,
                'delivery_plan_date' => date('Y-m-d'),
            ]
        ];
    }

    private function generateTransactionNumberPreview($type, $transactionDate, $farmId)
    {
        $date = \Illuminate\Support\Carbon::parse($transactionDate);

        $code =  'QSL';
        $year = $date->format('y');
        $month = $date->format('m');
        $prefix = "$year$month-$code-";

        // Get the last transaction number for the current month and year
        $lastTransaction = \App\Models\QurbanSaleLivestockH::whereYear('transaction_date', $date->year)
            ->whereMonth('transaction_date', $date->month)
            ->where('farm_id' , $farmId)
            ->where('transaction_number' , 'like' , "%$code%")
            ->orderBy('transaction_number', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->transaction_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function updatedCustomerId($value)
    {
        $this->sales_order_id = null;
        $this->sales_order_number = '-';

        if ($value) {
            $salesOrder = QurbanSalesOrder::where('farm_id', $this->farm->id)
                ->where('qurban_customer_id', $value)
                ->latest()
                ->first();
            
            if ($salesOrder) {
                $this->sales_order_id = $salesOrder->id;
                $this->sales_order_number = $salesOrder->transaction_number;
            }
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

    public function store(SalesLivestockCoreService $coreService)
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

            $coreService->store($this->farm, $data);

            session()->flash('success', 'Data penjualan ternak berhasil ditambahkan.');
            return redirect()->route('admin.care-livestock.sales-livestock.index', $this->farm->id);

        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
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

        $availableLivestock = $coreService->getAvailableLivestock($this->farm->id);

        $salesOrders = collect([]);
        if ($this->customer_id) {
            $salesOrders = QurbanSalesOrder::where('farm_id', $this->farm->id)
                ->where('qurban_customer_id', $this->customer_id)
                ->get();
        }

        return view('livewire.qurban.sales-livestock.create-component', [
            'customers' => $customers,
            'addresses' => $addresses,
            'availableLivestock' => $availableLivestock,
            'salesOrders' => $salesOrders,
        ]);
    }
}