<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Farm;
use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Models\LivestockType;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Enums\LivestockTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\PenResource;
use App\Http\Resources\LivestockListResource;
use App\Http\Resources\Farming\SupplierListResource;

class DashboardController extends Controller
{
    public function livestockPopulationSummary()
    {
        $farm = request()->attributes->get('farm');

        $livestockTypes = LivestockType::all();
        $summary = [];

        foreach ($livestockTypes as $type) {
            $typeId = $type->id;

            $summary[$type->name] = $farm->getLivestockSummary($typeId);
        }

        return ResponseHelper::success($summary, 'Population Summary retrieved successfully');
    }

    public function getLivestock(Request $request)
    {
        $farm = $request->attributes->get('farm');

        // Mulai query builder dengan livestock milik farm
        $query = $farm->livestocks();

        // Terapkan filter jika ada
        if ($request->filled('livestock_breed_id')) {
            $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
        }

        if ($request->filled('livestock_sex_id')) {
            $query->where('livestock_sex_id', $request->input('livestock_sex_id'));
        }

        if ($request->filled('livestock_type_id')) {
            $query->where('livestock_type_id', $request->input('livestock_type_id'));
        }

        if ($request->filled('livestock_group_id')) {
            $query->where('livestock_group_id', $request->input('livestock_group_id'));
        }

        // Dapatkan hasil akhir dan koleksi sebagai resource
        $data = LivestockListResource::collection($query->get());

        return ResponseHelper::success($data, 'Livestocks retrieved successfully');
    }

    public function getSupplier()
    {
        $farm = request()->attributes->get('farm');

        $data = SupplierListResource::collection($farm->suppliers);

        return ResponseHelper::success($data, 'Livestocks retrieved successfully');
    }

    public function getDetailLivestock(Request $request, $farmId , $id)
    {
        $farm = $request->attributes->get('farm');

        // Temukan livestock berdasarkan ID yang diberikan dan pastikan milik farm yang benar
        $livestock = $farm->livestocks()->find($id);

        // Periksa apakah livestock ditemukan
        if (!$livestock) {
            return ResponseHelper::error('Livestock not found', 404);
        }

        // Dapatkan hasil akhir sebagai resource tunggal
        $data = new LivestockListResource($livestock);

        return ResponseHelper::success($data, 'Livestock retrieved successfully');
    }

}
