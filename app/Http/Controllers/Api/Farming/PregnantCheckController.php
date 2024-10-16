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
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::GAVE_BIRTH->value;
            }else{
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::BIRTH_FAILED->value;
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

    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $pregnantCheckD =PregnantCheckD::whereHas('pregnantCheck', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->get();

        $data = PregnantCheckResource::collection($pregnantCheckD);

        $message = $pregnantCheckD->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function show($farmId , $PregnantCheckId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $inseminationNatural = InseminationNatural::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'Natural');
        })->findOrFail($PregnantCheckId);

        return ResponseHelper::success(new PregnantCheckResource($inseminationNatural), 'Data retrieved successfully');
    }

    public function update(PregnantCheckUpdateRequest $request , $farmId, $PregnantCheckId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $inseminationNatural = InseminationNatural::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'Natural');
        })->findOrFail($PregnantCheckId);

        $livestock =  $inseminationNatural->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $insemination = $inseminationNatural->insemination;

            $insemination->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestockExpenseOld = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::NI->value)
                    ->first();

            $oldAmount = $livestockExpenseOld->amount;

            $livestockExpenseOld->update(['amount' => $oldAmount - $inseminationNatural->cost + $validated['cost']]);

            $inseminationNatural->update([
                'action_time' => $validated['action_time'],
                'sire_breed_id' => $validated['sire_breed_id'],
                'sire_owner_name' => $validated['sire_owner_name'],
                'cycle_date' => getInseminationCycleDate($livestock->livestock_type_id , $validated['transaction_date']),
                'cost' => $validated['cost'],
            ]);

            DB::commit();

            return ResponseHelper::success(new PregnantCheckResource($inseminationNatural), 'Data updated successfully');

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while uodating the data.', 500);
        }
    }

    public function destroy($farmId, $PregnantCheckId)
    {
        $farm = request()->attributes->get('farm');

        $inseminationNatural = InseminationNatural::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'Natural');
        })->findOrFail($PregnantCheckId);

        $livestock =  $inseminationNatural->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::NI->value)
                ->first();

            if ($livestockExpense) {
                $livestockExpense->update([
                    'amount' => $livestockExpense->amount - $inseminationNatural->cost
                ]);
            }

            $inseminationNatural->delete();

            $insemination = $inseminationNatural->insemination;

            if (!$insemination->inseminationNatural()->exists()) {
                $insemination->delete();
            }

            $inseminationNatural->reproductionCycle->delete();

            DB::commit();

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }
}
