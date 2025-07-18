<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FeedMedicinePurchaseItem;
use App\Models\FeedMedicinePurchase;
use App\Http\Requests\Farming\FeedMedicinePurchaseStoreRequest;
use App\Http\Requests\Farming\FeedMedicinePurchaseUpdateRequest;

class FeedMedicinePurchaseController extends Controller
{
    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $feedMedicinePurchase = FeedMedicinePurchase::where('farm_id', $farm->id);

        if ($request->filled('start_date')) {
            $feedMedicinePurchase->where('transaction_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $feedMedicinePurchase->where('transaction_date', '<=', $request->input('end_date'));
        }

        $data = $feedMedicinePurchase->with('feedMedicinePurchaseItem')->get();


        if ($request->filled('purchase_type')) {
            $purchaseType = $request->input('purchase_type');
            $data = $data->filter(function ($purchase) use ($purchaseType) {
                return $purchase->feedMedicinePurchaseItem()->where('purchase_type', $purchaseType)->exists();
            });
        }

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
            $validated = $request->validated();

            $feedMedicinePurchase = FeedMedicinePurchase::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'supplier' =>  $validated['supplier'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalPrice = $item['quantity'] * $item['price_per_unit'];
                $totalAmount += $totalPrice;

                FeedMedicinePurchaseItem::create([
                    'feed_medicine_purchase_id' => $feedMedicinePurchase->id,
                    'purchase_type' => $item['purchase_type'],
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'price_per_unit' => $item['price_per_unit'],
                    'total_price' => $totalPrice,
                ]);
            }

            $feedMedicinePurchase->update([
                'total_amount' => $totalAmount,
            ]);

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
            $data = FeedMedicinePurchase::where('farm_id', $farm->id)->findOrFail($id);

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
            $data = FeedMedicinePurchase::where('farm_id', $farm->id)->findOrFail($id);

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
            $validated = $request->validated();

            $feedMedicinePurchase = FeedMedicinePurchase::where('farm_id', $farm->id)->findOrFail($id);

            $feedMedicinePurchase->update([
                'transaction_date' => $validated['transaction_date'],
                'supplier' =>  $validated['supplier'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $totalAmount = 0;
            $feedMedicinePurchase->feedMedicinePurchaseItem()->delete();

            foreach ($validated['items'] as $item) {
                $totalPrice = $item['quantity'] * $item['price_per_unit'];
                $totalAmount += $totalPrice;

                FeedMedicinePurchaseItem::create([
                    'feed_medicine_purchase_id' => $feedMedicinePurchase->id,
                    'purchase_type' => $item['purchase_type'],
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'price_per_unit' => $item['price_per_unit'],
                    'total_price' => $totalPrice,
                ]);
            }

            $feedMedicinePurchase->update([
                'total_amount' => $totalAmount,
            ]);

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
            $feedMedicinePurchase = FeedMedicinePurchase::where('farm_id', $farm->id)->findOrFail($id);

            $feedMedicinePurchase->feedMedicinePurchaseItem()->delete();
            $feedMedicinePurchase->delete();

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
