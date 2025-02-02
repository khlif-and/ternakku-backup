<?php

namespace App\Http\Controllers\Admin\Qurban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Qurban\FleetService;
use App\Http\Resources\Qurban\FleetResource;

class FleetController extends Controller
{
    private $fleetService;

    public function __construct(FleetService $fleetService)
    {
        $this->fleetService = $fleetService;
    }

    public function index()
    {
        //TODO : Get farm id from session
        $farmId = 1;

        $fleets = $this->fleetService->getFleets($farmId);

        return view('admin.qurban.fleet.index' , compact('fleets'));
    }
}
