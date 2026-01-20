<?php

namespace App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeight;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\LivestockSaleWeightStoreRequest;
use App\Http\Requests\Farming\LivestockSaleWeightUpdateRequest;
use App\Services\Web\Farming\LivestockSaleWeight\LivestockSaleWeightService;

class LivestockSaleWeightController extends Controller
{
    private $service;

    public function __construct(LivestockSaleWeightService $service)
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

    public function store(LivestockSaleWeightStoreRequest $request)
    {
        return $this->service->store($request);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function edit($id)
    {
        return $this->service->edit($id);
    }

    public function update(LivestockSaleWeightUpdateRequest $request, $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
