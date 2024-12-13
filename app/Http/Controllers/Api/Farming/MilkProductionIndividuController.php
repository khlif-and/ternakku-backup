<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Models\MilkProductionH;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MilkProductionIndividuD;
use App\Http\Resources\Farming\MilkProductionIndividuResource;
use App\Http\Requests\Farming\MilkProductionIndividuStoreRequest;
use App\Http\Requests\Farming\MilkProductionIndividuUpdateRequest;

class MilkProductionIndividuController extends Controller
{

    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $milkProductionIndividu = MilkProductionIndividuD::whereHas('milkProductionH', function ($query) use ($farm, $request) {
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
            $milkProductionIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $milkProductionIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $milkProductionIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('livestock_sex_id')) {
            $milkProductionIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_sex_id', $request->input('livestock_sex_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $milkProductionIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        if ($request->filled('livestock_id')) {
            $milkProductionIndividu->where('livestock_id', $request->input('livestock_id'));
        }

        $data = MilkProductionIndividuResource::collection($milkProductionIndividu->get());

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
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
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
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
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
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }
}
