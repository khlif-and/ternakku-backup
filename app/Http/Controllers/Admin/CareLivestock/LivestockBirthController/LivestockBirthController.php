<?php

namespace App\Http\Controllers\Admin\CareLivestock\LivestockBirthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\LivestockBirthStoreRequest;
use App\Http\Requests\Farming\LivestockBirthUpdateRequest;
use App\Services\Web\Farming\LivestockBirth\LivestockBirthService;

class LivestockBirthController extends Controller
{
    protected LivestockBirthService $service;

    public function __construct(LivestockBirthService $service)
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

    public function store(LivestockBirthStoreRequest $request, $farm_id)
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

    public function update(LivestockBirthUpdateRequest $request, $farm_id, $id)
    {
        return $this->service->update($farm_id, $id, $request);
    }

    public function destroy($farm_id, $id)
    {
        return $this->service->destroy($farm_id, $id);
    }
}
