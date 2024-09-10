<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Models\MilkProductionH;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MilkProductionIndividuD;
use App\Http\Resources\Farming\MilkProductionIndividuResource;
use App\Http\Requests\Farming\MilkProductionIndividuStoreRequest;
use App\Http\Requests\Farming\MilkProductionIndividuUpdateRequest;

class MilkProductionIndividuController extends Controller
{

    public function index($farmId)
    {
        $farm = request()->attributes->get('farm');

        $milkProductionIndividu = MilkProductionIndividuD::whereHas('milkProductionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->get();

        $data = MilkProductionIndividuResource::collection($milkProductionIndividu);

        $message = $milkProductionIndividu->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function store(MilkProductionIndividuStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->where('livestock_sex_id' , LivestockSexEnum::BETINA->value)->find($validated['livestock_id']);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        try {

            DB::beginTransaction();  // Awal transaksional

            $milkProductionH = MilkProductionH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'],
            ]);

            $milkProductionIndividuD = MilkProductionIndividuD::create([
                'milk_production_h_id' =>  $milkProductionH->id,
                'livestock_id' => $validated['livestock_id'],
                'milking_shift' => $validated['milking_shift'],
                'milking_time' => $validated['milking_time'],
                'milker_name' => $validated['milker_name'],
                'quantity_liters' => $validated['quantity_liters'],
                'milk_condition' => $validated['milk_condition'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return ResponseHelper::success(new MilkProductionIndividuResource($milkProductionIndividuD), 'Data created successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while recording the data.', 500);
        }
    }

    public function show($farmId, $milkProductionIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $milkProductionIndividu = MilkProductionIndividuD::whereHas('milkProductionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($milkProductionIndividuId);

        return ResponseHelper::success(new MilkProductionIndividuResource($milkProductionIndividu), 'Data retrieved successfully');
    }

    public function update(MilkProductionIndividuUpdateRequest $request, $farmId, $milkProductionIndividuId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');
        $milkProductionIndividuD = MilkProductionIndividuD::whereHas('milkProductionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($milkProductionIndividuId);

        $livestock = $farm->livestocks()->where('livestock_sex_id' , LivestockSexEnum::BETINA->value)->find($validated['livestock_id']);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        try {

            DB::beginTransaction();  // Awal transaksional

            $milkProductionH = $milkProductionIndividuD->milkProductionH;

            $milkProductionH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'],
            ]);

            $milkProductionIndividuD->update([
                'livestock_id' => $validated['livestock_id'],
                'milking_shift' => $validated['milking_shift'],
                'milking_time' => $validated['milking_time'],
                'milker_name' => $validated['milker_name'],
                'quantity_liters' => $validated['quantity_liters'],
                'milk_condition' => $validated['milk_condition'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return ResponseHelper::success(new MilkProductionIndividuResource($milkProductionIndividuD), 'Data updated successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while recording the data.', 500);
        }
    }

    public function destroy($farmId, $milkProductionIndividuId)
    {
        $farm = request()->attributes->get('farm');

        try {

            DB::beginTransaction();

            $milkProductionIndividu = MilkProductionIndividuD::whereHas('milkProductionH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id)->where('type' , 'individu');
            })->findOrFail($milkProductionIndividuId);

            $milkProductionH = $milkProductionIndividu->milkProductionH;


            $milkProductionIndividu->delete();

            if ($milkProductionH->milkProductionIndividuD()->count() === 0) {
                $milkProductionH->delete();
            }

            DB::commit();

            return ResponseHelper::success(null, 'The data deleted successfully', 200);

        } catch (\Exception $e) {

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while deleting the data.', 500);
        }
    }
}
