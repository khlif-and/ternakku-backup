<?php

namespace App\Http\Controllers\Admin\CareLivestock\TreatmentLivestock;

use App\Http\Controllers\Controller;
use App\Models\TreatmentH;
use App\Models\TreatmentColonyD;
use App\Models\TreatmentColonyMedicineItem;
use App\Models\TreatmentColonyTreatmentItem;
use App\Models\TreatmentColonyLivestock;
use App\Models\LivestockExpense;
use App\Models\Disease;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Requests\Farming\TreatmentColonyStoreRequest;
use App\Http\Requests\Farming\TreatmentColonyUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TreatmentColonyController extends Controller
{
    // LIST
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $query = TreatmentColonyD::with(['treatmentH', 'pen'])
            ->withCount([
                'treatmentColonyMedicineItems',
                'treatmentColonyTreatmentItems',
            ])
            ->whereHas('treatmentH', function ($q) use ($farm, $request) {
                $q->where('farm_id', $farm->id)->where('type', 'colony');

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
        if ($request->filled('pen_id')) {
            $query->where('pen_id', $request->input('pen_id'));
        }

        $items = $query->get();

        return view('admin.care_livestock.treatment_colony.index', [
            'farm'    => $farm,
            'items'   => $items,
            'filters' => $request->only(['start_date', 'end_date', 'disease_id', 'pen_id']),
        ]);
    }

    // FORM CREATE
    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        $pens     = $farm->pens()->get();
        $diseases = Disease::all(); // atau $farm->diseases() jika ada relasi per-farm

        return view('admin.care_livestock.treatment_colony.create', compact('farm', 'pens', 'diseases'));
    }

    // STORE
    public function store(TreatmentColonyStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $pen = $farm->pens()->find($validated['pen_id']);
        if (!$pen) {
            return back()->withInput()->with('error', 'Pen not found.');
        }

        $livestocks = $pen->livestocks;
        $totalLivestocks = $livestocks->count();

        if ($totalLivestocks < 1) {
            return back()->withInput()->with('error', 'There is no livestock in this pen.');
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
                'treatment_h_id'  => $treatmentH->id,
                'pen_id'          => $validated['pen_id'],
                'disease_id'      => $validated['disease_id'],
                'notes'           => $validated['notes'] ?? null,
                'total_livestock' => $totalLivestocks,
                'total_cost'      => 0,
                'average_cost'    => 0,
            ]);

            $totalCost = 0;

            foreach ($validated['medicines'] as $medicine) {
                $totalPrice = $medicine['qty_per_unit'] * $medicine['price_per_unit'];
                $totalCost += $totalPrice;

                TreatmentColonyMedicineItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name'           => $medicine['name'],
                    'unit'           => $medicine['unit'],
                    'qty_per_unit'   => $medicine['qty_per_unit'],
                    'price_per_unit' => $medicine['price_per_unit'],
                    'total_price'    => $totalPrice,
                ]);
            }

            foreach ($validated['treatments'] as $treatment) {
                $totalCost += $treatment['cost'];

                TreatmentColonyTreatmentItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name'  => $treatment['name'],
                    'cost'  => $treatment['cost'],
                ]);
            }

            $averageCost = $totalLivestocks > 0 ? ($totalCost / $totalLivestocks) : 0;

            $treatmentColonyD->update([
                'total_cost'   => $totalCost,
                'average_cost' => $averageCost,
            ]);

            foreach ($livestocks as $livestock) {
                TreatmentColonyLivestock::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'livestock_id'          => $livestock->id,
                ]);

                $expense = LivestockExpense::firstOrCreate(
                    [
                        'livestock_id'              => $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                    ],
                    ['amount' => 0]
                );
                $expense->update(['amount' => $expense->amount + $averageCost]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.treatment-colony.show', ['farm_id' => $farmId, 'id' => $treatmentColonyD->id])
                ->with('success', 'Data created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create TreatmentColony Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while recording the data.');
        }
    }

    // SHOW
    public function show($farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');

        $treatmentColony = TreatmentColonyD::with([
                'treatmentH',
                'pen',
                'livestocks',
                'treatmentColonyMedicineItems',
                'treatmentColonyTreatmentItems',
            ])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($treatmentColonyId);

        return view('admin.care_livestock.treatment_colony.show', [
            'farm' => $farm,
            'treatmentColony' => $treatmentColony,
        ]);
    }

    // FORM EDIT
    public function edit($farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');

        $treatmentColony = TreatmentColonyD::with([
                'treatmentH',
                'pen',
                'livestocks',
                'treatmentColonyMedicineItems',
                'treatmentColonyTreatmentItems',
            ])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($treatmentColonyId);

        $pens     = $farm->pens()->get();
        $diseases = Disease::all(); // atau $farm->diseases()

        return view('admin.care_livestock.treatment_colony.edit', compact('farm', 'treatmentColony', 'pens', 'diseases'));
    }

    // UPDATE
    public function update(TreatmentColonyUpdateRequest $request, $farmId, $treatmentColonyId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $treatmentColonyD = TreatmentColonyD::with(['treatmentH', 'livestocks'])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($treatmentColonyId);

        try {
            DB::beginTransaction();

            // 1) Update header
            $treatmentH = $treatmentColonyD->treatmentH;
            $treatmentH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestocks = $treatmentColonyD->livestocks;
            $totalLivestocks = $livestocks->count();

            // 2) Rollback biaya lama per ternak
            foreach ($livestocks as $livestock) {
                $expense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();

                if ($expense) {
                    $expense->update([
                        'amount' => $expense->amount - ($treatmentColonyD->average_cost ?? 0),
                    ]);
                }
            }

            // 3) Hapus item lama & reset total
            TreatmentColonyMedicineItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            TreatmentColonyTreatmentItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();

            $treatmentColonyD->update([
                'disease_id'   => $validated['disease_id'],
                'notes'        => $validated['notes'] ?? null,
                'total_cost'   => 0,
                'average_cost' => 0,
            ]);

            // 4) Tambahkan item baru & hitung ulang total
            $totalCost = 0;

            foreach ($validated['medicines'] as $medicine) {
                $totalPrice = $medicine['qty_per_unit'] * $medicine['price_per_unit'];
                $totalCost += $totalPrice;

                TreatmentColonyMedicineItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name'           => $medicine['name'],
                    'unit'           => $medicine['unit'],
                    'qty_per_unit'   => $medicine['qty_per_unit'],
                    'price_per_unit' => $medicine['price_per_unit'],
                    'total_price'    => $totalPrice,
                ]);
            }

            foreach ($validated['treatments'] as $treatment) {
                $totalCost += $treatment['cost'];

                TreatmentColonyTreatmentItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name'  => $treatment['name'],
                    'cost'  => $treatment['cost'],
                ]);
            }

            $averageCost = $totalLivestocks > 0 ? ($totalCost / $totalLivestocks) : 0;

            $treatmentColonyD->update([
                'total_cost'   => $totalCost,
                'average_cost' => $averageCost,
            ]);

            // 5) Tambahkan biaya baru ke tiap ternak
            foreach ($livestocks as $livestock) {
                $expense = LivestockExpense::firstOrCreate(
                    [
                        'livestock_id'              => $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                    ],
                    ['amount' => 0]
                );

                $expense->update([
                    'amount' => $expense->amount + $averageCost,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.treatment-colony.show', ['farm_id' => $farmId, 'id' => $treatmentColonyD->id])
                ->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update TreatmentColony Error: '.$e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the data.');
        }
    }

    // DELETE
    public function destroy($farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');

        $treatmentColonyD = TreatmentColonyD::with(['treatmentH', 'livestocks'])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($treatmentColonyId);

        try {
            DB::beginTransaction();

            // Rollback expense per ternak
            foreach ($treatmentColonyD->livestocks as $livestock) {
                $expense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();

                if ($expense) {
                    $expense->update([
                        'amount' => $expense->amount - ($treatmentColonyD->average_cost ?? 0),
                    ]);
                }
            }

            // Hapus item & pivot
            TreatmentColonyMedicineItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            TreatmentColonyTreatmentItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            TreatmentColonyLivestock::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();

            // Simpan reference header sebelum delete detail
            $treatmentH = $treatmentColonyD->treatmentH;

            // Hapus detail
            $treatmentColonyD->delete();

            // Hapus header jika tidak ada detail lain
            if ($treatmentH && !$treatmentH->treatmentColonyD()->exists()) {
                $treatmentH->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.treatment-colony.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete TreatmentColony Error: '.$e->getMessage());
            return back()->with('error', 'An error occurred while deleting the data.');
        }
    }
}
