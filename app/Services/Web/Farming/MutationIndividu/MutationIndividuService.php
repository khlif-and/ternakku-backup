<?php

namespace App\Services\Web\Farming\MutationIndividu;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $filters = $request->only([
            'start_date', 'end_date', 'livestock_type_id', 'livestock_group_id',
            'livestock_breed_id', 'livestock_sex_id', 'pen_id', 'livestock_id'
        ]);

        $items = $this->core->listMutations($farm, $filters);

        return view('admin.care_livestock.mutation_individu.index', compact('farm', 'items', 'filters'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()->get();
        $pens = $farm->pens()->get();

        return view('admin.care_livestock.mutation_individu.create', compact('farm', 'livestocks', 'pens'));
    }

    public function store($request, $farmId)
    {
        $farm = request()->attributes->get('farm');
        $validated = $request->validated();

        $livestock = $farm->livestocks()->find($validated['livestock_id']);
        if (!$livestock) {
            return back()->withInput()->with('error', 'Livestock not found in this farm.');
        }

        if ($validated['pen_destination'] == $livestock->pen_id) {
            return back()->withInput()->with('error', 'The destination pen must be different from the current pen.');
        }

        try {
            $mutationIndividuD = $this->core->store($farm, $validated);

            return redirect()
                ->route('admin.care-livestock.mutation-individu.show', ['farm_id' => $farmId, 'id' => $mutationIndividuD->id])
                ->with('success', 'Data created successfully');
        } catch (\Exception $e) {
            Log::error('Create MutationIndividu Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while recording the data.');
        }
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

    public function update($request, $farmId, $mutationIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $validated = $request->validated();

        try {
            $mutationIndividu = $this->core->find($farm, $mutationIndividuId);

            if (!$this->core->checkIsLatest($mutationIndividu)) {
                return back()->withInput()->with('error', 'Editing is not allowed because this is an old record.');
            }

            if ($validated['pen_destination'] == $mutationIndividu->from) {
                return back()->withInput()->with('error', 'The destination pen must be different from the current pen.');
            }

            $this->core->update($farm, $mutationIndividuId, $validated);

            return redirect()
                ->route('admin.care-livestock.mutation-individu.show', ['farm_id' => $farmId, 'id' => $mutationIndividuId])
                ->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            Log::error('Update MutationIndividu Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the data.');
        }
    }

    public function destroy($farmId, $mutationIndividuId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $mutationIndividu = $this->core->find($farm, $mutationIndividuId);

            if (!$this->core->checkIsLatest($mutationIndividu)) {
                return back()->with('error', 'Deleting is not allowed because this is an old record.');
            }

            $this->core->delete($farm, $mutationIndividuId);

            return redirect()
                ->route('admin.care-livestock.mutation-individu.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        } catch (\Exception $e) {
            Log::error('Delete MutationIndividu Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the data.');
        }
    }
}