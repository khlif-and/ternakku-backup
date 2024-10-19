<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Models\LivestockBirth;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Models\LivestockExpense;
use App\Models\ReproductionCycle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\LivestockExpenseTypeEnum;
use App\Enums\ReproductionCycleStatusEnum;
use App\Http\Requests\Farming\LivestockBirthStoreRequest;

class LivestockBirthController extends Controller
{
    public function store(LivestockBirthStoreRequest $request, $farmId): JsonResponse
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

            if(
                $check &&
                in_array( $check->reproduction_cycle_status_id ,
                    [ ReproductionCycleStatusEnum::INSEMINATION->value ,  ReproductionCycleStatusEnum::PREGNANT->value]
                )
            ){
                $reproductionCycle = $check;
            }else{
                $reproductionCycle = new ReproductionCycle;
                $reproductionCycle['livestock_id'] = $validated['livestock_id'];
                $reproductionCycle['insemination_type'] = 'unknown';
            }

            $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::GAVE_BIRTH->value;
            $reproductionCycle->save();

            $livestockBirth = LivestockBirth::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::BIRTH->value)
                ->first();

            if(!$livestockExpense){
                LivestockExpense::create([
                    'livestock_id' =>  $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::BIRTH->value,
                    'amount' => $validated['cost']
                ]);
            }else{
                $oldAmount = $livestockExpense->amount;
                $livestockExpense->update(['amount' => $oldAmount +  $validated['cost']]);
            }

            DB::commit();

            return ResponseHelper::success(new LivestockBirthResource($livestockBirth), 'Data created successfully', 200);

        } catch (\Exception $e) {

            Log::error($e);

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error('An error occurred while recording the data.', 500);
        }
    }
}
