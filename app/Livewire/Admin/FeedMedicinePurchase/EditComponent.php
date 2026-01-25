<?php

namespace App\Livewire\Admin\FeedMedicinePurchase;

use Livewire\Component;
use App\Models\Farm;
use App\Models\FeedMedicinePurchase;
use App\Services\Web\Farming\FeedMedicinePurchase\FeedMedicinePurchaseCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public FeedMedicinePurchase $purchase;

    public $transaction_date;
    public $supplier;
    public $notes;
    public $items = [];
    public $purchaseTypes = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'supplier' => 'required|string',
            'notes' => 'nullable|string',
            'items.*.purchase_type' => 'required|in:forage,concentrate,medicine',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string',
            'items.*.price_per_unit' => 'required|numeric|min:0',
        ];
    }

    public function mount(Farm $farm, FeedMedicinePurchase $purchase)
    {
        $this->farm = $farm;
        $this->purchase = $purchase;
        
        $this->purchaseTypes = [
            'forage' => 'Hijauan (Forage)',
            'concentrate' => 'Konsentrat (Concentrate)',
            'medicine' => 'Obat (Medicine)'
        ];

        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->purchase->transaction_date;
        $this->supplier = $this->purchase->supplier;
        $this->notes = $this->purchase->notes;

        $this->items = $this->purchase->feedMedicinePurchaseItem->map(function ($item) {
            return [
                'purchase_type' => $item->purchase_type,
                'item_name' => $item->item_name,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'price_per_unit' => $item->price_per_unit,
            ];
        })->toArray();

        if (empty($this->items)) {
            $this->addItem();
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'purchase_type' => '',
            'item_name' => '',
            'quantity' => 0,
            'unit' => '',
            'price_per_unit' => 0,
        ];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function save(FeedMedicinePurchaseCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->updatePurchase($this->farm, $this->purchase->id, [
                'transaction_date' => $this->transaction_date,
                'supplier' => $this->supplier,
                'notes' => $this->notes,
                'items' => $this->items,
            ]);

            session()->flash('success', 'Data pembelian berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.feed-medicine-purchase.show', [$this->farm->id, $this->purchase->id]);
        } catch (\Throwable $e) {
            Log::error('FeedMedicinePurchase Edit Error', ['message' => $e->getMessage()]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.feed-medicine-purchase.edit-component');
    }
}