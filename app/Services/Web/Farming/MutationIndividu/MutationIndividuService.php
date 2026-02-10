<?php

namespace App\Services\Web\Farming\MutationIndividu;

use Illuminate\Http\Request;

class MutationIndividuService
{
    protected MutationIndividuCoreService $core;

    public function __construct(MutationIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.mutation_individu.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()->get();
        $pens = $farm->pens()->get();

        return view('admin.care_livestock.mutation_individu.create', compact('farm', 'livestocks', 'pens'));
    }

    public function show($farmId, $mutationIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $mutationIndividu = $this->core->find($farm, $mutationIndividuId);

        $fromPen = $farm->pens()->find($mutationIndividu->from);
        $toPen = $farm->pens()->find($mutationIndividu->to);

        return view('admin.care_livestock.mutation_individu.show', compact('farm', 'mutationIndividu', 'fromPen', 'toPen'));
    }

    public function edit($farmId, $mutationIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $mutationIndividu = $this->core->find($farm, $mutationIndividuId);

        if (!$this->core->checkIsLatest($mutationIndividu)) {
            return redirect()
                ->route('admin.care-livestock.mutation-individu.index', ['farm_id' => $farmId])
                ->with('error', 'Editing is not allowed because this is an old record.');
        }

        $livestocks = $farm->livestocks()->get();
        $pens = $farm->pens()->get();

        return view('admin.care_livestock.mutation_individu.edit', compact('farm', 'mutationIndividu', 'livestocks', 'pens'));
    }
}