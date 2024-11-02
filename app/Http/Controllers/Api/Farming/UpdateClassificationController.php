<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LivestockResource;
use App\Http\Requests\Farming\UpdateClassificationRequest;

class UpdateClassificationController extends Controller
{
    public function update(UpdateClassificationRequest $request, $farmId, $dataId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $livestock = $farm->livestocks()->find($dataId);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found.', 404);
        }

        $livestock->update([
            'livestock_classification_id' => $validated['livestock_classification_id']
        ]);

        return ResponseHelper::success(new LivestockResource($livestock), 'Data updated successfully', 200);

    }
}
