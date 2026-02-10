<?php

namespace App\Http\Controllers\Admin\Shared;

use App\Models\Farm;
use App\Models\FarmUser;
use App\Http\Controllers\Controller;

class DriverController extends Controller
{
    public function index(Farm $farm)
    {
        return view('admin.shared.driver.index', compact('farm'));
    }

    public function create(Farm $farm)
    {
        return view('admin.shared.driver.create', compact('farm'));
    }

    public function show(Farm $farm, $id)
    {
        $driver = FarmUser::where('farm_id', $farm->id)
            ->where('farm_role', 'DRIVER')
            ->findOrFail($id);
        return view('admin.shared.driver.show', compact('farm', 'driver'));
    }

    public function edit(Farm $farm, $id)
    {
        $driver = FarmUser::where('farm_id', $farm->id)
            ->where('farm_role', 'DRIVER')
            ->findOrFail($id);
        return view('admin.shared.driver.edit', compact('farm', 'driver'));
    }
}
