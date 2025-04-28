<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\LivestockResource;
use App\Services\Qurban\SalesLivestockService;
use App\Http\Resources\Qurban\SalesLivestockResource;
use App\Http\Requests\Qurban\SalesLivestockStoreRequest;
use App\Http\Requests\Qurban\SalesLivestockUpdateRequest;

class SalesLivestockController extends Controller
{
    private $salesLivestockService;

    public function __construct(SalesLivestockService $salesLivestockService)
    {
        $this->salesLivestockService = $salesLivestockService;
    }

    public function availableLivestock(Request $request, $farm_id)
    {
        $livestockAvailable = $this->salesLivestockService->getAvailableLivestock($farm_id);

        // Dapatkan hasil akhir dan koleksi sebagai resource
        $data = LivestockResource::collection($livestockAvailable);

        return ResponseHelper::success($data, 'Livestocks retrieved successfully');
    }

    public function store(SalesLivestockStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        $response =  $this->salesLivestockService->storeSalesLivestock($farm_id, $validated);

        if($response['error']){
            return ResponseHelper::error('Failed to create data', 500);
        }

        return ResponseHelper::success(new SalesLivestockResource($response['data']), 'Data created successfully', 200);
    }

    public function show($farmId , $id)
    {
        $data =  $this->salesLivestockService->getSalesLivestock($farmId, $id);

        return ResponseHelper::success(new SalesLivestockResource($data), 'Data found', 200);
    }


    public function index($farmId, Request $request)
    {
        $data =  $this->salesLivestockService->getSalesLivestocks($farmId, $request);

        return ResponseHelper::success(SalesLivestockResource::collection($data), 'Data found', 200);
    }

    public function update(SalesLivestockUpdateRequest $request, $farm_id, $id)
    {
        $validated = $request->validated();

        $response =  $this->salesLivestockService->updateSalesLivestock($farm_id, $id, $validated);

        if($response['error']){
            return ResponseHelper::error('Failed to update data', 500);
        }

        return ResponseHelper::success(new SalesLivestockResource($response['data']), 'Data updated successfully', 200);
    }

    public function destroy($farm_id, $id)
    {
        $response =  $this->salesLivestockService->deleteSalesLivestock($farm_id, $id);

        if($response['error']){
            return ResponseHelper::error('Failed to delete data', 500);
        }

        return ResponseHelper::success(null, 'Data deleted successfully', 200);
    }

}
