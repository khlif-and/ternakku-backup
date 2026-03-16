<?php

namespace App\Http\Controllers\Admin\CareLivestock\LivestockDeathController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\LivestockDeath\LivestockDeathService;

class LivestockDeathController extends Controller
{
    protected LivestockDeathService $service;

    public function __construct(LivestockDeathService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.livestock_death.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.livestock_death.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $death = $this->service->find($farm, $id);

        return view('admin.care_livestock.livestock_death.show', compact('farm', 'death'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $death = $this->service->find($farm, $id);

        return view('admin.care_livestock.livestock_death.edit', compact('farm', 'death'));
    }
}
