<?php

namespace App\Services\Web\Farming\LivestockBirth;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\LivestockBirthStoreRequest;
use App\Http\Requests\Farming\LivestockBirthUpdateRequest;
use App\Enums\LivestockSexEnum;

class LivestockBirthService
{
    protected LivestockBirthCoreService $core;

    public function __construct(LivestockBirthCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $filters = $request->only([
            'start_date',
            'end_date',
            'livestock_type_id',
            'livestock_group_id',
            'livestock_breed_id',
            'pen_id'
        ]);

        $data = $this->core->listBirths($farm, $filters);

        return view('admin.care_livestock.livestock_birth.index', [
            'farm' => $farm,
            'births' => $data['births'],
            'femaleLivestocks' => $data['femaleLivestocks'],
        ]);
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();

        return view('admin.care_livestock.livestock_birth.create', compact('farm', 'livestocks'));
    }

    public function store(LivestockBirthStoreRequest $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request) {
            $farm = $request->attributes->get('farm');
            $birth = $this->core->storeBirth($farm, $request->validated());
            return redirect()
                ->route('admin.care_livestock.livestock_birth.show', [$farm->id, $birth->id])
                ->with('success', 'Data created successfully');
        }, 'LivestockBirth Store Error');
    }

    public function show($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $birth = $this->core->findBirth($farm, $id);
        return view('admin.care_livestock.livestock_birth.show', compact('farm', 'birth'));
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $birth = $this->core->findBirth($farm, $id);
        $femaleLivestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();

        return view('admin.care_livestock.livestock_birth.edit', compact('farm', 'birth', 'femaleLivestocks'));
    }

    public function update(LivestockBirthUpdateRequest $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $id) {
            $farm = $request->attributes->get('farm');
            $birth = $this->core->updateBirth($farm, $id, $request->validated());
            return redirect()
                ->route('admin.care_livestock.livestock_birth.show', [$farm->id, $birth->id])
                ->with('success', 'Data updated successfully');
        }, 'LivestockBirth Update Error');
    }

    public function destroy($farmId, $id, Request $request)
    {
        return ErrorHandler::handle(function () use ($request, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->deleteBirth($farm, $id);
            return redirect()
                ->route('admin.care_livestock.livestock_birth.index', [$farm->id])
                ->with('success', 'Data deleted successfully');
        }, 'LivestockBirth Delete Error');
    }
}
