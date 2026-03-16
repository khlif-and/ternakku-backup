<?php

namespace App\Http\Controllers\Admin\Qurban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Qurban\FleetService;
use App\Http\Requests\Qurban\FleetStoreRequest;
use App\Http\Requests\Qurban\FleetUpdateRequest;

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

    public function store(FleetStoreRequest $request)
    {
        $farmId = session('selected_farm');

        $response = $this->fleetService->storeFleet($farmId,$request);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while adding the fleet');
        }

        return redirect('qurban/fleet')->with('success', 'Fleet added to the farm successfully');
    }

    public function edit($fleetId)
    {
        $farmId = session('selected_farm');

        $fleet = $this->fleetService->getFleet($farmId, $fleetId);

        return view('admin.qurban.fleet.edit' , compact('fleet'));
    }

    public function update(FleetUpdateRequest $request, $fleetId)
    {
        $farmId = session('selected_farm');

        $response = $this->fleetService->updateFleet($farmId, $fleetId, $request);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while updating the fleet');
        }

        return redirect('qurban/fleet')->with('success', 'fleet updated successfully');
    }

    public function destroy($fleetId)
    {
        $farmId = session('selected_farm');

        $response = $this->fleetService->deleteFleet($farmId, $fleetId);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while deleting the fleet');
        }

        return redirect('qurban/fleet')->with('success', 'fleet deleted successfully');
    }
}
