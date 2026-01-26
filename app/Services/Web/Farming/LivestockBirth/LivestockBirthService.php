<?php

namespace App\Services\Web\Farming\LivestockBirth;

use Illuminate\Http\Request;

class LivestockBirthService
{
    protected LivestockBirthCoreService $core;

    public function __construct(LivestockBirthCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.livestock_birth.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.livestock_birth.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $birth = $this->core->findBirth($farm, $id);

        return view('admin.care_livestock.livestock_birth.show', compact('farm', 'birth'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $birth = $this->core->findBirth($farm, $id);

        return view('admin.care_livestock.livestock_birth.edit', compact('farm', 'birth'));
    }
}