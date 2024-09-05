<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FeedMedicinePurchaseD;
use App\Models\FeedMedicinePurchaseH;
use App\Http\Resources\Farming\FeedMedicinePurchaseResource;
use App\Http\Requests\Farming\FeedMedicinePurchaseStoreRequest;
use App\Http\Requests\Farming\FeedMedicinePurchaseUpdateRequest;

class FeedMedicinePurchaseController extends Controller
{
    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $data = FeedMedicinePurchaseH::where('farm_id', $farm->id)->get();

        $data = FeedMedicinePurchaseResource::collection($data);

        $message = $data->count() > 0 ? 'The data retrieved successfully' : 'No data found';

        // Return the response using ResponseHelper
        return ResponseHelper::success($data, $message);
    }

    public function store(FeedMedicinePurchaseStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            $feedMedicinePurchaseH = FeedMedicinePurchaseH::create([
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

                FeedMedicinePurchaseD::create([
                    'feed_medicine_purchase_h_id' => $feedMedicinePurchaseH->id,
                    'purchase_type' => $item['purchase_type'],
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'price_per_unit' => $item['price_per_unit'],
                    'total_price' => $totalPrice,
                ]);
            }

            // Update the total_amount in the header record
            $feedMedicinePurchaseH->update([
                'total_amount' => $totalAmount,
            ]);


            DB::commit();

            return ResponseHelper::success(new FeedMedicinePurchaseResource($feedMedicinePurchaseH), 'Data recorded successfully.', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while recording the livestock death.', 500);
        }
    }

    public function show(int $farmId, int $id): JsonResponse
    {
        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            $data = FeedMedicinePurchaseH::where('farm_id', $farm->id)->findOrFail($id);

            return ResponseHelper::success(new FeedMedicinePurchaseResource($data), 'The data retrieved successfully.');

        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while retrieving the data.', 500);
        }
    }

    public function update(FeedMedicinePurchaseUpdateRequest $request, $farmId, $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            // Find the LivestockDeath record
            $feedMedicinePurchaseH = FeedMedicinePurchaseH::where('farm_id', $farm->id)->findOrFail($id);

            // Update the header information
            $feedMedicinePurchaseH->update([
                'transaction_date' => $validated['transaction_date'],
                'supplier' =>  $validated['supplier'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $totalAmount = 0;

            // Hapus semua detail lama sebelum menambahkan yang baru
            $feedMedicinePurchaseH->feedMedicinePurchaseD()->delete();

            // Loop through the items and create detail records
            foreach ($validated['items'] as $item) {
                $totalPrice = $item['quantity'] * $item['price_per_unit'];
                $totalAmount += $totalPrice;

                FeedMedicinePurchaseD::create([
                    'feed_medicine_purchase_h_id' => $feedMedicinePurchaseH->id,
                    'purchase_type' => $item['purchase_type'],
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'price_per_unit' => $item['price_per_unit'],
                    'total_price' => $totalPrice,
                ]);
            }

            // Update the total_amount in the header record
            $feedMedicinePurchaseH->update([
                'total_amount' => $totalAmount,
            ]);

            DB::commit();

            return ResponseHelper::success(new FeedMedicinePurchaseResource($feedMedicinePurchaseH), 'Data updated successfully.', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while updating the livestock death.', 500);
        }
    }

    public function destroy($farmId , $id) : JsonResponse
    {
        DB::beginTransaction();

        try {

            $farm = request()->attributes->get('farm');

            $feedMedicinePurchaseH = FeedMedicinePurchaseH::where('farm_id', $farm->id)->findOrFail($id);

            // Hapus semua detail yang terkait dengan header
            $feedMedicinePurchaseH->feedMedicinePurchaseD()->delete();

            // Hapus header itu sendiri
            $feedMedicinePurchaseH->delete();

            DB::commit();

            return ResponseHelper::success(null, 'Data deleted successfully.', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while deleting the record.', 500);
        }
    }
}
