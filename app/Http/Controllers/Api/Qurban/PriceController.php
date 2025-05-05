<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\QurbanPrice;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Qurban\PriceService;
use App\Http\Resources\Qurban\PriceResource;
use App\Http\Requests\Qurban\PriceStoreRequest;
use App\Http\Requests\Qurban\PriceUpdateRequest;
use App\Http\Requests\Qurban\EstimationPriceRequest;

class PriceController extends Controller
{
    private $priceService;

    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    public function store(PriceStoreRequest $request, $farm_id)
    {
        $response = $this->priceService->storePrice($farm_id , $request);

        if($response['error']){
            return ResponseHelper::error($response['message'], 400);
        }

        return ResponseHelper::success(new PriceResource($response['data']), 'Price created successfully', 200);
    }

    public function show($farmId, $id)
    {
        $price =  $this->priceService->getPrice($farmId, $id);

        return ResponseHelper::success(new PriceResource($price), 'Price found', 200);
    }

    public function index(Request $request, $farmId)
    {
        $prices = $this->priceService->getPrices($request, $farmId);

        return ResponseHelper::success(PriceResource::collection($prices), 'Prices found', 200);
    }

    public function update(PriceUpdateRequest $request, $farm_id, $id)
    {
        $response = $this->priceService->updatePrice($farm_id , $id, $request);

        if($response['error']){
            return ResponseHelper::error($response['message'], 400);
        }

        return ResponseHelper::success(new PriceResource($response['data']), 'Price updated successfully', 200);
    }

    public function destroy($farm_id, $id)
    {
        $response = $this->priceService->deletePrice($farm_id, $id);

        if($response['error']) {
            return ResponseHelper::error('Failed to delete Price', 500);
        }

        return ResponseHelper::success(null, 'Price deleted successfully', 200);
    }

    public function getEstimationPrice(EstimationPriceRequest $request, $farmId)
    {
        $price = $this->priceService->getEstimationPrice($request, $farmId);

        if($price['error']) {
            return ResponseHelper::error('Failed to get estimation price', 500);
        }

        return ResponseHelper::success(new PriceResource($price['data']), 'Estimation price found', 200);
    }
}
