<?php

namespace App\Http\Controllers\Api\Qurban;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Qurban\DeliveryInstructionService;
use App\Http\Resources\Qurban\DeliveryInstructionResource;
use App\Http\Requests\Qurban\DeliveryInstructionStoreRequest;

class DeliveryInstructionController extends Controller
{
    protected $deliveryInstructionService;

    public function __construct(DeliveryInstructionService $deliveryInstructionService)
    {
        $this->deliveryInstructionService = $deliveryInstructionService;
    }

    public function store(DeliveryInstructionStoreRequest $request, $farm_id): JsonResponse
    {
        $validated = $request->validated();

        $response = $this->deliveryInstructionService->storeDeliveryInstruction($farm_id, $validated);

        if ($response['error']) {
            return ResponseHelper::error('Failed to create delivery instruction', 500);
        }

        return ResponseHelper::success(
            new DeliveryInstructionResource($response['data']),
            'Delivery instruction created successfully',
            200
        );
    }

    public function index(Request $request, $farm_id)
    {
        $param = $request->all();

        $result = $this->deliveryInstructionService->getDeliveryInstructions($farm_id, $param);

        return ResponseHelper::success(
            DeliveryInstructionResource::collection($result),
            'Delivery instructions retrieved successfully',
            200
        );
    }

    public function setReadyToDeliver(Request $request, $farm_id, $id)
    {
        $deliveryInstruction = $this->deliveryInstructionService->setToReadyToDeliver($farm_id, $id);

        return ResponseHelper::success(
            new DeliveryInstructionResource($deliveryInstruction),
            'Delivery instruction set to ready to deliver'
        );
    }

    public function show($farm_id, $id): JsonResponse
    {
        $deliveryInstruction = $this->deliveryInstructionService->getById($farm_id, $id);
    
        if (!$deliveryInstruction) {
            return ResponseHelper::error('Delivery instruction not found', 404);
        }
    
        return ResponseHelper::success(
            new DeliveryInstructionResource($deliveryInstruction),
            'Delivery instruction retrieved successfully'
        );
    }
    
    public function destroy($farm_id, $id): JsonResponse
    {
        $deleted = $this->deliveryInstructionService->deleteDeliveryInstruction($farm_id, $id);
    
        if (!$deleted) {
            return ResponseHelper::error('Failed to delete delivery instruction', 500);
        }
    
        return ResponseHelper::success(null, 'Delivery instruction deleted successfully');
    }
}
