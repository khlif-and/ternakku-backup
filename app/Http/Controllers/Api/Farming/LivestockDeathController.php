<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Models\LivestockDeath;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\LivestockDeathResource;
use App\Http\Requests\Farming\LivestockDeathStoreRequest;
use App\Http\Requests\Farming\LivestockDeathUpdateRequest;

class LivestockDeathController extends Controller
{
    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        // Fetch LivestockDeath records associated with the farm
        $deaths = LivestockDeath::where('farm_id', $farm->id);

        // Filter berdasarkan start_date atau end_date dari transaction_number
        if ($request->filled('start_date')) {
            $deaths->where('transaction_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $deaths->where('transaction_date', '<=', $request->input('end_date'));
        }

        // Filter berdasarkan relasi Livestock (misalnya livestock_type_id)
        if ($request->filled('livestock_type_id')) {
            $deaths->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $deaths->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $deaths->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('livestock_sex_id')) {
            $deaths->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_sex_id', $request->input('livestock_sex_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $deaths->whereHas('livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        // Transform the data using LivestockReceptionResource (you should create a specific resource if needed)
        $data = LivestockDeathResource::collection($deaths->get());


        // Determine the message based on the data count
        $message = $data->count() > 0 ? 'Livestock deaths retrieved successfully' : 'No livestock deaths found';

        // Return the response using ResponseHelper
        return ResponseHelper::success($data, $message);
    }

    public function store(LivestockDeathStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            // Find the livestock record
            $livestock = Livestock::find($validated['livestock_id']);

            // Check if the livestock exists
            if (!$livestock) {
                return ResponseHelper::error('Livestock not found.', 404);
            }

            // Check if the livestock is already deceased
            if ($livestock->livestock_status_id !== LivestockStatusEnum::HIDUP->value) {
                return ResponseHelper::error('This livestock not found', 404);
            }

            // Create a new LivestockDeath record
            $livestockDeath = LivestockDeath::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'livestock_id' => $validated['livestock_id'],
                'disease_id' => $validated['disease_id'] ?? null,
                'indication' => $validated['indication'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update the livestock status to 2 (e.g., deceased)
            $livestock->livestock_status_id = LivestockStatusEnum::MATI->value;
            $livestock->save();

            DB::commit();

            // Return the created resource using LivestockDeathResource
            return ResponseHelper::success(new LivestockDeathResource($livestockDeath), 'Livestock death recorded successfully.', 200);

        } catch (\Exception $e) {

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    public function show(int $farmId, int $id): JsonResponse
    {
        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Find the LivestockDeath record
            $livestockDeath = LivestockDeath::where('farm_id', $farm->id)->findOrFail($id);

            // Return the detail using LivestockDeathResource
            return ResponseHelper::success(new LivestockDeathResource($livestockDeath), 'Livestock death record retrieved successfully.');

        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while retrieving the livestock death record.', 500);
        }
    }


    public function update(LivestockDeathUpdateRequest $request, $farmId, $id)
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            // Find the LivestockDeath record
            $livestockDeath = LivestockDeath::where('farm_id', $farm->id)->findOrFail($id);

            // Get old livestock ID
            $oldLivestockId = $livestockDeath->livestock_id;

            // Update the LivestockDeath record
            $livestockDeath->update([
                'transaction_date' => $validated['transaction_date'],
                'livestock_id' => $validated['livestock_id'],
                'disease_id' => $validated['disease_id'] ?? null,
                'indication' => $validated['indication'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update the old livestock status if the livestock_id has changed
            if ($oldLivestockId && $oldLivestockId != $validated['livestock_id']) {
                $oldLivestock = Livestock::find($oldLivestockId);
                if ($oldLivestock && $oldLivestock->livestock_status_id === LivestockStatusEnum::MATI->value) {
                    // Change status to alive if it was previously marked as dead
                    $oldLivestock->livestock_status_id = LivestockStatusEnum::HIDUP->value;
                    $oldLivestock->save();
                }
            }

            // Update the new livestock status to deceased if necessary
            $newLivestock = Livestock::find($validated['livestock_id']);
            if ($newLivestock && $newLivestock->livestock_status_id !== LivestockStatusEnum::MATI->value) {
                $newLivestock->livestock_status_id = LivestockStatusEnum::MATI->value;
                $newLivestock->save();
            }

            DB::commit();

            // Return the updated resource using LivestockDeathResource
            return ResponseHelper::success(new LivestockDeathResource($livestockDeath), 'Livestock death updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the livestock death.', 500);
        }
    }


    public function destroy(int $farmId, int $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Find the LivestockDeath record
            $livestockDeath = LivestockDeath::where('farm_id', $farm->id)->findOrFail($id);

            // Retrieve the associated livestock
            $livestock = Livestock::find($livestockDeath->livestock_id);

            // Delete the LivestockDeath record
            $livestockDeath->delete();

            // Update the livestock status to active (e.g., living) if necessary
            if ($livestock) {
                $livestock->livestock_status_id = LivestockStatusEnum::HIDUP->value;
                $livestock->save();
            }

            DB::commit();

            // Return a success response
            return ResponseHelper::success(null, 'Livestock death record deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while deleting the livestock death record.', 500);
        }
    }

}
