<?php

namespace App\Http\Controllers\Admin\CareLivestock\FeedingLivestock;

use App\Http\Controllers\Controller;
use App\Models\FeedingH;
use App\Models\FeedingIndividuD;
use App\Models\FeedingIndividuItem;
use App\Models\LivestockExpense;
use App\Models\Livestock;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Requests\Farming\FeedingIndividuStoreRequest;
use App\Http\Requests\Farming\FeedingIndividuUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class FeedingIndividuController extends Controller
{
    // LIST
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $query = FeedingIndividuD::with(['feedingH','livestock'])
            ->withCount('feedingIndividuItems')
            ->whereHas('feedingH', function ($q) use ($farm, $request) {
                $q->where('farm_id', $farm->id)->where('type', 'individu');

                if ($request->filled('start_date')) {
                    $q->where('transaction_date', '>=', $request->input('start_date'));
                }
                if ($request->filled('end_date')) {
                    $q->where('transaction_date', '<=', $request->input('end_date'));
                }
            });

        if ($request->filled('livestock_type_id')) {
            $query->whereHas('livestock', fn($q) => $q->where('livestock_type_id', $request->input('livestock_type_id')));
        }
        if ($request->filled('livestock_group_id')) {
            $query->whereHas('livestock', fn($q) => $q->where('livestock_group_id', $request->input('livestock_group_id')));
        }
        if ($request->filled('livestock_breed_id')) {
            $query->whereHas('livestock', fn($q) => $q->where('livestock_breed_id', $request->input('livestock_breed_id')));
        }
        if ($request->filled('livestock_sex_id')) {
            $query->whereHas('livestock', fn($q) => $q->where('livestock_sex_id', $request->input('livestock_sex_id')));
        }
        if ($request->filled('pen_id')) {
            $query->whereHas('livestock', fn($q) => $q->where('pen_id', $request->input('pen_id')));
        }
        if ($request->filled('livestock_id')) {
            $query->where('livestock_id', $request->input('livestock_id'));
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
    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        // Ambil ternak + relasi untuk label dropdown
        $q = Livestock::with([
            'livestockType:id,name',
            'livestockBreed:id,name',
        ])->where('farm_id', $farm->id);

        // Hindari orderBy kolom yang belum tentu ada
        if (Schema::hasColumn('livestocks', 'eartag_number')) {
            $q->orderBy('eartag_number');
        } elseif (Schema::hasColumn('livestocks', 'eartag')) {
            $q->orderBy('eartag');
        } else {
            $q->orderBy('id');
        }

        $livestocks = $q->get();

        return view('admin.care_livestock.feeding_individu.create', compact('farm','livestocks'));
    }

    // STORE
    public function store(FeedingIndividuStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($validated['livestock_id']);
        if (!$livestock) {
            return back()->withInput()->with('error', 'Livestock not found.');
        }

        try {
            DB::beginTransaction();

            $feedingH = FeedingH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'] ?? null,
            ]);

            $feedingIndividuD = FeedingIndividuD::create([
                'feeding_h_id' => $feedingH->id,
                'livestock_id' => $validated['livestock_id'],
                'notes'        => $validated['notes'] ?? null,
                'total_cost'   => 0,
            ]);

            $totalCost = 0;
            foreach ($validated['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingIndividuItem::create([
                    'feeding_individu_d_id' => $feedingIndividuD->id,
                    'type'                   => $item['type'],
                    'name'                   => $item['name'],
                    'qty_kg'                 => $item['qty_kg'],
                    'price_per_kg'           => $item['price_per_kg'],
                    'total_price'            => $totalPrice,
                ]);
            }

            $feedingIndividuD->update(['total_cost' => $totalCost]);

            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if (!$livestockExpense) {
                LivestockExpense::create([
                    'livestock_id'              => $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                    'amount'                    => $totalCost,
                ]);
            } else {
                $livestockExpense->update(['amount' => $livestockExpense->amount + $totalCost]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.feeding-individu.show', [
                    'farm_id' => $farmId,
                    'id'      => $feedingIndividuD->id,
                ])
                ->with('success', 'Data created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create FeedingIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while recording the data.');
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

    // FORM EDIT
    public function edit($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $feedingIndividu = FeedingIndividuD::with(['feedingH','feedingIndividuItems','livestock'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($feedingIndividuId);

        $q = Livestock::with([
            'livestockType:id,name',
            'livestockBreed:id,name',
        ])->where('farm_id', $farm->id);

        if (Schema::hasColumn('livestocks', 'eartag_number')) {
            $q->orderBy('eartag_number');
        } elseif (Schema::hasColumn('livestocks', 'eartag')) {
            $q->orderBy('eartag');
        } else {
            $q->orderBy('id');
        }

        $livestocks = $q->get();

        return view('admin.care_livestock.feeding_individu.edit', compact('farm','feedingIndividu','livestocks'));
    }

    // UPDATE
    public function update(FeedingIndividuUpdateRequest $request, $farmId, $feedingIndividuId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $feedingIndividuD = FeedingIndividuD::whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($feedingIndividuId);

        try {
            DB::beginTransaction();

            $feedingH = $feedingIndividuD->feedingH;
            $feedingH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestockExpenseOld = LivestockExpense::where('livestock_id', $feedingIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if ($livestockExpenseOld) {
                $livestockExpenseOld->update(['amount' => $livestockExpenseOld->amount - $feedingIndividuD->total_cost]);
            }

            FeedingIndividuItem::where('feeding_individu_d_id', $feedingIndividuD->id)->delete();

            $feedingIndividuD->update([
                'livestock_id' => $validated['livestock_id'],
                'notes'        => $validated['notes'] ?? null,
                'total_cost'   => 0,
            ]);

            $totalCost = 0;
            foreach ($validated['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingIndividuItem::create([
                    'feeding_individu_d_id' => $feedingIndividuD->id,
                    'type'                   => $item['type'],
                    'name'                   => $item['name'],
                    'qty_kg'                 => $item['qty_kg'],
                    'price_per_kg'           => $item['price_per_kg'],
                    'total_price'            => $totalPrice,
                ]);
            }

            $feedingIndividuD->update(['total_cost' => $totalCost]);

            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if (!$livestockExpense) {
                LivestockExpense::create([
                    'livestock_id'              => $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                    'amount'                    => $totalCost,
                ]);
            } else {
                $livestockExpense->update(['amount' => $livestockExpense->amount + $totalCost]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.feeding-individu.show', [
                    'farm_id' => $farmId,
                    'id'      => $feedingIndividuD->id,
                ])
                ->with('success', 'Data updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update FeedingIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the data.');
        }
    }

    // DELETE
    public function destroy($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $feedingIndividuD = FeedingIndividuD::whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($feedingIndividuId);

        try {
            DB::beginTransaction();

            $livestockExpense = LivestockExpense::where('livestock_id', $feedingIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if ($livestockExpense) {
                $livestockExpense->update(['amount' => $livestockExpense->amount - $feedingIndividuD->total_cost]);
            }

            FeedingIndividuItem::where('feeding_individu_d_id', $feedingIndividuD->id)->delete();

            $feedingH = $feedingIndividuD->feedingH;
            $feedingIndividuD->delete();

            if (!$feedingH->feedingIndividuD()->exists()) {
                $feedingH->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.feeding-individu.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete FeedingIndividu Error: '.$e->getMessage());
            return back()->with('error', 'An error occurred while deleting the data.');
        }
    }
}
