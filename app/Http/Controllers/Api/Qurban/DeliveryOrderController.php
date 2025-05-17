<?php

namespace App\Http\Controllers\Api\Qurban;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\Qurban\DeliveryOrderService;
use App\Http\Resources\Qurban\DeliveryOrderResource;
use App\Http\Requests\Qurban\DeliveryScheduleRequest;
use App\Http\Requests\Qurban\DeliveryOrderStoreRequest;

class DeliveryOrderController extends Controller
{
    private $deliveryOrderService;

    public function __construct(DeliveryOrderService $deliveryOrderService)
    {
        $this->deliveryOrderService = $deliveryOrderService;
    }

    public function store(DeliveryOrderStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        $response =  $this->deliveryOrderService->storeDeliveryOrder($farm_id, $validated);

        if($response['error']){
            return ResponseHelper::error('Failed to create data', 500);
        }

        return ResponseHelper::success(DeliveryOrderResource::collection($response['data']), 'Data created successfully', 200);
    }

    public function index(Request $request, $farm_id)
    {
        $params = $request->all();

        $data = $this->deliveryOrderService->getDeliveryOrders($farm_id, $params);

        return ResponseHelper::success(
            DeliveryOrderResource::collection($data),
            'Data fetched successfully',
            200
        );
    }

    public function deliverySchedule(DeliveryScheduleRequest $request, $farm_id, $id)
    {
        $validated = $request->validated();

        $response = $this->deliveryOrderService->setDeliverySchedule($farm_id, $id, $validated['delivery_schedule']);

        if ($response['error']) {
            return ResponseHelper::error('Failed to set delivery schedule', 500);
        }

        return ResponseHelper::success(
            new DeliveryOrderResource($response['data']),
            'Delivery schedule set successfully',
            200
        );
    }


    public function sendWA($farm_id, $id)
    {
        
    }
}
