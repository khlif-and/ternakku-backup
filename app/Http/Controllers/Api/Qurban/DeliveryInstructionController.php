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

}
