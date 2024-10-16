<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\TreatmentSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\TreatmentScheduleIndividu;
use App\Http\Resources\Farming\TreatmentScheduleIndividuResource;
use App\Http\Requests\Farming\TreatmentScheduleIndividuStoreRequest;

class TreatmentScheduleIndividuController extends Controller
{
    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $treatmentScheduleIndividu = TreatmentScheduleIndividu::whereHas('treatmentSchedule', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->get();

        $data = TreatmentScheduleIndividuResource::collection($treatmentScheduleIndividu);

        $message = $treatmentScheduleIndividu->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function store(TreatmentScheduleIndividuStoreRequest $request, $farmId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($validated['livestock_id']);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        try {

            DB::beginTransaction();  // Awal transaksional

            $treatmentSchedule = TreatmentSchedule::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'],
            ]);

            $treatmentScheduleIndividu = TreatmentScheduleIndividu::create([
                'treatment_schedule_id' =>  $treatmentSchedule->id,
                'livestock_id' => $validated['livestock_id'],
                'notes' => $validated['notes'] ?? null,
                'medicine_name' => $validated['medicine_name'] ?? null,
                'medicine_unit' => $validated['medicine_unit'] ?? null,
                'medicine_qty_per_unit' => $validated['medicine_qty_per_unit'] ?? null,
                'treatment_name' => $validated['treatment_name'] ?? null,
            ]);


            DB::commit();

            return ResponseHelper::success(new TreatmentScheduleIndividuResource($treatmentScheduleIndividu), 'Data created successfully', 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }
}
