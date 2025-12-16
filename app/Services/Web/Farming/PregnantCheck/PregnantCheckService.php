<?php

namespace App\Services\Web\Farming\PregnantCheck;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\PregnantCheckStoreRequest;
use App\Http\Requests\Farming\PregnantCheckUpdateRequest;
use App\Enums\LivestockSexEnum;

class PregnantCheckService
{
    protected PregnantCheckCoreService $core;

    public function __construct(PregnantCheckCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $filters = $request->only([
            'start_date', 'end_date', 'livestock_type_id', 'livestock_group_id',
            'livestock_breed_id', 'pen_id'
        ]);

        $items = $this->core->listChecks($farm, $filters);

        return view('admin.care_livestock.pregnant_check.index', compact('farm', 'items', 'filters'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->with(['livestockType', 'livestockBreed', 'pen'])
            ->get();

        return view('admin.care_livestock.pregnant_check.create', compact('farm', 'livestocks'));
    }

    public function store(PregnantCheckStoreRequest $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId) {
            $farm = $request->attributes->get('farm');
            $this->core->storeCheck($farm, $request->validated());

            return redirect()
                ->route('admin.care_livestock.pregnant_check.index', ['farm_id' => $farmId])
                ->with('success', 'Data created successfully');
        }, 'PregnantCheck Store Error');
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->findCheck($farm, $id);

        return view('admin.care_livestock.pregnant_check.show', compact('farm', 'item'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->findCheck($farm, $id);

        $livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->with(['livestockType', 'livestockBreed', 'pen'])
            ->get();

        return view('admin.care_livestock.pregnant_check.edit', compact('farm', 'item', 'livestocks'));
    }

    public function update(PregnantCheckUpdateRequest $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->updateCheck($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care_livestock.pregnant_check.index', ['farm_id' => $farmId])
                ->with('success', 'Data updated successfully');
        }, 'PregnantCheck Update Error');
    }

    public function destroy($farmId, $id)
    {
        return ErrorHandler::handle(function () use ($farmId, $id) {
            $farm = request()->attributes->get('farm');
            $this->core->deleteCheck($farm, $id);

            return redirect()
                ->route('admin.care_livestock.pregnant_check.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        }, 'PregnantCheck Delete Error');
    }
}
