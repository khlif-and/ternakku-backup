<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\QurbanFleet;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Qurban\FleetResource;
use App\Http\Requests\Qurban\FleetStoreRequest;
use App\Http\Requests\Qurban\FleetUpdateRequest;
use App\Services\Qurban\FleetService;

class FleetController extends Controller
{
    private $fleetService;

    public function __construct(FleetService $fleetService)
    {
        $this->fleetService = $fleetService;
    }

    public function store(FleetStoreRequest $request, $farm_id)
    {
        $response = $this->fleetService->storeFleet($farm_id , $request);

        if($response['error']){
            return ResponseHelper::error('Failed to create Fleet', 500);
        }

        return ResponseHelper::success(new FleetResource($fleet), 'Fleet created successfully', 200);
    }

    public function show($id)
    {
        $fleet = QurbanFleet::findOrFail($id);

        return ResponseHelper::success(new FleetResource($fleet), 'Fleet found', 200);
    }

    public function index($farmId)
    {
        $fleets = $this->fleetService->getFleets($farmId);

        return ResponseHelper::success(FleetResource::collection($fleets), 'Fleets found', 200);
    }

    public function update(FleetUpdateRequest $request, $farm_id, $id)
    {
        $response = $this->fleetService->updateFleet($farm_id , $id, $request);

        if($response['error']){
            return ResponseHelper::error('Failed to create Fleet', 500);
        }

        return ResponseHelper::success(new FleetResource($fleet), 'Fleet updated successfully', 200);
    }

    public function destroy($farm_id, $id)
    {
        $response = $this->customerService->deleteFleet($farm_id, $id);

        if($response['error']) {
            return ResponseHelper::error('Failed to delete Fleet', 500);
        }

        return ResponseHelper::success(null, 'Fleet deleted successfully', 200);
    }
}
