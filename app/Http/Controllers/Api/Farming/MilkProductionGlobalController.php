<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MilkProductionGlobal;
use App\Http\Resources\Farming\MilkProductionGlobalResource;
use App\Http\Requests\Farming\MilkProductionGlobalStoreRequest;
use App\Http\Requests\Farming\MilkProductionGlobalUpdateRequest;

class MilkProductionGlobalController extends Controller
{
    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $data = MilkProductionGlobal::where('farm_id', $farm->id)->get();

        $data = MilkProductionGlobalResource::collection($data);

        $message = $data->count() > 0 ? 'Data retrieved successfully' : 'No data found';

        // Return the response using ResponseHelper
        return ResponseHelper::success($data, $message);
    }

    public function store(MilkProductionGlobalStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            $milkProductionGlobal = MilkProductionGlobal::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'milking_shift' => $validated['milking_shift'],
                'milking_time' => $validated['milking_time'],
                'milker_name' => $validated['milker_name'],
                'quantity_liters' => $validated['quantity_liters'],
                'milk_condition' => $validated['milk_condition'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return ResponseHelper::success(new MilkProductionGlobalResource($milkProductionGlobal), 'The data recorded successfully.', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while recording the data.', 500);
        }
    }

    public function show(int $farmId, int $id): JsonResponse
    {
        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            $livestockDeath = MilkProductionGlobal::where('farm_id', $farm->id)->findOrFail($id);

            return ResponseHelper::success(new MilkProductionGlobalResource($livestockDeath), 'Data retrieved successfully.');

        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while retrieving the data.', 500);
        }
    }

    public function update(MilkProductionGlobalUpdateRequest $request, $farmId, $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            $data = MilkProductionGlobal::where('farm_id', $farm->id)->findOrFail($id);

            $data->update([
                'transaction_date' => $validated['transaction_date'],
                'milking_shift' => $validated['milking_shift'],
                'milking_time' => $validated['milking_time'],
                'milker_name' => $validated['milker_name'],
                'quantity_liters' => $validated['quantity_liters'],
                'milk_condition' => $validated['milk_condition'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);

            DB::commit();

            // Return the updated resource using LivestockDeathResource
            return ResponseHelper::success(new MilkProductionGlobalResource($data), 'Data updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while updating the data.', 500);
        }
    }


    public function destroy(int $farmId, int $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            $data = MilkProductionGlobal::where('farm_id', $farm->id)->findOrFail($id);

            $data->delete();

            DB::commit();

            // Return a success response
            return ResponseHelper::success(null, 'Data deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while deleting the data.', 500);
        }
    }
}
