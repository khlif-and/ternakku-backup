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

class MilkAnalysisGlobalController extends Controller
{
    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $data = MilkAnalysisGlobal::where('farm_id', $farm->id)->get();

        $data = MilkAnalysisGlobalResource::collection($data);

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
            return ResponseHelper::error(null, 'An error occurred while recording the data.', 500);
        }
    }
}
