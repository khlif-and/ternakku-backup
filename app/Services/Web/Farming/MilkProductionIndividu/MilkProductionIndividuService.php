<?php

namespace App\Services\Web\Farming\MilkProductionIndividu;

use Illuminate\Http\Request;

class MilkProductionIndividuService
{
    protected MilkProductionIndividuCoreService $core;

    public function __construct(MilkProductionIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_production_individu.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_production_individu.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkProductionIndividu = $this->core->find($farm, $id);

        return view('admin.care_livestock.milk_production_individu.show', compact('farm', 'milkProductionIndividu'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkProductionIndividu = $this->core->find($farm, $id);

        return view('admin.care_livestock.milk_production_individu.edit', compact('farm', 'milkProductionIndividu'));
    }
}