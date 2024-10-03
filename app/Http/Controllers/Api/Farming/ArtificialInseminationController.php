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

            /*
                1. cek data reproduction cycle
                    1.1 kalau ada, cek ReproductionCycleStatusId, kalau 1 => 2, kalau 3 => 5
                2. create data reproduction cycle , set ReproductionCycleStatusId jadi 1
                3. create data inseminasi header
                4. create data inseminasi_artificial
                5. create / update data expenses
            */

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
}
