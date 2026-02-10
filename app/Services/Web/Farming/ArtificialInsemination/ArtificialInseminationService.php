<?php

namespace App\Services\Web\Farming\ArtificialInsemination;

use Illuminate\Http\Request;

class ArtificialInseminationService
{
    protected ArtificialInseminationCoreService $core;

    public function __construct(ArtificialInseminationCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.artificial_inseminasi.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.artificial_inseminasi.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->find($farm, $id);

        return view('admin.care_livestock.artificial_inseminasi.show', compact('farm', 'item'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->find($farm, $id);

        return view('admin.care_livestock.artificial_inseminasi.edit', compact('farm', 'item'));
    }
}