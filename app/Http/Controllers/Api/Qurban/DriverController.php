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

class DriverController extends Controller
{
    private $driverService;

    public function __construct(DriverService $driverService)
    {
        $this->driverService = $driverService;
    }

    public function store(DriverStoreRequest $request, $farm_id)
    {
        $response = $this->driverService->storeDriver($farm_id , $request);

        if($response['error']){
            return ResponseHelper::error('Failed to create Driver', 500);
        }

        return ResponseHelper::success(new DriverResource($driver), 'Driver created successfully', 200);
    }

    public function show($id)
    {
        $driver = QurbanDriver::findOrFail($id);

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

        return ResponseHelper::success(new DriverResource($driver), 'Driver updated successfully', 200);
    }

    public function destroy($farm_id, $id)
    {
        $response = $this->customerService->deleteDriver($farm_id, $id);

        if($response['error']) {
            return ResponseHelper::error('Failed to delete Driver', 500);
        }

        return ResponseHelper::success(null, 'Driver deleted successfully', 200);
    }
}
