<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Bcs;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LivestockResource;
use App\Http\Requests\Farming\UpdateBcsRequest;

class UpdateBcsController extends Controller
{
    public function update(UpdateBcsRequest $request, $farmId, $dataId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($dataId);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        $bcs = Bcs::where('lower_limit', '<=', $validated['bcs_number'])
            ->where('upper_limit', '>=', $validated['bcs_number'])
            ->first();


        $livestock->update([
            'bcs_number' => $validated['bcs_number'],
            'bcs_id' => $bcs->id
        ]);

        return ResponseHelper::success(new LivestockResource($livestock), 'Data updated successfully', 200);

    }
}
