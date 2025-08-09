<?php

namespace App\Http\Controllers\Admin\CareLivestock\TreatmentLivestock;

use App\Http\Controllers\Controller;
use App\Models\TreatmentH;
use App\Models\TreatmentIndividuD;
use App\Models\TreatmentIndividuMedicineItem;
use App\Models\TreatmentIndividuTreatmentItem;
use App\Models\LivestockExpense;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Requests\Farming\TreatmentIndividuStoreRequest;
use App\Http\Requests\Farming\TreatmentIndividuUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Disease;

class TreatmentIndividuController extends Controller
{
    // LIST
public function index($farmId, Request $request)
{
    $farm = request()->attributes->get('farm');

    $query = TreatmentIndividuD::with(['treatmentH', 'livestock', 'disease'])
        ->withCount([
            'treatmentIndividuMedicineItems',
            'treatmentIndividuTreatmentItems',
        ])
        ->whereHas('treatmentH', function ($q) use ($farm, $request) {
            $q->where('farm_id', $farm->id)
              ->where('type', 'individu');

            if ($request->filled('start_date')) {
                $q->where('transaction_date', '>=', $request->input('start_date'));
            }
            if ($request->filled('end_date')) {
                $q->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

    if ($request->filled('disease_id')) {
        $query->where('disease_id', $request->input('disease_id'));
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
    if ($request->filled('livestock_id')) {
        $query->where('livestock_id', $request->input('livestock_id'));
    }

    $items = $query->get();

    return view('admin.care_livestock.treatment_individu.index', [
        'farm'    => $farm,
        'items'   => $items,
        'filters' => $request->only([
            'start_date','end_date','disease_id','livestock_type_id','livestock_group_id',
            'livestock_breed_id','livestock_sex_id','pen_id','livestock_id'
        ]),
    ]);
}


    // FORM CREATE
    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        $livestocks = $farm->livestocks()->get();
        $diseases   = Disease::all(); // atau $farm->diseases() kalau ada relasi per-farm

        return view('admin.care_livestock.treatment_individu.create', compact('farm', 'livestocks', 'diseases'));
    }

    // STORE
    public function store(TreatmentIndividuStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($validated['livestock_id']);
        if (!$livestock) {
            return back()->withInput()->with('error', 'Livestock not found in this farm.');
        }

        try {
            DB::beginTransaction();

            $treatmentH = TreatmentH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'] ?? null,
            ]);

            $treatmentIndividuD = TreatmentIndividuD::create([
                'treatment_h_id' => $treatmentH->id,
                'livestock_id'   => $validated['livestock_id'],
                'disease_id'     => $validated['disease_id'],
                'notes'          => $validated['notes'] ?? null,
                'total_cost'     => 0,
            ]);

            $totalCost = 0;

            foreach ($validated['medicines'] as $medicine) {
                $totalPrice = $medicine['qty_per_unit'] * $medicine['price_per_unit'];
                $totalCost += $totalPrice;

                TreatmentIndividuMedicineItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name'           => $medicine['name'],
                    'unit'           => $medicine['unit'],
                    'qty_per_unit'   => $medicine['qty_per_unit'],
                    'price_per_unit' => $medicine['price_per_unit'],
                    'total_price'    => $totalPrice,
                ]);
            }

            foreach ($validated['treatments'] as $treatment) {
                $totalCost += $treatment['cost'];

                TreatmentIndividuTreatmentItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name'  => $treatment['name'],
                    'cost'  => $treatment['cost'],
                ]);
            }

            $treatmentIndividuD->update(['total_cost' => $totalCost]);

            $expense = LivestockExpense::firstOrCreate(
                [
                    'livestock_id'              => $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                ],
                ['amount' => 0]
            );
            $expense->update(['amount' => $expense->amount + $totalCost]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.treatment-individu.show', ['farm_id' => $farmId, 'id' => $treatmentIndividuD->id])
                ->with('success', 'Data created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create TreatmentIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while recording the data.');
        }
    }

    // SHOW
    public function show($farmId, $treatmentIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $treatmentIndividu = TreatmentIndividuD::with([
                'treatmentH',
                'livestock',
                'treatmentIndividuMedicineItems',
                'treatmentIndividuTreatmentItems',
            ])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type','individu'))
            ->findOrFail($treatmentIndividuId);

        return view('admin.care_livestock.treatment_individu.show', [
            'farm' => $farm,
            'treatmentIndividu' => $treatmentIndividu,
        ]);
    }

    // FORM EDIT
    public function edit($farmId, $treatmentIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $treatmentIndividu = TreatmentIndividuD::with([
                'treatmentH',
                'livestock',
                'treatmentIndividuMedicineItems',
                'treatmentIndividuTreatmentItems',
            ])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type','individu'))
            ->findOrFail($treatmentIndividuId);

        $livestocks = $farm->livestocks()->get();
        $diseases   = Disease::all(); // atau $farm->diseases()

        return view('admin.care_livestock.treatment_individu.edit', compact('farm','treatmentIndividu','livestocks','diseases'));
    }

    // UPDATE
    public function update(TreatmentIndividuUpdateRequest $request, $farmId, $treatmentIndividuId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $treatmentIndividuD = TreatmentIndividuD::with(['treatmentH','livestock'])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type','individu'))
            ->findOrFail($treatmentIndividuId);

        try {
            DB::beginTransaction();

            // update header
            $treatmentH = $treatmentIndividuD->treatmentH;
            $treatmentH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            // rollback biaya lama
            $oldExpense = LivestockExpense::where('livestock_id', $treatmentIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                ->first();
            if ($oldExpense) {
                $oldExpense->update([
                    'amount' => $oldExpense->amount - ($treatmentIndividuD->total_cost ?? 0),
                ]);
            }

            // reset items
            TreatmentIndividuMedicineItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();
            TreatmentIndividuTreatmentItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();

            // update detail utama
            $treatmentIndividuD->update([
                'livestock_id' => $validated['livestock_id'],
                'disease_id'   => $validated['disease_id'],
                'notes'        => $validated['notes'] ?? null,
                'total_cost'   => 0,
            ]);

            // hitung ulang items
            $totalCost = 0;

            foreach ($validated['medicines'] as $medicine) {
                $totalPrice = $medicine['qty_per_unit'] * $medicine['price_per_unit'];
                $totalCost += $totalPrice;

                TreatmentIndividuMedicineItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name'           => $medicine['name'],
                    'unit'           => $medicine['unit'],
                    'qty_per_unit'   => $medicine['qty_per_unit'],
                    'price_per_unit' => $medicine['price_per_unit'],
                    'total_price'    => $totalPrice,
                ]);
            }

            foreach ($validated['treatments'] as $treatment) {
                $totalCost += $treatment['cost'];

                TreatmentIndividuTreatmentItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name'  => $treatment['name'],
                    'cost'  => $treatment['cost'],
                ]);
            }

            $treatmentIndividuD->update(['total_cost' => $totalCost]);

            // update expense baru
            $expense = LivestockExpense::firstOrCreate(
                [
                    'livestock_id'              => $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                ],
                ['amount' => 0]
            );
            $expense->update(['amount' => $expense->amount + $totalCost]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.treatment-individu.show', ['farm_id' => $farmId, 'id' => $treatmentIndividuD->id])
                ->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update TreatmentIndividu Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the data.');
        }
    }

    // DELETE
    public function destroy($farmId, $treatmentIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $treatmentIndividuD = TreatmentIndividuD::with('treatmentH','livestock')
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type','individu'))
            ->findOrFail($treatmentIndividuId);

        try {
            DB::beginTransaction();

            // rollback expense
            $expense = LivestockExpense::where('livestock_id', $treatmentIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                ->first();
            if ($expense) {
                $expense->update([
                    'amount' => $expense->amount - ($treatmentIndividuD->total_cost ?? 0),
                ]);
            }

            // hapus items
            TreatmentIndividuMedicineItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();
            TreatmentIndividuTreatmentItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();

            // simpan reference sebelum delete
            $treatmentH = $treatmentIndividuD->treatmentH;

            // delete detail
            $treatmentIndividuD->delete();

            // hapus header jika tak ada detail lain
            if ($treatmentH && !$treatmentH->treatmentIndividuD()->exists()) {
                $treatmentH->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.treatment-individu.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete TreatmentIndividu Error: '.$e->getMessage());
            return back()->with('error', 'An error occurred while deleting the data.');
        }
    }
}
