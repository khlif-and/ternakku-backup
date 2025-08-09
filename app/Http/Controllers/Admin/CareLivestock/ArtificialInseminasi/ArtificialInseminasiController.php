<?php

namespace App\Http\Controllers\Admin\CareLivestock\ArtificialInseminasi;

use App\Http\Controllers\Controller;
use App\Models\Insemination;
use App\Models\InseminationArtificial;
use App\Models\LivestockExpense;
use App\Models\ReproductionCycle;
use App\Models\LivestockBreed;
use App\Enums\LivestockSexEnum;
use App\Enums\LivestockExpenseTypeEnum;
use App\Enums\ReproductionCycleStatusEnum;
use App\Http\Requests\Farming\ArtificialInseminationStoreRequest;
use App\Http\Requests\Farming\ArtificialInseminationUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArtificialInseminasiController extends Controller
{
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $query = InseminationArtificial::with([
                'insemination',
                'reproductionCycle.livestock.livestockType',
                'reproductionCycle.livestock.livestockBreed',
                'reproductionCycle.livestock.pen',
            ])
            ->whereHas('insemination', function ($q) use ($farm, $request) {
                $q->where('farm_id', $farm->id)->where('type', 'artificial');

                if ($request->filled('start_date')) {
                    $q->where('transaction_date', '>=', $request->input('start_date'));
                }
                if ($request->filled('end_date')) {
                    $q->where('transaction_date', '<=', $request->input('end_date'));
                }
            });

        if ($request->filled('livestock_type_id')) {
            $query->whereHas('reproductionCycle.livestock', function ($q) use ($request) {
                $q->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }
        if ($request->filled('livestock_group_id')) {
            $query->whereHas('reproductionCycle.livestock', function ($q) use ($request) {
                $q->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }
        if ($request->filled('livestock_breed_id')) {
            $query->whereHas('reproductionCycle.livestock', function ($q) use ($request) {
                $q->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }
        if ($request->filled('pen_id')) {
            $query->whereHas('reproductionCycle.livestock', function ($q) use ($request) {
                $q->where('pen_id', $request->input('pen_id'));
            });
        }

        $items = $query->get();

        return view('admin.care_livestock.artificial_inseminasi.index', [
            'farm'    => $farm,
            'items'   => $items,
            'filters' => $request->only(['start_date', 'end_date', 'livestock_type_id', 'livestock_group_id', 'livestock_breed_id', 'pen_id']),
        ]);
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        $livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->with(['livestockType', 'livestockBreed', 'pen'])
            ->get();

        $breeds = LivestockBreed::query()->orderBy('name')->get();

        return view('admin.care_livestock.artificial_inseminasi.create', [
            'farm'       => $farm,
            'livestocks' => $livestocks,
            'breeds'     => $breeds,
        ]);
    }

    public function store(ArtificialInseminationStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($validated['livestock_id']);
        if (!$livestock) {
            return back()->withInput()->with('error', 'Livestock not found.');
        }
        if ($livestock->livestock_sex_id !== LivestockSexEnum::BETINA->value) {
            return back()->withInput()->with('error', 'Livestock is not female.');
        }

        try {
            DB::beginTransaction();

            $check = ReproductionCycle::where('livestock_id', $validated['livestock_id'])
                ->latest()
                ->first();

            if ($check && $check->reproduction_cycle_status_id == ReproductionCycleStatusEnum::INSEMINATION->value) {
                $check->update([
                    'reproduction_cycle_status_id' => ReproductionCycleStatusEnum::INSEMINATION_FAILED->value
                ]);
            }

            if ($check && $check->reproduction_cycle_status_id == ReproductionCycleStatusEnum::PREGNANT->value) {
                $check->update([
                    'reproduction_cycle_status_id' => ReproductionCycleStatusEnum::BIRTH_FAILED->value
                ]);
            }

            $reproCycle = ReproductionCycle::create([
                'livestock_id'                  => $validated['livestock_id'],
                'reproduction_cycle_status_id'  => ReproductionCycleStatusEnum::INSEMINATION->value,
                'insemination_type'             => 'artificial',
            ]);

            $insemination = Insemination::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'artificial',
                'notes'            => $validated['notes'] ?? null,
            ]);

            $item = InseminationArtificial::create([
                'reproduction_cycle_id' => $reproCycle->id,
                'insemination_id'       => $insemination->id,
                'action_time'           => $validated['action_time'],
                'officer_name'          => $validated['officer_name'],
                'insemination_number'   => $livestock->insemination_number(),
                'pregnant_number'       => $livestock->pregnant_number() + 1,
                'children_number'       => $livestock->children_number() + 1,
                'semen_breed_id'        => $validated['semen_breed_id'],
                'sire_name'             => $validated['sire_name'],
                'semen_producer'        => $validated['semen_producer'],
                'semen_batch'           => $validated['semen_batch'],
                'cycle_date'            => getInseminationCycleDate($livestock->livestock_type_id, $validated['transaction_date']),
                'cost'                  => $validated['cost'],
            ]);

            $exp = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
                ->first();

            if (!$exp) {
                LivestockExpense::create([
                    'livestock_id'              => $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::AI->value,
                    'amount'                    => $validated['cost'],
                ]);
            } else {
                $exp->update(['amount' => $exp->amount + $validated['cost']]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care_livestock.artificial_inseminasi.index', ['farm_id' => $farm->id])
                ->with('success', 'Data created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('AI store error: '.$e->getMessage());

            return back()->withInput()->with('error', 'An error occurred while recording the data.');
        }
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        $item = InseminationArtificial::with([
                'insemination',
                'reproductionCycle.livestock.livestockType',
                'reproductionCycle.livestock.livestockBreed',
                'reproductionCycle.livestock.pen',
            ])
            ->whereHas('insemination', function ($q) use ($farm) {
                $q->where('farm_id', $farm->id)->where('type', 'artificial');
            })
            ->findOrFail($id);

        return view('admin.care_livestock.artificial_inseminasi.show', [
            'farm' => $farm,
            'item' => $item,
        ]);
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        $item = InseminationArtificial::with([
                'insemination',
                'reproductionCycle.livestock',
            ])
            ->whereHas('insemination', function ($q) use ($farm) {
                $q->where('farm_id', $farm->id)->where('type', 'artificial');
            })
            ->findOrFail($id);

        $livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->with(['livestockType', 'livestockBreed', 'pen'])
            ->get();

        $breeds = LivestockBreed::query()->orderBy('name')->get();

        return view('admin.care_livestock.artificial_inseminasi.edit', [
            'farm'       => $farm,
            'item'       => $item,
            'livestocks' => $livestocks,
            'breeds'     => $breeds,
        ]);
    }

    public function update(ArtificialInseminationUpdateRequest $request, $farmId, $id)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $item = InseminationArtificial::with(['insemination', 'reproductionCycle.livestock'])
            ->whereHas('insemination', function ($q) use ($farm) {
                $q->where('farm_id', $farm->id)->where('type', 'artificial');
            })
            ->findOrFail($id);

        $livestock = $item->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $insemination = $item->insemination;
            $insemination->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $exp = LivestockExpense::where('livestock_id', $livestock->id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
                ->first();

            if ($exp) {
                $exp->update([
                    'amount' => $exp->amount - $item->cost + $validated['cost'],
                ]);
            }

            $item->update([
                'action_time'    => $validated['action_time'],
                'officer_name'   => $validated['officer_name'],
                'semen_breed_id' => $validated['semen_breed_id'],
                'sire_name'      => $validated['sire_name'],
                'semen_producer' => $validated['semen_producer'],
                'semen_batch'    => $validated['semen_batch'],
                'cycle_date'     => getInseminationCycleDate($livestock->livestock_type_id, $validated['transaction_date']),
                'cost'           => $validated['cost'],
                'notes'          => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.care_livestock.artificial_inseminasi.show', ['farm_id' => $farm->id, 'id' => $item->id])
                ->with('success', 'Data updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('AI update error: '.$e->getMessage());

            return back()->withInput()->with('error', 'An error occurred while updating the data.');
        }
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        $item = InseminationArtificial::with(['insemination', 'reproductionCycle.livestock'])
            ->whereHas('insemination', function ($q) use ($farm) {
                $q->where('farm_id', $farm->id)->where('type', 'artificial');
            })
            ->findOrFail($id);

        $livestock = $item->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $exp = LivestockExpense::where('livestock_id', $livestock->id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
                ->first();

            if ($exp) {
                $exp->update([
                    'amount' => $exp->amount - $item->cost,
                ]);
            }

            $insemination = $item->insemination;

            $item->delete();

            if (!$insemination->inseminationArtificial()->exists()) {
                $insemination->delete();
            }

            $item->reproductionCycle->delete();

            DB::commit();

            return redirect()
                ->route('admin.care_livestock.artificial_inseminasi.index', ['farm_id' => $farm->id])
                ->with('success', 'Data deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('AI destroy error: '.$e->getMessage());

            return back()->with('error', 'An error occurred while deleting the data.');
        }
    }
}
