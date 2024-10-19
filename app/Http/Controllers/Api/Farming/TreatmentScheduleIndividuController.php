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
use App\Http\Requests\Farming\TreatmentScheduleIndividuUpdateRequest;

class TreatmentScheduleIndividuController extends Controller
{
    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $treatmentScheduleIndividu = TreatmentScheduleIndividu::whereHas('treatmentSchedule', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');

            // Filter berdasarkan start_date atau end_date dari transaction_number
            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }

            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        // Filter berdasarkan relasi Livestock (misalnya livestock_type_id)
        if ($request->filled('livestock_type_id')) {
            $treatmentScheduleIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $treatmentScheduleIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $treatmentScheduleIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('livestock_sex_id')) {
            $treatmentScheduleIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_sex_id', $request->input('livestock_sex_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $treatmentScheduleIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        $data = TreatmentScheduleIndividuResource::collection($treatmentScheduleIndividu->get());

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

    public function show($farmId, $treatmentScheduleIndividuId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $treatmentScheduleIndividu = TreatmentScheduleIndividu::whereHas('treatmentSchedule', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($treatmentScheduleIndividuId);

        return ResponseHelper::success(new TreatmentScheduleIndividuResource($treatmentScheduleIndividu), 'Data retrieved successfully');
    }

    public function update(TreatmentScheduleIndividuUpdateRequest $request, $farmId , $treatmentScheduleIndividuId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $treatmentScheduleIndividu = TreatmentScheduleIndividu::whereHas('treatmentSchedule', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($treatmentScheduleIndividuId);

        try {

            DB::beginTransaction();  // Awal transaksional

            $treatmentSchedule = $treatmentScheduleIndividu->treatmentSchedule;

            $treatmentSchedule->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $treatmentScheduleIndividu->update([
                'livestock_id' => $validated['livestock_id'],
                'livestock_id' => $validated['livestock_id'],
                'notes' => $validated['notes'] ?? null,
                'medicine_name' => $validated['medicine_name'] ?? null,
                'medicine_unit' => $validated['medicine_unit'] ?? null,
                'medicine_qty_per_unit' => $validated['medicine_qty_per_unit'] ?? null,
                'treatment_name' => $validated['treatment_name'] ?? null,
            ]);

            DB::commit();

            return ResponseHelper::success(new TreatmentScheduleIndividuResource($treatmentScheduleIndividu), 'Data updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while uodating the data.', 500);
        }
    }

    public function destroy($farmId, $treatmentScheduleIndividuId)
    {
        $farm = request()->attributes->get('farm');

        // Cari treatmentScheduleIndividu dengan memastikan farm dan tipe individu
        $treatmentScheduleIndividu = TreatmentScheduleIndividu::whereHas('treatmentSchedule', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'individu');
        })->findOrFail($treatmentScheduleIndividuId);

        try {
            DB::beginTransaction();

            $treatmentScheduleIndividu->delete();

            $treatmentSchedule = $treatmentScheduleIndividu->treatmentSchedule;

            if (!$treatmentSchedule->treatmentScheduleIndividu()->exists()) {
                $treatmentSchedule->delete();
            }

            DB::commit();

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();  // Rollback jika ada kesalahan

            // Log error untuk debugging (opsional)
            Log::error('Delete treatmentScheduleIndividu Error: ', ['error' => $e->getMessage()]);

            // Handle exceptions dan kembalikan respon error
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }
}
