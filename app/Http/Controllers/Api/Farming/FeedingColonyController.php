<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\FeedingH;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\FeedingColonyD;
use App\Helpers\ResponseHelper;
use App\Models\LivestockExpense;
use App\Models\FeedingColonyItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\FeedingColonyLivestock;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Resources\Farming\FeedingColonyResource;
use App\Http\Requests\Farming\FeedingColonyStoreRequest;
use App\Http\Requests\Farming\FeedingColonyUpdateRequest;

class FeedingColonyController extends Controller
{
    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $feedingColony = FeedingColonyD::whereHas('feedingH', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id)->where('type' , 'colony');

            // Filter berdasarkan start_date atau end_date dari transaction_number
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

        $data = FeedingColonyResource::collection($feedingColony->get());

        $message = $feedingColony->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function store(FeedingColonyStoreRequest $request, $farmId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $pen = $farm->pens()->find($validated['pen_id']);

        if (!$pen) {
            return ResponseHelper::error('Pen not found.', 404);
        }

        $livestocks = $pen->livestocks;

        $totalLivestocks = count($livestocks);

        if ($totalLivestocks < 1) {
            return ResponseHelper::error('There is no livestock in this pen.', 404);
        }

        try {

            DB::beginTransaction();  // Awal transaksional

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

            return ResponseHelper::success(new FeedingColonyResource($feedingColonyD), 'Data created successfully', 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function show($farmId, $feedingColonyId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $feedingColony = FeedingColonyD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'colony');
        })->findOrFail($feedingColonyId);

        return ResponseHelper::success(new FeedingColonyResource($feedingColony), 'Data retrieved successfully');
    }

    public function update(FeedingColonyUpdateRequest $request, $farmId , $feedingColonyId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $feedingColonyD = FeedingColonyD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'Colony');
        })->findOrFail($feedingColonyId);

        try {
            DB::beginTransaction();  // Awal transaksional

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
                // Step 6: Update the LivestockExpense with the new total cost
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

            return ResponseHelper::success(new FeedingColonyResource($feedingColonyD), 'Data updated successfully');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the data.', 500);
        }
    }

    public function destroy($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');

        // Cari FeedingColonyD dengan memastikan farm dan tipe Colony
        $feedingColonyD = FeedingColonyD::whereHas('feedingH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($feedingColonyId);

        try {
            DB::beginTransaction();  // Awal transaksional

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


            // Step 3: Hapus FeedingColonyD
            $feedingColonyD->delete();

            // Step 4: Hapus FeedingH jika tidak ada FeedingColonyD lain yang terkait
            $feedingH = $feedingColonyD->feedingH;
            if (!$feedingH->feedingColonyD()->exists()) {
                $feedingH->delete();
            }

            DB::commit();  // Commit transaksi jika semua proses berhasil

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Exception $e) {

            DB::rollBack();  // Rollback jika ada kesalahan

            // Log error untuk debugging (opsional)
            Log::error('Delete FeedingColony Error: ', ['error' => $e->getMessage()]);

            // Handle exceptions dan kembalikan respon error
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }

}
