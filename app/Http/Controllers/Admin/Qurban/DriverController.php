<?php

namespace App\Http\Controllers\Admin\Qurban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Qurban\DriverService;
use App\Http\Resources\Qurban\DriverResource;

class DriverController extends Controller
{
    private $driverService;

    public function __construct(DriverService $driverService)
    {
        $this->driverService = $driverService;
    }

    public function index()
    {
        //TODO : Get farm id from session
        $farmId = 1;

        $drivers = $this->driverService->getDrivers($farmId);

        return view('admin.qurban.driver.index' , compact('drivers'));
    }
}
