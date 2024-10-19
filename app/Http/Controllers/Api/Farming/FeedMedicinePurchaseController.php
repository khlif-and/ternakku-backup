<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FeedMedicinePurchaseItem;
use App\Models\FeedMedicinePurchase;
use App\Http\Resources\Farming\FeedMedicinePurchaseResource;
use App\Http\Requests\Farming\FeedMedicinePurchaseStoreRequest;
use App\Http\Requests\Farming\FeedMedicinePurchaseUpdateRequest;

class FeedMedicinePurchaseController extends Controller
{
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $feedMedicinePurchase = FeedMedicinePurchase::where('farm_id', $farm->id);

        // Filter berdasarkan start_date atau end_date dari transaction_number
        if ($request->filled('start_date')) {
            $feedMedicinePurchase->where('transaction_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $feedMedicinePurchase->where('transaction_date', '<=', $request->input('end_date'));
        }

        $data = FeedMedicinePurchaseResource::collection($feedMedicinePurchase->get());

        $message = $data->count() > 0 ? 'The data retrieved successfully' : 'No data found';

        // Return the response using ResponseHelper
        return ResponseHelper::success($data, $message);
    }

    public function store(FeedMedicinePurchaseStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            $feedMedicinePurchase = FeedMedicinePurchase::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'supplier' =>  $validated['supplier'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $totalAmount = 0;

            // Loop through the items and create detail records
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

            // Update the total_amount in the header record
            $feedMedicinePurchase->update([
                'total_amount' => $totalAmount,
            ]);


            DB::commit();

            return ResponseHelper::success(new FeedMedicinePurchaseResource($feedMedicinePurchase), 'Data recorded successfully.', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the livestock death.', 500);
        }
    }

    public function show(int $farmId, int $id)
    {
        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            $data = FeedMedicinePurchase::where('farm_id', $farm->id)->findOrFail($id);

            return ResponseHelper::success(new FeedMedicinePurchaseResource($data), 'The data retrieved successfully.');

        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while retrieving the data.', 500);
        }
    }

    public function update(FeedMedicinePurchaseUpdateRequest $request, $farmId, $id)
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            // Find the LivestockDeath record
            $feedMedicinePurchase = FeedMedicinePurchase::where('farm_id', $farm->id)->findOrFail($id);

            // Update the header information
            $feedMedicinePurchase->update([
                'transaction_date' => $validated['transaction_date'],
                'supplier' =>  $validated['supplier'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $totalAmount = 0;

            // Hapus semua detail lama sebelum menambahkan yang baru
            $feedMedicinePurchase->feedMedicinePurchaseItem()->delete();

            // Loop through the items and create detail records
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

            // Update the total_amount in the header record
            $feedMedicinePurchase->update([
                'total_amount' => $totalAmount,
            ]);

            DB::commit();

            return ResponseHelper::success(new FeedMedicinePurchaseResource($feedMedicinePurchase), 'Data updated successfully.', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the livestock death.', 500);
        }
    }

    public function destroy($farmId , $id) : JsonResponse
    {
        DB::beginTransaction();

        try {

            $farm = request()->attributes->get('farm');

            $feedMedicinePurchase = FeedMedicinePurchase::where('farm_id', $farm->id)->findOrFail($id);

            // Hapus semua detail yang terkait dengan header
            $feedMedicinePurchase->feedMedicinePurchaseItem()->delete();

            // Hapus header itu sendiri
            $feedMedicinePurchase->delete();

            DB::commit();

            return ResponseHelper::success(null, 'Data deleted successfully.', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while deleting the record.', 500);
        }
    }
}
