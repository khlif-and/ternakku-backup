<?php

namespace App\Http\Controllers\Admin\CareLivestock\LivestockDeathController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\LivestockDeathStoreRequest;
use App\Http\Requests\Farming\LivestockDeathUpdateRequest;
use App\Services\Web\Farming\LivestockDeath\LivestockDeathService;

class LivestockDeathController extends Controller
{
    protected LivestockDeathService $service;

    public function __construct(LivestockDeathService $service)
    {
        $this->service = $service;
    }

    public function index($farm_id, Request $request)
    {
        return $this->service->index($farm_id, $request);
    }

    public function create($farm_id)
    {
        return $this->service->create($farm_id);
    }

    public function store(LivestockDeathStoreRequest $request, $farm_id)
    {
        return $this->service->store($request, $farm_id);
    }

    public function show($farm_id, $id)
    {
        return $this->service->show($farm_id, $id);
    }

    public function edit($farm_id, $id)
    {
        return $this->service->edit($farm_id, $id);
    }

    public function update(LivestockDeathUpdateRequest $request, $farm_id, $id)
    {
        return $this->service->update($request, $farm_id, $id);
    }

    public function destroy($farm_id, $id)
    {
        return $this->service->destroy($farm_id, $id);
    }
}
