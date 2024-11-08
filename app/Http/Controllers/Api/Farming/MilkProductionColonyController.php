<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\MilkProductionH;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MilkProductionColonyD;
use App\Helpers\ResponseHelper;
use App\Models\LivestockExpense;
use App\Models\MilkProductionColonyItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\MilkProductionColonyLivestock;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Resources\Farming\MilkProductionColonyResource;
use App\Http\Requests\Farming\MilkProductionColonyStoreRequest;
use App\Http\Requests\Farming\MilkProductionColonyUpdateRequest;

class MilkProductionColonyController extends Controller
{
    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $milkProductionColony = MilkProductionColonyD::whereHas('milkProductionH', function ($query) use ($farm, $request) {
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
            $milkProductionColony->where('pen_id', $request->input('pen_id'));
        }

        $data = MilkProductionColonyResource::collection($milkProductionColony->get());

        $message = $milkProductionColony->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function store(MilkProductionColonyStoreRequest $request, $farmId): JsonResponse
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

            $milkProductionH = MilkProductionH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'colony',
                'notes'            => $validated['notes'],
            ]);

            $milkProductionColonyD = MilkProductionColonyD::create([
                'milk_production_h_id' =>  $milkProductionH->id,
                'pen_id' => $validated['pen_id'],
                'milking_shift' => $validated['milking_shift'],
                'milking_time' => $validated['milking_time'],
                'milker_name' => $validated['milker_name'],
                'milk_condition' => $validated['milk_condition'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'total_livestock' => $totalLivestockLactations,
                'quantity_liters' => $validated['quantity_liters'] ,
                'average_liters' => $validated['quantity_liters']  / $totalLivestockLactations,
            ]);

            foreach($livestockLactations as $livestock){
                MilkProductionColonyLivestock::create([
                    'milk_production_colony_d_id' => $milkProductionColonyD->id,
                    'livestock_id' => $livestock->id
                ]);
            }

            DB::commit();

            return ResponseHelper::success(new MilkProductionColonyResource($milkProductionColonyD), 'Data created successfully', 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function show($farmId, $milkProductionColonyId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $milkProductionColony = MilkProductionColonyD::whereHas('milkProductionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'colony');
        })->findOrFail($milkProductionColonyId);

        return ResponseHelper::success(new MilkProductionColonyResource($milkProductionColony), 'Data retrieved successfully');
    }

    public function update(MilkProductionColonyUpdateRequest $request, $farmId , $milkProductionColonyId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $milkProductionColonyD = MilkProductionColonyD::whereHas('milkProductionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'colony');
        })->findOrFail($milkProductionColonyId);

        try {
            DB::beginTransaction();  // Awal transaksional

            $milkProductionH = $milkProductionColonyD->MilkProductionH;

            $milkProductionH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestocks =  $milkProductionColonyD->livestocks;

            $totalLivestocks = count($livestocks);

            MilkProductionColonyItem::where('milk_production_colony_d_id', $milkProductionColonyD->id)->delete();

            $milkProductionColonyD->update([
                'notes' => $validated['notes'] ?? null,
                'quantity_liters' => $validated['quantity_liters'] ,
                'average_liters' => $validated['quantity_liters']  / $totalLivestocks,
            ]);

            DB::commit();

            return ResponseHelper::success(new MilkProductionColonyResource($milkProductionColonyD), 'Data updated successfully');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the data.', 500);
        }
    }

    public function destroy($farmId, $milkProductionColonyId)
    {
        $farm = request()->attributes->get('farm');

        // Cari MilkProductionColonyD dengan memastikan farm dan tipe Colony
        $milkProductionColonyD = MilkProductionColonyD::whereHas('milkProductionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($milkProductionColonyId);

        try {
            DB::beginTransaction();  // Awal transaksional

            MilkProductionColonyLivestock::where('milk_production_colony_d_id', $milkProductionColonyD->id)->delete();

            $milkProductionColonyD->delete();

            $milkProductionH = $milkProductionColonyD->MilkProductionH;
            if (!$milkProductionH->milkProductionColonyD()->exists()) {
                $milkProductionH->delete();
            }

            DB::commit();  // Commit transaksi jika semua proses berhasil

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Exception $e) {

            DB::rollBack();  // Rollback jika ada kesalahan

            // Log error untuk debugging (opsional)
            Log::error('Delete MilkProductionColony Error: ', ['error' => $e->getMessage()]);

            // Handle exceptions dan kembalikan respon error
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }

}
