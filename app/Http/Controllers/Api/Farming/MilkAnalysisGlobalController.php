<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Models\MilkAnalysisGlobal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\MilkAnalysisGlobalResource;
use App\Http\Requests\Farming\MilkAnalysisGlobalStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisGlobalUpdateRequest;

class MilkAnalysisGlobalController extends Controller
{
    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $milkAnalysisGlobal = MilkAnalysisGlobal::where('farm_id', $farm->id);

        // Filter berdasarkan start_date atau end_date dari transaction_number
        if ($request->filled('start_date')) {
            $milkAnalysisGlobal->where('transaction_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $milkAnalysisGlobal->where('transaction_date', '<=', $request->input('end_date'));
        }

        $data = MilkAnalysisGlobalResource::collection($milkAnalysisGlobal->get());

        $message = $data->count() > 0 ? 'Data retrieved successfully' : 'No data found';

        // Return the response using ResponseHelper
        return ResponseHelper::success($data, $message);
    }

    public function store(MilkAnalysisGlobalStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            $milkAnalysisGlobal = MilkAnalysisGlobal::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'bj' => $validated['bj'] ?? null,
                'at' => $validated['at'] ?? null,
                'ab' => $validated['ab'] ?? null,
                'mbrt' => $validated['mbrt'] ?? null,
                'a_water' => $validated['a_water'] ?? null,
                'protein' => $validated['protein'] ?? null,
                'fat' => $validated['fat'] ?? null,
                'snf' => $validated['snf'] ?? null,
                'ts' => $validated['ts'] ?? null,
                'rzn' => $validated['rzn'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return ResponseHelper::success(new MilkAnalysisGlobalResource($milkAnalysisGlobal), 'The data recorded successfully.', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function show(int $farmId, int $id): JsonResponse
    {
        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            $livestockDeath = MilkAnalysisGlobal::where('farm_id', $farm->id)->findOrFail($id);

            return ResponseHelper::success(new MilkAnalysisGlobalResource($livestockDeath), 'Data retrieved successfully.');

        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while retrieving the data.', 500);
        }
    }

    public function update(MilkAnalysisGlobalUpdateRequest $request, $farmId, $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            // Retrieve the validated input data
            $validated = $request->validated();

            $data = MilkAnalysisGlobal::where('farm_id', $farm->id)->findOrFail($id);

            $data->update([
                'transaction_date' => $validated['transaction_date'],
                'bj' => $validated['bj'] ?? null,
                'at' => $validated['at'] ?? null,
                'ab' => $validated['ab'] ?? null,
                'mbrt' => $validated['mbrt'] ?? null,
                'a_water' => $validated['a_water'] ?? null,
                'protein' => $validated['protein'] ?? null,
                'fat' => $validated['fat'] ?? null,
                'snf' => $validated['snf'] ?? null,
                'ts' => $validated['ts'] ?? null,
                'rzn' => $validated['rzn'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);

            DB::commit();

            // Return the updated resource using LivestockDeathResource
            return ResponseHelper::success(new MilkAnalysisGlobalResource($data), 'Data updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the data.', 500);
        }
    }


    public function destroy(int $farmId, int $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Get the farm from request attributes
            $farm = request()->attributes->get('farm');

            $data = MilkAnalysisGlobal::where('farm_id', $farm->id)->findOrFail($id);

            $data->delete();

            DB::commit();

            // Return a success response
            return ResponseHelper::success(null, 'Data deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }
}
