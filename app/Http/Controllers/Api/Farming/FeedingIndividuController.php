<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\FeedingH;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Models\FeedingIndividuD;
use App\Models\LivestockExpense;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\FeedingIndividuItem;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Resources\Farming\FeedingIndividuResource;
use App\Http\Requests\Farming\FeedingIndividuStoreRequest;
use App\Http\Requests\Farming\FeedingIndividuUpdateRequest;

class FeedingIndividuController extends Controller
{
    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $feedingIndividu = FeedingIndividuD::whereHas('feedingH', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');

            // Filter berdasarkan start_date atau end_date dari transaction_number
            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }

            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        // Filter berdasarkan relasi Livestock (misalnya livestock_type_id)
        if ($request->filled('livestock_type_id')) {
            $feedingIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $feedingIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $feedingIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('livestock_sex_id')) {
            $feedingIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_sex_id', $request->input('livestock_sex_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $feedingIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        if ($request->filled('livestock_id')) {
            $feedingIndividu->where('livestock_id', $request->input('livestock_id'));
        }

        $data = FeedingIndividuResource::collection($feedingIndividu->get());

        $message = $feedingIndividu->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function store(FeedingIndividuStoreRequest $request, $farmId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($validated['livestock_id']);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        try {

            DB::beginTransaction();  // Awal transaksional

            $feedingH = FeedingH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'],
            ]);

            $feedingIndividuD = FeedingIndividuD::create([
                'feeding_h_id' =>  $feedingH->id,
                'livestock_id' => $validated['livestock_id'],
                'notes' => $validated['notes'] ?? null,
                'total_cost' => 0
            ]);

            $totalCost = 0;

            foreach($validated['items'] as $item){
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

            $feedingIndividuD->update([
                'total_cost' => $totalCost
            ]);

            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if(!$livestockExpense){
                LivestockExpense::create([
                    'livestock_id' =>  $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                    'amount' => $totalCost
                ]);
            }else{
                $oldAmount = $livestockExpense->amount;
                $livestockExpense->update(['amount' => $oldAmount + $totalCost]);
            }

            DB::commit();

            return ResponseHelper::success(new FeedingIndividuResource($feedingIndividuD), 'Data created successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function show($farmId, $feedingIndividuId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $feedingIndividu = FeedingIndividuD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($feedingIndividuId);

        return ResponseHelper::success(new FeedingIndividuResource($feedingIndividu), 'Data retrieved successfully');
    }

    public function update(FeedingIndividuUpdateRequest $request, $farmId , $feedingIndividuId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $feedingIndividuD = FeedingIndividuD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($feedingIndividuId);
        try {

            DB::beginTransaction();  // Awal transaksional

            $feedingH = $feedingIndividuD->feedingH;

            $feedingH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestockExpenseOld = LivestockExpense::where('livestock_id', $feedingIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();


            $oldAmount = $livestockExpenseOld->amount;
            $livestockExpenseOld->update(['amount' => $oldAmount - $feedingIndividuD->total_cost]);

            // Step 2: Delete existing FeedingIndividuItem records associated with this FeedingIndividuD
            FeedingIndividuItem::where('feeding_individu_d_id', $feedingIndividuD->id)->delete();

             // Step 3: Update the FeedingIndividuD record with new notes or other fields
            $feedingIndividuD->update([
                'livestock_id' => $validated['livestock_id'],
                'notes' => $validated['notes'] ?? null,
                'total_cost' => 0
            ]);

            // Step 4: Create new FeedingIndividuItem records
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

            // Step 5: Update the total cost in FeedingIndividuD
            $feedingIndividuD->update(['total_cost' => $totalCost]);

            // Step 6: Update the LivestockExpense with the new total cost
            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if(!$livestockExpense){
                LivestockExpense::create([
                    'livestock_id' =>  $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                    'amount' => $totalCost
                ]);
            }else{
                $oldAmount = $livestockExpense->amount;
                $livestockExpense->update(['amount' => $oldAmount + $totalCost]);
            }

            DB::commit();

            return ResponseHelper::success(new FeedingIndividuResource($feedingIndividuD), 'Data updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while uodating the data.', 500);
        }
    }

    public function destroy($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');

        // Cari FeedingIndividuD dengan memastikan farm dan tipe individu
        $feedingIndividuD = FeedingIndividuD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'individu');
        })->findOrFail($feedingIndividuId);

        try {
            DB::beginTransaction();  // Awal transaksional

            // Step 1: Kurangi nilai LivestockExpense sesuai dengan total_cost dari FeedingIndividuD
            $livestockExpense = LivestockExpense::where('livestock_id', $feedingIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if ($livestockExpense) {
                $livestockExpense->update([
                    'amount' => $livestockExpense->amount - $feedingIndividuD->total_cost
                ]);
            }

            // Step 2: Hapus FeedingIndividuItem yang terkait dengan FeedingIndividuD ini
            FeedingIndividuItem::where('feeding_individu_d_id', $feedingIndividuD->id)->delete();

            // Step 3: Hapus FeedingIndividuD
            $feedingIndividuD->delete();

            // Step 4: Hapus FeedingH jika tidak ada FeedingIndividuD lain yang terkait
            $feedingH = $feedingIndividuD->feedingH;
            if (!$feedingH->feedingIndividuD()->exists()) {
                $feedingH->delete();
            }

            DB::commit();  // Commit transaksi jika semua proses berhasil

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();  // Rollback jika ada kesalahan

            // Log error untuk debugging (opsional)
            Log::error('Delete FeedingIndividu Error: ', ['error' => $e->getMessage()]);

            // Handle exceptions dan kembalikan respon error
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }

}
