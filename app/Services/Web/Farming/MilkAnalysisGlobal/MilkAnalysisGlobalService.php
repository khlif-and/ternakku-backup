<?php

namespace App\Services\Web\Farming\MilkAnalysisGlobal;

use Illuminate\Http\Request;

class MilkAnalysisGlobalService
{
    protected MilkAnalysisGlobalCoreService $core;

    public function __construct(MilkAnalysisGlobalCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');

        return view('admin.care_livestock.milk_analysis_global.index', compact('farm'));
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');

        return view('admin.care_livestock.milk_analysis_global.create', compact('farm'));
    }

    public function show($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkAnalysisGlobal = $this->core->find($farm, $id);

        return view('admin.care_livestock.milk_analysis_global.show', compact('farm', 'milkAnalysisGlobal'));
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkAnalysisGlobal = $this->core->find($farm, $id);

        return view('admin.care_livestock.milk_analysis_global.edit', compact('farm', 'milkAnalysisGlobal'));
    }
}