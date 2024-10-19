<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Models\LivestockBirth;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Models\LivestockBirthD;
use App\Models\LivestockExpense;
use App\Models\ReproductionCycle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Enums\LivestockExpenseTypeEnum;
use App\Enums\ReproductionCycleStatusEnum;
use App\Http\Resources\Farming\LivestockBirthResource;
use App\Http\Requests\Farming\LivestockBirthStoreRequest;
use App\Http\Requests\Farming\LivestockBirthUpdateRequest;

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

            if ($validated['status'] !== 'ABORTUS' && isset($validated['details'])) {
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::GAVE_BIRTH->value;
                $reproductionCycle->save();
            }else{
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::BIRTH_FAILED->value;
                $reproductionCycle->save();
            }


            $livestockBirth = LivestockBirth::create([
                'reproduction_cycle_id' => $reproductionCycle->id,
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'officer_name' => $validated['officer_name'] ?? null,
                'cost' => $validated['cost'],
                'status' => $validated['status'],
                'estimated_weaning' => $validated['estimated_weaning'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Handle details only if status is not ABORTUS
            if ($validated['status'] !== 'ABORTUS' && isset($validated['details'])) {
                foreach ($validated['details'] as $detail) {
                    LivestockBirthD::create([
                        'livestock_birth_id' => $livestockBirth->id,
                        'livestock_sex_id' => $detail['livestock_sex_id'],
                        'livestock_breed_id' => $detail['livestock_breed_id'],
                        'weight' => $detail['weight'],
                        'birth_order' => $detail['birth_order'],
                        'status' => $detail['status'],
                        'offspring_value' => $detail['status'] === 'alive' ? $detail['offspring_value'] : null,
                        'disease_id' => $detail['status'] === 'dead' ? $detail['disease_id'] : null,
                        'indication' => $detail['status'] === 'dead' ? $detail['indication'] : null,
                    ]);
                }
            }

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

    public function index($farmId , Request $request) : JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $livestockBirth = LivestockBirth::where('farm_id', $farm->id);

        // Filter berdasarkan start_date atau end_date
        if ($request->filled('start_date')) {
            $livestockBirth->where('transaction_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $livestockBirth->where('transaction_date', '<=', $request->input('end_date'));
        }

        // Filter menggunakan whereHas pada relasi livestock
        if ($request->filled('livestock_type_id')) {
            $livestockBirth->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $livestockBirth->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $livestockBirth->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $livestockBirth->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }


        $data = LivestockBirthResource::collection($livestockBirth->get());

        $message = $livestockBirth->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function show($farmId , $dataId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $data = LivestockBirth::where('farm_id', $farm->id)->findOrFail($dataId);

        return ResponseHelper::success(new LivestockBirthResource($data), 'Data retrieved successfully');
    }

    public function update($farmId, $dataId, LivestockBirthUpdateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $livestockBirth =  LivestockBirth::where('farm_id', $farm->id)->findOrFail($dataId);

        $livestock =  $livestockBirth->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $reproductionCycle = $livestockBirth->reproductionCycle;

            if ($validated['status'] !== 'ABORTUS' && isset($validated['details'])) {
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::GAVE_BIRTH->value;
                $reproductionCycle->save();
            }else{
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::BIRTH_FAILED->value;
                $reproductionCycle->save();
            }

            $livestockBirth->LivestockBirthD()->delete();

            if ($validated['status'] !== 'ABORTUS' && isset($validated['details'])) {
                foreach ($validated['details'] as $detail) {
                    LivestockBirthD::create([
                        'livestock_birth_id' => $livestockBirth->id,
                        'livestock_sex_id' => $detail['livestock_sex_id'],
                        'livestock_breed_id' => $detail['livestock_breed_id'],
                        'weight' => $detail['weight'],
                        'birth_order' => $detail['birth_order'],
                        'status' => $detail['status'],
                        'offspring_value' => $detail['status'] === 'alive' ? $detail['offspring_value'] : null,
                        'disease_id' => $detail['status'] === 'dead' ? $detail['disease_id'] : null,
                        'indication' => $detail['status'] === 'dead' ? $detail['indication'] : null,
                    ]);
                }
            }

            $livestockExpenseOld = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::BIRTH->value)
                    ->first();

            $oldAmount = $livestockExpenseOld->amount;

            $livestockExpenseOld->update(['amount' => $oldAmount - $livestockBirth->cost + $validated['cost']]);

            $livestockBirth->update([
                'transaction_date' => $validated['transaction_date'],
                'officer_name' => $validated['officer_name'] ?? null,
                'cost' => $validated['cost'],
                'status' => $validated['status'],
                'estimated_weaning' => $validated['estimated_weaning'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return ResponseHelper::success(new LivestockBirthResource($livestockBirth), 'Data retrieved successfully');

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error('An error occurred while updating the data.', 500);
        }
    }

    public function destroy($farmId, $dataId)
    {
        $farm = request()->attributes->get('farm');

        $livestockBirth =  LivestockBirth::where('farm_id', $farm->id)->findOrFail($dataId);

        $livestock =  $livestockBirth->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::BIRTH->value)
                ->first();

            if ($livestockExpense) {
                $livestockExpense->update([
                    'amount' => $livestockExpense->amount - $livestockBirth->cost
                ]);
            }

            $reproductionCycle =  $livestockBirth->reproductionCycle;

            $livestockBirth->livestockBirthD()->delete();

            $livestockBirth->delete();

            if($reproductionCycle->pregnantCheckD && $reproductionCycle->pregnantCheckD->status == 'PREGNANT'){
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::PREGNANT->value;
                $reproductionCycle->save();
            }else if($reproductionCycle->pregnantCheckD && $reproductionCycle->pregnantCheckD->status == 'NOT_PREGNANT'){
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::INSEMINATION_FAILED->value;
                $reproductionCycle->save();
            }else if( $reproductionCycle->inseminationArtificial || $reproductionCycle->inseminationNatural){
                $reproductionCycle['reproduction_cycle_status_id'] = ReproductionCycleStatusEnum::INSEMINATION->value;
                $reproductionCycle->save();
            }else{
                $reproductionCycle->delete();
            }

            DB::commit();

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Throwable $e) {

            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }
}
