<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FleetTrackingController extends Controller
{
    public function index()
    {
        $fleetTrackings = [];

        return view('admin.qurban.fleet_tracking.index', compact('fleetTrackings'));
    }

    public function create()
    {
        return view('admin.qurban.fleet_tracking.create');
    }
}
