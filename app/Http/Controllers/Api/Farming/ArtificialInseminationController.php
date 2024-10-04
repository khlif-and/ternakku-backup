<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Insemination;
use Illuminate\Http\Request;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Models\LivestockExpense;
use App\Models\ReproductionCycle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\InseminationArtificial;
use App\Enums\LivestockExpenseTypeEnum;
use App\Models\ReproductionCycleStatus;
use App\Enums\ReproductionCycleStatusEnum;
use App\Http\Resources\Farming\ArtificialInseminationResource;
use App\Http\Requests\Farming\ArtificialInseminationStoreRequest;
use App\Http\Requests\Farming\ArtificialInseminationUpdateRequest;

class ArtificialInseminationController extends Controller
{
    public function store(ArtificialInseminationStoreRequest $request, $farmId): JsonResponse
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
                $check->update([
                    'reproduction_cycle_status_id' => ReproductionCycleStatusEnum::INSEMINATION_FAILED->value
                ]);
            }

            if($check && $check->reproduction_cycle_status_id == ReproductionCycleStatusEnum::PREGNANT->value){
                $check->update([
                    'reproduction_cycle_status_id' => ReproductionCycleStatusEnum::BIRTH_FAILED->value
                ]);
            }

            $reproCycle = ReproductionCycle::create([
                'livestock_id' => $validated['livestock_id'],
                'reproduction_cycle_status_id' =>ReproductionCycleStatusEnum::INSEMINATION->value,
                'insemination_type' => 'artificial'
            ]);

            $insemination = Insemination::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'artificial',
                'notes'            => $validated['notes'],
            ]);

            $inseminationArtificial = InseminationArtificial::create([
                'reproduction_cycle_id' => $reproCycle->id,
                'insemination_id' => $insemination->id,
                'action_time' => $validated['action_time'],
                'officer_name' => $validated['officer_name'],
                'insemination_number' => $livestock->insemination_number() + 1,
                'pregnant_number' => $livestock->pregnant_number() + 1,
                'children_number' => $livestock->children_number() + 1,
                'semen_breed_id' => $validated['semen_breed_id'],
                'sire_name' => $validated['sire_name'],
                'semen_producer' => $validated['semen_producer'],
                'semen_batch' => $validated['semen_batch'],
                'cycle_date' => getInseminationCycleDate($livestock->livestock_type_id , $validated['transaction_date']),
                'cost' => $validated['cost'],
            ]);

            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
                ->first();

            if(!$livestockExpense){
                LivestockExpense::create([
                    'livestock_id' =>  $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::AI->value,
                    'amount' => $validated['cost']
                ]);
            }else{
                $oldAmount = $livestockExpense->amount;
                $livestockExpense->update(['amount' => $oldAmount +  $validated['cost']]);
            }

            DB::commit();

            return ResponseHelper::success(new ArtificialInseminationResource($inseminationArtificial), 'Data created successfully', 200);

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $inseminationArtificial = InseminationArtificial::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'artificial');
        })->get();

        $data = ArtificialInseminationResource::collection($inseminationArtificial);

        $message = $inseminationArtificial->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function show($farmId , $artificialInseminationId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $inseminationArtificial = InseminationArtificial::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'artificial');
        })->findOrFail($artificialInseminationId);

        return ResponseHelper::success(new ArtificialInseminationResource($inseminationArtificial), 'Data retrieved successfully');
    }

    public function update(ArtificialInseminationUpdateRequest $request , $farmId, $artificialInseminationId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $inseminationArtificial = InseminationArtificial::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'artificial');
        })->findOrFail($artificialInseminationId);

        $livestock =  $inseminationArtificial->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $insemination = $inseminationArtificial->insemination;

            $insemination->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $livestockExpenseOld = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
                    ->first();

            $oldAmount = $livestockExpenseOld->amount;

            $livestockExpenseOld->update(['amount' => $oldAmount - $inseminationArtificial->cost + $validated['cost']]);

            $inseminationArtificial->update([
                'action_time' => $validated['action_time'],
                'officer_name' => $validated['officer_name'],
                'semen_breed_id' => $validated['semen_breed_id'],
                'sire_name' => $validated['sire_name'],
                'semen_producer' => $validated['semen_producer'],
                'semen_batch' => $validated['semen_batch'],
                'cycle_date' => getInseminationCycleDate($livestock->livestock_type_id , $validated['transaction_date']),
                'cost' => $validated['cost'],
            ]);

            DB::commit();

            return ResponseHelper::success(new ArtificialInseminationResource($inseminationArtificial), 'Data updated successfully');

        } catch (\Throwable $e) {

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while uodating the data.', 500);
        }
    }

    public function destroy($farmId, $artificialInseminationId)
    {
        $farm = request()->attributes->get('farm');

        $inseminationArtificial = InseminationArtificial::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'artificial');
        })->findOrFail($artificialInseminationId);

        $livestock =  $inseminationArtificial->reproductionCycle->livestock;

        try {
            DB::beginTransaction();

            $livestockExpense = LivestockExpense::where('livestock_id', $livestock->id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
                ->first();

            if ($livestockExpense) {
                $livestockExpense->update([
                    'amount' => $livestockExpense->amount - $inseminationArtificial->cost
                ]);
            }

            $inseminationArtificial->delete();

            $insemination = $inseminationArtificial->insemination;

            if (!$insemination->inseminationArtificial()->exists()) {
                $insemination->delete();
            }

            $inseminationArtificial->reproductionCycle->delete();

            DB::commit();

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while deleting the data.', 500);
        }
    }
}
