<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\FeedingH;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Models\FeedingIndividuD;
use App\Models\LivestockExpense;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Resources\Farming\FeedingIndividuResource;
use App\Http\Requests\Farming\FeedingIndividuStoreRequest;
use App\Http\Requests\Farming\FeedingIndividuUpdateRequest;

class FeedingIndividuController extends Controller
{
    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $feedingIndividu = FeedingIndividuD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->get();

        $data = FeedingIndividuResource::collection($feedingIndividu);

        $message = $feedingIndividu->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function store(FeedingIndividuStoreRequest $request, $farmId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $feedingIndividu = null;

        $livestock = $farm->livestocks()->find($validated['livestock_id']);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        DB::transaction(function () use ($validated, $farm, &$feedingIndividu) {

            $feedingH = FeedingH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'],
            ]);

            $feedingIndividu['feeding_h_id'] = $feedingH->id;

            $forage_total = $validated['forage_qty_kg'] * $validated['forage_price_kg'];
            $concentrate_total = $validated['concentrate_qty_kg'] * $validated['concentrate_price_kg'];
            $feed_material_total = $validated['feed_material_qty_kg'] * $validated['feed_material_price_kg'];
            $total_cost = $forage_total + $concentrate_total + $feed_material_total;


            // $feedingIndividu = FeedingIndividuD::create([
            //     'feeding_h_id' =>  $feedingH->id,
            //     'livestock_id' =>
            // ]);

            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();
        });

        return ResponseHelper::success(new FeedingIndividuResource($feedingIndividu), 'Data created successfully', 200);
    }

    public function show($farmId, $feedingIndividuId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $feedingIndividu = FeedingIndividuD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($feedingIndividuId);

        return ResponseHelper::success(new FeedingIndividuResource($feedingIndividu), 'Data retrieved successfully');
    }

    public function update(FeedingIndividuUpdateRequest $request, $farmId , $feedingIndividuId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');
        $feedingIndividu = FeedingIndividuD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($feedingIndividuId);

        DB::transaction(function () use ($validated, $feedingIndividu, $farm) {

            $feedingH = $feedingIndividu->FeedingH;

            $feedingH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'],
            ]);

            $feedingIndividuData = $validated;
            $feedingIndividuData['feeding_h_id'] = $feedingH->id;

            unset($feedingIndividuData['transaction_date']);

            $feedingIndividu->update($feedingIndividuData);
        });

        return ResponseHelper::success(new FeedingIndividuResource($feedingIndividu), 'Data updated successfully');
    }

    public function destroy($farmId, $feedingIndividuId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        return DB::transaction(function () use ($feedingIndividuId, $farm) {

            $feedingIndividu = FeedingIndividuD::whereHas('feedingH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id)->where('type' , 'individu');
            })->findOrFail($feedingIndividuId);

            $feedingH = $feedingIndividu->FeedingH;

            $feedingIndividu->delete();

            if ($feedingH->feedingIndividuD()->count() === 0) {
                $feedingH->delete();
            }

            return ResponseHelper::success(null, 'Data deleted successfully', 200);
        });
    }
}
