<?php

namespace App\Http\Controllers\Api;

use App\Models\Bank;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Models\LivestockSex;
use Illuminate\Http\Request;
use App\Models\LivestockType;
use App\Models\LivestockBreed;
use App\Models\LivestockGroup;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\LivestockBreedResource;

class DataMasterController extends Controller
{
    public function getLivestockType()
    {
        $data = LivestockType::all();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getLivestockSex()
    {
        $data = LivestockSex::all();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getLivestockGroup()
    {
        $data = LivestockGroup::all();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getLivestockBreed(Request $request)
    {
        $livestockTypeId = $request->query('livestock_type_id');

        $query = LivestockBreed::with('livestockType');

        if ($livestockTypeId) {
            $query->where('livestock_type_id', $livestockTypeId);
        }

        $data = LivestockBreedResource::collection($query->get());

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getBank()
    {
        $data = Bank::select('id', 'name', 'swift_code')
                ->orderBy('name')
                ->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getProvince()
    {
        $data = Province::orderBy('name')->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getRegency(Request $request)
    {
        $provinceId = $request->query('province_id');

        $query = Regency::orderBy('name');

        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }

        $data = $query->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getDistrict(Request $request)
    {
        $regencyId = $request->query('regency_id');

        $query = District::orderBy('name');

        if ($regencyId) {
            $query->where('regency_id', $regencyId);
        }

        $data = $query->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getVillage(Request $request)
    {
        $districtId = $request->query('district_id');

        $query = Village::orderBy('name');

        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        $data = $query->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }
}
