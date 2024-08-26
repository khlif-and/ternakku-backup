<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Models\LivestockDeath;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Enums\LivestockStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\LivestockDeathResource;
use App\Http\Requests\Farming\LivestockDeathUpdateRequest;

class LivestockDeathController extends Controller
{
    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        // Fetch LivestockDeath records associated with the farm
        $data = LivestockDeath::where('farm_id', $farm->id)->get();

        // Transform the data using LivestockReceptionResource (you should create a specific resource if needed)
        $data = LivestockDeathResource::collection($data);

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

            // Create a new LivestockDeath record
            $livestockDeath = LivestockDeath::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'livestock_id' => $validated['livestock_id'],
                'diagnosis' => $validated['diagnosis'] ?? null,
                'indication' => $validated['indication'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update the livestock status to 2 (e.g., deceased)
            $livestock = Livestock::find($validated['livestock_id']);
            $livestock->livestock_status_id = LivestockStatusEnum::MATI->value;
            $livestock->save();

            DB::commit();

            // Return the created resource using LivestockDeathResource
            return ResponseHelper::success(new LivestockDeathResource($livestockDeath), 'Livestock death recorded successfully.', 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while recording the livestock death.', 500);
        }
    }

    public function update(LivestockDeathUpdateRequest $request, $farmId, $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            // Find the LivestockDeath record
            $livestockDeath = LivestockDeath::where('farm_id', $farm->id)->findOrFail($id);

            // Check if livestock_id has changed
            $oldLivestockId = $livestockDeath->livestock_id;

            // Update the LivestockDeath record
            $livestockDeath->update([
                'transaction_date' => $validated['transaction_date'],
                'livestock_id' => $validated['livestock_id'],
                'diagnosis' => $validated['diagnosis'] ?? null,
                'indication' => $validated['indication'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update the old livestock status to deceased if necessary
            if ($oldLivestockId && $oldLivestockId != $validated['livestock_id']) {
                $oldLivestock = Livestock::find($oldLivestockId);
                if ($oldLivestock && $oldLivestock->livestock_status_id != LivestockStatusEnum::MATI->value) {
                    $oldLivestock->livestock_status_id = LivestockStatusEnum::MATI->value;
                    $oldLivestock->save();
                }
            }

            // Update the new livestock status to active if necessary
            $newLivestock = Livestock::find($validated['livestock_id']);
            if ($newLivestock && $newLivestock->livestock_status_id != LivestockStatusEnum::HIDUP->value) {
                $newLivestock->livestock_status_id = LivestockStatusEnum::HIDUP->value;
                $newLivestock->save();
            }

            DB::commit();

            // Return the updated resource using LivestockDeathResource
            return ResponseHelper::success(new LivestockDeathResource($livestockDeath), 'Livestock death updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while updating the livestock death.', 500);
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
            return ResponseHelper::error(null, 'An error occurred while deleting the livestock death record.', 500);
        }
    }

}
