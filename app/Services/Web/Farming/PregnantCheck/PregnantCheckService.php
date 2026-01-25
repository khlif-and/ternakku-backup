<?php

namespace App\Services\Web\Farming\PregnantCheck;

use Illuminate\Http\Request;

class PregnantCheckService
{
    protected PregnantCheckCoreService $core;

    public function __construct(PregnantCheckCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.pregnant_check.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.pregnant_check.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->find($farm, $id);

        return view('admin.care_livestock.pregnant_check.show', compact('farm', 'item'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->find($farm, $id);

        return view('admin.care_livestock.pregnant_check.edit', compact('farm', 'item'));
    }
}