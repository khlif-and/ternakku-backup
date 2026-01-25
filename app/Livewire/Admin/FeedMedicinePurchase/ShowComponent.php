<?php

namespace App\Livewire\Admin\FeedMedicinePurchase;

use Livewire\Component;
use App\Models\Farm;
use App\Models\FeedMedicinePurchase;
use App\Services\Web\Farming\FeedMedicinePurchase\FeedMedicinePurchaseCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;
    public FeedMedicinePurchase $purchase;

    public function mount(Farm $farm, FeedMedicinePurchase $purchase)
    {
        $this->farm = $farm;
        $this->purchase = $purchase->load(['feedMedicinePurchaseItem']);
    }

    public function delete(FeedMedicinePurchaseCoreService $coreService)
    {
        try {
            $coreService->deletePurchase($this->farm, $this->purchase->id);
            
            session()->flash('success', 'Data pembelian berhasil dihapus.');
            return redirect()->route('admin.care-livestock.feed-medicine-purchase.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('FeedMedicinePurchase Delete Error', ['message' => $e->getMessage()]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.feed-medicine-purchase.show-component');
    }
}