<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\MutationH;
use App\Models\PenHistory;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\MutationIndividuD;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\MutationIndividuResource;
use App\Http\Requests\Farming\MutationIndividuStoreRequest;
use App\Http\Requests\Farming\MutationIndividuUpdateRequest;

class MutationIndividuController extends Controller
{
    public function store(MutationIndividuStoreRequest $request)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');


        $livestock = $farm->livestocks()->find($validated['livestock_id']);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        $penDestination = $farm->pens()->find($validated['pen_destination']);

        if (!$penDestination) {
            return ResponseHelper::error('The destination pen not found.', 404);
        }

        if ($penDestination->id == $livestock->pen_id) {
            return ResponseHelper::error('The destination pen must be different from the current pen.', 422);
        }

        try {

            DB::beginTransaction();

            $mutationH = MutationH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'individu',
                'notes'            => $validated['notes'] ?? null,
            ]);

            $mutationIndividuD = MutationIndividuD::create([
                'mutation_h_id' =>  $mutationH->id,
                'livestock_id' => $validated['livestock_id'],
                'from' => $livestock->pen_id,
                'to' => $validated['pen_destination'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $penHistory = PenHistory::create([
                'livestock_id' => $validated['livestock_id'],
                'pen_id' => $validated['pen_destination'],
            ]);

            $livestock->update([
                'pen_id' =>  $validated['pen_destination']
            ]);

            DB::commit();

            return ResponseHelper::success(new MutationIndividuResource($mutationIndividuD), 'Data created successfully', 200);

        } catch (\Exception $e) {

            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }

    }

    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $mutationIndividu = MutationIndividuD::whereHas('mutationH', function ($query) use ($farm, $request) {
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
            $mutationIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $mutationIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $mutationIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('livestock_sex_id')) {
            $mutationIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_sex_id', $request->input('livestock_sex_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $mutationIndividu->whereHas('livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        if ($request->filled('livestock_id')) {
            $mutationIndividu->where('livestock_id', $request->input('livestock_id'));
        }

        $data = MutationIndividuResource::collection($mutationIndividu->get());

        $message = $mutationIndividu->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function show($farmId, $mutationIndividuId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        $mutationIndividu = MutationIndividuD::whereHas('mutationH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($mutationIndividuId);

        return ResponseHelper::success(new MutationIndividuResource($mutationIndividu), 'Data retrieved successfully');
    }

    public function update(MutationIndividuUpdateRequest $request, $farmId , $mutationIndividuId)
    {
        $validated = $request->validated();

        $farm = request()->attributes->get('farm');

        $mutationIndividuD = MutationIndividuD::whereHas('mutationH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($mutationIndividuId);

        $livestock =  $mutationIndividuD->livestock;

        if ($mutationIndividuD->to !== $livestock->pen_id) {
            return ResponseHelper::error(
                'Editing is not allowed because this is an old record.',
                422
            );
        }

        $penDestination = $farm->pens()->find($validated['pen_destination']);

        if (!$penDestination) {
            return ResponseHelper::error('The destination pen not found.', 404);
        }

        if ($penDestination->id == $mutationIndividuD->from) {
            return ResponseHelper::error('The destination pen must be different from the current pen.', 422);
        }

        try {

            DB::beginTransaction();  // Awal transaksional

            $mutationH = $mutationIndividuD->MutationH;

            $mutationH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $mutationIndividuD->update([
                'notes' => $validated['notes'] ?? null,
                'to' => $validated['pen_destination'],
            ]);

            $penHistory = PenHistory::where('livestock_id' , $livestock->id)->orderBy('created_at' , 'desc')->first();

            $penHistory->update([
                'pen_id' =>  $validated['pen_destination']
            ]);

            $livestock->update([
                'pen_id' =>  $validated['pen_destination']
            ]);

            DB::commit();

            return ResponseHelper::success(new MutationIndividuResource($mutationIndividuD), 'Data updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the data.', 500);
        }
    }

    public function destroy($farmId, $mutationIndividuId)
    {

        $farm = request()->attributes->get('farm');

        $mutationIndividuD = MutationIndividuD::whereHas('mutationH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type' , 'individu');
        })->findOrFail($mutationIndividuId);

        $livestock =  $mutationIndividuD->livestock;

        if ($mutationIndividuD->to !== $livestock->pen_id) {
            return ResponseHelper::error(
                'Deleting is not allowed because this is an old record.',
                422
            );
        }

        try {

            DB::beginTransaction();

            $livestock->update([
                'pen_id' =>  $mutationIndividuD->from
            ]);

            $penHistory = PenHistory::where('livestock_id' , $livestock->id)->orderBy('created_at' , 'desc')->first();

            $penHistory->delete();

            $mutationIndividuD->delete();

            $mutationH = $mutationIndividuD->mutationH;
            if (!$mutationH->mutationIndividuD()->exists()) {
                $mutationH->delete();
            }

            DB::commit();

            return ResponseHelper::success(null, 'Data deleted successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while updating the data.', 500);
        }
    }


}
