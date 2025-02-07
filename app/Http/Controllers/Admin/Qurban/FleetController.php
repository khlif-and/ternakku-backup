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
        $farmId = session("selected_farm");

        $fleets = $this->fleetService->getFleets($farmId);

        return view('admin.qurban.fleet.index' , compact('fleets'));
    }

    public function create()
    {
        return view('admin.qurban.fleet.create');
    }

    public function store()
    {

    }
}
