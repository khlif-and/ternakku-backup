<?php

namespace App\Services\Web\Farming\MilkProductionIndividu;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\MilkProductionIndividuStoreRequest;
use App\Http\Requests\Farming\MilkProductionIndividuUpdateRequest;
use App\Enums\LivestockSexEnum;

class MilkProductionIndividuService
{
    protected MilkProductionIndividuCoreService $core;

    public function __construct(MilkProductionIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $filters = $request->only(['start_date', 'end_date', 'livestock_id']);

        $data = $this->core->listProductions($farm, $filters);

        return view('admin.care_livestock.milk_production_individu.index', [
            'farm' => $farm,
            'milkProductions' => $data['productions'],
            'livestocks' => $data['livestocks'],
        ]);
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();

        return view('admin.care_livestock.milk_production_individu.create', compact('farm', 'livestocks'));
    }

    public function store(MilkProductionIndividuStoreRequest $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId) {
            $farm = $request->attributes->get('farm');
            $this->core->storeProduction($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-production-individu.index', $farmId)
                ->with('success', 'Data produksi susu berhasil ditambahkan.');
        }, 'MilkProduction Store Error');
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkProductionIndividu = $this->core->findProduction($farm, $id);

        $livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();

        return view('admin.care_livestock.milk_production_individu.edit', compact(
            'farm',
            'milkProductionIndividu',
            'livestocks'
        ));
    }

    public function update(MilkProductionIndividuUpdateRequest $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->updateProduction($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-production-individu.index', $farmId)
                ->with('success', 'Data produksi susu berhasil diperbarui.');
        }, 'MilkProduction Update Error');
    }

    public function destroy($farmId, $id, Request $request)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->deleteProduction($farm, $id);

            return redirect()
                ->route('admin.care-livestock.milk-production-individu.index', $farmId)
                ->with('success', 'Data produksi susu berhasil dihapus.');
        }, 'MilkProduction Delete Error');
    }
}
