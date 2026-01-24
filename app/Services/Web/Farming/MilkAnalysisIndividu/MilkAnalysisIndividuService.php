<?php

namespace App\Services\Web\Farming\MilkAnalysisIndividu;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\MilkAnalysisIndividuStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisIndividuUpdateRequest;

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
        $filters = $request->only(['start_date', 'end_date', 'livestock_id']);

        $data = $this->core->listAnalyses($farm, $filters);

        return view('admin.care_livestock.milk_analysis_individu.index', [
            'farm' => $farm,
            'analyses' => $data['analyses'],
            'livestocks' => $data['livestocks'],
        ]);
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $livestocks = $farm->livestocks()->where('livestock_sex_id', 2)->get();
        return view('admin.care_livestock.milk_analysis_individu.create', compact('farm', 'livestocks'));
    }

    public function store(MilkAnalysisIndividuStoreRequest $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId) {
            $farm = $request->attributes->get('farm');
            $record = $this->core->storeAnalysis($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-analysis-individu.show', [$farmId, $record->id])
                ->with('success', 'Data analisis susu berhasil ditambahkan.');
        }, 'MilkAnalysis Store Error');
    }

    public function show($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkAnalysisIndividu = $this->core->findAnalysis($farm, $id);

        return view('admin.care_livestock.milk_analysis_individu.show', compact('farm', 'milkAnalysisIndividu'));
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkAnalysisIndividu = $this->core->findAnalysis($farm, $id);
        $livestocks = $farm->livestocks()->where('livestock_sex_id', 2)->get();

        return view('admin.care_livestock.milk_analysis_individu.edit', compact('farm', 'milkAnalysisIndividu', 'livestocks'));
    }

    public function update(MilkAnalysisIndividuUpdateRequest $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->updateAnalysis($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-analysis-individu.show', [$farmId, $id])
                ->with('success', 'Data analisis susu berhasil diperbarui.');
        }, 'MilkAnalysis Update Error');
    }

    public function destroy($farmId, $id, Request $request)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->deleteAnalysis($farm, $id);

            return redirect()
                ->route('admin.care-livestock.milk-analysis-individu.index', $farmId)
                ->with('success', 'Data analisis susu berhasil dihapus.');
        }, 'MilkAnalysis Delete Error');
    }
}