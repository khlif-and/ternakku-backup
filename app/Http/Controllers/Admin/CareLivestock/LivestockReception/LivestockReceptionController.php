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
        $farm = $request->attributes->get('farm');

        return view('admin.care_livestock.livestock_reception.index', compact('farm'));
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $farm->load('pens');

        return view('admin.care_livestock.livestock_reception.create', compact('farm'));
    }

    public function show($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $reception = $this->service->find($farm, $id);

        return view('admin.care_livestock.livestock_reception.show', compact('farm', 'reception'));
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $farm->load('pens');
        $reception = $this->service->find($farm, $id);

        return view('admin.care_livestock.livestock_reception.edit', compact('farm', 'reception'));
    }
}

