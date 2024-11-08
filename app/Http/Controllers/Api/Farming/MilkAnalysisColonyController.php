<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\MilkAnalysisH;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MilkAnalysisColonyD;
use App\Helpers\ResponseHelper;
use App\Models\LivestockExpense;
use App\Models\MilkAnalysisColonyItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\MilkAnalysisColonyLivestock;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Resources\Farming\MilkAnalysisColonyResource;
use App\Http\Requests\Farming\MilkAnalysisColonyStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisColonyUpdateRequest;

class MilkAnalysisColonyController extends Controller
{
    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $milkAnalysisColony = MilkAnalysisColonyD::whereHas('milkAnalysisH', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id)->where('type' , 'colony');

            // Filter berdasarkan start_date atau end_date dari transaction_number
            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }

            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        if ($request->filled('pen_id')) {
            $milkAnalysisColony->where('pen_id', $request->input('pen_id'));
        }

        $data = MilkAnalysisColonyResource::collection($milkAnalysisColony->get());

        $message = $milkAnalysisColony->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function store(MilkAnalysisColonyStoreRequest $request, $farmId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $pen = $farm->pens()->find($validated['pen_id']);

        if (!$pen) {
            return ResponseHelper::error('Pen not found.', 404);
        }

        $livestockLactations = $pen->livestockLactations();

        $totalLivestockLactations = count($livestockLactations);

        if ($totalLivestockLactations < 1) {
            return ResponseHelper::error('No lactating livestock found in this pen.', 404);
        }

        try {

            DB::beginTransaction();  // Awal transaksional

            $milkAnalysisH = MilkAnalysisH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'colony',
                'notes'            => $validated['notes'],
            ]);

            $milkAnalysisColonyD = MilkAnalysisColonyD::create([
                'milk_Analysis_h_id' =>  $milkAnalysisH->id,
                'pen_id' => $validated['pen_id'],
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
                'total_livestock' => $totalLivestockLactations,
            ]);

            foreach($livestockLactations as $livestock){
                MilkAnalysisColonyLivestock::create([
                    'milk_Analysis_colony_d_id' => $milkAnalysisColonyD->id,
                    'livestock_id' => $livestock->id
                ]);
            }

            DB::commit();

            return ResponseHelper::success(new MilkAnalysisColonyResource($milkAnalysisColonyD), 'Data created successfully', 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function show($farmId, $milkAnalysisColonyId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $milkAnalysisColony = MilkAnalysisColonyD::whereHas('milkAnalysisH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'colony');
        })->findOrFail($milkAnalysisColonyId);

        return ResponseHelper::success(new MilkAnalysisColonyResource($milkAnalysisColony), 'Data retrieved successfully');
    }

    public function update(MilkAnalysisColonyUpdateRequest $request, $farmId , $milkAnalysisColonyId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $milkAnalysisColonyD = MilkAnalysisColonyD::whereHas('milkAnalysisH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'colony');
        })->findOrFail($milkAnalysisColonyId);

        try {
            DB::beginTransaction();  // Awal transaksional

            $milkAnalysisH = $milkAnalysisColonyD->MilkAnalysisH;

            $milkAnalysisH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $milkAnalysisColonyD->update([
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
                'quantity_liters' => $validated['quantity_liters'] ,
                'average_liters' => $validated['quantity_liters']  / $milkAnalysisColonyD->total_livestock,
            ]);

            DB::commit();

            return ResponseHelper::success(new MilkAnalysisColonyResource($milkAnalysisColonyD), 'Data updated successfully');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the data.', 500);
        }
    }

    public function destroy($farmId, $milkAnalysisColonyId)
    {
        $farm = request()->attributes->get('farm');

        // Cari MilkAnalysisColonyD dengan memastikan farm dan tipe Colony
        $milkAnalysisColonyD = MilkAnalysisColonyD::whereHas('milkAnalysisH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($milkAnalysisColonyId);

        try {
            DB::beginTransaction();  // Awal transaksional

            MilkAnalysisColonyLivestock::where('milk_Analysis_colony_d_id', $milkAnalysisColonyD->id)->delete();

            $milkAnalysisColonyD->delete();

            $milkAnalysisH = $milkAnalysisColonyD->MilkAnalysisH;
            if (!$milkAnalysisH->milkAnalysisColonyD()->exists()) {
                $milkAnalysisH->delete();
            }

            DB::commit();  // Commit transaksi jika semua proses berhasil

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Exception $e) {

            DB::rollBack();  // Rollback jika ada kesalahan

            // Log error untuk debugging (opsional)
            Log::error('Delete MilkAnalysisColony Error: ', ['error' => $e->getMessage()]);

            // Handle exceptions dan kembalikan respon error
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }

}
