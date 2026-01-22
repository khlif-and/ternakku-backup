<?php

namespace App\Services\Web\Farming\LivestockDeath;

use Illuminate\Http\Request;
use App\Models\LivestockDeath;

class LivestockDeathService
{
    protected LivestockDeathCoreService $core;

    public function __construct(LivestockDeathCoreService $core)
    {
        $this->core = $core;
    }

    public function index(Request $request)
    {
        $farm = $request->attributes->get('farm');

        return view('admin.care_livestock.livestock_death.index', compact('farm'));
    }

    public function create(Request $request)
    {
        $farm = $request->attributes->get('farm');

        return view('admin.care_livestock.livestock_death.create', compact('farm'));
    }

    public function show($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $death = LivestockDeath::where('farm_id', $farm->id)->with(['livestock', 'disease'])->findOrFail($id);

        return view('admin.care_livestock.livestock_death.show', compact('farm', 'death'));
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $death = LivestockDeath::where('farm_id', $farm->id)->findOrFail($id);

        return view('admin.care_livestock.livestock_death.edit', compact('farm', 'death'));
    }
}
