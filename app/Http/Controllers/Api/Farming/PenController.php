<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Pen;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\PenResource;
use App\Http\Requests\Farming\PenStoreRequest;
use App\Http\Requests\Farming\PenUpdateRequest;

class PenController extends Controller
{
    public function index(): JsonResponse
    {
       // Mendapatkan farm dari middleware
       $farm = request()->attributes->get('farm');

       // Mengambil semua pens terkait dengan farm
       $pens = $farm->pens;
       $data = PenResource::collection($pens);

       $message = $pens->count() > 0 ? 'Pens retrieved successfully' : 'No pens found';
       return ResponseHelper::success($data, $message);
    }

    public function store(PenStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm'); // Mendapatkan farm dari middleware

        $pen = new Pen($validated);
        $pen->farm_id = $farm->id;

        // Handle file upload if present
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = 'pens/';
            $pen->photo = uploadNeoObject($file, $fileName, $filePath);
        }

        $pen->save();

        return ResponseHelper::success(new PenResource($pen), 'Pen created successfully', Response::HTTP_CREATED);
    }

    public function show($penId): JsonResponse
    {
        $farm = request()->attributes->get('farm'); // Mendapatkan farm dari middleware

        $pen = $farm->pens()->findOrFail($penId);

        return ResponseHelper::success(new PenResource($pen), 'Pen retrieved successfully');
    }

    public function update(PenUpdateRequest $request, $penId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm'); // Mendapatkan farm dari middleware

        $pen = $farm->pens()->findOrFail($penId);

        // Handle file upload if present
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($pen->photo) {
                deleteNeoObject($pen->photo);
            }
            $file = $request->file('photo');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = 'pens/';
            $pen->photo = uploadNeoObject($file, $fileName, $filePath);
        }

        $pen->fill($validated);
        $pen->save();

        return ResponseHelper::success(new PenResource($pen), 'Pen updated successfully');
    }

    public function destroy($penId): JsonResponse
    {
        $farm = request()->attributes->get('farm'); // Mendapatkan farm dari middleware

        $pen = $farm->pens()->findOrFail($penId);

        // Delete photo if exists
        if ($pen->photo) {
            deleteNeoObject($pen->photo);
        }

        $pen->delete();

        return ResponseHelper::success(null, 'Pen deleted successfully', Response::HTTP_NO_CONTENT);
    }

}
