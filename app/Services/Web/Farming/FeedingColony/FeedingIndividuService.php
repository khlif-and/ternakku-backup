<?php

namespace App\Services\Web\Farming\FeedingColony;

use App\Models\FeedingH;
use App\Models\FeedingIndividuD;
use App\Models\FeedingIndividuItem;
use App\Models\LivestockExpense;
use App\Models\Livestock;
use App\Enums\LivestockExpenseTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class FeedingIndividuService
{
    // LIST
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $query = FeedingIndividuD::with(['feedingH','livestock'])
            ->withCount('feedingIndividuItems')
            ->whereHas('feedingH', function ($q) use ($farm, $request) {
                $q->where('farm_id', $farm->id)->where('type', 'individu');
                if ($request->filled('start_date')) $q->where('transaction_date', '>=', $request->input('start_date'));
                if ($request->filled('end_date')) $q->where('transaction_date', '<=', $request->input('end_date'));
            });

        foreach (['livestock_type_id','livestock_group_id','livestock_breed_id','livestock_sex_id','pen_id','livestock_id'] as $filter) {
            if ($request->filled($filter)) {
                $query->whereHas('livestock', fn($q) => $q->where($filter, $request->input($filter)));
            }
        }

        $items = $query->get();

        return view('admin.care_livestock.feeding_individu.index', [
            'farm'    => $farm,
            'items'   => $items,
            'filters' => $request->only([
                'start_date','end_date','livestock_type_id','livestock_group_id',
                'livestock_breed_id','livestock_sex_id','pen_id','livestock_id'
            ]),
        ]);
    }

    // FORM CREATE
    public function create($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $q = Livestock::with(['livestockType:id,name','livestockBreed:id,name'])
            ->where('farm_id', $farm->id);

        if (Schema::hasColumn('livestocks', 'eartag_number')) $q->orderBy('eartag_number');
        elseif (Schema::hasColumn('livestocks', 'eartag')) $q->orderBy('eartag');
        else $q->orderBy('id');

        $livestocks = $q->get();

        return view('admin.care_livestock.feeding_individu.create', compact('farm','livestocks'));
    }

    // STORE
    public function store($request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');
        $livestock = $farm->livestocks()->find($validated['livestock_id']);
        if (!$livestock) return back()->withInput()->with('error', 'Livestock not found.');

        try {
            DB::beginTransaction();

            $feedingH = FeedingH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type' => 'individu',
                'notes' => $validated['notes'] ?? null,
            ]);

            $feedingIndividuD = FeedingIndividuD::create([
                'feeding_h_id' => $feedingH->id,
                'livestock_id' => $validated['livestock_id'],
                'notes' => $validated['notes'] ?? null,
                'total_cost' => 0,
            ]);

            $totalCost = 0;
            foreach ($validated['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingIndividuItem::create([
                    'feeding_individu_d_id' => $feedingIndividuD->id,
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'qty_kg' => $item['qty_kg'],
                    'price_per_kg' => $item['price_per_kg'],
                    'total_price' => $totalPrice,
                ]);
            }

            $feedingIndividuD->update(['total_cost' => $totalCost]);
            $this->updateExpense($validated['livestock_id'], $totalCost);

            DB::commit();
            return redirect()->route('admin.care-livestock.feeding-individu.show', [
                'farm_id' => $farmId,
                'id' => $feedingIndividuD->id,
            ])->with('success', 'Data created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create FeedingIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'Error while recording data.');
        }
    }

    // SHOW
    public function show($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $feedingIndividu = FeedingIndividuD::with(['feedingH','feedingIndividuItems','livestock'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($feedingIndividuId);

        return view('admin.care_livestock.feeding_individu.show', compact('farm','feedingIndividu'));
    }

    // EDIT
    public function edit($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $feedingIndividu = FeedingIndividuD::with(['feedingH','feedingIndividuItems','livestock'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($feedingIndividuId);

        $q = Livestock::with(['livestockType:id,name','livestockBreed:id,name'])
            ->where('farm_id', $farm->id);

        if (Schema::hasColumn('livestocks', 'eartag_number')) $q->orderBy('eartag_number');
        elseif (Schema::hasColumn('livestocks', 'eartag')) $q->orderBy('eartag');
        else $q->orderBy('id');

        $livestocks = $q->get();

        return view('admin.care_livestock.feeding_individu.edit', compact('farm','feedingIndividu','livestocks'));
    }

    // UPDATE
    public function update($request, $farmId, $feedingIndividuId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');
        $feedingIndividuD = FeedingIndividuD::whereHas('feedingH', fn($q)
            => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($feedingIndividuId);

        try {
            DB::beginTransaction();

            $feedingH = $feedingIndividuD->feedingH;
            $feedingH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $oldExpense = LivestockExpense::where('livestock_id', $feedingIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();
            if ($oldExpense) $oldExpense->update(['amount' => $oldExpense->amount - $feedingIndividuD->total_cost]);

            FeedingIndividuItem::where('feeding_individu_d_id', $feedingIndividuD->id)->delete();

            $totalCost = 0;
            foreach ($validated['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;
                FeedingIndividuItem::create([
                    'feeding_individu_d_id' => $feedingIndividuD->id,
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'qty_kg' => $item['qty_kg'],
                    'price_per_kg' => $item['price_per_kg'],
                    'total_price' => $totalPrice,
                ]);
            }

            $feedingIndividuD->update(['total_cost' => $totalCost]);
            $this->updateExpense($validated['livestock_id'], $totalCost);

            DB::commit();
            return redirect()->route('admin.care-livestock.feeding-individu.show', [
                'farm_id' => $farmId,
                'id' => $feedingIndividuD->id,
            ])->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update FeedingIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'Error updating data.');
        }
    }

    // DELETE
    public function destroy($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $feedingIndividuD = FeedingIndividuD::whereHas('feedingH', fn($q)
            => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($feedingIndividuId);

        try {
            DB::beginTransaction();

            $expense = LivestockExpense::where('livestock_id', $feedingIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();
            if ($expense) $expense->update(['amount' => $expense->amount - $feedingIndividuD->total_cost]);

            FeedingIndividuItem::where('feeding_individu_d_id', $feedingIndividuD->id)->delete();

            $feedingH = $feedingIndividuD->feedingH;
            $feedingIndividuD->delete();

            if (!$feedingH->feedingIndividuD()->exists()) $feedingH->delete();

            DB::commit();
            return redirect()->route('admin.care-livestock.feeding-individu.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete FeedingIndividu Error: '.$e->getMessage());
            return back()->with('error', 'Error deleting data.');
        }
    }

    private function updateExpense($livestockId, $amount)
    {
        $expense = LivestockExpense::where('livestock_id', $livestockId)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
            ->first();

        if (!$expense) {
            LivestockExpense::create([
                'livestock_id' => $livestockId,
                'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                'amount' => $amount,
            ]);
        } else {
            $expense->update(['amount' => $expense->amount + $amount]);
        }
    }
}
