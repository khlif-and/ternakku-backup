<?php

namespace App\Services\Web\Farming\LivestockReception;

use Illuminate\Http\Request;

class LivestockReceptionService
{
    protected LivestockReceptionCoreService $core;

    public function __construct(LivestockReceptionCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');

        return view('admin.care_livestock.livestock_reception.index', [
            'farm' => $farm,
        ]);
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        if (!$farm) {
            abort(404, 'Farm tidak ditemukan');
        }

        $farm->load('pens');

        return view('admin.care_livestock.livestock_reception.create', [
            'farm' => $farm,
        ]);
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        if (!$farm) {
            abort(404, 'Farm tidak ditemukan');
        }

        $farm->load('pens');
        $reception = $this->core->findReception($farm, $id);

        return view('admin.care_livestock.livestock_reception.edit', [
            'farm' => $farm,
            'reception' => $reception,
        ]);
    }
}
