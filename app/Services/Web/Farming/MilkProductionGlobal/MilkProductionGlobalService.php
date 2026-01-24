<?php

namespace App\Services\Web\Farming\MilkProductionGlobal;

use Illuminate\Http\Request;

class MilkProductionGlobalService
{
    protected MilkProductionGlobalCoreService $core;

    public function __construct(MilkProductionGlobalCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_production_global.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_production_global.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkProductionGlobal = $this->core->find($farm, $id);

        return view('admin.care_livestock.milk_production_global.show', compact('farm', 'milkProductionGlobal'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkProductionGlobal = $this->core->find($farm, $id);

        return view('admin.care_livestock.milk_production_global.edit', compact('farm', 'milkProductionGlobal'));
    }
}