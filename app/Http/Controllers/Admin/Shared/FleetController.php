<?php

namespace App\Http\Controllers\Admin\Shared;

use App\Models\Farm;
use App\Models\QurbanFleet;
use App\Http\Controllers\Controller;

class FleetController extends Controller
{
    public function index(Farm $farm)
    {
        return view('admin.shared.fleet.index', compact('farm'));
    }

    public function create(Farm $farm)
    {
        return view('admin.shared.fleet.create', compact('farm'));
    }

    public function show(Farm $farm, $id)
    {
        $fleet = QurbanFleet::where('farm_id', $farm->id)->findOrFail($id);
        return view('admin.shared.fleet.show', compact('farm', 'fleet'));
    }

    public function edit(Farm $farm, $id)
    {
        $fleet = QurbanFleet::where('farm_id', $farm->id)->findOrFail($id);
        return view('admin.shared.fleet.edit', compact('farm', 'fleet'));
    }
}
