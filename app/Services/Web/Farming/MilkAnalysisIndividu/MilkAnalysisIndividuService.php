<?php

namespace App\Services\Web\Farming\MilkAnalysisIndividu;

use Illuminate\Http\Request;

class MilkAnalysisIndividuService
{
    protected MilkAnalysisIndividuCoreService $core;

    public function __construct(MilkAnalysisIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');

        return view('admin.care_livestock.milk_analysis_individu.index', compact('farm'));
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $livestocks = $farm->livestocks()->where('livestock_sex_id', 2)->get();

        return view('admin.care_livestock.milk_analysis_individu.create', compact('farm', 'livestocks'));
    }

    public function show($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkAnalysisIndividu = $this->core->find($farm, $id);

        return view('admin.care_livestock.milk_analysis_individu.show', compact('farm', 'milkAnalysisIndividu'));
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkAnalysisIndividu = $this->core->find($farm, $id);
        $livestocks = $farm->livestocks()->where('livestock_sex_id', 2)->get();

        return view('admin.care_livestock.milk_analysis_individu.edit', compact('farm', 'milkAnalysisIndividu', 'livestocks'));
    }
}