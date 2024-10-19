<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\TreatmentH;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\LivestockExpense;
use App\Models\TreatmentIndividuD;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\LivestockExpenseTypeEnum;
use App\Models\TreatmentIndividuMedicineItem;
use App\Models\TreatmentIndividuTreatmentItem;
use App\Http\Resources\Farming\TreatmentIndividuResource;
use App\Http\Requests\Farming\TreatmentIndividuStoreRequest;
use App\Http\Requests\Farming\TreatmentIndividuUpdateRequest;

class TreatmentIndividuController extends Controller
{
    public function store(TreatmentIndividuStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($validated['livestock_id']);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        try {
            DB::beginTransaction();  // Awal transaksional

            $treatmentH = TreatmentH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'] ?? null,
            ]);

            $treatmentIndividuD = TreatmentIndividuD::create([
                'treatment_h_id' =>  $treatmentH->id,
                'livestock_id' => $validated['livestock_id'],
                'disease_id' => $validated['disease_id'],
                'notes' => $validated['notes'] ?? null,
                'total_cost' => 0
            ]);

            $totalCost = 0;

            foreach($validated['medicines'] as $medicine){
                $totalPrice = $medicine['qty_per_unit'] * $medicine['price_per_unit'];
                $totalCost += $totalPrice;

                TreatmentIndividuMedicineItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name' => $medicine['name'],
                    'unit' => $medicine['unit'],
                    'qty_per_unit' => $medicine['qty_per_unit'],
                    'price_per_unit' => $medicine['price_per_unit'],
                    'total_price' => $totalPrice,
                ]);
            }

            foreach($validated['treatments'] as $treatment){
                $totalCost += $treatment['cost'];

                TreatmentIndividuTreatmentItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name' => $treatment['name'],
                    'cost' => $treatment['cost'],
                ]);
            }
            $treatmentIndividuD->update([
                'total_cost' => $totalCost
            ]);
            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                ->first();

            if(!$livestockExpense){
                LivestockExpense::create([
                    'livestock_id' =>  $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                    'amount' => $totalCost
                ]);
            }else{
                $oldAmount = $livestockExpense->amount;
                $livestockExpense->update(['amount' => $oldAmount + $totalCost]);
            }

            DB::commit();

            return ResponseHelper::success(new TreatmentIndividuResource($treatmentIndividuD), 'Data created successfully', 200);


        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $treatmentIndividu = TreatmentIndividuD::whereHas('treatmentH', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');

            // Filter berdasarkan start_date atau end_date dari transaction_number
            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }

            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        if ($request->filled('disease_id')) {
            $treatmentIndividu->where('disease_id', $request->input('disease_id'));
        }

        // Filter berdasarkan relasi Livestock (misalnya livestock_type_id)
        if ($request->filled('livestock_type_id')) {
            $treatmentIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $treatmentIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $treatmentIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('livestock_sex_id')) {
            $treatmentIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_sex_id', $request->input('livestock_sex_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $treatmentIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        if ($request->filled('livestock_id')) {
            $treatmentIndividu->where('livestock_id', $request->input('livestock_id'));
        }

        $data = TreatmentIndividuResource::collection($treatmentIndividu->get());

        $message = $treatmentIndividu->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function show($farmId, $treatmentIndividuId)
    {
        $farm = request()->attributes->get('farm');

        $treatmentIndividu = TreatmentIndividuD::whereHas('treatmentH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($treatmentIndividuId);

        return ResponseHelper::success(new TreatmentIndividuResource($treatmentIndividu), 'Data retrieved successfully');
    }

    public function update(TreatmentIndividuUpdateRequest $request, $farmId , $treatmentIndividuId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $treatmentIndividuD = TreatmentIndividuD::whereHas('treatmentH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($treatmentIndividuId);

        try {

            DB::beginTransaction();

            $treatmentH = $treatmentIndividuD->treatmentH;

            $treatmentH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestockExpenseOld = LivestockExpense::where('livestock_id', $treatmentIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                ->first();


            $oldAmount = $livestockExpenseOld->amount;
            $livestockExpenseOld->update(['amount' => $oldAmount - $treatmentIndividuD->total_cost]);

            TreatmentIndividuMedicineItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();

            TreatmentIndividuTreatmentItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();

            $treatmentIndividuD->update([
                'livestock_id' => $validated['livestock_id'],
                'disease_id' => $validated['disease_id'],
                'notes' => $validated['notes'] ?? null,
                'total_cost' => 0
            ]);

            $totalCost = 0;

            foreach($validated['medicines'] as $medicine){
                $totalPrice = $medicine['qty_per_unit'] * $medicine['price_per_unit'];
                $totalCost += $totalPrice;

                TreatmentIndividuMedicineItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name' => $medicine['name'],
                    'unit' => $medicine['unit'],
                    'qty_per_unit' => $medicine['qty_per_unit'],
                    'price_per_unit' => $medicine['price_per_unit'],
                    'total_price' => $totalPrice,
                ]);
            }

            foreach($validated['treatments'] as $treatment){
                $totalCost += $treatment['cost'];

                TreatmentIndividuTreatmentItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name' => $treatment['name'],
                    'cost' => $treatment['cost'],
                ]);
            }

            // Step 5: Update the total cost in treatmentIndividuD
            $treatmentIndividuD->update(['total_cost' => $totalCost]);

            // Step 6: Update the LivestockExpense with the new total cost
            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                ->first();

            if(!$livestockExpense){
                LivestockExpense::create([
                    'livestock_id' =>  $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                    'amount' => $totalCost
                ]);
            }else{
                $oldAmount = $livestockExpense->amount;
                $livestockExpense->update(['amount' => $oldAmount + $totalCost]);
            }

            DB::commit();

            return ResponseHelper::success(new TreatmentIndividuResource($treatmentIndividuD), 'Data updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while uodating the data.', 500);
        }
    }

    public function destroy($farmId, $treatmentIndividuId)
    {
        $farm = request()->attributes->get('farm');

        // Cari treatmentIndividuD dengan memastikan farm dan tipe individu
        $treatmentIndividuD = TreatmentIndividuD::whereHas('treatmentH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'individu');
        })->findOrFail($treatmentIndividuId);

        try {
            DB::beginTransaction();  // Awal transaksional

            $livestockExpense = LivestockExpense::where('livestock_id', $treatmentIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                ->first();

            if ($livestockExpense) {
                $livestockExpense->update([
                    'amount' => $livestockExpense->amount - $treatmentIndividuD->total_cost
                ]);
            }

            TreatmentIndividuMedicineItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();
            TreatmentIndividuTreatmentItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();

            $treatmentIndividuD->delete();

            $treatmentH = $treatmentIndividuD->treatmentH;
            if (!$treatmentH->treatmentIndividuD()->exists()) {
                $treatmentH->delete();
            }

            DB::commit();  // Commit transaksi jika semua proses berhasil

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();  // Rollback jika ada kesalahan

            // Log error untuk debugging (opsional)
            Log::error('Delete treatmentIndividu Error: ', ['error' => $e->getMessage()]);

            // Handle exceptions dan kembalikan respon error
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }
}
