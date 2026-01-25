<?php

namespace App\Services\Web\Farming\FeedMedicinePurchase;

use Illuminate\Http\Request;
use App\Http\Requests\Farming\FeedMedicinePurchaseStoreRequest;
use App\Http\Requests\Farming\FeedMedicinePurchaseUpdateRequest;

class FeedMedicinePurchaseService
{
    protected FeedMedicinePurchaseCoreService $core;

    public function __construct(FeedMedicinePurchaseCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $filters = $request->only(['start_date', 'end_date', 'purchase_type']);
        $items = $this->core->listPurchases($farm, $filters);

        return view('admin.care_livestock.feed_medicine_purchase.index', compact('farm', 'items', 'request'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.feed_medicine_purchase.create', compact('farm'));
    }

    public function store(FeedMedicinePurchaseStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');
        $this->core->storePurchase($farm, $request->validated());

        return redirect()
            ->route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)
            ->with('success', 'Data pembelian berhasil disimpan.');
    }

public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        // Gunakan findPurchase() agar sinkron dengan CoreService
        $data = $this->core->findPurchase($farm, $id);

        return view('admin.care_livestock.feed_medicine_purchase.show', compact('farm', 'data'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $data = $this->core->findPurchase($farm, $id);

        return view('admin.care_livestock.feed_medicine_purchase.edit', compact('farm', 'data'));
    }

    public function update(FeedMedicinePurchaseUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $this->core->updatePurchase($farm, $id, $request->validated());

        return redirect()
            ->route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)
            ->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $this->core->deletePurchase($farm, $id);

        return redirect()
            ->route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)
            ->with('success', 'Data berhasil dihapus.');
    }
}