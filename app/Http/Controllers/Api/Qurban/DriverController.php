<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\QurbanDriver;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Qurban\DriverService;
use App\Http\Resources\Qurban\DriverResource;
use App\Http\Requests\Qurban\DriverStoreRequest;
use App\Http\Requests\Qurban\DriverUpdateRequest;
use App\Services\Qurban\DeliveryInstructionService;
use App\Http\Requests\Qurban\DeliveryLocationRequest;
use App\Http\Resources\Qurban\DeliveryLocationResource;
use App\Http\Resources\Qurban\DeliveryInstructionResource;

class DriverController extends Controller
{
    private $driverService, $deliveryInstructionService;

    public function __construct(DriverService $driverService, DeliveryInstructionService $deliveryInstructionService)
    {
        $this->driverService = $driverService;
        $this->deliveryInstructionService = $deliveryInstructionService;
    }

    public function store(DriverStoreRequest $request, $farm_id)
    {
        $response = $this->driverService->storeDriver($farm_id , $request);

        if($response['error']){
            return ResponseHelper::error('Failed to create Driver', 500);
        }

        return ResponseHelper::success(new DriverResource($response['data']), 'Driver created successfully', 200);
    }

    public function show($farmId, $id)
    {
        $driver = $this->driverService->getDriver($farmId, $id);


        return ResponseHelper::success(new DriverResource($driver), 'Driver found', 200);
    }

    public function index($farmId)
    {
        $drivers = $this->driverService->getDrivers($farmId);

        return ResponseHelper::success(DriverResource::collection($drivers), 'Drivers found', 200);
    }

    public function update(DriverUpdateRequest $request, $farm_id, $id)
    {
        $response = $this->driverService->updateDriver($farm_id , $id, $request);

        if($response['error']){
            return ResponseHelper::error('Failed to create Driver', 500);
        }

        return ResponseHelper::success(new DriverResource($response['data']), 'Driver updated successfully', 200);
    }

    public function destroy($farm_id, $id)
    {
        $response = $this->driverService->deleteDriver($farm_id, $id);

        if($response['error']) {
            return ResponseHelper::error('Failed to delete Driver', 500);
        }

        return ResponseHelper::success(null, 'Driver deleted successfully', 200);
    }

    public function getDeliveryInstruction(Request $request)
    {
        $param = $request->all();

        $user_id = auth()->user()->id;

        $result = $this->deliveryInstructionService->getDeliveryInstructionForDriver($user_id, $param);

        return ResponseHelper::success(
            DeliveryInstructionResource::collection($result),
            'Delivery instructions retrieved successfully',
            200
        );
    }

    public function setToInDelivery($id)
    {
        $user_id = auth()->user()->id;

        $deliveryInstruction = $this->deliveryInstructionService->setToInDelivery($user_id, $id);

        return ResponseHelper::success(
            new DeliveryInstructionResource($deliveryInstruction),
            'Delivery instruction set to in delivery'
        );
    }

    public function storeLocation(DeliveryLocationRequest $request, $id)
    {
        $user_id = auth()->user()->id;

        $response = $this->deliveryInstructionService->storeDriverLocation($user_id, $id, $request->validated());

        if ($response['error']) {
            return ResponseHelper::error('Failed to store location', 500);
        }

        return ResponseHelper::success(
            DeliveryLocationResource::collection($response['data']),
            'Location stored and list retrieved successfully'
        );
    }

    public function setToDelivered($id)
    {
        $user_id = auth()->user()->id;

        $deliveryInstruction = $this->deliveryInstructionService->setToDelivered($user_id, $id);

        return ResponseHelper::success(
            new DeliveryInstructionResource($deliveryInstruction),
            'Delivery instruction set to delivered'
        );
    }
}
