<?php

namespace App\Http\Controllers\Admin\CareLivestock\LivestockDeathController;

use App\Http\Controllers\Controller;
use App\Services\Web\Farming\LivestockDeath\LivestockDeathService;
use Illuminate\Http\Request;

class LivestockDeathController extends Controller
{
    private $service;

    public function __construct(LivestockDeathService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function create(Request $request)
    {
        return $this->service->create($request);
    }

    public function show($farmId, $id, Request $request)
    {
        return $this->service->show($farmId, $id, $request);
    }

    public function edit($farmId, $id, Request $request)
    {
        return $this->service->edit($farmId, $id, $request);
    }
}
