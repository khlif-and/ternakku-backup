<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\FeedingH;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Models\FeedingIndividuD;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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

        DB::transaction(function () use ($validated, $farm, &$feedingIndividu) {

            $feedingH = FeedingH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'inividu',
                'notes'            => $validated['notes'],
            ]);

            $feedingIndividu = $validated;
            $feedingIndividu['feeding_h_id'] = $feedingH->id;

            unset($feedingIndividu['transaction_date']);

            $feedingIndividu = FeedingIndividuD::create($feedingIndividu);
        });

        return ResponseHelper::success(new FeedingIndividuResource($feedingIndividu), 'Data created successfully', \Illuminate\Http\Response::HTTP_CREATED);
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
