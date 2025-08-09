<?php

namespace App\Http\Controllers\Admin\CareLivestock\TreatmentSchedule;

use App\Http\Controllers\Controller;
use App\Models\TreatmentSchedule;
use App\Models\TreatmentScheduleIndividu;
use App\Http\Requests\Farming\TreatmentScheduleIndividuStoreRequest;
use App\Http\Requests\Farming\TreatmentScheduleIndividuUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TreatmentScheduleIndividuController extends Controller
{
    // LIST
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $query = TreatmentScheduleIndividu::with(['treatmentSchedule', 'livestock'])
            ->whereHas('treatmentSchedule', function ($q) use ($farm) {
                $q->where('farm_id', $farm->id)->where('type', 'individu');
            });

        // Filters
        if ($request->filled('start_date')) {
            $query->where('schedule_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->where('schedule_date', '<=', $request->input('end_date'));
        }
        if ($request->filled('livestock_id')) {
            $query->where('livestock_id', $request->input('livestock_id'));
        }
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

        $items = $query->orderByDesc('schedule_date')->get();

        return view('admin.care_livestock.treatment_schedule_individu.index', [
            'farm'    => $farm,
            'items'   => $items,
            'filters' => $request->only([
                'start_date','end_date','livestock_id','livestock_type_id','livestock_group_id',
                'livestock_breed_id','livestock_sex_id','pen_id'
            ]),
        ]);
    }

    // FORM CREATE
    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()->get();

        return view('admin.care_livestock.treatment_schedule_individu.create', compact('farm', 'livestocks'));
    }

    // STORE
    public function store(TreatmentScheduleIndividuStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($validated['livestock_id']);
        if (!$livestock) {
            return back()->withInput()->with('error', 'Livestock not found in this farm.');
        }

        try {
            DB::beginTransaction();

            $treatmentSchedule = TreatmentSchedule::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'] ?? null,
            ]);

            $detail = TreatmentScheduleIndividu::create([
                'treatment_schedule_id'  => $treatmentSchedule->id,
                'schedule_date'          => $validated['schedule_date'],
                'livestock_id'           => $validated['livestock_id'],
                'notes'                  => $validated['notes'] ?? null,
                'medicine_name'          => $validated['medicine_name'] ?? null,
                'medicine_unit'          => $validated['medicine_unit'] ?? null,
                'medicine_qty_per_unit'  => $validated['medicine_qty_per_unit'] ?? null,
                'treatment_name'         => $validated['treatment_name'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.treatment-schedule-individu.show', ['farm_id' => $farmId, 'id' => $detail->id])
                ->with('success', 'Data created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create TreatmentScheduleIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while recording the data.');
        }
    }

    // SHOW
    public function show($farmId, $treatmentScheduleIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $item = TreatmentScheduleIndividu::with(['treatmentSchedule', 'livestock'])
            ->whereHas('treatmentSchedule', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($treatmentScheduleIndividuId);

        return view('admin.care_livestock.treatment_schedule_individu.show', [
            'farm' => $farm,
            'treatmentScheduleIndividu' => $item,
        ]);
    }

    // FORM EDIT
    public function edit($farmId, $treatmentScheduleIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $item = TreatmentScheduleIndividu::with(['treatmentSchedule', 'livestock'])
            ->whereHas('treatmentSchedule', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($treatmentScheduleIndividuId);

        $livestocks = $farm->livestocks()->get();

        return view('admin.care_livestock.treatment_schedule_individu.edit', compact('farm', 'item', 'livestocks'));
    }

    // UPDATE
    public function update(TreatmentScheduleIndividuUpdateRequest $request, $farmId, $treatmentScheduleIndividuId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $item = TreatmentScheduleIndividu::with('treatmentSchedule')
            ->whereHas('treatmentSchedule', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($treatmentScheduleIndividuId);

        try {
            DB::beginTransaction();

            $header = $item->treatmentSchedule;
            $header->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $item->update([
                'livestock_id'          => $validated['livestock_id'],
                'schedule_date'         => $validated['schedule_date'],
                'notes'                 => $validated['notes'] ?? null,
                'medicine_name'         => $validated['medicine_name'] ?? null,
                'medicine_unit'         => $validated['medicine_unit'] ?? null,
                'medicine_qty_per_unit' => $validated['medicine_qty_per_unit'] ?? null,
                'treatment_name'        => $validated['treatment_name'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.treatment-schedule-individu.show', ['farm_id' => $farmId, 'id' => $item->id])
                ->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update TreatmentScheduleIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the data.');
        }
    }

    // DELETE
    public function destroy($farmId, $treatmentScheduleIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $item = TreatmentScheduleIndividu::with('treatmentSchedule')
            ->whereHas('treatmentSchedule', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($treatmentScheduleIndividuId);

        try {
            DB::beginTransaction();

            $header = $item->treatmentSchedule;
            $item->delete();

            // Hapus header jika sudah tidak ada detail
            if ($header && !$header->treatmentScheduleIndividu()->exists()) {
                $header->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.treatment-schedule-individu.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete TreatmentScheduleIndividu Error: '.$e->getMessage());
            return back()->with('error', 'An error occurred while deleting the data.');
        }
    }
}
