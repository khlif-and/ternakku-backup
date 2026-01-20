<?php

namespace App\Services\Web\Farming\FeedMedicinePurchase;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FeedMedicinePurchase;
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
        $farm = $request->attributes->get('farm');
        
        $filters = $request->only(['start_date', 'end_date', 'purchase_type']);
        $data = $this->core->listPurchases($farm, $filters);

        return view('admin.care_livestock.feed_medicine_purchase.index', [
            'data' => $data,
            'farm' => $farm,
            'request' => $request,
        ]);
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        return view('admin.care_livestock.feed_medicine_purchase.create', [
            'farm' => $farm,
        ]);
    }

    public function store(FeedMedicinePurchaseStoreRequest $request, $farmId)
    {
        DB::beginTransaction();

        try {
            $farm = $request->attributes->get('farm');
            $this->core->storePurchase($farm, $request->validated());
            
            DB::commit();

            return redirect()
                ->route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)
                ->with('success', 'Data pembelian berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage() . ' | LINE: ' . $e->getLine());
        }
    }

    public function show($farmId, $id, Request $request)
    {
        try {
            $farm = $request->attributes->get('farm');
            $data = $this->core->findPurchase($farm, $id);

            return view('admin.care_livestock.feed_medicine_purchase.show', [
                'data' => $data,
                'farm' => $farm,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function edit($farmId, $id, Request $request)
    {
        try {
            $farm = $request->attributes->get('farm');
            $data = $this->core->findPurchase($farm, $id);

            return view('admin.care_livestock.feed_medicine_purchase.edit', [
                'data' => $data,
                'farm' => $farm,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function update(FeedMedicinePurchaseUpdateRequest $request, $farmId, $id)
    {
        DB::beginTransaction();

        try {
            $farm = $request->attributes->get('farm');
            $this->core->updatePurchase($farm, $id, $request->validated());

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)
                ->with('success', 'Data berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update data.');
        }
    }

    public function destroy($farmId, $id, Request $request)
    {
        DB::beginTransaction();

        try {
            $farm = $request->attributes->get('farm');
            $this->core->deletePurchase($farm, $id);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}
