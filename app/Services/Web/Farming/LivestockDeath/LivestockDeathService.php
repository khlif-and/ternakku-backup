<?php

namespace App\Services\Web\Farming\LivestockDeath;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\LivestockDeathStoreRequest;
use App\Http\Requests\Farming\LivestockDeathUpdateRequest;
use App\Models\Livestock;
use App\Enums\LivestockStatusEnum;

class LivestockDeathService
{
    protected LivestockDeathCoreService $core;

    public function __construct(LivestockDeathCoreService $core)
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
            'livestock_sex_id',
            'pen_id'
        ]);

        $data = $this->core->listDeaths($farm, $filters);

        return view('admin.care_livestock.livestock_death.index', [
            'farm' => $farm,
            'deaths' => $data['deaths']
        ]);
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = Livestock::where('farm_id', $farm->id)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->get();
        $diseases = DB::table('diseases')->pluck('name', 'id');
        return view('admin.care_livestock.livestock_death.create', compact('farm', 'livestocks', 'diseases'));
    }

    public function store(LivestockDeathStoreRequest $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId) {
            $farm = $request->attributes->get('farm');
            $this->core->storeDeath($farm, $request->validated());
            return redirect()
                ->route('admin.care-livestock.livestock-death.index', $farmId)
                ->with('success', 'Data kematian ternak berhasil disimpan. Semua data penjualan terkait telah dihapus otomatis.');
        }, 'LivestockDeath Store Error');
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $death = $this->core->findDeath($farm, $id);
        return view('admin.care_livestock.livestock_death.show', compact('farm', 'death'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $death = $this->core->findDeath($farm, $id);
        $livestocks = Livestock::where('farm_id', $farm->id)->get();
        $diseases = DB::table('diseases')->pluck('name', 'id');
        return view('admin.care_livestock.livestock_death.edit', compact('farm', 'death', 'livestocks', 'diseases'));
    }

    public function update(LivestockDeathUpdateRequest $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->updateDeath($farm, $id, $request->validated());
            return redirect()
                ->route('admin.care-livestock.livestock-death.index', $farmId)
                ->with('success', 'Data kematian ternak berhasil diupdate.');
        }, 'LivestockDeath Update Error');
    }

    public function destroy($farmId, $id)
    {
        return ErrorHandler::handle(function () use ($farmId, $id) {
            $farm = request()->attributes->get('farm');
            $this->core->deleteDeath($farm, $id);
            return redirect()
                ->route('admin.care-livestock.livestock-death.index', $farmId)
                ->with('success', 'Data kematian ternak berhasil dihapus.');
        }, 'LivestockDeath Delete Error');
    }
}
