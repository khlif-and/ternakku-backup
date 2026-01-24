<?php

namespace App\Services\Web\Farming\TreatmentColony;

use Illuminate\Http\Request;

class TreatmentColonyService
{
    protected TreatmentColonyCoreService $core;

    public function __construct(TreatmentColonyCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.treatment_colony.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.treatment_colony.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentColony = $this->core->find($farm, $id);

        return view('admin.care_livestock.treatment_colony.show', compact('farm', 'treatmentColony'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentColony = $this->core->find($farm, $id);

        return view('admin.care_livestock.treatment_colony.edit', compact('farm', 'treatmentColony'));
    }
}