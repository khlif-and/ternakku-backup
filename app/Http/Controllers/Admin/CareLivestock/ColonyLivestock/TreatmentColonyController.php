<?php

namespace App\Http\Controllers\Admin\CareLivestock\ColonyLivestock;

use App\Models\TreatmentH;
use Illuminate\Http\Request;
use App\Models\LivestockExpense;
use App\Models\TreatmentColonyD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Enums\LivestockExpenseTypeEnum;
use App\Models\TreatmentColonyLivestock;
use App\Models\TreatmentColonyMedicineItem;
use App\Models\TreatmentColonyTreatmentItem;
use App\Http\Requests\Farming\TreatmentColonyStoreRequest;
use App\Http\Requests\Farming\TreatmentColonyUpdateRequest;

class TreatmentColonyController extends Controller
{
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $treatmentColony = TreatmentColonyD::whereHas('treatmentH', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }
            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        if ($request->filled('disease_id')) {
            $treatmentColony->where('disease_id', $request->input('disease_id'));
        }

        if ($request->filled('pen_id')) {
            $treatmentColony->where('pen_id', $request->input('pen_id'));
        }

        $data = $treatmentColony->get();

        return view('admin.care_livestock.colony_livestock.treatment_colony.index', compact('farm', 'data'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.colony_livestock.treatment_colony.create', compact('farm'));
    }

    public function store(TreatmentColonyStoreRequest $request, $farmId)
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

            $treatmentH = TreatmentH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'colony',
                'notes'            => $validated['notes'] ?? null,
            ]);

            $treatmentColonyD = TreatmentColonyD::create([
                'treatment_h_id' => $treatmentH->id,
                'pen_id' => $validated['pen_id'],
                'disease_id' => $validated['disease_id'],
                'notes' => $validated['notes'] ?? null,
                'total_livestock' => $totalLivestocks,
                'total_cost' => 0,
                'average_cost' => 0,
            ]);

            $totalCost = 0;

            foreach($validated['medicines'] as $medicine){
                $totalPrice = $medicine['qty_per_unit'] * $medicine['price_per_unit'];
                $totalCost += $totalPrice;

                TreatmentColonyMedicineItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name' => $medicine['name'],
                    'unit' => $medicine['unit'],
                    'qty_per_unit' => $medicine['qty_per_unit'],
                    'price_per_unit' => $medicine['price_per_unit'],
                    'total_price' => $totalPrice,
                ]);
            }

            foreach($validated['treatments'] as $treatment){
                $totalCost += $treatment['cost'];

                TreatmentColonyTreatmentItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name' => $treatment['name'],
                    'cost' => $treatment['cost'],
                ]);
            }

            $averageCost =  $totalCost / $totalLivestocks;

            $treatmentColonyD->update([
                'total_cost' => $totalCost,
                'average_cost' => $averageCost
            ]);

            foreach($livestocks as $livestock){
                TreatmentColonyLivestock::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'livestock_id' => $livestock->id
                ]);

                $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();

                if(!$livestockExpense){
                    LivestockExpense::create([
                        'livestock_id' =>  $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                        'amount' => $averageCost
                    ]);
                }else{
                    $oldAmount = $livestockExpense->amount;
                    $livestockExpense->update(['amount' => $oldAmount + $averageCost]);
                }
            }

            DB::commit();

            return redirect()->route('admin.care-livestock.treatment-colony.index', $farm->id)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');
        $treatmentColony = TreatmentColonyD::whereHas('treatmentH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($treatmentColonyId);

        return view('admin.care_livestock.colony_livestock.treatment_colony.show', compact('farm', 'treatmentColony'));
    }

    public function edit($farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');
        $treatmentColony = TreatmentColonyD::whereHas('treatmentH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($treatmentColonyId);

        return view('admin.care_livestock.colony_livestock.treatment_colony.edit', compact('farm', 'treatmentColony'));
    }

    public function update(TreatmentColonyUpdateRequest $request, $farmId, $treatmentColonyId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');
        $treatmentColonyD = TreatmentColonyD::whereHas('treatmentH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($treatmentColonyId);

        try {
            DB::beginTransaction();

            $treatmentH = $treatmentColonyD->treatmentH;
            $treatmentH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestocks =  $treatmentColonyD->livestocks;
            $totalLivestocks = count($livestocks);

            foreach($livestocks as $livestock){
                $livestockExpenseOld = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();

                $oldAmount = $livestockExpenseOld->amount;
                $livestockExpenseOld->update(['amount' => $oldAmount - $treatmentColonyD->average_cost]);
            }

            TreatmentColonyMedicineItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            TreatmentColonyTreatmentItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();

            $treatmentColonyD->update([
                'disease_id' => $validated['disease_id'],
                'notes' => $validated['notes'] ?? null,
                'total_cost' => 0,
                'average_cost' => 0,
            ]);

            $totalCost = 0;

            foreach($validated['medicines'] as $medicine){
                $totalPrice = $medicine['qty_per_unit'] * $medicine['price_per_unit'];
                $totalCost += $totalPrice;

                TreatmentColonyMedicineItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name' => $medicine['name'],
                    'unit' => $medicine['unit'],
                    'qty_per_unit' => $medicine['qty_per_unit'],
                    'price_per_unit' => $medicine['price_per_unit'],
                    'total_price' => $totalPrice,
                ]);
            }

            foreach($validated['treatments'] as $treatment){
                $totalCost += $treatment['cost'];

                TreatmentColonyTreatmentItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name' => $treatment['name'],
                    'cost' => $treatment['cost'],
                ]);
            }

            $averageCost =  $totalCost / $totalLivestocks;

            $treatmentColonyD->update([
                'total_cost' => $totalCost,
                'average_cost' => $averageCost
            ]);

            foreach($livestocks as $livestock){
                $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();

                if(!$livestockExpense){
                    LivestockExpense::create([
                        'livestock_id' =>  $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                        'amount' => $averageCost
                    ]);
                }else{
                    $oldAmount = $livestockExpense->amount;
                    $livestockExpense->update(['amount' => $oldAmount + $averageCost]);
                }
            }

            DB::commit();

            return redirect()->route('admin.care-livestock.treatment-colony.index', $farm->id)
                ->with('success', 'Data berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat update data.');
        }
    }

    public function destroy($farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');
        $treatmentColonyD = TreatmentColonyD::whereHas('treatmentH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($treatmentColonyId);

        try {
            DB::beginTransaction();

            $livestocks = $treatmentColonyD->livestocks;

            foreach($livestocks as $livestock){
                $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();

                if ($livestockExpense) {
                    $livestockExpense->update([
                        'amount' => $livestockExpense->amount - $treatmentColonyD->average_cost
                    ]);
                }
            }

            TreatmentColonyMedicineItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            TreatmentColonyTreatmentItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            TreatmentColonyLivestock::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            $treatmentColonyD->delete();

            $treatmentH = $treatmentColonyD->treatmentH;
            if ($treatmentH && !$treatmentH->treatmentColonyD()->exists()) {
                $treatmentH->delete();
            }

            DB::commit();

            return redirect()->route('admin.care-livestock.treatment-colony.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete treatmentColony Error: ', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
