<?php

namespace App\Services\Web\Farming\FeedingColony;

use Illuminate\Http\Request;

class FeedingIndividuService
{
    protected FeedingIndividuCoreService $core;

    public function __construct(FeedingIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.feeding_individu.index', compact('farm'));
    }

    public function create($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.feeding_individu.create', compact('farm'));
    }

    public function show($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $feedingIndividu = $this->core->find($farm, $feedingIndividuId);

        return view('admin.care_livestock.feeding_individu.show', compact('farm', 'feedingIndividu'));
    }

    public function edit($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $feedingIndividu = $this->core->find($farm, $feedingIndividuId);

        return view('admin.care_livestock.feeding_individu.edit', compact('farm', 'feedingIndividu'));
    }
}
