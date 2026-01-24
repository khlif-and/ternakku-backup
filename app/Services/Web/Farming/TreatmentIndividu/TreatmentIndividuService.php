<?php

namespace App\Services\Web\Farming\TreatmentIndividu;

use Illuminate\Http\Request;

class TreatmentIndividuService
{
    protected TreatmentIndividuCoreService $core;

    public function __construct(TreatmentIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.treatment_individu.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.treatment_individu.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentIndividu = $this->core->find($farm, $id);

        return view('admin.care_livestock.treatment_individu.show', compact('farm', 'treatmentIndividu'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentIndividu = $this->core->find($farm, $id);

        return view('admin.care_livestock.treatment_individu.edit', compact('farm', 'treatmentIndividu'));
    }
}