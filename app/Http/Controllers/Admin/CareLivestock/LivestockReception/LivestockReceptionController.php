<?php

namespace App\Http\Controllers\Admin\CareLivestock\LivestockReception;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\LivestockReception\LivestockReceptionService;

class LivestockReceptionController extends Controller
{
    protected LivestockReceptionService $service;

    public function __construct(LivestockReceptionService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        return $this->service->index($farmId, $request);
    }

    public function create($farmId, Request $request)
    {
        return $this->service->create($farmId, $request);
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
