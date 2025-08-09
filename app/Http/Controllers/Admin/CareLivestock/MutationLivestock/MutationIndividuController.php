<?php

namespace App\Http\Controllers\Admin\CareLivestock\MutationLivestock;

use App\Http\Controllers\Controller;
use App\Models\MutationH;
use App\Models\MutationIndividuD;
use App\Models\PenHistory;
use App\Http\Requests\Farming\MutationIndividuStoreRequest;
use App\Http\Requests\Farming\MutationIndividuUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MutationIndividuController extends Controller
{
    // LIST
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $query = MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', function ($q) use ($farm, $request) {
                $q->where('farm_id', $farm->id)->where('type', 'individu');

                if ($request->filled('start_date')) {
                    $q->where('transaction_date', '>=', $request->input('start_date'));
                }
                if ($request->filled('end_date')) {
                    $q->where('transaction_date', '<=', $request->input('end_date'));
                }
            });

        // Filter by livestock attributes
        if ($request->filled('livestock_type_id')) {
            $query->whereHas('livestock', function ($q) use ($request) {
                $q->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }
        if ($request->filled('livestock_group_id')) {
            $query->whereHas('livestock', function ($q) use ($request) {
                $q->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }
        if ($request->filled('livestock_breed_id')) {
            $query->whereHas('livestock', function ($q) use ($request) {
                $q->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }
        if ($request->filled('livestock_sex_id')) {
            $query->whereHas('livestock', function ($q) use ($request) {
                $q->where('livestock_sex_id', $request->input('livestock_sex_id'));
            });
        }
        if ($request->filled('pen_id')) {
            $query->whereHas('livestock', function ($q) use ($request) {
                $q->where('pen_id', $request->input('pen_id'));
            });
        }
        if ($request->filled('livestock_id')) {
            $query->where('livestock_id', $request->input('livestock_id'));
        }

        $items = $query->get();

        return view('admin.care_livestock.mutation_individu.index', [
            'farm'    => $farm,
            'items'   => $items,
            'filters' => $request->only([
                'start_date','end_date','livestock_type_id','livestock_group_id',
                'livestock_breed_id','livestock_sex_id','pen_id','livestock_id'
            ]),
        ]);
    }

    // FORM CREATE
    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        $livestocks = $farm->livestocks()->get();
        $pens       = $farm->pens()->get();

        return view('admin.care_livestock.mutation_individu.create', compact('farm', 'livestocks', 'pens'));
    }

    // STORE
    public function store(MutationIndividuStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($validated['livestock_id']);
        if (!$livestock) {
            return back()->withInput()->with('error', 'Livestock not found in this farm.');
        }

        $penDestination = $farm->pens()->find($validated['pen_destination']);
        if (!$penDestination) {
            return back()->withInput()->with('error', 'The destination pen not found.');
        }

        if ($penDestination->id == $livestock->pen_id) {
            return back()->withInput()->with('error', 'The destination pen must be different from the current pen.');
        }

        try {
            DB::beginTransaction();

            $mutationH = MutationH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'] ?? null,
            ]);

            $mutationIndividuD = MutationIndividuD::create([
                'mutation_h_id' => $mutationH->id,
                'livestock_id'  => $validated['livestock_id'],
                'from'          => $livestock->pen_id,
                'to'            => $validated['pen_destination'],
                'notes'         => $validated['notes'] ?? null,
            ]);

            PenHistory::create([
                'livestock_id' => $validated['livestock_id'],
                'pen_id'       => $validated['pen_destination'],
            ]);

            $livestock->update([
                'pen_id' => $validated['pen_destination'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.mutation-individu.show', ['farm_id' => $farmId, 'id' => $mutationIndividuD->id])
                ->with('success', 'Data created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create MutationIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while recording the data.');
        }
    }

    // SHOW
    public function show($farmId, $mutationIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $mutationIndividu = MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', fn ($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($mutationIndividuId);

        // (opsional) ambil nama kandang asal/tujuan untuk ditampilkan di view
        $fromPen = $farm->pens()->find($mutationIndividu->from);
        $toPen   = $farm->pens()->find($mutationIndividu->to);

        return view('admin.care_livestock.mutation_individu.show', [
            'farm'             => $farm,
            'mutationIndividu' => $mutationIndividu,
            'fromPen'          => $fromPen,
            'toPen'            => $toPen,
        ]);
    }

    // FORM EDIT
    public function edit($farmId, $mutationIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $mutationIndividu = MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', fn ($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($mutationIndividuId);

        $livestock = $mutationIndividu->livestock;
        if ($mutationIndividu->to !== ($livestock->pen_id ?? null)) {
            return redirect()
                ->route('admin.care-livestock.mutation-individu.index', ['farm_id' => $farmId])
                ->with('error', 'Editing is not allowed because this is an old record.');
        }

        $livestocks = $farm->livestocks()->get();
        $pens       = $farm->pens()->get();

        return view('admin.care_livestock.mutation_individu.edit', compact('farm', 'mutationIndividu', 'livestocks', 'pens'));
    }

    // UPDATE
    public function update(MutationIndividuUpdateRequest $request, $farmId, $mutationIndividuId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $mutationIndividuD = MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', fn ($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($mutationIndividuId);

        $livestock = $mutationIndividuD->livestock;

        if ($mutationIndividuD->to !== ($livestock->pen_id ?? null)) {
            return back()->withInput()->with('error', 'Editing is not allowed because this is an old record.');
        }

        $penDestination = $farm->pens()->find($validated['pen_destination']);
        if (!$penDestination) {
            return back()->withInput()->with('error', 'The destination pen not found.');
        }
        if ($penDestination->id == $mutationIndividuD->from) {
            return back()->withInput()->with('error', 'The destination pen must be different from the current pen.');
        }

        try {
            DB::beginTransaction();

            $mutationH = $mutationIndividuD->mutationH;
            $mutationH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $mutationIndividuD->update([
                'notes' => $validated['notes'] ?? null,
                'to'    => $validated['pen_destination'],
            ]);

            $penHistory = PenHistory::where('livestock_id', $livestock->id)->latest()->first();
            if ($penHistory) {
                $penHistory->update(['pen_id' => $validated['pen_destination']]);
            } else {
                // fallback jika belum ada pen history
                PenHistory::create([
                    'livestock_id' => $livestock->id,
                    'pen_id'       => $validated['pen_destination'],
                ]);
            }

            $livestock->update(['pen_id' => $validated['pen_destination']]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.mutation-individu.show', ['farm_id' => $farmId, 'id' => $mutationIndividuD->id])
                ->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update MutationIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the data.');
        }
    }

    // DELETE
    public function destroy($farmId, $mutationIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $mutationIndividuD = MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', fn ($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($mutationIndividuId);

        $livestock = $mutationIndividuD->livestock;

        if ($mutationIndividuD->to !== ($livestock->pen_id ?? null)) {
            return back()->with('error', 'Deleting is not allowed because this is an old record.');
        }

        try {
            DB::beginTransaction();

            // kembalikan ternak ke kandang asal
            $livestock->update(['pen_id' => $mutationIndividuD->from]);

            // hapus pen history terakhir (kalau ada)
            $penHistory = PenHistory::where('livestock_id', $livestock->id)->latest()->first();
            if ($penHistory) {
                $penHistory->delete();
            }

            // hapus detail
            $mutationIndividuD->delete();

            // hapus header jika tak ada detail lain
            $mutationH = $mutationIndividuD->mutationH;
            if ($mutationH && !$mutationH->mutationIndividuD()->exists()) {
                $mutationH->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.mutation-individu.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete MutationIndividu Error: '.$e->getMessage());
            return back()->with('error', 'An error occurred while deleting the data.');
        }
    }
}
