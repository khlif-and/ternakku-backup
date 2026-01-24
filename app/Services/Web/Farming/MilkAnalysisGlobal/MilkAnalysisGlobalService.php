<?php

namespace App\Services\Web\Farming\MilkAnalysisGlobal;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\MilkAnalysisGlobalStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisGlobalUpdateRequest;

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
        $filters = $request->only(['start_date', 'end_date']);
        $data = $this->core->listAnalyses($farm, $filters);

        return view('admin.care_livestock.milk_analysis_global.index', [
            'farm' => $farm,
            'analyses' => $data['analyses'],
        ]);
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        return view('admin.care_livestock.milk_analysis_global.create', compact('farm'));
    }

    public function store(MilkAnalysisGlobalStoreRequest $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId) {
            $farm = $request->attributes->get('farm');
            $record = $this->core->storeAnalysis($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-analysis-global.show', [$farmId, $record->id])
                ->with('success', 'Data analisis susu global berhasil ditambahkan.');
        }, 'MilkAnalysisGlobal Store Error');
    }

    public function show($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkAnalysisGlobal = $this->core->findAnalysis($farm, $id);

        return view('admin.care_livestock.milk_analysis_global.show', compact('farm', 'milkAnalysisGlobal'));
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkAnalysisGlobal = $this->core->findAnalysis($farm, $id);

        return view('admin.care_livestock.milk_analysis_global.edit', compact('farm', 'milkAnalysisGlobal'));
    }

    public function update(MilkAnalysisGlobalUpdateRequest $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->updateAnalysis($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-analysis-global.show', [$farmId, $id])
                ->with('success', 'Data analisis susu global berhasil diperbarui.');
        }, 'MilkAnalysisGlobal Update Error');
    }

    public function destroy($farmId, $id, Request $request)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->deleteAnalysis($farm, $id);

            return redirect()
                ->route('admin.care-livestock.milk-analysis-global.index', $farmId)
                ->with('success', 'Data analisis susu global berhasil dihapus.');
        }, 'MilkAnalysisGlobal Delete Error');
    }
}