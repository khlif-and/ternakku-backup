<?php

namespace App\Http\Controllers\Api;

use App\Models\Bcs;
use App\Models\Bank;
use App\Models\Module;
use App\Models\Region;
use App\Models\Disease;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Models\LivestockSex;
use App\Models\ModuleDetail;
use Illuminate\Http\Request;
use App\Models\LivestockType;
use App\Models\LivestockBreed;
use App\Models\LivestockGroup;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\LivestockClassification;
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

    public function getLivestockClassification()
    {
        $data = LivestockClassification::orderBy('id' , 'asc')->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getLivestockBcs()
    {
        $data = Bcs::orderBy('id' , 'asc')->get();

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

    public function getRegion(Request $request)
    {
        // Mengatur default page dan per_page jika tidak ada parameter
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        // Melakukan query dengan paginasi dan halaman tertentu
        $data = Region::where('name', 'like', '%' . $request->name . '%')
            ->paginate($perPage, ['*'], 'page', $page);

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getBank()
    {
        $data = Bank::select('id', 'name', 'swift_code')
                ->orderBy('name')
                ->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }


    public function getLivestockDisease()
    {
        $data = Disease::orderBy('name')->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getModule()
    {
        $data = Module::select('id', 'name', 'description')
                ->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getModuleDetail($moduleId)
    {
        $data = ModuleDetail::where('module_id' , $moduleId)->select('id', 'name', 'description')
                ->get();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }
}
