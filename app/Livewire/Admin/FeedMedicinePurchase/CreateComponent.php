<?php

namespace App\Livewire\Admin\FeedMedicinePurchase;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\FeedMedicinePurchase\FeedMedicinePurchaseCoreService;

class CreateComponent extends Component
{
    public Farm $farm;
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
            'items.*.purchase_type' => 'required|in:forage,concentrate,medicine',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string',
            'items.*.price_per_unit' => 'required|numeric|min:0',
        ];
    }

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->purchaseTypes = [
            'forage' => 'Hijauan (Forage)',
            'concentrate' => 'Konsentrat (Concentrate)',
            'medicine' => 'Obat (Medicine)'
        ];
        $this->addItem();
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
        $purchase = $coreService->storePurchase($this->farm, [
            'transaction_date' => $this->transaction_date,
            'supplier' => $this->supplier,
            'notes' => $this->notes,
            'items' => $this->items,
        ]);

        session()->flash('success', 'Data berhasil disimpan.');
        return redirect()->route('admin.care-livestock.feed-medicine-purchase.show', [$this->farm->id, $purchase->id]);
    }

    public function render()
    {
        return view('livewire.admin.feed-medicine-purchase.create-component');
    }
}