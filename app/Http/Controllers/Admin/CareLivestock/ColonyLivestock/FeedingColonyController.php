<?php

namespace App\Http\Controllers\Admin\CareLivestock\ColonyLivestock;

use App\Models\FeedingH;
use Illuminate\Http\Request;
use App\Models\FeedingColonyD;
use App\Models\LivestockExpense;
use App\Models\FeedingColonyItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\FeedingColonyLivestock;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Requests\Farming\FeedingColonyStoreRequest;
use App\Http\Requests\Farming\FeedingColonyUpdateRequest;

class FeedingColonyController extends Controller
{
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $feedingColony = FeedingColonyD::whereHas('feedingH', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }
            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        if ($request->filled('pen_id')) {
            $feedingColony->where('pen_id', $request->input('pen_id'));
        }

        $data = $feedingColony->get();

        return view('admin.care_livestock.colony_livestock.feeding_colony.index', compact('farm', 'data'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        // Tambahkan data lain (pens, dsb) kalau view kamu butuh
        return view('admin.care_livestock.colony_livestock.feeding_colony.create', compact('farm'));
    }

    public function store(FeedingColonyStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');
        $pen = $farm->pens()->find($validated['pen_id']);

        if (!$pen) {
            return redirect()->back()->with('error', 'Pen not found.');
        }

        $livestocks = $pen->livestocks;
        $totalLivestocks = count($livestocks);

        if ($totalLivestocks < 1) {
            return redirect()->back()->with('error', 'There is no livestock in this pen.');
        }

        try {
            DB::beginTransaction();

            $feedingH = FeedingH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'colony',
                'notes'            => $validated['notes'],
            ]);

            $feedingColonyD = FeedingColonyD::create([
                'feeding_h_id' =>  $feedingH->id,
                'pen_id' => $validated['pen_id'],
                'notes' => $validated['notes'] ?? null,
                'total_livestock' => $totalLivestocks,
                'total_cost' => 0,
                'average_cost' => 0,
            ]);

            $totalCost = 0;

            foreach($validated['items'] as $item){
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingColonyItem::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'qty_kg' => $item['qty_kg'],
                    'price_per_kg' => $item['price_per_kg'],
                    'total_price' => $totalPrice,
                ]);
            }

            $averageCost =  $totalCost / $totalLivestocks;

            $feedingColonyD->update([
                'total_cost' => $totalCost,
                'average_cost' => $averageCost
            ]);

            foreach($livestocks as $livestock){
                FeedingColonyLivestock::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'livestock_id' => $livestock->id
                ]);

                $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();

                if(!$livestockExpense){
                    LivestockExpense::create([
                        'livestock_id' =>  $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                        'amount' => $averageCost
                    ]);
                }else{
                    $oldAmount = $livestockExpense->amount;
                    $livestockExpense->update(['amount' => $oldAmount + $averageCost]);
                }
            }

            DB::commit();

            return redirect()->route('admin.care-livestock.feeding-colony.index', $farm->id)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');
        $feedingColony = FeedingColonyD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($feedingColonyId);

        return view('admin.care_livestock.colony_livestock.feeding_colony.show', compact('farm', 'feedingColony'));
    }

    public function edit($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');
        $feedingColony = FeedingColonyD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($feedingColonyId);

        return view('admin.care_livestock.colony_livestock.feeding_colony.edit', compact('farm', 'feedingColony'));
    }

    public function update(FeedingColonyUpdateRequest $request, $farmId , $feedingColonyId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');
        $feedingColonyD = FeedingColonyD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($feedingColonyId);

        try {
            DB::beginTransaction();

            $feedingH = $feedingColonyD->feedingH;

            $feedingH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestocks =  $feedingColonyD->livestocks;
            $totalLivestocks = count($livestocks);

            foreach($livestocks as $livestock){
                $livestockExpenseOld = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();

                $oldAmount = $livestockExpenseOld->amount;
                $livestockExpenseOld->update(['amount' => $oldAmount - $feedingColonyD->average_cost]);
            }

            FeedingColonyItem::where('feeding_colony_d_id', $feedingColonyD->id)->delete();

            $feedingColonyD->update([
                'notes' => $validated['notes'] ?? null,
                'total_cost' => 0,
                'average_cost' => 0,
            ]);

            $totalCost = 0;

            foreach ($validated['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingColonyItem::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'qty_kg' => $item['qty_kg'],
                    'price_per_kg' => $item['price_per_kg'],
                    'total_price' => $totalPrice,
                ]);
            }

            $averageCost =  $totalCost / $totalLivestocks;

            $feedingColonyD->update([
                'total_cost' => $totalCost,
                'average_cost' => $averageCost
            ]);

            foreach($livestocks as $livestock){
                $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();

                if(!$livestockExpense){
                    LivestockExpense::create([
                        'livestock_id' => $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                        'amount' => $averageCost
                    ]);
                }else{
                    $oldAmount = $livestockExpense->amount;
                    $livestockExpense->update(['amount' => $oldAmount + $averageCost]);
                }
            }

            DB::commit();

            return redirect()->route('admin.care-livestock.feeding-colony.index', $farm->id)
                ->with('success', 'Data berhasil diupdate.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat update data.');
        }
    }

    public function destroy($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');
        $feedingColonyD = FeedingColonyD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($feedingColonyId);

        try {
            DB::beginTransaction();

            $livestocks = $feedingColonyD->livestocks;

            foreach($livestocks as $livestock){
                $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();

                if ($livestockExpense) {
                    $livestockExpense->update([
                        'amount' => $livestockExpense->amount - $feedingColonyD->average_cost
                    ]);
                }
            }

            FeedingColonyItem::where('feeding_colony_d_id', $feedingColonyD->id)->delete();
            FeedingColonyLivestock::where('feeding_colony_d_id', $feedingColonyD->id)->delete();
            $feedingColonyD->delete();

            $feedingH = $feedingColonyD->feedingH;
            if ($feedingH && !$feedingH->feedingColonyD()->exists()) {
                $feedingH->delete();
            }

            DB::commit();

            return redirect()->route('admin.care-livestock.feeding-colony.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete FeedingColony Error: ', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
