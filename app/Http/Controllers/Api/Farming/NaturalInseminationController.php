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
use App\Models\InseminationNatural;
use App\Enums\LivestockExpenseTypeEnum;
use App\Models\ReproductionCycleStatus;
use App\Enums\ReproductionCycleStatusEnum;
use App\Http\Resources\Farming\NaturalInseminationResource;
use App\Http\Requests\Farming\NaturalInseminationStoreRequest;
use App\Http\Requests\Farming\NaturalInseminationUpdateRequest;

class NaturalInseminationController extends Controller
{
    public function store(NaturalInseminationStoreRequest $request, $farmId): JsonResponse
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
                'insemination_type' => 'natural'
            ]);

            $insemination = Insemination::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'natural',
                'notes'            => $validated['notes'],
            ]);

            $inseminationNatural = InseminationNatural::create([
                'reproduction_cycle_id' => $reproCycle->id,
                'insemination_id' => $insemination->id,
                'action_time' => $validated['action_time'],
                'insemination_number' => $livestock->insemination_number(),
                'pregnant_number' => $livestock->pregnant_number() + 1,
                'children_number' => $livestock->children_number() + 1,
                'sire_breed_id' => $validated['sire_breed_id'],
                'sire_owner_name' => $validated['sire_owner_name'],
                'cycle_date' => getInseminationCycleDate($livestock->livestock_type_id , $validated['transaction_date']),
                'cost' => $validated['cost'],
            ]);

            $livestockExpense = LivestockExpense::where('livestock_id', $validated['livestock_id'])
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::NI->value)
                ->first();

            if(!$livestockExpense){
                LivestockExpense::create([
                    'livestock_id' =>  $validated['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::NI->value,
                    'amount' => $validated['cost']
                ]);
            }else{
                $oldAmount = $livestockExpense->amount;
                $livestockExpense->update(['amount' => $oldAmount +  $validated['cost']]);
            }

            DB::commit();

            return ResponseHelper::success(new NaturalInseminationResource($inseminationNatural), 'Data created successfully', 200);

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }
    }

    public function index($farmId , Request $request) : JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $inseminationNatural = InseminationNatural::whereHas('insemination', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id)->where('type' , 'Natural');

            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }

            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        if ($request->filled('livestock_type_id')) {
            $inseminationNatural->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $inseminationNatural->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $inseminationNatural->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $inseminationNatural->whereHas('reproductionCycle.livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        $data = NaturalInseminationResource::collection($inseminationNatural->get());

        $message = $inseminationNatural->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function show($farmId , $naturalInseminationId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $inseminationNatural = InseminationNatural::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'Natural');
        })->findOrFail($naturalInseminationId);

        return ResponseHelper::success(new NaturalInseminationResource($inseminationNatural), 'Data retrieved successfully');
    }

    public function update(NaturalInseminationUpdateRequest $request , $farmId, $naturalInseminationId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $inseminationNatural = InseminationNatural::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'Natural');
        })->findOrFail($naturalInseminationId);

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

            return ResponseHelper::success(new NaturalInseminationResource($inseminationNatural), 'Data updated successfully');

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the data.', 500);
        }
    }

    public function destroy($farmId, $naturalInseminationId)
    {
        $farm = request()->attributes->get('farm');

        $inseminationNatural = InseminationNatural::whereHas('insemination', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'Natural');
        })->findOrFail($naturalInseminationId);

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
