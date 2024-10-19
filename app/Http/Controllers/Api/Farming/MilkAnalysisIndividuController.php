<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Models\MilkAnalysisH;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MilkAnalysisIndividuD;
use App\Http\Resources\Farming\MilkAnalysisIndividuResource;
use App\Http\Requests\Farming\MilkAnalysisIndividuStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisIndividuUpdateRequest;

class MilkAnalysisIndividuController extends Controller
{

    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $milkAnalysisIndividu = MilkAnalysisIndividuD::whereHas('milkAnalysisH', function ($query) use ($farm, $request) {
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
            $milkAnalysisIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $milkAnalysisIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $milkAnalysisIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('livestock_sex_id')) {
            $milkAnalysisIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_sex_id', $request->input('livestock_sex_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $milkAnalysisIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        $data = MilkAnalysisIndividuResource::collection($milkAnalysisIndividu->get());

        $message = $milkAnalysisIndividu->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function store(MilkAnalysisIndividuStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');
        $livestock = $farm->livestocks()->where('livestock_sex_id' , LivestockSexEnum::BETINA->value)->find($validated['livestock_id']);


        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        try {

            DB::beginTransaction();  // Awal transaksional

            $milkAnalysisH = MilkAnalysisH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'],
            ]);

            $milkAnalysisIndividuD = MilkAnalysisIndividuD::create([
                'milk_analysis_h_id' =>  $milkAnalysisH->id,
                'livestock_id' => $validated['livestock_id'],
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

            return ResponseHelper::success(new MilkAnalysisIndividuResource($milkAnalysisIndividuD), 'Data created successfully', 200);

        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function show($farmId, $milkAnalysisIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $milkAnalysisIndividu = MilkAnalysisIndividuD::whereHas('milkAnalysisH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($milkAnalysisIndividuId);

        return ResponseHelper::success(new MilkAnalysisIndividuResource($milkAnalysisIndividu), 'Data retrieved successfully');
    }

    public function update(MilkAnalysisIndividuUpdateRequest $request, $farmId, $milkAnalysisIndividuId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');
        $milkAnalysisIndividuD = MilkAnalysisIndividuD::whereHas('milkAnalysisH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($milkAnalysisIndividuId);

        $livestock = $farm->livestocks()->where('livestock_sex_id' , LivestockSexEnum::BETINA->value)->find($validated['livestock_id']);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        try {

            DB::beginTransaction();  // Awal transaksional

            $milkAnalysisH = $milkAnalysisIndividuD->milkAnalysisH;

            $milkAnalysisH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'],
            ]);

            $milkAnalysisIndividuD->update([
                'livestock_id' => $validated['livestock_id'],
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

            return ResponseHelper::success(new MilkAnalysisIndividuResource($milkAnalysisIndividuD), 'Data updated successfully', 200);

        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function destroy($farmId, $milkAnalysisIndividuId)
    {
        $farm = request()->attributes->get('farm');

        try {

            DB::beginTransaction();

            $milkAnalysisIndividu = MilkAnalysisIndividuD::whereHas('milkAnalysisH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id)->where('type' , 'individu');
            })->findOrFail($milkAnalysisIndividuId);

            $milkAnalysisH = $milkAnalysisIndividu->milkAnalysisH;


            $milkAnalysisIndividu->delete();

            if ($milkAnalysisH->milkAnalysisIndividuD()->count() === 0) {
                $milkAnalysisH->delete();
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
