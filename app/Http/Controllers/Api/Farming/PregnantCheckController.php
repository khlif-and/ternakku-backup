<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Models\PregnantCheck;
use App\Models\PregnantCheckD;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Models\LivestockExpense;
use App\Models\ReproductionCycle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Enums\LivestockExpenseTypeEnum;
use App\Enums\ReproductionCycleStatusEnum;
use App\Http\Resources\Farming\PregnantCheckResource;
use App\Http\Requests\Farming\PregnantCheckStoreRequest;
use App\Http\Requests\Farming\PregnantCheckUpdateRequest;

class PregnantCheckController extends Controller
{
    public function store(PregnantCheckStoreRequest $request, $farmId): JsonResponse
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($validated['livestock_id']);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        if($livestock->livestock_sex_id !== LivestockSexEnum::BETINA->value){
            return ResponseHelper::error('Livestock is not female.', 400);
        }

        try {

            DB::beginTransaction();

            $check = ReproductionCycle::where('livestock_id' , $validated['livestock_id'])->orderBy('created_at' , 'desc')->first();

            if($check && $check->reproduction_cycle_status_id == ReproductionCycleStatusEnum::INSEMINATION->value){
                $reproductionCycle = $check;
            }else{
                $reproductionCycle = new ReproductionCycle;
                $reproductionCycle['livestock_id'] = $validated['livestock_id'];
                $reproductionCycle['insemination_type'] = 'unknown';
            }

            if($validated['status'] == 'PREGNANT'){
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::PREGNANT->value;
            }else{
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::INSEMINATION_FAILED->value;
            }

            $reproductionCycle->save();

            $pregnantCheck = PregnantCheck::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'],
            ]);

            $pregnantCheckD = PregnantCheckD::create([
                'reproduction_cycle_id' => $reproductionCycle->id,
                'pregnant_check_id' => $pregnantCheck->id,
                'action_time' => $validated['action_time'],
                'officer_name' => $validated['officer_name'],
                'pregnant_number' => $livestock->pregnant_number() + 1,
                'children_number' => $livestock->children_number() + 1,
                'status' =>  $validated['status'],
                'pregnant_age' =>  $validated['pregnant_age'],
                'estimated_birth_date' =>  $validated['status'] == 'PREGNANT' ? getEstimatedBirthDate($livestock->livestock_type_id , $validated['transaction_date'] , $validated['pregnant_age']) : null,
                'cost' => $validated['cost'],
            ]);

            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::PREGNANT_CHECK->value)
                ->first();

            if(!$livestockExpense){
                LivestockExpense::create([
                    'livestock_id' =>  $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::PREGNANT_CHECK->value,
                    'amount' => $validated['cost']
                ]);
            }else{
                $oldAmount = $livestockExpense->amount;
                $livestockExpense->update(['amount' => $oldAmount +  $validated['cost']]);
            }

            DB::commit();

            return ResponseHelper::success(new PregnantCheckResource($pregnantCheckD), 'Data created successfully', 200);

        } catch (\Exception $e) {

            Log::error($e);

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error('An error occurred while recording the data.', 500);
        }
    }

    public function index($farmId, Request $request) : JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $pregnantCheckD =PregnantCheckD::whereHas('pregnantCheck', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id);

            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }

            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        if ($request->filled('livestock_type_id')) {
            $pregnantCheckD->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $pregnantCheckD->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $pregnantCheckD->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $pregnantCheckD->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        $data = PregnantCheckResource::collection($pregnantCheckD->get());

        $message = $pregnantCheckD->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function show($farmId , $pregnantCheckId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $pregnantCheckD = PregnantCheckD::whereHas('pregnantCheck', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->findOrFail($pregnantCheckId);

        return ResponseHelper::success(new PregnantCheckResource($pregnantCheckD), 'Data retrieved successfully');
    }

    public function update(PregnantCheckUpdateRequest $request , $farmId, $pregnantCheckId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $pregnantCheckD = PregnantCheckD::whereHas('pregnantCheck', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->findOrFail($pregnantCheckId);

        $livestock =  $pregnantCheckD->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $pregnantCheck = $pregnantCheckD->pregnantCheck;

            $pregnantCheck->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $reproductionCycle =   $pregnantCheckD->reproductionCycle;

            if($validated['status'] == 'PREGNANT'){
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::PREGNANT->value;
            }else{
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::INSEMINATION_FAILED->value;
            }

            $reproductionCycle->save();

            $livestockExpenseOld = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::PREGNANT_CHECK->value)
                    ->first();

            $oldAmount = $livestockExpenseOld->amount;

            $livestockExpenseOld->update(['amount' => $oldAmount - $pregnantCheckD->cost + $validated['cost']]);

            $pregnantCheckD->update([
                'pregnant_check_id' => $pregnantCheck->id,
                'action_time' => $validated['action_time'],
                'officer_name' => $validated['officer_name'],
                'status' =>  $validated['status'],
                'pregnant_age' =>  $validated['pregnant_age'],
                'estimated_birth_date' =>  $validated['status'] == 'PREGNANT' ? getEstimatedBirthDate($livestock->livestock_type_id , $validated['transaction_date'] , $validated['pregnant_age']) : null,
                'cost' => $validated['cost'],
            ]);

            DB::commit();

            return ResponseHelper::success(new PregnantCheckResource($pregnantCheckD), 'Data updated successfully');

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the data.', 500);
        }
    }

    public function destroy($farmId, $pregnantCheckId)
    {
        $farm = request()->attributes->get('farm');

        $pregnantCheckD = PregnantCheckD::whereHas('pregnantCheck', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->findOrFail($pregnantCheckId);

        $livestock =  $pregnantCheckD->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::PREGNANT_CHECK->value)
                ->first();

            if ($livestockExpense) {
                $livestockExpense->update([
                    'amount' => $livestockExpense->amount - $pregnantCheckD->cost
                ]);
            }

            $reproductionCycle =  $pregnantCheckD->reproductionCycle;

            $pregnantCheckD->delete();

            $pregnantCheck = $pregnantCheckD->pregnantCheck;

            if (!$pregnantCheck->pregnantCheckD()->exists()) {
                $pregnantCheck->delete();
            }

            if( !$reproductionCycle->pregnantCheckD && !$reproductionCycle->inseminationNatural){
                $reproductionCycle->delete();
            }else{
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::INSEMINATION->value;
                $reproductionCycle->save();
            }

            DB::commit();

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }
}
