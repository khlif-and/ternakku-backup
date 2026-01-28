<?php

namespace App\Livewire\Admin\SalesLivestock;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\QurbanSalesOrder;
use App\Models\QurbanSaleLivestockH;
use App\Services\Qurban\SalesLivestockCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanSaleLivestockH $salesLivestock;

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

    public function mount(Farm $farm, QurbanSaleLivestockH $salesLivestock)
    {
        $this->farm = $farm;
        $this->salesLivestock = $salesLivestock;
        
        $this->customers = QurbanCustomer::all();
        $this->salesOrders = QurbanSalesOrder::where('farm_id', $farm->id)->get();
        // Mengambil ternak yang tersedia ditambah ternak yang sudah ada di transaksi ini (agar tidak hilang dari dropdown saat edit)
        $existingLivestockIds = $salesLivestock->qurbanSaleLivestockD->pluck('livestock_id')->toArray();
        $this->livestocks = $farm->livestocks()->where(function($query) use ($existingLivestockIds) {
            $query->alive()->orWhereIn('id', $existingLivestockIds);
        })->get();

        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->salesLivestock->transaction_date;
        $this->customer_id = $this->salesLivestock->qurban_customer_id;
        $this->sales_order_id = $this->salesLivestock->qurban_sales_order_id;
        $this->notes = $this->salesLivestock->notes;

        $this->details = $this->salesLivestock->qurbanSaleLivestockD->map(function ($item) {
            return [
                'livestock_id' => $item->livestock_id,
                'customer_address_id' => $item->qurban_customer_address_id,
                'weight' => $item->min_weight, // Asumsi min_weight = berat saat jual
                'price_per_kg' => $item->price_per_kg,
                'price_per_head' => $item->price_per_head,
                'delivery_plan_date' => $item->delivery_plan_date,
            ];
        })->toArray();

        if (empty($this->details)) {
            $this->addDetail();
        }
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
            $coreService->update($this->farm, $this->salesLivestock->id, [
                'transaction_date' => $this->transaction_date,
                'customer_id' => $this->customer_id,
                'sales_order_id' => $this->sales_order_id,
                'notes' => $this->notes,
                'details' => $this->details,
            ]);

            session()->flash('success', 'Data penjualan ternak berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.sales-livestock.show', [$this->farm->id, $this->salesLivestock->id]);
        } catch (\Throwable $e) {
            Log::error('SalesLivestock Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.sales-livestock.edit-component');
    }
}