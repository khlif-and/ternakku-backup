<?php

namespace App\Livewire\Admin\SalesLivestock;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\QurbanSalesOrder;
use App\Services\Qurban\SalesLivestockCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $customer_id;
    public $sales_order_id;
    public $notes;
    public $details = [];

    public $customers = [];
    public $salesOrders = [];
    public $livestocks = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'customer_id' => 'required|exists:qurban_customers,id',
            'sales_order_id' => 'nullable|exists:qurban_sales_orders,id',
            'notes' => 'nullable|string',
            'details' => 'array|min:1',
            'details.*.livestock_id' => 'required|exists:livestocks,id',
            'details.*.customer_address_id' => 'nullable|integer',
            'details.*.weight' => 'required|numeric|min:0',
            'details.*.price_per_kg' => 'required|numeric|min:0',
            'details.*.price_per_head' => 'required|numeric|min:0',
            'details.*.delivery_plan_date' => 'nullable|date',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
        'customer_id.required' => 'Customer wajib dipilih.',
        'details.min' => 'Minimal satu ternak harus dipilih.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        
        $this->customers = QurbanCustomer::all();
        $this->salesOrders = QurbanSalesOrder::where('farm_id', $farm->id)->get();
        $this->livestocks = $farm->livestocks()->alive()->get();

        $this->addDetail();
    }

    public function addDetail()
    {
        $this->details[] = [
            'livestock_id' => '',
            'customer_address_id' => null,
            'weight' => 0,
            'price_per_kg' => 0,
            'price_per_head' => 0,
            'delivery_plan_date' => null,
        ];
    }

    public function removeDetail($index)
    {
        if (count($this->details) > 1) {
            unset($this->details[$index]);
            $this->details = array_values($this->details);
        }
    }

    public function save(SalesLivestockCoreService $coreService)
    {
        $this->validate();

        try {
            $salesLivestockH = $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'customer_id' => $this->customer_id,
                'sales_order_id' => $this->sales_order_id,
                'notes' => $this->notes,
                'details' => $this->details,
            ]);

            session()->flash('success', 'Data penjualan ternak berhasil ditambahkan.');
            return redirect()->route('admin.care-livestock.sales-livestock.show', [$this->farm->id, $salesLivestockH->id]);
        } catch (\Throwable $e) {
            Log::error('SalesLivestock Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.sales-livestock.create-component');
    }
}